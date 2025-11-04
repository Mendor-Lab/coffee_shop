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

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/menu.js"></script>
</body>
</html>
