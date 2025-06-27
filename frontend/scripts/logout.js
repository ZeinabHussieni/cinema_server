document.getElementById('logout').addEventListener('click', function(e) {
  e.preventDefault(); 
  localStorage.removeItem('userId');
  window.location.href = 'http://localhost/cinema_server/frontend/Pages/login.html';
});
