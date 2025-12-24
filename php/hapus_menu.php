<?php
include 'koneksi.php';

$id = $_GET['id'];

// Hapus gambar
$query_gambar = mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id_menu = '$id'");
$row = mysqli_fetch_assoc($query_gambar);
unlink("../uploads/" . $row['gambar']);

// Hapus data dari database
$query = "DELETE FROM menu WHERE id_menu = '$id'";
mysqli_query($koneksi, $query);

echo "<script>
        alert('Menu berhasil dihapus!');
        window.location.href = 'daftar_menu.php';
      </script>";
?>
