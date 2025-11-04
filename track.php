<?php require_once __DIR__ . '/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order - Bites&Brew</title>
    <meta name="description" content="Track your Bite&Brew order status and ETA.">
    <?php if (isset($_SESSION['csrf_token'])) { echo '<meta name="csrf-token" content="'.htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES).'">'; } ?>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body { padding-top: 100px; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 16px; }
        .card { background:#fff; border:1px solid #eee; border-radius:8px; padding:16px; }
        .track-form { display:flex; gap:8px; flex-wrap:wrap; }
        .track-form input { flex:1; min-width:220px; padding:10px; border:1px solid #ddd; border-radius:6px; }
        .track-form button { padding:10px 16px; }
        .muted{ color:#666; }
        .progress-wrap { margin-top: 16px; }
        .progress-bar { height:10px; background:#eee; border-radius:999px; overflow:hidden; }
        .progress-bar > div { height:100%; width:0; background:var(--primary, #8B5CF6); transition:width .3s ease; }
        .status-pill { display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; margin-left:8px; }
        .status-pending{ background:#fff7e6; color:#b26b00; }
        .status-preparing{ background:#e6f4ff; color:#00559b; }
        .status-ready{ background:#e8f5e9; color:#1b5e20; }
        .status-completed{ background:#eceff1; color:#37474f; }
        .eta{ font-size:14px; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="section">
            <div class="container">
                <div class="section-title">
                    <h2>Track Your Order</h2>
                    <p>Enter your Order ID (e.g. BB20251027-ABC123) to see real-time status and ETA.</p>
                </div>
                <div class="card">
                    <form id="trackForm" class="track-form" autocomplete="off">
                        <input type="text" id="orderIdInput" placeholder="Enter Order ID" required />
                        <button type="submit" class="primary-button">Track</button>
                    </form>
                    <div id="result" style="margin-top:16px; display:none;">
                        <div id="orderSummary"></div>
                        <div class="progress-wrap">
                            <div class="progress-bar"><div id="progressInner"></div></div>
                        </div>
                        <div id="eta" class="eta muted" style="margin-top:8px"></div>
                    </div>
                    <div id="trackMsg" class="muted" style="margin-top:12px"></div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/track.js"></script>
</body>
</html>
