function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function optionsKey(options){
    if(!options) return '';
    try {
        const norm = {
            size: options.size?.label||null,
            milk: options.milk?.label||null,
            sugar: (typeof options.sugar==='number')? options.sugar : null,
            extraShots: options.extraShots?.count||0,
            extras: Array.isArray(options.extras)? options.extras.map(e=>e.key).sort() : []
        };
        return JSON.stringify(norm);
    } catch(e){ return ''; }
}

function addToCart(item) {
    let cart = getCart();
    const key = item.id + '|' + optionsKey(item.options);
    const existingItem = cart.find(cartItem => (cartItem.id + '|' + optionsKey(cartItem.options)) === key);

    if (existingItem) {
        existingItem.quantity += item.quantity || 1;
        // Recalculate line total if unitPrice present
        if (typeof existingItem.unitPrice === 'number') {
            existingItem.lineTotal = Number((existingItem.unitPrice * existingItem.quantity).toFixed(2));
        }
    } else {
        // ensure unit/line totals exist for new shape
        if (typeof item.unitPrice === 'number' && typeof item.lineTotal !== 'number') {
            item.lineTotal = Number((item.unitPrice * (item.quantity||1)).toFixed(2));
        }
        cart.push(item);
    }

    saveCart(cart);
}

function removeFromCart(itemId) {
    // Backward-compatible removal by id (removes all variants)
    let cart = getCart();
    cart = cart.filter(item => item.id !== itemId);
    saveCart(cart);
}

function removeFromCartByKey(key){
    let cart = getCart();
    cart = cart.filter(item => (item.id + '|' + optionsKey(item.options)) !== key);
    saveCart(cart);
}

function updateQuantity(itemId, quantity) {
    // Backward-compatible by id: updates first match
    let cart = getCart();
    const item = cart.find(cartItem => cartItem.id === itemId);

    if (item) {
        if (quantity <= 0) {
            removeFromCart(itemId);
        } else {
            item.quantity = quantity;
            if (typeof item.unitPrice === 'number') {
                item.lineTotal = Number((item.unitPrice * item.quantity).toFixed(2));
            }
            saveCart(cart);
        }
    }
}

function updateQuantityByKey(key, quantity){
    let cart = getCart();
    const item = cart.find(ci => (ci.id + '|' + optionsKey(ci.options)) === key);
    if (item){
        if (quantity <= 0){
            removeFromCartByKey(key);
        } else {
            item.quantity = quantity;
            if (typeof item.unitPrice === 'number') {
                item.lineTotal = Number((item.unitPrice * item.quantity).toFixed(2));
            }
            saveCart(cart);
        }
    }
}

function clearCart() {
    localStorage.removeItem('cart');
    updateCartCount();
}

function getCartTotal() {
    const cart = getCart();
    return cart.reduce((total, item) => {
        const unit = (typeof item.unitPrice === 'number') ? item.unitPrice : Number(item.price||0);
        const qty = Number(item.quantity||1);
        return total + (unit * qty);
    }, 0);
}

function getCartItemCount() {
    const cart = getCart();
    return cart.reduce((count, item) => count + item.quantity, 0);
}

function updateCartCount() {
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        const count = getCartItemCount();
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'flex' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
