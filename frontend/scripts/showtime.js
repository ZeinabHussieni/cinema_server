document.addEventListener("DOMContentLoaded", () => {
  // Lottie animation
  lottie.loadAnimation({
    container: document.getElementById("lottie-player"),
    renderer: "svg",
    loop: true,
    autoplay: true,
    path: "http://localhost/cinema_server/frontend/jsons/movie.json",
  });

  const form = document.getElementById("addShowtimeForm");
  if (!form) {
    console.error("Showtime form not found!");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get("id");
    const show_datetime = document.getElementById("show_datetime").value;
    const capacity = document.getElementById("capacity").value;
    const ticket_price = document.getElementById("ticket_price").value;
    const created_at = new Date().toISOString().slice(0, 19).replace("T", " ");

    if (!movieId) {
      alert("Missing movie ID — can’t assign showtime");
      return;
    }

    const showtimeData = {
      movie_id: movieId,
      show_datetime,
      capacity,
      created_at,
      ticket_price,
    };

    axios
      .post(
        "http://localhost/cinema_server/backend/controllers/create_showtimes.php",
        showtimeData
      )
      .then((response) => {
        if (response.data.success) {
          alert("Showtime added successfully");
         window.location.href = `addRating.html?id=${movieId}`;
        } else {
          alert("Failed to add showtime: " + response.data.message);
        }
      })
      .catch((error) => {
        console.error("Showtime Error:", error);
        alert("Something went wrong while adding showtime");
      });
  });
});
