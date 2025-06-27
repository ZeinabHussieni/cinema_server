lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/logiin.json'  
});

//axois
document.addEventListener("DOMContentLoaded", () => {
  const loginbutton = document.querySelector(".login-button");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password"); 

  loginbutton.addEventListener("click", async (e) => {
    e.preventDefault(); 

    const email = emailInput.value.trim();
    const password = passwordInput.value;

    if (email === "" || password === "") {
      alert("Please enter both email and password.");
      return;
    }

    try {
      const response = await axios.get("http://localhost/cinema-project/backend/controllers/get_users.php", {
        params: {
          email: email,
          password: password
        }
      });

      if (response.data.user) {
        alert("Login successful!");
        localStorage.setItem("userId", response.data.user.id);
        window.location.href = "http://localhost/cinema_server/frontend/Pages/index.html"; 
      } else {
        alert(response.data.message || "User not found.");
      }

    } catch (error) {
      console.error(error);
      alert("Something went wrong. Please try again later.");
    }
  });
});


