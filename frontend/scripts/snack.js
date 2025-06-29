lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/popcorn.json'  
});
const userId = localStorage.getItem("userId");

if (!userId) {
  alert("You must be logged in to order snacks.");
  throw new Error("User not logged in");
}
//first we have to define them
let selectedTicket = null;
let snackMenu = [];
let selectedSnacks = []; 

//get all tickets 
function fetchUserTickets() {
  return axios.get("http://localhost/cinema_server/backend/controllers/get_usertickets.php", {
    params: { user_id: userId }
  });
}
//get snack menu
function fetchSnackMenu() {
  return axios.get("http://localhost/cinema_server/backend/controllers/get_showtimesnacks.php");
}
//get snack menu per showtime
function fetchSnacksByShowtime(showtime_id) {
  return axios.get("http://localhost/cinema_server/backend/controllers/get_showtimesnacks.php", {
    params: { showtime_id }
  });
}
//allow user to select which ticket he want 
function renderUserTickets(tickets) {
  const container = document.getElementById("userTickets");
  container.innerHTML = "";

  if (!tickets.length) {
    container.textContent = "You have no tickets yet, so no snacks to order.";
    return;
  }
  tickets.forEach(ticket => {
    const div = document.createElement("div");
    div.classList.add("ticket-item");
    div.textContent = `${ticket.movie_title} |${new Date(ticket.show_datetime).toLocaleString()} | Seat: ${ticket.seat_number}`;
    div.dataset.ticketId = ticket.ticket_id;
    div.addEventListener("click", () => {
      selectTicket(ticket);
    });
    container.appendChild(div);
  });
}

function selectTicket(ticket) {
  selectedTicket = ticket;
  document.getElementById("selectedTicketetinformations").textContent = 
    `Selected Ticket: ${ticket.movie_title} - Seat ${ticket.seat_number} at ${new Date(ticket.show_datetime).toLocaleString()}`;

  // fetch snacks for the selected showtime only
  fetchSnacksByShowtime(ticket.showtime_id)
    .then(res => {
      const snacks = res.data.snacks || [];
      renderSnackMenu(snacks);
    })
    .catch(err => {
      console.error("Failed to load snacks for this showtime:", err);
      alert("Snacks for this showtime couldn’t be loaded");
    });
}

//add as much snacks user want
function renderSnackMenu(snacks) {
  const container = document.getElementById("snackMenuContainer");
  container.innerHTML = "";

  if (!selectedTicket) {
    container.textContent = "Select a ticket first to order snacks.";
    return;
  }

  snacks.forEach(snack => {
    const div = document.createElement("div");
    div.classList.add("snack-item");
    div.innerHTML = `
      <span>${snack.snack_name} - $${snack.price.toFixed(2)}</span>
      <input type="number" min="0" max="10" value="0" data-snack-id="${snack.id}" data-snack-price="${snack.price}" />
    `;
    container.appendChild(div);
    
  });
  // adding listeners to all inputs here to calculate
  const inputs = container.querySelectorAll("input[type='number']");
  inputs.forEach(input => {
    input.addEventListener("input", updateTotalPrice);
  });

  //reset total price when loading
  updateTotalPrice();
}

//here to collect snack selections from inputs
function getSelectedSnacks() {
  const inputs = document.querySelectorAll("#snackMenuContainer input[type='number']");
  selectedSnacks = [];

  inputs.forEach(input => {
    const qty = parseInt(input.value);
    if (qty > 0) {
      selectedSnacks.push({
        snack_id: parseInt(input.dataset.snackId),
        quantity: qty,
        price: parseFloat(input.dataset.snackPrice)
      });
    }
  });
}

//ordering 
function submitSnackOrder() {
  if (!selectedTicket) {
    alert("Please select a ticket to deliver your snacks to.");
    return;
  }
  getSelectedSnacks();

  if (selectedSnacks.length === 0) {
    alert("Please select at least one snack to order.");
    return;
  }

  const order = selectedSnacks.map(snack => {
    return axios.post("http://localhost/cinema_server/backend/controllers/order_snack.php", {
      user_id: userId,
      ticket_id: selectedTicket.ticket_id,
      movie_id: selectedTicket.movie_id,
      showtime_id: selectedTicket.showtime_id,
      seat_number: selectedTicket.seat_number,
      snack_id: snack.snack_id,
      quantity: snack.quantity,
      price: snack.price * snack.quantity
    });
  });

  Promise.all(order)
    .then(responses => {
      alert("Snacks ordered successfully");
      resetSnackOrder();
    })
    .catch(err => {
      console.error("Error ordering snacks:", err);
      alert("Failed to place snack order. Try again.");
    });
}
//then reset and load everything
function resetSnackOrder() {
  selectedTicket = null;
  selectedSnacks = [];
  document.getElementById("selectedTicketetinformations").textContent = "";
  document.getElementById("userTickets").innerHTML = "";
  document.getElementById("snackMenuContainer").innerHTML = "";
  loadPageData();
}
function loadPageData() {
  Promise.all([fetchUserTickets(), fetchSnackMenu()])
    .then(([ticketsRes, snacksRes]) => {
      const tickets = ticketsRes.data.tickets || [];
      snackMenu = snacksRes.data.snacks || [];
      renderUserTickets(tickets);
    })
    .catch(err => {
      console.error("Error loading data:", err);
      alert("Failed to load your tickets or snacks.");
    });
}
// Event listener for order button
document.getElementById("orderSnacksButton").addEventListener("click", submitSnackOrder);

//loading data when script runs
loadPageData();
//to calculate total price when getting data
function updateTotalPrice() {
  const inputs = document.querySelectorAll("#snackMenuContainer input[type='number']");
  let total = 0;
  inputs.forEach(input => {
    const quantity = parseInt(input.value) || 0;
    const price = parseFloat(input.dataset.snackPrice) || 0;
    total += quantity * price;
  });
  document.getElementById("totalPrice").textContent = `Total Price: $${total.toFixed(2)}`;
}
