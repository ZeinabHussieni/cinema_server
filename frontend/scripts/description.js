// get movie id from URL 
const urlParams = new URLSearchParams(window.location.search);
const movieId = urlParams.get("id");

// global state variables to use them 
let selectedShowtimeId = null;
let selectedSeatsArray = [];
let ticketPrice = 0;

// movie details 
if (movieId) {
  axios.get("http://localhost/cinema_server/backend/get_movieDetailsById", {
    params: { id: movieId }
  })
  .then(response => {
    const data = response.data;
    if (data.movie) {
      const movie = data.movie;
      const [actorName, roleName] = movie.movie_cast && movie.movie_cast.includes("as")
        ? movie.movie_cast.split("as").map(str => str.trim())
        : ["Unknown", "Unknown"];

      document.querySelector(".heading").textContent = movie.title;
      document.querySelector(".movie-details").innerHTML = `
        <p><strong>Description:</strong> ${movie.description}</p>
        <p><strong>Actor Name:</strong> ${actorName}</p>
        <p><strong>Actor Role:</strong> ${roleName}</p>
        <p><strong>Rating:</strong> ${movie.ratings ? generateStars(movie.ratings) : "Not rated"}</p>
        <p><strong>Status:</strong> ${movie.status}</p>
        <p><strong>Release Date:</strong> ${movie.release_date}</p>
      `;

      // poster and trailer 
      const poster = document.querySelector(".poster_url");
      poster.src = movie.poster_url;
      poster.alt = movie.title;

      const trailerLink = (movie.trailers || "").split(",")[0];
      document.querySelector(".video-wrapper iframe").src = trailerLink.replace("watch?v=", "embed/");
    } else {
      alert(data.message || "Movie not found");
    }
  })
  .catch(error => {
    console.error("Axios error:", error);
    alert("Failed to fetch movie data.");
  });
} else {
  alert("No movie ID provided in the URL");
}

//  showtimes dropdown 
if (movieId) {
  //fill in all showtimes available
  axios.get("http://localhost/cinema_server/backend/get_showtimebyMovieId", {
    params: { movie_id: movieId }
  })
  .then(res => {
    const showtimes = res.data.showtimes;
    if (!showtimes || showtimes.length === 0) {
      alert("No showtimes available");
      return;
    }
     
    //get the list and fill it in dropdown
    const select = document.getElementById("showtimeSelect");
    select.innerHTML = "";

    showtimes.forEach(showtime => {
      const option = document.createElement("option");
      option.value = showtime.id;
      option.textContent = new Date(showtime.show_datetime).toLocaleString();
      option.dataset.capacity = showtime.capacity;
      option.dataset.ticket_price = showtime.ticket_price;
      select.appendChild(option);
    });

    // take the first showtime default with price and save id 
    const initialOption = select.options[select.selectedIndex];
    ticketPrice = initialOption ? Number(initialOption.dataset.ticket_price) : 0;
    selectedShowtimeId = select.value;

    //display seats already taken
    loadTakenSeats(selectedShowtimeId);

    // update when pick different showtimes
    select.addEventListener("change", () => {
      selectedShowtimeId = select.value;
      const selectedOption = select.querySelector(`option[value="${selectedShowtimeId}"]`);
      ticketPrice = selectedOption ? Number(selectedOption.dataset.ticket_price) : 0;

      selectedSeatsArray = [];
      document.getElementById("totalPrice").textContent = `Total Price: $0.00`;
      loadTakenSeats(selectedShowtimeId);
    });
  })
  .catch(err => {
    console.error("Error loading showtimes:", err);
  });
}

// get seats that are booked
function loadTakenSeats(showtimeId) {
  const select = document.getElementById("showtimeSelect");
  const selectedOption = select.querySelector(`option[value="${showtimeId}"]`);
  const capacity = selectedOption && !isNaN(selectedOption.dataset.capacity)
    ? Number(selectedOption.dataset.capacity)
    : 50; //default

  axios.get("http://localhost/cinema_server/backend/get_TakenSeats", {
    params: { showtime_id: showtimeId }
  })
  .then(res => {
    const takenSeats = res.data.takenSeats || [];
    renderSeats(takenSeats, capacity);
  })
  .catch(err => {
    console.error("Failed to load taken seats", err);
  });
}
//matrix of seats
function renderSeats(takenSeats, capacity) {
  const seatsGrid = document.getElementById("seats-grid");
  seatsGrid.innerHTML = "";
  const COLS = 10;
  const ROWS = Math.ceil(capacity / COLS);

  for (let seatNum = 1; seatNum <= capacity; seatNum++) {
    const seat = document.createElement("button");
    seat.classList.add("seat");
    seat.textContent = seatNum;

    if (takenSeats.includes(seatNum)) {
      seat.classList.add("taken");
      seat.disabled = true;
    } else {
      seat.addEventListener("click", () => {
        if (seat.classList.contains("selected")) {
          seat.classList.remove("selected");
          selectedSeatsArray = selectedSeatsArray.filter(s => s !== seatNum);
        } else {
          seat.classList.add("selected");
          selectedSeatsArray.push(seatNum);
        }
        const total = selectedSeatsArray.length * ticketPrice;
        document.getElementById("totalPrice").textContent = `Total Price: $${total.toFixed(2)}`;
      });

      seat.setAttribute("role", "button");
      seat.setAttribute("aria-pressed", "false");
    }

    seatsGrid.appendChild(seat);
  }
}

//  Buy ticket 
document.querySelector(".ticket-button").addEventListener("click", () => {
  const userId = localStorage.getItem("userId");
  const quantity = selectedSeatsArray.length;

  if (!selectedShowtimeId || !userId || quantity === 0) {
    alert("Missing user, showtime, or no seats selected!");
    return;
  }

  axios.post("http://localhost/cinema_server/backend/Buy_Ticket", {
    user_id: userId,
    movie_id: movieId,
    showtime_id: selectedShowtimeId,
    seat_numbers: selectedSeatsArray,
    quantity
  })
  .then(res => {
    if (res.data.success) {
      alert("Ticket purchased successfully");
      // reflesh seats so newly bought seats show as taken
      loadTakenSeats(selectedShowtimeId);
      selectedSeatsArray = [];
      document.getElementById("totalPrice").textContent = `Total Price: $0.00`;
    } else {
      alert(res.data.message || "Purchase failed");
    }
  })
  .catch(err => {
    console.error("Error:", err);
    alert("Something went wrong while purchasing.");
  });
});

// stars generate
function generateStars(rating) {
  rating = Number(rating);
  if (isNaN(rating) || rating < 0) rating = 0;
  if (rating > 5) rating = 5;

  const fullStars = Math.floor(rating);
  const halfStar = rating % 1 >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;

  let starsHTML = "";
  for (let i = 0; i < fullStars; i++) starsHTML += `<i class="fas fa-star"></i>`;
  if (halfStar) starsHTML += `<i class="fas fa-star-half-alt"></i>`;
  for (let i = 0; i < emptyStars; i++) starsHTML += `<i class="far fa-star"></i>`;

  return starsHTML;
}
