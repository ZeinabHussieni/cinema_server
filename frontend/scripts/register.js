lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/register.json'  
});

document.addEventListener("DOMContentLoaded", () => {
  const emailInput = document.getElementById("email");
  const phoneNumberInput = document.getElementById("PhoneNumber"); 
  const passwordInput = document.getElementById("password"); 
  const registerbutton = document.querySelector(".register-button");

  registerbutton.addEventListener("click", async (e) => {
    e.preventDefault(); 

    const email = emailInput.value.trim();
    const phoneNumber = phoneNumberInput.value.trim();
    const password = passwordInput.value;

    if (email === "" || password === "" || phoneNumber === "") {
      alert("Please enter email, phone number, and password.");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("email", email);
      formData.append("phoneNumber", phoneNumber);
      formData.append("password", password);

      const response = await axios.post("http://localhost/cinema_server/backend/controllers/get_users.php", formData);

      if (response.data.status === 200) {
        alert("Register successful");
        window.location.href = "../Pages/index.html";
      } else {
        alert(response.data.message || "Registration failed");
      }
    } catch (error) {
      console.error(error);
      alert("Something went wrong. Please try again later.");
    }
  });
});
