let menuData = [];
let currentCategory = 'All';

async function loadMenu() {
    try {
        const response = await fetch('data/menu.json');
        if (!response.ok) throw new Error('Failed to load menu');
        menuData = await response.json();
        displayMenu(menuData);
    } catch (error) {
        console.error('Error loading menu:', error);
        document.getElementById('menuGrid').innerHTML = '<div class="no-items">Failed to load menu. Please try again later.</div>';
    }
}

// -----------------------
// SEARCH BAR FUNCTIONALITY
// -----------------------
function searchItems() {
    const input = document.getElementById('searchInput');
    const term = (input ? input.value : '').trim().toLowerCase();

    // Start from the current category selection
    let base = currentCategory === 'All'
        ? menuData.slice()
        : menuData.filter(item => (item.category === currentCategory));

    // Apply search term across name and description
    if (term.length > 0) {
        base = base.filter(item => {
            const name = (item.name || '').toLowerCase();
            const desc = (item.description || '').toLowerCase();
            return name.includes(term) || desc.includes(term);
        });
    }

    // Re-render the grid with the filtered items
    displayMenu(base);
}

function displayMenu(items) {
    const menuGrid = document.getElementById('menuGrid');

    if (items.length === 0) {
        menuGrid.innerHTML = '<div class="no-items">No items found in this category.</div>';
        return;
    }

    menuGrid.innerHTML = items.map(item => `
        <div class="menu-item" data-category="${item.category}">
            <div class="menu-item-image">
                <img src="${item.image}" alt="${item.name}" loading="lazy">
                <span class="menu-item-badge">${item.category}</span>
            </div>
            <div class="menu-item-content">
                <div class="menu-item-header">
                    <h3 class="menu-item-name">${item.name}</h3>
                    <span class="menu-item-price">R${item.price.toFixed(2)}</span>
                </div>
                <p class="menu-item-description">${item.description}</p>
                <div class="menu-item-actions">
                    <button class="add-to-cart-btn"
                            data-id="${item.id}"
                            data-name="${item.name}"
                            data-price="${item.price}"
                            data-image="${item.image}">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    attachCartListeners();
}

function filterMenu(category) {
    currentCategory = category;
    // Reuse search logic so category + current search term are applied together
    searchItems();
}

function attachCartListeners() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const item = {
                id: parseInt(this.dataset.id),
                name: this.dataset.name,
                price: parseFloat(this.dataset.price),
                image: this.dataset.image,
                quantity: 1
            };
            addToCart(item);
            showNotification(`${item.name} added to cart!`);
        });
    });
}

function showNotification(message) {
    const notification = document.getElementById('notification');
    notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    notification.classList.add('show');

    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    loadMenu();

    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterMenu(this.dataset.category);
        });
    });
});
