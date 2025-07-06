document.addEventListener("DOMContentLoaded", () => {
  lottie.loadAnimation({
    container: document.getElementById('lottie-player'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'http://localhost/cinema_server/frontend/jsons/movie.json'
  });

 const form = document.getElementById("addCastForm");


  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get("id");
    const actor_name = document.getElementById("actor_name").value;
    const role_name = document.getElementById("role_name").value;

    if (!movieId) {
      alert("Movie ID is missing. Can't add trailer");
      return;
    }

    const MovieCastData = {
      movie_id: movieId,
      actor_name: actor_name,
      role_name: role_name,
    };

    axios.post("http://localhost/cinema_server/backend/create_Moviecast", MovieCastData)
      .then(response => {
        if (response.data.success) {
          alert("Movie cast added successfully");
          window.location.href = `addShowtime.html?id=${movieId}`;
        } else {
          alert("Failed to add Movie Cast: " + response.data.message);
        }
      })
      .catch(error => {
        alert("Something went wrong while adding Movie cast");
      });
  });
});
