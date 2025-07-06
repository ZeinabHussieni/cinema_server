//call user
const userId = localStorage.getItem("userId");

if (!userId) {
  alert("You must be logged in to order snacks.");
  throw new Error("User not logged in");
}
//animation
lottie.loadAnimation({
  container: document.getElementById('lottie-player'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: 'http://localhost/cinema_server/frontend/jsons/popcorn.json'
});
//globals
let selectedTicket = null;
let snackMenu = [];
let selectedSnacks = [];

//fetch tickets to know seats
function fetchUserTickets() {
  return axios.get("http://localhost/cinema_server/backend/get_UserTicket", {
    params: { user_id: userId }
  });
}
//menu for each showtime
function fetchSnackMenu() {
  return axios.get("http://localhost/cinema_server/backend/get_ShowtimeSnacks");
}

function fetchSnacksByShowtime(showtime_id) {
  return axios.get("http://localhost/cinema_server/backend/get_ShowtimeSnacks", {
    params: { showtime_id }
  });
}


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
    div.textContent = `${ticket.movie_title} | ${new Date(ticket.show_datetime).toLocaleString()} | Seat: ${ticket.seat_number}`;
    div.dataset.ticketId = ticket.ticket_id;
    div.addEventListener("click", () => selectTicket(ticket));
    container.appendChild(div);
  });
}

function selectTicket(ticket) {
  selectedTicket = ticket;
  document.getElementById("selectedTicketetinformations").textContent = 
    `Selected Ticket: ${ticket.movie_title} - Seat ${ticket.seat_number} at ${new Date(ticket.show_datetime).toLocaleString()}`;

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

  container.querySelectorAll("input[type='number']").forEach(input => {
    input.addEventListener("input", updateTotalPrice);
  });

  updateTotalPrice();
}

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

function submitSnackOrder() {
  if (!selectedTicket) return alert("Please select a ticket to deliver your snacks to.");

  getSelectedSnacks();
  if (selectedSnacks.length === 0) return alert("Please select at least one snack to order.");

  const orders = selectedSnacks.map(snack => axios.post("http://localhost/cinema_server/backend/Order_snack", {
    user_id: userId,
    ticket_id: selectedTicket.ticket_id,
    movie_id: selectedTicket.movie_id,
    showtime_id: selectedTicket.showtime_id,
    seat_number: selectedTicket.seat_number,
    snack_id: snack.snack_id,
    quantity: snack.quantity,
    price: snack.price * snack.quantity
  }));

  Promise.all(orders)
    .then(() => {
      alert("Snacks ordered successfully");
      resetSnackOrder();
    })
    .catch(err => {
      console.error("Error ordering snacks:", err);
      alert("Failed to place snack order. Try again.");
    });
}

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

document.getElementById("orderSnacksButton").addEventListener("click", submitSnackOrder);
loadPageData();
