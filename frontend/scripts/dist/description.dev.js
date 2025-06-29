"use strict";

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var urlParams = new URLSearchParams(window.location.search);
var movieId = urlParams.get('id');

if (movieId) {
  axios.get("http://localhost/cinema_server/backend/controllers/get_movies.php", {
    params: {
      id: movieId
    }
  }).then(function (response) {
    var data = response.data;

    if (data.movie) {
      var movie = data.movie;

      var _ref = movie.movie_cast && movie.movie_cast.includes('as') ? movie.movie_cast.split('as') : ["Unknown", "Unknown"],
          _ref2 = _slicedToArray(_ref, 2),
          actorName = _ref2[0],
          roleName = _ref2[1]; //here to update the content in the page


      document.querySelector('.heading').textContent = movie.title;
      document.querySelector('.movie-details').innerHTML = "\n        <p><strong>Description:</strong> ".concat(movie.description, "</p>\n        <p><strong>Actor Name:</strong> ").concat(actorName, "</p>\n        <p><strong>Actor Role:</strong> ").concat(roleName, "</p>\n        <p><strong>Rating:</strong> ").concat(movie.ratings || "Not rated", "</p>\n        <p><strong>Status:</strong> ").concat(movie.status, "</p>\n        <p><strong>Release Date:</strong> ").concat(movie.release_date, "</p>"); // to update the poster that we have

      var poster = document.querySelector('.poster_url');
      poster.src = movie.poster_url;
      poster.alt = movie.title; // to update the trailer

      var trailerLink = (movie.trailers || "").split(",")[0]; // in case we have more then one trailers

      document.querySelector('.video-wrapper iframe').src = trailerLink.replace("watch?v=", "embed/"); //replace it to make iframe understand the url
    } else {
      alert(data.message || "Movie not found");
    }
  })["catch"](function (error) {
    console.error("Axios error:", error);
    alert("Failed to fetch movie data.");
  });
} else {
  alert("No movie ID provided in the URL");
} //get showtime id


var selectedShowtimeId = null;
var selectedSeatsArray = [];

if (movieId) {
  // Fetch movie details here...
  // Fetch showtimes for movie
  axios.get("http://localhost/cinema_server/backend/controllers/get_showtimeId.php", {
    params: {
      movie_id: movieId
    }
  }).then(function (res) {
    var showtimes = res.data.showtimes;

    if (showtimes.length === 0) {
      alert("No showtimes available");
      return;
    }

    var select = document.getElementById("showtimeSelect");
    select.innerHTML = "";
    showtimes.forEach(function (showtime) {
      var option = document.createElement("option");
      option.value = showtime.id;
      option.textContent = new Date(showtime.show_datetime).toLocaleString();
      select.appendChild(option);
    });
    selectedShowtimeId = select.value;
    loadTakenSeats(selectedShowtimeId);
    select.addEventListener("change", function (e) {
      selectedShowtimeId = e.target.value;
      selectedSeatsArray = [];
      loadTakenSeats(selectedShowtimeId);
    });
  })["catch"](function (err) {
    console.error("Error loading showtimes:", err);
  });
} else {
  alert("No movie ID provided in the URL");
}

function renderSeats(takenSeats) {
  var seatsGrid = document.getElementById("seats-grid");
  seatsGrid.innerHTML = "";
  var ROWS = 5;
  var COLS = 10;
  var totalSeats = ROWS * COLS;

  var _loop = function _loop(seatNum) {
    var seat = document.createElement("button");
    seat.classList.add("seat");
    seat.textContent = seatNum;

    if (takenSeats.includes(seatNum)) {
      seat.classList.add("taken");
      seat.setAttribute("aria-label", "Seat ".concat(seatNum, ", taken"));
      seat.disabled = true; // disable taken seats so user can't click
    } else {
      seat.setAttribute("aria-label", "Seat ".concat(seatNum, ", available"));
      seat.addEventListener("click", function () {
        if (seat.classList.contains("selected")) {
          seat.classList.remove("selected");
          selectedSeatsArray = selectedSeatsArray.filter(function (s) {
            return s !== seatNum;
          });
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
  };

  for (var seatNum = 1; seatNum <= totalSeats; seatNum++) {
    _loop(seatNum);
  }
}

function loadTakenSeats(showtimeId) {
  axios.get("http://localhost/cinema_server/backend/controllers/show_takenseats.php", {
    params: {
      showtime_id: showtimeId
    }
  }).then(function (res) {
    var takenSeats = res.data.takenSeats || [];
    renderSeats(takenSeats);
  })["catch"](function (err) {
    console.error("Failed to load taken seats", err);
  });
}

document.querySelector('.ticket-button').addEventListener('click', function () {
  var userId = localStorage.getItem("userId");
  var quantity = selectedSeatsArray.length;

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
  }).then(function (res) {
    if (res.data.success) {
      alert("Ticket purchased successfully"); // Optionally clear selections or reload seats here
    } else {
      alert(res.data.message || "Purchase failed");
    }
  })["catch"](function (err) {
    console.error("Error:", err);
    alert("Something went wrong");
  });
});
//# sourceMappingURL=description.dev.js.map
