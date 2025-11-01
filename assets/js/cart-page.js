function displayCart() {
    const cart = getCart();
    const cartContent = document.getElementById('cartContent');

    if (cart.length === 0) {
        cartContent.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Add some delicious items from our menu to get started!</p>
                <a href="menu.php" class="primary-button">Browse Menu</a>
            </div>
        `;
        return;
    }

    const subtotal = getCartTotal();
    const tax = subtotal * 0.15;
    const total = subtotal + tax;

    cartContent.innerHTML = `
        <div class="cart-container">
            <div class="cart-items">
                <h3>Cart Items (${getCartItemCount()})</h3>
                ${cart.map(item => `
                    <div class="cart-item" data-id="${item.id}">
                        <div class="cart-item-image">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-header">
                                <h4 class="cart-item-name">${item.name}</h4>
                                <span class="cart-item-price">R${(item.price * item.quantity).toFixed(2)}</span>
                            </div>
                            <div class="cart-item-actions">
                                <div class="quantity-controls">
                                    <button class="quantity-btn decrease-qty" data-id="${item.id}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="quantity-display">${item.quantity}</span>
                                    <button class="quantity-btn increase-qty" data-id="${item.id}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <button class="remove-btn" data-id="${item.id}">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('')}
                <div class="cart-actions">
                    <a href="menu.php" class="continue-shopping">Continue Shopping</a>
                    <button class="clear-cart" id="clearCartBtn">Clear Cart</button>
                </div>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span class="amount">R${subtotal.toFixed(2)}</span>
                </div>
                <div class="summary-row">
                    <span>Tax (15%):</span>
                    <span class="amount">R${tax.toFixed(2)}</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span class="amount">R${total.toFixed(2)}</span>
                </div>

                <div class="checkout-section">
                    <h4>Customer Details</h4>
                    <form id="checkoutForm" class="checkout-form">
                        <div class="form-group">
                            <label for="customerName">Full Name *</label>
                            <input type="text" id="customerName" name="customerName" class="form-control" required>
                            <span class="error-message" id="customerNameError"></span>
                        </div>
                        <div class="form-group">
                            <label for="customerEmail">Email *</label>
                            <input type="email" id="customerEmail" name="customerEmail" class="form-control" required>
                            <span class="error-message" id="customerEmailError"></span>
                        </div>
                        <div class="form-group">
                            <label for="customerPhone">Phone *</label>
                            <input type="tel" id="customerPhone" name="customerPhone" class="form-control" required>
                            <span class="error-message" id="customerPhoneError"></span>
                        </div>
                        <div class="form-group">
                            <label for="customerAddress">Delivery Address *</label>
                            <textarea id="customerAddress" name="customerAddress" class="form-control" rows="3" required></textarea>
                            <span class="error-message" id="customerAddressError"></span>
                        </div>
                        <button type="submit" class="primary-button checkout-btn">
                            <span class="btn-text">Place Order - R${total.toFixed(2)}</span>
                            <span class="btn-loader" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Processing...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    `;

    attachCartEventListeners();
}

function attachCartEventListeners() {
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            const cart = getCart();
            const item = cart.find(item => item.id === id);
            if (item) {
                updateQuantity(id, item.quantity + 1);
                displayCart();
            }
        });
    });

    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            const cart = getCart();
            const item = cart.find(item => item.id === id);
            if (item && item.quantity > 1) {
                updateQuantity(id, item.quantity - 1);
                displayCart();
            }
        });
    });

    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            if (confirm('Are you sure you want to remove this item?')) {
                removeFromCart(id);
                displayCart();
                showNotification('Item removed from cart');
            }
        });
    });

    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                clearCart();
                displayCart();
                showNotification('Cart cleared');
            }
        });
    }

    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', handleCheckout);
    }
}

function validateCheckoutForm() {
    let isValid = true;
    clearCheckoutErrors();

    const name = document.getElementById('customerName').value.trim();
    const email = document.getElementById('customerEmail').value.trim();
    const phone = document.getElementById('customerPhone').value.trim();
    const address = document.getElementById('customerAddress').value.trim();

    if (name.length < 2) {
        showCheckoutError('customerName', 'Name must be at least 2 characters');
        isValid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showCheckoutError('customerEmail', 'Please enter a valid email');
        isValid = false;
    }

    const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    if (!phoneRegex.test(phone)) {
        showCheckoutError('customerPhone', 'Please enter a valid phone number');
        isValid = false;
    }

    if (address.length < 10) {
        showCheckoutError('customerAddress', 'Please provide a complete address');
        isValid = false;
    }

    return isValid;
}

function showCheckoutError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + 'Error');
    const inputElement = document.getElementById(fieldId);

    if (errorElement && inputElement) {
        errorElement.textContent = message;
        inputElement.classList.add('error');
    }
}

function clearCheckoutErrors() {
    ['customerName', 'customerEmail', 'customerPhone', 'customerAddress'].forEach(fieldId => {
        const errorElement = document.getElementById(fieldId + 'Error');
        const inputElement = document.getElementById(fieldId);

        if (errorElement && inputElement) {
            errorElement.textContent = '';
            inputElement.classList.remove('error');
        }
    });
}

async function handleCheckout(e) {
    e.preventDefault();

    if (!validateCheckoutForm()) {
        return;
    }

    const submitBtn = e.target.querySelector('.checkout-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    submitBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoader.style.display = 'inline-block';

    const orderData = {
        customer_name: document.getElementById('customerName').value.trim(),
        customer_email: document.getElementById('customerEmail').value.trim(),
        customer_phone: document.getElementById('customerPhone').value.trim(),
        delivery_address: document.getElementById('customerAddress').value.trim(),
        items: getCart(),
        subtotal: getCartTotal(),
        tax: getCartTotal() * 0.15,
        total: getCartTotal() * 1.15
    };

    try {
        const response = await fetch('php/process-order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();

        if (result.success) {
            clearCart();
            displayOrderSuccess(result.order_id);
        } else {
            throw new Error(result.message || 'Order processing failed');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Sorry, there was an error processing your order. Please try again.');
        submitBtn.disabled = false;
        btnText.style.display = 'inline-block';
        btnLoader.style.display = 'none';
    }
}

function displayOrderSuccess(orderId) {
    const cartContent = document.getElementById('cartContent');
    cartContent.innerHTML = `
        <div class="order-success">
            <i class="fas fa-check-circle"></i>
            <h3>Order Placed Successfully!</h3>
            <p>Thank you for your order. We've received your request and will process it shortly.</p>
            <div class="order-id">Order ID: ${orderId}</div>
            <p>A confirmation email has been sent to your email address.</p>
            <a href="menu.php" class="primary-button" style="margin-top: 1rem;">Continue Shopping</a>
        </div>
    `;
}

function showNotification(message) {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        notification.classList.add('show');

        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    displayCart();
});
