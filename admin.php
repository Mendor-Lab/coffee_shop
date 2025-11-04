<?php require_once __DIR__ . '/includes/bootstrap.php';
// Localhost-only guard
$remote = $_SERVER['REMOTE_ADDR'] ?? '';
$allowed = in_array($remote, ['127.0.0.1', '::1']);
if (!$allowed) {
    http_response_code(403);
    echo 'Admin Dashboard is available on localhost only.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bites&Brew</title>
    <?php if (isset($_SESSION['csrf_token'])) { echo '<meta name="csrf-token" content="'.htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES).'">'; } ?>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body { padding-top: 100px; }
        .admin-container { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
        .admin-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        .card { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 16px; }
        .card h3 { margin: 0 0 12px; }
        .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat { background: #fafafa; border: 1px solid #eee; border-radius: 8px; padding: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #eee; }
        .status { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 12px; }
        .status.pending { background: #fff7e6; color: #b26b00; }
        .status.preparing { background: #e6f4ff; color: #00559b; }
        .status.ready { background: #e8f5e9; color: #1b5e20; }
        .status.completed { background: #eceff1; color: #37474f; }
        select.status-select { padding: 6px 8px; }
        .muted { color: #666; font-size: 12px; }
        @media (min-width: 900px) { .admin-grid { grid-template-columns: 2fr 1fr; } .stats { grid-template-columns: repeat(4, 1fr);} }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <div class="admin-container">
            <h2>Admin Dashboard</h2>
            <div class="admin-grid">
                <section class="card">
                    <h3>Orders</h3>
                    <div id="ordersLoading" class="muted">Loading orders…</div>
                    <table id="ordersTable" style="display:none">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </section>
                <section class="card">
                    <h3>Today Stats</h3>
                    <div class="stats" id="stats">
                        <div class="stat"><div class="muted">Orders</div><div id="statOrders" style="font-size:20px; font-weight:600">—</div></div>
                        <div class="stat"><div class="muted">Revenue</div><div id="statRevenue" style="font-size:20px; font-weight:600">—</div></div>
                        <div class="stat"><div class="muted">Pending</div><div id="statPending" style="font-size:20px; font-weight:600">—</div></div>
                        <div class="stat"><div class="muted">Ready</div><div id="statReady" style="font-size:20px; font-weight:600">—</div></div>
                    </div>
                    <hr style="margin:16px 0" />
                    <h3 style="margin-top:0">Messages</h3>
                    <div id="messagesLoading" class="muted">Loading messages…</div>
                    <div id="messagesList" style="display:none"></div>
                </section>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/admin.js"></script>
</body>
</html>
