<?php
// Memulai sesi dan menyertakan koneksi database
include 'koneksi.php';

// Mendapatkan ID pesanan dari URL
if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan.");
}
$id_pesanan = $_GET['id'];

// Query untuk menghapus data pesanan
$deleteQuery = "DELETE FROM pesanan WHERE id_pesanan = '$id_pesanan'";
if (mysqli_query($koneksi, $deleteQuery)) {
    echo "<script>alert('Pesanan berhasil dihapus!'); window.location='daftar_transaksi.php';</script>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
