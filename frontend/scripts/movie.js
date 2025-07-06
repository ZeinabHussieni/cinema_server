
lottie.loadAnimation({
  container: document.getElementById("lottie-player"),
  renderer: "svg",
  loop: true,
  autoplay: true,
  path: "http://localhost/cinema_server/frontend/jsons/movie.json"
});


document.addEventListener("DOMContentLoaded", () => {
  console.log("movie.js loaded!");

  const form = document.getElementById("addMovieForm");
  if (!form) {
    console.error("Form not found!");
    return;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const title = document.getElementById("title").value.trim();
    const description = document.getElementById("description").value.trim();
    const status = document.getElementById("status").value.trim();
    const release_date = document.getElementById("release_date").value;
    const poster_url = document.getElementById("poster_url").value.trim();
    const created_at = new Date().toISOString().slice(0, 19).replace("T", " ");

    const movieData = {
      title,
      description,
      status,
      release_date,
      poster_url,
      created_at
    };

    try {
      const response = await axios.post("http://localhost/cinema_server/backend/create_movie", movieData);

      if (response.data.success) {
        const movieId = response.data.movie_id;
        alert("Movie added. Redirecting to trailer page...");
        window.location.href = `addTrailer.html?id=${movieId}`;
      } else {
        alert("Failed: " + response.data.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Something went wrong. Please try again.");
    }
  });
});
