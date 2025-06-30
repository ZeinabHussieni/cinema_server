document.addEventListener("DOMContentLoaded", () => {
  // Lottie animation
  lottie.loadAnimation({
    container: document.getElementById("lottie-player"),
    renderer: "svg",
    loop: true,
    autoplay: true,
    path: "http://localhost/cinema_server/frontend/jsons/movie.json",
  });

  const form = document.getElementById("addRatingForm");
  if (!form) {
    console.error("Rating form not found!");
    return;
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get("id");
    const rating = document.getElementById("rating").value;

    if (!movieId) {
      alert("Movie ID is missing — can’t add rating");
      return;
    }

    const ratingData = {
      movie_id: movieId,
      rating: rating,
    };

    axios
      .post("http://localhost/cinema_server/backend/controllers/create_rating.php", ratingData)
      .then((response) => {
        if (response.data.success) {
          alert("Rating added successfully");
          window.location.href = "index.html";
        } else {
          alert("Failed to add rating: " + response.data.message);
        }
      })
      .catch((error) => {
        console.error("Rating Error:", error);
        alert("Something went wrong while adding rating");
      });
  });
});
