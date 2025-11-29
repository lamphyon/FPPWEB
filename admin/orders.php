<?php
require '../connect.php';
require '../includes/functions.php';
cekAdmin();

// Ambil data pesanan terbaru
$orders = query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pesanan</title> 
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <a href="dashboard.php">&laquo; Kembali ke Dashboard</a>
    <h1>Data Pesanan Masuk</h1>

    <table border="1">
        <tr>
            <th>ID Order</th>
            <th>Waktu Pesan</th>
            <th>Data Pembeli</th>
            <th>Alamat Pengiriman</th>
            <th>Total Bayar</th>
        </tr>
        <?php foreach( $orders as $o ) : ?>
        <tr>
            <td>#<?= $o["id"]; ?></td>
            <td><?= $o["created_at"]; ?></td>
            <td>
                <b><?= $o["customer_name"]; ?></b><br>
                <small><?= $o["customer_phone"]; ?></small>
            </td>
            <td><?= $o["customer_address"]; ?></td>
            <td><b><?= formatRupiah($o["total_price"]); ?></b></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>