<?php require_once __DIR__ . '/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Bite & Brew</title>
    <meta name="description" content="Browse our delicious menu of artisan coffee, specialty drinks, fresh pastries, and snacks.">
    <?php if (isset($_SESSION['csrf_token'])) { echo '<meta name="csrf-token" content="'.htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES).'">'; } ?>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/menu.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main style="padding-top: 100px;">
        <section class="section menu-section">
            <div class="container">
                <div class="section-title">
                    <h2>Our Menu</h2>
                    <p>Discover our handcrafted beverages, fresh pastries, and delicious snacks</p>
                </div>
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search items..." onkeyup="searchItems()">
                </div>


                <div class="menu-filters">
                    <button class="filter-btn active" data-category="All">
                        <i class="fa-solid fa-border-all"></i>
                        <span class="filter-text">
                            <span class="filter-title">All</span>
                            <span class="filter-subtitle">Items</span>
                        </span>
                    </button>
                    <button class="filter-btn" data-category="Coffee">
                        <i class="fa-solid fa-mug-saucer"></i>
                        <span class="filter-text">
                            <span class="filter-title">Fresh</span>
                            <span class="filter-subtitle">Coffee</span>
                        </span>
                    </button>
                    <button class="filter-btn" data-category="Specialty Drinks">
                        <i class="fa-solid fa-martini-glass-citrus"></i>
                        <span class="filter-text">
                            <span class="filter-title">Signature</span>
                            <span class="filter-subtitle">Drinks</span>
                        </span>
                    </button>
                    <button class="filter-btn" data-category="Pastries">
                        <i class="fa-solid fa-croissant"></i>
                        <span class="filter-text">
                            <span class="filter-title">Fresh</span>
                            <span class="filter-subtitle">Pastries</span>
                        </span>
                    </button>
                    <button class="filter-btn" data-category="Snacks">
                        <i class="fa-solid fa-burger"></i>
                        <span class="filter-text">
                            <span class="filter-title">Tasty</span>
                            <span class="filter-subtitle">Snacks</span>
                        </span>
                    </button>
                    <button class="filter-btn" data-category="Combos">
                        <i class="fa-solid fa-layer-group"></i>
                        <span class="filter-text">
                            <span class="filter-title">Value</span>
                            <span class="filter-subtitle">Combos</span>
                        </span>
                    </button>
                </div>

                <div id="menuGrid" class="menu-grid">
                    <div class="loading">Loading menu...</div>
                </div>
            </div>
        </section>
    </main>

    <div id="notification" class="notification"></div>

    <!-- Customization Modal -->
    <div id="customizeModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true" style="display:none">
        <div class="modal-dialog" role="document" style="max-width:520px; margin:40px auto; background:#fff; border:1px solid #eee; border-radius:10px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.12)">
            <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid #eee">
                <h3 id="customizeTitle" style="margin:0;font-size:18px">Customize</h3>
                <button id="cancelCustomizeBtn" aria-label="Close" style="background:none;border:0;font-size:18px;cursor:pointer">×</button>
            </div>
            <div class="modal-body" style="padding:16px">
                <div id="customizeImage" style="text-align:center;margin-bottom:12px"></div>
                <div id="sizeGroup" style="margin-bottom:12px;display:none">
                    <div class="muted" style="margin-bottom:6px">Size</div>
                    <div id="sizeOptions"></div>
                </div>
                <div id="milkGroup" style="margin-bottom:12px;display:none">
                    <label class="muted" for="optMilk">Milk</label>
                    <select id="optMilk" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px"></select>
                </div>
                <div id="sugarGroup" style="margin-bottom:12px;display:none">
                    <div class="muted" style="margin-bottom:6px">Sugar</div>
                    <div>
                        <button id="sugarMinus" type="button">−</button>
                        <input id="optSugar" type="number" min="0" value="0" style="width:60px;text-align:center" />
                        <button id="sugarPlus" type="button">+</button>
                    </div>
                </div>
                <div id="shotsGroup" style="margin-bottom:12px;display:none">
                    <div class="muted" style="margin-bottom:6px">Extra shots</div>
                    <div>
                        <button id="shotsMinus" type="button">−</button>
                        <input id="optShots" type="number" min="0" value="0" style="width:60px;text-align:center" />
                        <button id="shotsPlus" type="button">+</button>
                    </div>
                </div>
                <div id="extrasGroup" style="margin-bottom:12px;display:none">
                    <div class="muted" style="margin-bottom:6px">Extras</div>
                    <div id="optExtras"></div>
                </div>
                <div id="notesGroup" style="margin-bottom:12px;display:none">
                    <label class="muted" for="optNotes">Special instructions</label>
                    <textarea id="optNotes" rows="3" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px" maxlength="200" placeholder="e.g., Less foam please"></textarea>
                </div>
                <div id="qtyGroup" style="margin-bottom:12px">
                    <div class="muted" style="margin-bottom:6px">Quantity</div>
                    <div>
                        <button id="qtyMinus" type="button">−</button>
                        <input id="optQty" type="number" min="1" value="1" style="width:60px;text-align:center" />
                        <button id="qtyPlus" type="button">+</button>
                    </div>
                </div>
                <div class="priceRow" style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-top:1px solid #eee;margin-top:8px">
                    <div>
                        <div class="muted">Unit</div>
                        <div id="unitPrice" style="font-weight:600">R0.00</div>
                    </div>
                    <div>
                        <div class="muted">Line total</div>
                        <div id="linePrice" style="font-weight:700;font-size:18px">R0.00</div>
                    </div>
                </div>
                <button id="addToCartBtn" class="primary-button" style="width:100%;margin-top:12px">Add to Cart</button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/menu.js"></script>
</body>
</html>
