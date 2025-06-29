const urlParams = new URLSearchParams(window.location.search);//grabs everything in the info after th '?' in the url
const movieId = urlParams.get('id');

if (movieId) {
  axios.get("http://localhost/cinema_server/backend/controllers/get_movies.php", {
    params: { id: movieId }
  }).then(response => {
    const data = response.data; 
    if (data.movie) {
      const movie = data.movie;
      const [actorName, roleName] = movie.movie_cast && movie.movie_cast.includes('as')
      ? movie.movie_cast.split('as')
      : ["Unknown", "Unknown"];

      //we put the title of movie
      document.querySelector('.heading').textContent = movie.title;
      //and other movie details
      document.querySelector('.movie-details').innerHTML = `
        <p><strong>Description:</strong> ${movie.description}</p>
        <p><strong>Actor Name:</strong> ${actorName}</p>
        <p><strong>Actor Role:</strong> ${roleName}</p>
        <p><strong>Rating:</strong> 
        ${movie.ratings ? `${generateStars(movie.ratings)}` : "Not rated"}
        </p>
        <p><strong>Status:</strong> ${movie.status}</p>
        <p><strong>Release Date:</strong> ${movie.release_date}</p>`;

      // to update the poster that we have
      const poster = document.querySelector('.poster_url');
      poster.src = movie.poster_url;
      poster.alt = movie.title;

      // to update the trailer
      const trailerLink = (movie.trailers || "").split(",")[0]; // in case we have more then one trailers
      document.querySelector('.video-wrapper iframe').src = trailerLink.replace("watch?v=", "embed/");//replace it to make iframe understand the url
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

//get showtime id
let selectedShowtimeId = null;
let selectedSeatsArray = [];
let ticketPrice = 0;

if (movieId) {
  // get the showtimes for movie
  axios.get("http://localhost/cinema_server/backend/controllers/get_showtimeId.php", {
    params: { movie_id: movieId }
  }).then(res => {

      const showtimes = res.data.showtimes;
  console.log(showtimes); // debug check
    if (showtimes.length === 0) {
      alert("No showtimes available");
      return;
    }
    //we do this method to asgin all showtimes to a dropdown
    const select=document.getElementById("showtimeSelect");
    select.innerHTML = "";
   // After this part where you append all options:
   showtimes.forEach(showtime => {
   const option = document.createElement("option");
   option.value = showtime.id;
   option.textContent = new Date(showtime.show_datetime).toLocaleString();
   option.dataset.capacity = showtime.capacity;
   option.dataset.ticket_price = showtime.ticket_price;
   select.appendChild(option);
   console.log("Option ticket price:", option.dataset.ticket_price, typeof option.dataset.ticket_price);
});
 const initialOption = select.options[select.selectedIndex];
 ticketPrice = initialOption ? Number(initialOption.dataset.ticket_price) : 0;
 console.log("Initial ticketPrice set to:", ticketPrice);

 // continue loading seats and stuff
 selectedShowtimeId = select.value;
 loadTakenSeats(selectedShowtimeId);
 select.addEventListener("change", (e) => {
 selectedShowtimeId = select.value;

 const initialSelectedOption = select.querySelector(`option[value="${selectedShowtimeId}"]`);
 ticketPrice = initialSelectedOption ? Number(initialSelectedOption.dataset.ticket_price) : 0;
 console.log("Initial ticketPrice:", ticketPrice);

 loadTakenSeats(selectedShowtimeId);

  document.getElementById("totalPrice").textContent = `Total Price: $0.00`;
  loadTakenSeats(selectedShowtimeId);
});
  }).catch(err => {
    console.error("Error loading showtimes:", err);
  });

} else {
  alert("No movie ID provided in the URL");
}
//create table of seats
function renderSeats(takenSeats, capacity) {
  const seatsGrid = document.getElementById("seats-grid");
  seatsGrid.innerHTML = "";
  const COLS = 10;
  const seatsToRender = capacity;
  const ROWS = Math.ceil(seatsToRender / COLS);


  for (let seatNum = 1; seatNum <= seatsToRender; seatNum++) {
    const seat = document.createElement("button");
    seat.classList.add("seat");
    seat.textContent = seatNum;

    if (takenSeats.includes(seatNum)) {
      seat.classList.add("taken");
      seat.setAttribute("aria-label", `Seat ${seatNum}, taken`);
      seat.disabled = true;
    } else {
      seat.setAttribute("aria-label", `Seat ${seatNum}, available`);

      seat.addEventListener("click", () => {
        if (seat.classList.contains("selected")) {
          seat.classList.remove("selected");
          selectedSeatsArray = selectedSeatsArray.filter(s => s !== seatNum);
          seat.setAttribute("aria-pressed", "false");
        } else {
          seat.classList.add("selected");
          selectedSeatsArray.push(seatNum);
          seat.setAttribute("aria-pressed", "true");
        }
        // Calculate total price

        const total = selectedSeatsArray.length * ticketPrice;
        document.getElementById("totalPrice").textContent = `Total Price: $${total.toFixed(2)}`;

        console.log("Ticket price:", ticketPrice);

       console.log("Selected seats:", selectedSeatsArray);
        console.log("Selected seats:", selectedSeatsArray);
      });

      seat.setAttribute("aria-pressed", "false");
      seat.setAttribute("role", "button");
    }

    seatsGrid.appendChild(seat);
  }
}

function loadTakenSeats(showtimeId) {
  const select = document.getElementById("showtimeSelect");
  const selectedOption = select.querySelector(`option[value="${showtimeId}"]`);
  const capacity = selectedOption && !isNaN(selectedOption.dataset.capacity)
    ? Number(selectedOption.dataset.capacity)
    : 50; // fallback capacity
console.log(`Using capacity ${capacity} for showtime ${showtimeId}`);

  axios.get("http://localhost/cinema_server/backend/controllers/show_takenseats.php", {
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

document.querySelector('.ticket-button').addEventListener('click', () => {
  const userId = localStorage.getItem("userId");
  const quantity = selectedSeatsArray.length;

  if (!selectedShowtimeId || !userId || quantity === 0) {
    alert("Missing user, showtime info or no seats selected!");
    return;
  }
axios.post("http://localhost/cinema_server/backend/controllers/buy_ticket.php", {
  user_id: userId,
  movie_id: movieId,
  showtime_id: selectedShowtimeId,
  seat_numbers: selectedSeatsArray,
  quantity: selectedSeatsArray.length,
}).then(res => {
    if (res.data.success) {
      alert("Ticket purchased successfully");
      loadTakenSeats(selectedShowtimeId);
    } else {
      alert(res.data.message || "Purchase failed");
    }
  }).catch(err => {
    console.error("Error:", err);
    alert("Something went wrong");
  });
});
function generateStars(rating) {
  rating = Number(rating);
  if (isNaN(rating) || rating < 0) rating = 0;
  if (rating > 5) rating = 5;
  const fullStars = Math.floor(rating);//we use it to see how many we have full stars
  const halfStar = rating % 1 >= 0.5 ? 1 : 0;//check if the decimal part of the star is near to half star 
  const emptyStars = 5 - fullStars - halfStar;

  let starsHTML = "";

  for (let i = 0; i < fullStars; i++) {
    starsHTML += `<i class="fas fa-star"></i>`;
  }
  if (halfStar) {
    starsHTML += `<i class="fas fa-star-half-alt"></i>`;
  }
  for (let i = 0; i < emptyStars; i++) {
    starsHTML += `<i class="far fa-star"></i>`;
  }

  return starsHTML;
}
