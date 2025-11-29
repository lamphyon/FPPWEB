<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION["user_id"]);
$user_name = htmlspecialchars($_SESSION["user_name"], ENT_QUOTES, 'UTF-8');

// Fetch user's orders
$stmt = $conn->prepare("
    SELECT id, total_price, customer_name, customer_phone, customer_address, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Profil & Riwayat Belanja — Rumah Jamur</title>
<link rel="stylesheet" href="style.css">
<style>
/* Page layout */
.container {
    max-width: 1100px;
    margin: 90px auto;
    padding: 24px;
}

/* Profile header */
.profile-head {
    display:flex;
    gap:20px;
    align-items:center;
    background: linear-gradient(90deg,#f0f7ec,#ffffff);
    padding:18px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
}
.avatar {
    width:86px;
    height:86px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:34px;
    color:#fff;
    background:#849377;
    flex-shrink:0;
    box-shadow: 0 4px 12px rgba(132,147,119,0.18);
}
.profile-info h1{ margin:0; font-size:20pt; color:#334; }
.profile-info p{ margin:6px 0 0; color:#666; }

/* Action buttons */
.actions { margin-left:auto; display:flex; gap:10px; align-items:center;}
.actions .btn {
    text-decoration:none;
    padding:10px 14px;
    border-radius:10px;
    font-weight:600;
    color:#fff;
}
.btn-primary { background:#849377; }

/* Orders list */
.orders-list { margin-top:24px; display:flex; flex-direction:column; gap:14px; }
.order-card {
    background:#fff;
    border-radius:10px;
    box-shadow:0 6px 18px rgba(0,0,0,0.04);
    overflow:hidden;
}
.order-header {
    display:flex;
    gap:12px;
    align-items:center;
    padding:16px 18px;
}
.order-meta { flex:1; }
.order-meta h3 { margin:0; font-size:16px; color:#2b3; }
.order-meta .muted { color:#777; font-size:13px; margin-top:6px; }
.order-right {
    text-align:right;
}
.badge {
    display:inline-block;
    padding:6px 10px;
    background:#eef7ec;
    color:#2d6b2b;
    border-radius:8px;
    font-weight:700;
}

/* detail area hidden by default */
.order-details {
    display:none;
    padding:16px 18px 22px;
    border-top:1px solid #f1f1f1;
}
.items-table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:12px;
}
.items-table th, .items-table td {
    text-align:left;
    padding:8px 6px;
    border-bottom:1px solid #f2f2f2;
}
.items-table img { width:64px; height:64px; object-fit:cover; border-radius:6px; }

/* total & actions */
.order-footer {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
}
.small { font-size:13px; color:#666; }

/* Empty orders */
.empty {
    text-align:center;
    padding:40px;
    background:#fff;
    border-radius:10px;
    box-shadow:0 6px 18px rgba(0,0,0,0.04);
}

/* responsive */
@media (max-width:720px){
    .order-header { flex-direction:column; align-items:flex-start; gap:8px; }
    .order-right { width:100%; text-align:left; }
    .profile-head{flex-direction:column; align-items:flex-start;}
    .actions { margin-left:0; width:100%; justify-content:space-between;}
}
</style>
</head>
<body>

<div class="container">
    <div class="profile-head">
        <div class="avatar"><?= strtoupper(substr($user_name,0,1)) ?></div>
        <div class="profile-info">
            <h1>Halo, <?= $user_name ?></h1>
            <p class="small">Terima kasih sudah menjadi pelanggan Rumah Jamur. Berikut ringkasan pesanan & riwayat belanja Anda.</p>
        </div>

        <div class="actions">
            <a class="btn btn-primary" href="product.php">Lanjut Belanja</a>
            <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
    </div>

    <h2 style="margin-top:22px;">Riwayat Pesanan</h2>

    <?php if (empty($orders)): ?>
        <div class="empty">
            <p class="small">Belum ada pesanan.</p>
            <p>Mulai berbelanja dan pesanan Anda akan muncul di sini.</p>
            <br>
            <a class="tombol" href="product.php">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): 
                $order_id = intval($order['id']);
                $total_price = intval($order['total_price']);
                $created = htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8');

                // fetch items for this order
                $s = $conn->prepare("
                    SELECT oi.quantity, oi.price, p.name, p.image_url
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = ?
                ");
                $s->bind_param("i", $order_id);
                $s->execute();
                $items_res = $s->get_result();
                $items = $items_res->fetch_all(MYSQLI_ASSOC);
                $s->close();
            ?>
            <div class="order-card" id="order-<?= $order_id ?>">
                <div class="order-header">
                    <div class="order-meta">
                        <h3>Pesanan #<?= $order_id ?></h3>
                        <div class="muted">Tanggal: <?= $created ?> • Penerima: <?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></div>
                    </div>

                    <div class="order-right">
                        <div class="badge">Rp <?= number_format($total_price,0,',','.') ?></div>
                        <div class="small" style="margin-top:8px;">Jumlah item: <?= array_sum(array_column($items,'quantity')) ?></div>
                        <div style="margin-top:8px;">
                            <button class="tombol" onclick="toggleDetails(<?= $order_id ?>)">Detail</button>
                            <button class="tombol" onclick="window.print()">Cetak</button>
                        </div>
                    </div>
                </div>

                <div class="order-details" id="details-<?= $order_id ?>">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $it):
                            $subtotal = $it['price'] * $it['quantity'];
                        ?>
                            <tr>
                                <td>
                                    <div style="display:flex;gap:12px;align-items:center">
                                        <img src="<?= htmlspecialchars($it['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="">
                                        <div><?= htmlspecialchars($it['name'], ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                </td>
                                <td>Rp <?= number_format($it['price'],0,',','.') ?></td>
                                <td><?= intval($it['quantity']) ?></td>
                                <td>Rp <?= number_format($subtotal,0,',','.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="order-footer">
                        <div>
                            <div class="small">Alamat pengiriman:</div>
                            <div><?= nl2br(htmlspecialchars($order['customer_address'], ENT_QUOTES, 'UTF-8')) ?></div>
                            <div class="small" style="margin-top:6px;">Telepon: <?= htmlspecialchars($order['customer_phone'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                        <div style="text-align:right">
                            <div class="small">Total:</div>
                            <div style="font-weight:700; font-size:18px; margin-top:6px;">Rp <?= number_format($total_price,0,',','.') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php include "footer.php"; ?>

<script>
function toggleDetails(id){
    const el = document.getElementById('details-' + id);
    if(!el) return;
    el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}
</script>
</body>
</html>
