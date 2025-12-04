<?php
// Panggil koneksi dari folder luar
require '../connect.php'; 
require '../includes/functions.php';

// Cek hak akses
cekAdmin(); 

// Hitung data statistik sederhana
$jml_produk = count(query("SELECT * FROM products"));
$jml_pesanan = count(query("SELECT * FROM orders"));
$jml_user = count(query("SELECT * FROM users"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .nav a { margin-right: 15px; text-decoration: none; color: #333; font-weight: bold; }
        .nav a:hover { color: blue; }
        .card { 
            border: 1px solid #ddd; padding: 20px; 
            display: inline-block; margin-right: 10px; margin-top: 20px;
            border-radius: 8px; width: 200px; text-align: center;
            background-color: #fff;
        }
        h1 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Admin Rumah Jamur</h1>
        <p>Selamat Datang, <b><?= $_SESSION['user_name']; ?></b> (Admin)</p>
        
        <div class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_products.php">Kelola Produk</a>
            <a href="orders.php">Data Pesanan</a>
            <a href="../user/logout.php" style="color:red;">Logout</a>
        </div>
        <hr>

        <h3>Ringkasan Toko</h3>
        <div class="card">
            <h1><?= $jml_produk; ?></h1>
            <p>Total Produk</p>
        </div>
        <div class="card">
            <h1><?= $jml_pesanan; ?></h1>
            <p>Pesanan Masuk</p>
        </div>
        <div class="card">
            <h1><?= $jml_user; ?></h1>
            <p>User Terdaftar</p>
        </div>
    </div>
</body>
</html>