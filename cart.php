<?php require_once __DIR__ . '/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Bite & Brew</title>
    <meta name="description" content="Review your order and proceed to checkout">
    <?php if (isset($_SESSION['csrf_token'])) { echo '<meta name="csrf-token" content="'.htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES).'">'; } ?>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main style="padding-top: 100px;">
        <section class="section cart-section">
            <div class="container">
                <div class="section-title">
                    <h2>Shopping Cart</h2>
                    <p>Review your items and proceed to checkout</p>
                </div>

                <div id="cartContent">
                    <div class="loading">Loading cart...</div>
                </div>
            </div>
        </section>
    </main>

    <div id="notification" class="notification"></div>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/cart-page.js"></script>
</body>
</html>
