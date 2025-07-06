
lottie.loadAnimation({
  container: document.getElementById("lottie-player"),
  renderer: "svg",
  loop: true,
  autoplay: true,
  path: "http://localhost/cinema_server/frontend/jsons/profile.json"
});



document.addEventListener("DOMContentLoaded", async () => {
  const userId = localStorage.getItem("userId");
  console.log("userId from localStorage:", userId);

  if (!userId || userId === "undefined") {
    alert("Login first");
    window.location.href = "http://localhost/cinema_server/frontend/Pages/login.html";
    return;
  }

  try {
    const response = await axios.get("http://localhost/cinema_server/backend/user", {
      params: { id: userId }
    });

    if (response.data.user) {
      const [_, email, phoneNumber, password, favoriteGenres, paymentMethod, communicationPrefs] = response.data.user;

      document.getElementById("email").value = email;
      document.getElementById("PhoneNumber").value = phoneNumber;
      document.getElementById("password").value = password;
      document.getElementById("favoriteGenres").value = favoriteGenres;
      document.getElementById("paymentMethod").value = paymentMethod;
      document.getElementById("communicationPrefs").value = communicationPrefs;
    } else {
      alert(response.data.message || "User not found.");
    }
  } catch (error) {
    console.error("User load error:", error);
    alert("Failed to load user data");
  }
});



document.addEventListener("DOMContentLoaded", () => {
  const saveButton = document.querySelector(".save-button");

  saveButton.addEventListener("click", async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const phoneNumber = document.getElementById("PhoneNumber").value.trim();
    const password = document.getElementById("password").value;
    const favoriteGenres = document.getElementById("favoriteGenres").value.trim();
    const paymentMethod = document.getElementById("paymentMethod").value.trim();
    const communicationPrefs = document.getElementById("communicationPrefs").value.trim();
    const userId = localStorage.getItem("userId");

    if (!email || !password || !phoneNumber || !favoriteGenres || !paymentMethod || !communicationPrefs) {
      alert("Please fill out all the fields.");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("id", userId);
      formData.append("email", email);
      formData.append("phoneNumber", phoneNumber);
      formData.append("password", password);
      formData.append("favoriteGenres", favoriteGenres);
      formData.append("paymentMethod", paymentMethod);
      formData.append("communicationPrefs", communicationPrefs);

      const response = await axios.post("http://localhost/cinema_server/backend/update_user", formData);
      console.log("Update response:", response.data);

      if (response.data.status == 200 || response.data.status === "200") {
        alert("Changes saved successfully");
        window.location.href = "../Pages/homePage.html";
      } else {
        alert(response.data.message || "Changes failed.");
      }
    } catch (error) {
      console.error("Update error:", error);
      alert("Something went wrong. Please try again later.");
    }
  });
});
