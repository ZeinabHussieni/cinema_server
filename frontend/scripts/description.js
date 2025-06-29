const urlParams = new URLSearchParams(window.location.search);
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

      //here to update the content in the page
      document.querySelector('.heading').textContent = movie.title;

      document.querySelector('.movie-details').innerHTML = `
        <p><strong>Description:</strong> ${movie.description}</p>
        <p><strong>Actor Name:</strong> ${actorName}</p>
        <p><strong>Actor Role:</strong> ${roleName}</p>
        <p><strong>Rating:</strong> ${movie.ratings || "Not rated"}</p>
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

if (movieId) {
  // Fetch movie details here...

  // Fetch showtimes for movie
  axios.get("http://localhost/cinema_server/backend/controllers/get_showtimeId.php", {
    params: { movie_id: movieId }
  }).then(res => {
    const showtimes = res.data.showtimes;

    if (showtimes.length === 0) {
      alert("No showtimes available");
      return;
    }

    const select = document.getElementById("showtimeSelect");
    select.innerHTML = "";

    showtimes.forEach(showtime => {
      const option = document.createElement("option");
      option.value = showtime.id;
      option.textContent = new Date(showtime.show_datetime).toLocaleString();
      select.appendChild(option);
    });

    selectedShowtimeId = select.value;
    loadTakenSeats(selectedShowtimeId);

    select.addEventListener("change", (e) => {
      selectedShowtimeId = e.target.value;
      selectedSeatsArray = [];
      loadTakenSeats(selectedShowtimeId);
    });

  }).catch(err => {
    console.error("Error loading showtimes:", err);
  });

} else {
  alert("No movie ID provided in the URL");
}
function renderSeats(takenSeats) {
  const seatsGrid = document.getElementById("seats-grid");
  seatsGrid.innerHTML = "";

  const ROWS = 5;
  const COLS = 10;
  const totalSeats = ROWS * COLS;

  for (let seatNum = 1; seatNum <= totalSeats; seatNum++) {
    const seat = document.createElement("button");
    seat.classList.add("seat");
    seat.textContent = seatNum;

    if (takenSeats.includes(seatNum)) {
      seat.classList.add("taken");
      seat.setAttribute("aria-label", `Seat ${seatNum}, taken`);
      seat.disabled = true; // disable taken seats so user can't click
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
        console.log("Selected seats:", selectedSeatsArray);
      });

      seat.setAttribute("aria-pressed", "false"); // track toggle state for screen readers
      seat.setAttribute("role", "button");
    }

    seatsGrid.appendChild(seat);
  }
}


function loadTakenSeats(showtimeId) {
  axios.get("http://localhost/cinema_server/backend/controllers/show_takenseats.php", {
    params: { showtime_id: showtimeId }
  }).then(res => {
    const takenSeats = res.data.takenSeats || [];
    renderSeats(takenSeats);
  }).catch(err => {
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
    quantity: quantity
  }).then(res => {
    if (res.data.success) {
      alert("Ticket purchased successfully");
      // Optionally clear selections or reload seats here
    } else {
      alert(res.data.message || "Purchase failed");
    }
  }).catch(err => {
    console.error("Error:", err);
    alert("Something went wrong");
  });
});
