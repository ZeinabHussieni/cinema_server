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
