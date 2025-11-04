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

    menuGrid.innerHTML = items.map(item => {
        const customizeBtn = item.customizable ? `
            <button class="btn-customize"
                    data-id="${item.id}">
                <i class="fa fa-sliders-h"></i> Customize
            </button>` : '';
        return `
        <div class="menu-item" data-category="${item.category}">
            <div class="menu-item-image">
                <img src="${item.image}" alt="${item.name}" loading="lazy">
                <span class="menu-item-badge">${item.category}</span>
            </div>
            <div class="menu-item-content">
                <div class="menu-item-header">
                    <h3 class="menu-item-name">${item.name}</h3>
                    <span class="menu-item-price">R${Number(item.price||0).toFixed(2)}</span>
                </div>
                <p class="menu-item-description">${item.description||''}</p>
                <div class="menu-item-actions">
                    <button class="add-to-cart-btn"
                            data-id="${item.id}"
                            data-name="${item.name}"
                            data-price="${item.price}"
                            data-image="${item.image}">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    ${customizeBtn}
                </div>
            </div>
        </div>`;
    }).join('');

    attachCartListeners();
    attachCustomizeListeners();
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

function attachCustomizeListeners(){
    document.querySelectorAll('.btn-customize').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const id = parseInt(btn.dataset.id);
            const item = menuData.find(m=>m.id===id);
            if(!item) return;
            openCustomizeModal(item);
        });
    });
}

let modalState = { item:null };

function openCustomizeModal(item){
    modalState.item = item;
    const modal = document.getElementById('customizeModal');
    const title = document.getElementById('customizeTitle');
    const img = document.getElementById('customizeImage');
    const sizeGroup = document.getElementById('sizeGroup');
    const sizeOptions = document.getElementById('sizeOptions');
    const milkGroup = document.getElementById('milkGroup');
    const optMilk = document.getElementById('optMilk');
    const sugarGroup = document.getElementById('sugarGroup');
    const shotsGroup = document.getElementById('shotsGroup');
    const extrasGroup = document.getElementById('extrasGroup');
    const optExtras = document.getElementById('optExtras');
    const notesGroup = document.getElementById('notesGroup');
    const optNotes = document.getElementById('optNotes');
    const optQty = document.getElementById('optQty');

    title.textContent = `Customize ${item.name}`;
    img.innerHTML = `<img src="${item.image}" alt="${item.name}" style="max-width:160px;border-radius:8px">`;

    // Sizes
    sizeGroup.style.display = item.sizes ? '' : 'none';
    sizeOptions.innerHTML = '';
    let defaultSize = 'Regular';
    if(item.sizes){
        const values = item.sizes.values || {};
        const keys = Object.keys(values);
        if(keys.length){ defaultSize = keys.includes('Regular')? 'Regular' : keys[0]; }
        keys.forEach(k=>{
            const delta = values[k]||0;
            const labelPrice = item.sizes.type==='absolute' ? values[k] : (Number(item.price||0)+delta);
            const id = `size_${k}`.replace(/\s+/g,'_');
            const checked = (k===defaultSize)? 'checked' : '';
            sizeOptions.insertAdjacentHTML('beforeend',
                `<label style="margin-right:10px"><input type="radio" class="opt-size" name="size" value="${k}" ${checked}> ${k} <span class="muted">(R${labelPrice.toFixed(2)})</span></label>`);
        });
    }

    // Milk
    milkGroup.style.display = item.milkOptions ? '' : 'none';
    optMilk.innerHTML = '';
    if(item.milkOptions){
        (item.milkOptions.values||[]).forEach(v=>{
            const delta = (item.milkOptions.priceDelta||{})[v]||0;
            const extra = delta? ` (+R${Number(delta).toFixed(2)})` : '';
            optMilk.insertAdjacentHTML('beforeend', `<option value="${v}">${v}${extra}</option>`);
        });
    }

    // Sugar
    sugarGroup.style.display = item.sugar ? '' : 'none';
    document.getElementById('optSugar').value = Math.max(item.sugar?.min||0, 0);

    // Shots
    shotsGroup.style.display = item.extraShots ? '' : 'none';
    document.getElementById('optShots').value = item.extraShots? (item.extraShots.min||0) : 0;

    // Extras
    extrasGroup.style.display = Array.isArray(item.extras) && item.extras.length? '' : 'none';
    optExtras.innerHTML = '';
    (item.extras||[]).forEach(ex=>{
        optExtras.insertAdjacentHTML('beforeend', `<label style="display:block"><input type="checkbox" value="${ex.key}"> ${ex.label} <span class="muted">(+R${Number(ex.price||0).toFixed(2)})</span></label>`);
    });

    // Notes
    notesGroup.style.display = item.allowNotes? '' : 'none';
    optNotes.value='';

    // Qty
    optQty.value = 1;

    // Wire controls
    const recalc = ()=> updateModalPrices();
    modal.querySelectorAll('input[name="size"], #optMilk, #optSugar, #optShots, #optExtras input, #optQty').forEach(el=>{
        el.addEventListener('change', recalc);
        el.addEventListener('input', recalc);
    });
    document.getElementById('qtyMinus').onclick = ()=>{ optQty.value = Math.max(1, parseInt(optQty.value||'1')-1); recalc(); };
    document.getElementById('qtyPlus').onclick = ()=>{ optQty.value = Math.max(1, parseInt(optQty.value||'1')+1); recalc(); };
    document.getElementById('sugarMinus').onclick = ()=>{
        const min = item.sugar? (item.sugar.min||0) : 0;
        const el = document.getElementById('optSugar'); el.value = Math.max(min, parseInt(el.value||'0')-1); recalc();
    };
    document.getElementById('sugarPlus').onclick = ()=>{
        const max = item.sugar? (item.sugar.max||5) : 5;
        const el = document.getElementById('optSugar'); el.value = Math.min(max, parseInt(el.value||'0')+1); recalc();
    };
    document.getElementById('shotsMinus').onclick = ()=>{
        const min = item.extraShots? (item.extraShots.min||0) : 0;
        const el = document.getElementById('optShots'); el.value = Math.max(min, parseInt(el.value||'0')-1); recalc();
    };
    document.getElementById('shotsPlus').onclick = ()=>{
        const max = item.extraShots? (item.extraShots.max||0) : 0;
        const el = document.getElementById('optShots'); el.value = Math.min(max, parseInt(el.value||'0')+1); recalc();
    };

    document.getElementById('cancelCustomizeBtn').onclick = ()=> closeCustomizeModal();
    document.getElementById('addToCartBtn').onclick = ()=> addCustomizedToCart();

    updateModalPrices();
    modal.style.display='block';
    modal.setAttribute('aria-hidden','false');
}

