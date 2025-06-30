lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/movie.json'  
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("addMovieForm");
console.log("movie.js loaded!");

  if (!form) {
    console.error("Form not found!");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault(); 

    const title = document.getElementById("title").value;
    const description = document.getElementById("description").value;
    const status = document.getElementById("status").value;
    const release_date = document.getElementById("release_date").value;
    const poster_url = document.getElementById("poster_url").value;
    const created_at = new Date().toISOString().slice(0, 19).replace("T", " ");

    const movieData = {
      title,
      description,
      status,
      release_date,
      poster_url,
      created_at,
    };

    axios
      .post("http://localhost/cinema_server/backend/controllers/create_movie.php", movieData)
      .then((response) => {
  if (response.data.success) {
    const movieId = response.data.movie_id;
    alert("Movie added! Redirecting to trailer page ");
    window.location.href = `addTrailer.html?id=${movieId}`;

  }else {alert("Failed: " + response.data.message);}
      }).catch((error) => {
        console.error("Error:", error);
        alert("Something went wrong");
      });
  });
});