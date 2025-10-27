function loadCheckout() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let checkoutItems = document.getElementById('checkout-items');
    let total = 0;

    checkoutItems.innerHTML = "";
    cart.forEach(item => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;
        checkoutItems.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>R${itemTotal.toFixed(2)}</td>
            </tr>
        `;
    });

    document.getElementById('checkout-total').innerText = total.toFixed(2);
    document.getElementById('final-total').innerText = total.toFixed(2);
    document.getElementById('cart-count').innerText = `(${cart.length})`;
}

// ✅ Toggle options based on delivery or collection
function toggleOptions() {
    let orderType = document.querySelector('input[name="orderType"]:checked').value;
    if (orderType === "delivery") {
        document.getElementById("delivery-address").style.display = "block";
        document.getElementById("collection-time").style.display = "none";
        document.getElementById("delivery-fee").style.display = "block";

        let subtotal = parseFloat(document.getElementById('checkout-total').innerText);
        document.getElementById('final-total').innerText = (subtotal + 20).toFixed(2);
    } else {
        document.getElementById("delivery-address").style.display = "none";
        document.getElementById("collection-time").style.display = "block";
        document.getElementById("delivery-fee").style.display = "none";

        let subtotal = parseFloat(document.getElementById('checkout-total').innerText);
        document.getElementById('final-total').innerText = subtotal.toFixed(2);
    }
}

// ✅ Submit order
function submitOrder() {
    let name = document.getElementById("name").value;
    let phone = document.getElementById("phone").value;
    let orderType = document.querySelector('input[name="orderType"]:checked').value;
    let address = document.getElementById("address").value;
    let paymentMethod = document.querySelector('input[name="payment"]:checked').value;

    if (orderType === "delivery" && address.trim() === "") {
        alert("Please enter delivery address.");
        return;
    }

    document.getElementById("orderSuccess").style.display = "block";
    document.getElementById("orderSuccess").innerText = 
        `✅ Order placed successfully, ${name}!\nPayment Method: ${paymentMethod}`;

    localStorage.removeItem('cart');
    loadCheckout();
    document.getElementById("backToMenu").style.display = "block";
}

document.addEventListener("DOMContentLoaded", loadCheckout);