function closeCustomizeModal(){
    const modal = document.getElementById('customizeModal');
    modal.style.display='none';
    modal.setAttribute('aria-hidden','true');
}

function getSelections(){
    const item = modalState.item;
    const size = item.sizes ? (document.querySelector('input[name="size"]:checked')?.value) : null;
    const milk = item.milkOptions ? document.getElementById('optMilk').value : null;
    const sugar = item.sugar ? parseInt(document.getElementById('optSugar').value||'0') : null;
    const shots = item.extraShots ? parseInt(document.getElementById('optShots').value||'0') : null;
    const extras = [];
    if (Array.isArray(item.extras)) {
        document.querySelectorAll('#optExtras input[type="checkbox"]:checked').forEach(ck=>extras.push(ck.value));
    }
    const notes = item.allowNotes ? (document.getElementById('optNotes').value||'').trim() : '';
    const qty = Math.max(1, parseInt(document.getElementById('optQty').value||'1'));
    return { size, milk, sugar, shots, extras, notes, qty };
}

function computeUnitPrice(menuItem, sel){
    let base = Number(menuItem.price||0);
    if(menuItem.sizes){
        const values = menuItem.sizes.values||{};
        const type = menuItem.sizes.type||'delta';
        const chosen = sel.size && values.hasOwnProperty(sel.size) ? sel.size : Object.keys(values)[0];
        if(type==='absolute') base = Number(values[chosen]||base);
        else base = Number(menuItem.price||0) + Number(values[chosen]||0);
    }
    let delta = 0;
    if(menuItem.milkOptions && sel.milk){
        delta += Number((menuItem.milkOptions.priceDelta||{})[sel.milk]||0);
    }
    if(menuItem.extraShots){
        const c = Math.max(menuItem.extraShots.min||0, Math.min(menuItem.extraShots.max||0, sel.shots||0));
        delta += c * Number(menuItem.extraShots.pricePerUnit||0);
    }
    if(Array.isArray(menuItem.extras)){
        const map = {}; menuItem.extras.forEach(e=>map[e.key]=Number(e.price||0));
        (sel.extras||[]).forEach(k=>{ if(map.hasOwnProperty(k)) delta += map[k]; });
    }
    // sugar has no price effect by default
    return Math.max(0, Number((base + delta).toFixed(2)));
}

function updateModalPrices(){
    const sel = getSelections();
    const unit = computeUnitPrice(modalState.item, sel);
    const line = Number((unit * sel.qty).toFixed(2));
    document.getElementById('unitPrice').textContent = 'R' + unit.toFixed(2);
    document.getElementById('linePrice').textContent = 'R' + line.toFixed(2);
}

function addCustomizedToCart(){
    const item = modalState.item;
    const sel = getSelections();
    const unit = computeUnitPrice(item, sel);
    const cartItem = {
        id: item.id,
        name: item.name,
        image: item.image,
        basePrice: Number(item.price||0),
        quantity: sel.qty,
        options: {
            size: sel.size? { label: sel.size, absolute: (item.sizes?.type==='absolute')||false, delta: item.sizes? (item.sizes.type==='absolute'? 0 : Number(item.sizes.values?.[sel.size]||0)) : 0 } : undefined,
            milk: sel.milk? { label: sel.milk, delta: Number(item.milkOptions?.priceDelta?.[sel.milk]||0) } : undefined,
            sugar: (typeof sel.sugar==='number')? sel.sugar : undefined,
            extraShots: item.extraShots? { count: Math.max(item.extraShots.min||0, Math.min(item.extraShots.max||0, sel.shots||0)), unitPrice: Number(item.extraShots.pricePerUnit||0), delta: Math.max(item.extraShots.min||0, Math.min(item.extraShots.max||0, sel.shots||0)) * Number(item.extraShots.pricePerUnit||0) } : undefined,
            extras: Array.isArray(item.extras)? (sel.extras||[]).map(k=>{ const e=item.extras.find(x=>x.key===k); return e? { key:e.key, label:e.label, price:Number(e.price||0)}:null; }).filter(Boolean) : undefined,
            notes: item.allowNotes? sel.notes : undefined
        },
        unitPrice: unit,
        lineTotal: Number((unit * sel.qty).toFixed(2))
    };
    addToCart(cartItem);
    showNotification(`${item.name} added to cart!`);
    closeCustomizeModal();
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
