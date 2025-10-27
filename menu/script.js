// -----------------------
// CART FUNCTIONALITY
// -----------------------
let cart = [];
let total = 0;

function addToCart(itemName, itemPrice, qty = 1) {
    const existing = cart.find(i => i.name === itemName);
    if (existing) {
        existing.qty += qty;
        existing.price += itemPrice * qty;
    } else {
        cart.push({ name: itemName, price: itemPrice * qty, qty: qty });
    }
    updateCart();
}

function updateCart() {
    const cartItems = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");
    const cartCount = document.getElementById("cart-count");

    cartItems.innerHTML = "";
    total = 0;
    let count = 0;

    cart.forEach((item, index) => {
        total += item.price;
        count += item.qty;
        cartItems.innerHTML += `
            <li>
                ${item.name} - R${item.price} 
                (Qty: <input type="number" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)">)
                <button onclick="removeItem(${index})">Remove</button>
            </li>`;
    });

    cartTotal.innerText = `Total: R${total}`;
    cartCount.innerText = count;
}

function updateQty(index, qty) {
    qty = parseInt(qty);
    if(qty < 1) qty = 1;
    let pricePerItem = cart[index].price / cart[index].qty;
    cart[index].qty = qty;
    cart[index].price = pricePerItem * qty;
    updateCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

// -----------------------
// SEARCH BAR FUNCTIONALITY
// -----------------------
function searchItems() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let items = document.getElementsByClassName('menu-item');

    for (let i = 0; i < items.length; i++) {
        let itemName = items[i].querySelector('h3').innerText.toLowerCase();
        items[i].style.display = itemName.includes(input) ? "block" : "none";
    }
}

// -----------------------
// TOGGLE BETWEEN MENU SECTIONS
// -----------------------
function showSection(sectionId) {
    let sections = document.getElementsByClassName('menu-section');
    for (let i = 0; i < sections.length; i++) {
        sections[i].style.display = "none";
    }
    document.getElementById(sectionId).style.display = "block";
}

// -----------------------
// DELIVERY ADDRESS TOGGLE
// -----------------------
function toggleAddress() {
    const deliveryType = document.getElementById("deliveryType").value;
    const addressLabel = document.getElementById("addressLabel");
    if(deliveryType === "Collect") {
        addressLabel.style.display = "none";
    } else {
        addressLabel.style.display = "block";
    }
}

// -----------------------
// PLACE ORDER
// -----------------------
function placeOrder() {
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let address = document.getElementById("address").value;
    let deliveryType = document.getElementById("deliveryType").value;

    if(cart.length === 0){
        alert("Your cart is empty!");
        return;
    }

    if(deliveryType === "Delivery" && address === ""){
        alert("Please enter your delivery address.");
        return;
    }

    // Show success message
    document.getElementById("successMessage").style.display = "block";
    document.getElementById("successMessage").innerText = 
        `✅ Order Successfully Placed! Thank you ${name}.`;

    // Clear cart and form
    cart = [];
    updateCart();
    document.getElementById("orderForm").reset();
    function updateCart() {
    localStorage.setItem("cartItems", JSON.stringify(cart)); 
    window.location.href = "menu.html?updated=true";
}
}

// -----------------------
// CLOSE CART
// -----------------------
function closeCart(){
    document.getElementById('cartSidebar').style.display='none';
}
function addToCart(name, price) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    let existingItem = cart.find(item => item.name === name);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ name: name, price: price, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));

    updateCartCount();
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    document.getElementById("cart-count").innerText = `(${cart.length})`;
}

document.addEventListener("DOMContentLoaded", updateCartCount);
