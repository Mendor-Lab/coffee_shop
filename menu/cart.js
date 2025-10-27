// Load cart from localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Display cart count in the navbar
document.getElementById("cart-count").innerText = `(${cart.length})`;

// Display cart items on the table
function displayCart() {
    const cartItems = document.getElementById("cart-items");
    const totalElement = document.getElementById("cart-total");
    cartItems.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;

        cartItems.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>R${item.price}</td>
                <td>${item.quantity}</td>
                <td>R${itemTotal.toFixed(2)}</td>
                <td><button onclick="removeItem(${index})">X</button></td>
            </tr>`;
    });

    totalElement.innerText = total.toFixed(2);
}

// Remove item function
function removeItem(index) {
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
}

// Run display function when page loads
displayCart();
function displayCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartItems = document.getElementById('cart-items');
    let total = 0;
    cartItems.innerHTML = "";

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;

        cartItems.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>R${item.price}</td>
                <td><input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)"></td>
                <td>R${itemTotal.toFixed(2)}</td>
                <td><button onclick="removeItem(${index})">X</button></td>
            </tr>
        `;
    });

    document.getElementById('cart-total').innerText = total.toFixed(2);
    document.getElementById('cart-count').innerText = `(${cart.length})`;
}

function updateQuantity(index, newQty) {
    let cart = JSON.parse(localStorage.getItem('cart'));
    cart[index].quantity = parseInt(newQty);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart'));
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

document.addEventListener("DOMContentLoaded", displayCart);
