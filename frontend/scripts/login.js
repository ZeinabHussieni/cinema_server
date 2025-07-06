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
      const response = await axios.get("http://localhost/cinema_server/backend/get_userbyemail", {
        params: { email, password }
      });

      const user = response.data; 

      if (user) {
        const userId = user.id;
        const userEmail = user.email;

        localStorage.setItem("userId", userId);
        localStorage.setItem("email", userEmail);

        const isAdmin = (userEmail === "ZeinabAdmin@gmail.com" && password === "Ah.2392002");
        localStorage.setItem("isAdmin", isAdmin ? "true" : "false");

        window.location.href = "http://localhost/cinema_server/frontend/Pages/index.html";
      } else {
        alert("User not found.");
      }

    } catch (error) {
      console.error(error);
      alert("Something went wrong. Please try again later.");
    }
  });
});
