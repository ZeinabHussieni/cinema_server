document.addEventListener("DOMContentLoaded", () => {
  lottie.loadAnimation({
    container: document.getElementById('lottie-player'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'http://localhost/cinema_server/frontend/jsons/movie.json'
  });

  const form = document.getElementById("addCastForm");
  if (!form) {
    console.error("Cast form not found!");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get("id"); 
    const actor_name = document.getElementById("actor_name").value;
    const role_name = document.getElementById("role_name").value;
    if (!movieId) {
      alert("Missing movie ID. Can't assign cast!");
      return;
    }
    const castData = {
      movie_id: movieId,
      actor_name,
      role_name
    };
    axios.post("http://localhost/cinema_server/backend/controllers/create_moviecast.php", castData)
      .then(response => {
        if (response.data.success) {
          alert("Cast added successfully");
         
          window.location.href = `addShowtime.html?id=${movieId}`;

        } else {
          alert("Failed to add cast: " + response.data.message);
        }
      })
      .catch(error => {
        console.error("Cast error:", error);
        alert("Something went wrong");
      });
  });
});
