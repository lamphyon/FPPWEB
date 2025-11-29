<?php
// File: includes/functions.php

// Fungsi untuk mengambil data (SELECT)
function query($query) {
    global $conn; // Mengambil variabel $conn dari connect.php
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi Format Rupiah
function formatRupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// LOGIKA CEK ADMIN (Tanpa Role di Database)
function cekAdmin() {
    // Cek apakah session sudah dimulai
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Cek apakah sudah login?
    if (!isset($_SESSION['user_id'])) { 
        header("Location: ../login.php");
        exit;
    }

    // 2. Cek apakah emailnya "admin@admin.com"?
    $admin_email = "admin@admin.com"; 
    
    // Ambil email dari session (pastikan login.php sudah menyimpan $_SESSION['email'])
    if (!isset($_SESSION['email']) || $_SESSION['email'] !== $admin_email) {
        echo "<script>
                alert('Akses Ditolak! Anda bukan Admin.');
                window.location.href = '../index.php';
              </script>";
        exit;
    }
}
?>