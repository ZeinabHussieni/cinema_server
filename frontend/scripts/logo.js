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
