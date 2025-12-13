<?php
require '../connect.php';
require '../includes/functions.php';
cekAdmin();

$is_edit = false;
$edit_data = [
    'id' => '',
    'name' => '',
    'price' => '',
    'image_url' => '',
    'description' => ''
];

if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id_edit");
    
    if (mysqli_num_rows($result) > 0) {
        $is_edit = true;
        $edit_data = mysqli_fetch_assoc($result);
    }
}

if (isset($_POST["submit"])) {
    $nama = htmlspecialchars($_POST["name"]);
    $harga = htmlspecialchars($_POST["price"]);
    $deskripsi = htmlspecialchars($_POST["description"]);
    $image_url = htmlspecialchars($_POST["image_url"]);
    
    // Cek apakah ada ID (artinya ini Update) atau tidak (artinya Insert)
    if (!empty($_POST['id'])) {
        $id_produk = $_POST['id'];
        $query = "UPDATE products SET 
                    name = '$nama',
                    price = '$harga',
                    image_url = '$image_url',
                    description = '$deskripsi'
                  WHERE id = $id_produk";
        $pesan = "Produk berhasil diupdate!";
    } else {
        $query = "INSERT INTO products (name, price, image_url, description) 
                  VALUES ('$nama', '$harga', '$image_url', '$deskripsi')";
        $pesan = "Produk berhasil ditambahkan!";
    }

    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0 || mysqli_errno($conn) == 0) {
        echo "<script>alert('$pesan'); document.location.href = 'manage_products.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}

if (isset($_GET["hapus"])) {
    $id = $_GET["hapus"];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href = 'manage_products.php';</script>";
}

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
        .btn-edit { color: blue; text-decoration: none; margin-right: 10px; }
        .btn-hapus { color: red; text-decoration: none; }
        .btn-batal { background-color: #ccc; text-decoration: none; padding: 10px; color: black; font-size: 13px; border: 1px solid #999;}
    </style>
</head>
<body>
    <a href="dashboard.php">&laquo; Kembali ke Dashboard</a>
    <h1>Kelola Produk</h1>

    <div class="form-box">
        <h3><?= $is_edit ? "Edit Produk" : "Tambah Produk Baru"; ?></h3>
        
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">

            <label>Nama Produk:</label>
            <input type="text" name="name" value="<?= $edit_data['name']; ?>" required>
            
            <label>Harga (Rp):</label>
            <input type="number" name="price" value="<?= $edit_data['price']; ?>" required>
            
            <label>Link Gambar (URL):</label>
            <input type="text" name="image_url" value="<?= $edit_data['image_url']; ?>" placeholder="https://..." required>

            <label>Deskripsi:</label>
            <textarea name="description" rows="3"><?= $edit_data['description']; ?></textarea>
            
            <br><br>
            <button type="submit" name="submit" style="padding:10px; cursor:pointer; background-color: #4CAF50; color: white; border: none;">
                <?= $is_edit ? "Update Produk" : "Simpan Produk"; ?>
            </button>

            <?php if($is_edit): ?>
                <a href="manage_products.php" class="btn-batal">Batal Edit</a>
            <?php endif; ?>
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
                <a href="manage_products.php?edit=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                
                <a href="manage_products.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?');" class="btn-hapus">Hapus</a>
            </td>
        </tr>
        <?php $i++; endforeach; ?>
    </table>
</body>
</html>
