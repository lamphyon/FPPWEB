<?php
session_start();
include "connect.php";

if (!isset($_GET['payment_id'])) {
    die("Payment not found.");
}
$pid = intval($_GET['payment_id']);
$stmt = $conn->prepare("SELECT p.*, o.id AS order_id, o.total_price FROM payments p JOIN orders o ON p.order_id = o.id WHERE p.id = ?");
$stmt->bind_param("i", $pid);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die("Payment not found.");
}
$pay = $res->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran — Rumah Jamur</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .sandbox-container { max-width: 900px; margin: 120px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 3px 12px rgba(0,0,0,0.06); }
    .sandbox-row { display:flex; gap:20px; align-items:center; margin-top:18px; }
    .sandbox-btn { padding:12px 18px; border-radius:8px; border:1px solid #ccc; cursor:pointer; background:#f7f7f7; font-weight:600; }
    .success { background:#849377; color:white; border:none; }
    .danger { background:#d9534f; color:white; border:none; }
    </style>
</head>
<body>
    <div class="sandbox-container">
        <h2>Sandbox Payment Gateway</h2>

        <p><b>Payment ID:</b> <?php echo htmlspecialchars($pay['id']); ?></p>
        <p><b>Order ID:</b> <?php echo htmlspecialchars($pay['order_id']); ?></p>
        <p><b>Amount:</b> Rp <?php echo number_format($pay['amount'],0,',','.'); ?></p>

        <div class="sandbox-row">
            <form method="POST" action="webhook.php" style="display:inline">
                <input type="hidden" name="payment_id" value="<?php echo intval($pay['id']); ?>">
                <input type="hidden" name="order_id" value="<?php echo intval($pay['order_id']); ?>">
                <input type="hidden" name="status" value="paid">
                <button type="submit" class="sandbox-btn success">Simulate Pay (Success)</button>
            </form>

            <form method="POST" action="webhook.php" style="display:inline; margin-left:12px;">
                <input type="hidden" name="payment_id" value="<?php echo intval($pay['id']); ?>">
                <input type="hidden" name="order_id" value="<?php echo intval($pay['order_id']); ?>">
                <input type="hidden" name="status" value="failed">
                <button type="submit" class="sandbox-btn danger">Simulate Fail</button>
            </form>
        </div>

        <p style="margin-top:18px; color:#666; font-size:0.95em;">
            Catatan: tombol di atas hanya simulasi lokal (sandbox). Pada integrasi nyata provider akan memanggil endpoint webhook (POST) ke server kamu.
        </p>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
