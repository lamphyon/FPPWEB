<?php
require '../connect.php';
require '../includes/functions.php';
cekAdmin();

// --- LOGIKA TAMBAH PRODUK ---
if( isset($_POST["submit"]) ) {
    $nama = htmlspecialchars($_POST["name"]);
    $harga = htmlspecialchars($_POST["price"]);
    $deskripsi = htmlspecialchars($_POST["description"]);
    $image_url = htmlspecialchars($_POST["image_url"]);

    // Query Insert ke Database
    $query = "INSERT INTO products (name, price, image_url, description) 
              VALUES ('$nama', '$harga', '$image_url', '$deskripsi')";
    
    // Jalankan query pakai mysqli_query biasa karena $conn dari connect.php gaya OOP tetap kompatibel
    mysqli_query($conn, $query);

    if(mysqli_affected_rows($conn) > 0) {
        echo "<script>alert('Produk berhasil ditambahkan!'); document.location.href = 'manage_products.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah data!');</script>";
    }
}

// --- LOGIKA HAPUS PRODUK ---
if( isset($_GET["hapus"]) ) {
    $id = $_GET["hapus"];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: manage_products.php");
}

// Ambil semua data produk
$produk = query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-box { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; width: 50%; margin-bottom: 20px;}
        input, textarea { width: 95%; padding: 8px; margin: 5px 0; }
    </style>
</head>
<body>
    <a href="dashboard.php">&laquo; Kembali ke Dashboard</a>
    <h1>Kelola Produk</h1>

    <div class="form-box">
        <h3>Tambah Produk Baru</h3>
        <form action="" method="post">
            <label>Nama Produk:</label>
            <input type="text" name="name" required>
            
            <label>Harga (Rp):</label>
            <input type="number" name="price" required>
            
            <label>Link Gambar (URL):</label>
            <input type="text" name="image_url" placeholder="https://..." required>

            <label>Deskripsi:</label>
            <textarea name="description" rows="3"></textarea>
            
            <button type="submit" name="submit" style="padding:10px; cursor:pointer;">Simpan Produk</button>
        </form>
    </div>

    <table border="1">
        <tr>
            <th>No.</th>
            <th>Gambar</th>
            <th>Info Produk</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php $i = 1; ?>
        <?php foreach( $produk as $row ) : ?>
        <tr>
            <td><?= $i; ?></td>
            <td><img src="<?= $row["image_url"]; ?>" width="80" alt="Gambar"></td>
            <td>
                <b><?= $row["name"]; ?></b><br>
                <small><?= $row["description"]; ?></small>
            </td>
            <td><?= formatRupiah($row["price"]); ?></td>
            <td>
                <a href="manage_products.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?');" style="color:red;">Hapus</a>
            </td>
        </tr>
        <?php $i++; endforeach; ?>
    </table>
</body>
</html>