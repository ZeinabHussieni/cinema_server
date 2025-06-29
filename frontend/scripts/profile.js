lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/profile.json'  
});
const userId = localStorage.getItem("userId");


//axois for show info
document.addEventListener("DOMContentLoaded",async()=>{
    const userId = localStorage.getItem("userId");
  console.log("userId from localStorage:", userId);
  if (!userId) {
    alert("login first");
    window.location.href = "http://localhost/cinema_server/frontend/Pages/login.html"; 
    return; 
          }
    if(!userId){
        alert("user not found");
        return;
    }
    try{
        const response=await axios.get("http://localhost/cinema_server/backend/controllers/get_users.php",
           { params:{
                id:userId
            }
             });
         if (response.data.user) {
          const user = response.data.user; 
          const email = user[1];
          const phoneNumber = user[2];
          const password = user[3];
          const favoriteGenres =user[4];
          const paymentMethod=user[5];
          const communicationPrefs=user[6];

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
     console.error(error);
     alert("Failed to load user data");
    }
   });
//axois for update user
document.addEventListener("DOMContentLoaded", () => {
  const savebutton = document.querySelector(".save-button");
  const emailInput = document.getElementById("email");
  const phoneNumberInput = document.getElementById("PhoneNumber");
  const passwordInput = document.getElementById("password"); 
  const favoriteGenresInput=document.getElementById("favoriteGenres");
  const paymentMethodInput=document.getElementById("paymentMethod");
  const communicationPrefsInput=document.getElementById("communicationPrefs");
    
  savebutton.addEventListener("click", async (e) => {
    e.preventDefault(); 

    const email = emailInput.value.trim();
    const phoneNumber = phoneNumberInput.value.trim();
    const password = passwordInput.value;
    const favoriteGenres = favoriteGenresInput.value.trim();
    const paymentMethod = paymentMethodInput.value.trim();
    const communicationPrefs = communicationPrefsInput.value.trim();
    if (email === "" || password === "" || phoneNumber === ""||favoriteGenres===""||paymentMethod===""||communicationPrefs=="") {
      alert("Please enter email, phone number, and password.");
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
      const response = await axios.post("http://localhost/cinema_server/backend/controllers/update_users.php", formData);

    console.log(response.data);

     if (response.data.status == 200 || response.data.status === "200")
    {
        alert("Changed successful");
        window.location.href = "../Pages/index.html";
      } else {
        alert(response.data.message || "Changes failed");
      }
    } catch (error) {
      console.error(error);
      alert("Something went wrong. Please try again later.");
    }
  });
});
