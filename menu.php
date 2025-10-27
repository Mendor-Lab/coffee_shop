<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Bite & Brew</title>
    <meta name="description" content="Browse our delicious menu of artisan coffee, specialty drinks, fresh pastries, and snacks.">

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

                <div class="menu-filters">
                    <button class="filter-btn active" data-category="All">All Items</button>
                    <button class="filter-btn" data-category="Coffee">Coffee</button>
                    <button class="filter-btn" data-category="Specialty Drinks">Specialty Drinks</button>
                    <button class="filter-btn" data-category="Pastries">Pastries</button>
                    <button class="filter-btn" data-category="Snacks">Snacks</button>
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
