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
      const response = await axios.get("http://localhost/cinema_server/backend/controllers/get_users_byemail.php", {
        params: {
          email: email,
          password: password
        }
      });

      if (response.data.user) {
       const user = response.data.user; 
       const userId = user[0];
       const email = user[1];
       const phoneNumber = user[2];

       localStorage.setItem("userId", userId);
       console.log("Saved userId in localStorage:", userId);
        
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


