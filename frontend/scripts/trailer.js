document.addEventListener("DOMContentLoaded", () => {
  // Lottie animation
  lottie.loadAnimation({
    container: document.getElementById('lottie-player'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'http://localhost/cinema_server/frontend/jsons/movie.json'
  });

 const form = document.getElementById("addTrailerForm");
  if (!form) {
    console.error("Trailer form not found!");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get("id");
    const trailer_url = document.getElementById("trailer_url").value;

    if (!movieId) {
      alert("Movie ID is missing. Can't add trailer");
      return;
    }

    const trailerData = {
      movie_id: movieId,
      trailer_url: trailer_url
    };

    axios.post("http://localhost/cinema_server/backend/controllers/create_trailer.php", trailerData)
      .then(response => {
        if (response.data.success) {
          alert("Trailer added successfully");
          window.location.href = `addMovie_cast.html?id=${movieId}`;
        } else {
          alert("Failed to add trailer: " + response.data.message);
        }
      })
      .catch(error => {
        console.error("Trailer Error:", error);
        alert("Something went wrong while adding trailer");
      });
  });
});