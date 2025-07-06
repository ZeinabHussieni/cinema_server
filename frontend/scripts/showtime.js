document.addEventListener("DOMContentLoaded", () => {
  lottie.loadAnimation({
    container: document.getElementById("lottie-player"),
    renderer: "svg",
    loop: true,
    autoplay: true,
    path: "http://localhost/cinema_server/frontend/jsons/movie.json",
  });

  const form = document.getElementById("addShowtimeForm");
  const addAnotherBtn = document.getElementById("addAnotherBtn");
  const next = document.getElementById("next");

  if (!form || !addAnotherBtn || !next) {
    console.error("Required elements missing!");
    return;
  }

  const urlParams = new URLSearchParams(window.location.search);
  const movieId = urlParams.get("id");

  function collectShowtimeData() {
    return {
      movie_id: movieId,
      show_datetime: document.getElementById("show_datetime").value,
      capacity: document.getElementById("capacity").value,
      ticket_price: document.getElementById("ticket_price").value,
      created_at: new Date().toISOString().slice(0, 19).replace("T", " "),
    };
  }

  function validateShowtime(data) {
    return (
      data.movie_id &&
      data.show_datetime &&
      data.capacity &&
      data.ticket_price
    );
  }

  function saveShowtimeAndRedirect(redirectUrl) {
    const data = collectShowtimeData();

    if (!validateShowtime(data)) {
      alert("Please fill all fields!");
      return;
    }

    axios
      .post(
        "http://localhost/cinema_server/backend/create_Showtime",
        data
      )
      .then((response) => {
        if (response.data.success) {
          alert("Showtime saved!");
          window.location.href = redirectUrl;
        } else {
          alert("Failed to save showtime: " + response.data.message);
        }
      })
      .catch((error) => {
        console.error("Showtime save error:", error);
        alert("Error saving showtime.");
      });
  }

  addAnotherBtn.addEventListener("click", () => {
    saveShowtimeAndRedirect(`addShowtime.html?id=${movieId}`);
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    saveShowtimeAndRedirect(`addRating.html?id=${movieId}`);
  });
});
