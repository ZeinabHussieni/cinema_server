lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/logo.json'  
});
lottie.loadAnimation({
  container: document.getElementById('lottie2-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/action.json'  
});
lottie.loadAnimation({
  container: document.getElementById('upcoming-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/upcoming.json'  
});

const profilePic = document.querySelector('.pro-pic');
const dropdown = document.querySelector('.dropdown');

  profilePic.addEventListener('click', () => {
      dropdown.classList.toggle('show');
  });

  window.addEventListener('click', (e) => {
      if (!profilePic.contains(e.target)) {
        dropdown.classList.remove('show');
      }
  });


document.addEventListener("DOMContentLoaded", async () => {
  try {
    const response = await axios.get("http://localhost/cinema_server/backend/controllers/get_movies.php");
    console.log("Response data:", response.data);
    const movies = response.data.movies; 
    const Current = document.querySelectorAll(".current-movies")[0];
    const upcoming = document.querySelectorAll(".current-movies")[1];

    movies.forEach((movie) => {
      const ratingStars = generateStars(movie.ratings || 0);
      const movieCard = `
        <div class="card">
          <img src="${movie.poster_url}" alt="${movie.title}" />
          <h3>${movie.title.toUpperCase()}</h3>
          <div class="rating">${ratingStars}</div>
          <a href="./frontend/Pages/Description.html?id=${movie.id}">Learn More</a>
        </div>
      `;
      if (movie.status === "Current") {
        Current.innerHTML += movieCard;
      } else if (movie.status === "Upcoming") {
        upcoming.innerHTML += movieCard;
      }
    });

  } catch (error) {
    console.error("Failed to fetch movies:", error);
  }
});

function generateStars(rating) {
  rating = Number(rating);
  if (isNaN(rating) || rating < 0) rating = 0;
  if (rating > 5) rating = 5;

  const fullStars = Math.floor(rating);
  const halfStar = rating % 1 >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;

  let starsHTML = "";

  for (let i = 0; i < fullStars; i++) {
    starsHTML += `<i class="fas fa-star"></i>`;
  }
  if (halfStar) {
    starsHTML += `<i class="fas fa-star-half-alt"></i>`;
  }
  for (let i = 0; i < emptyStars; i++) {
    starsHTML += `<i class="far fa-star"></i>`;
  }

  return starsHTML;
}
