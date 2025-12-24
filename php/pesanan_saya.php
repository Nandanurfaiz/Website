<?php
session_start();
include 'koneksi.php';

$id_pelanggan = $_SESSION['user_id'];

// Query dengan JOIN untuk mengambil nama menu
$query = "SELECT pesanan.*, menu.nama_makanan 
          FROM pesanan 
          JOIN menu ON pesanan.id_menu = menu.id_menu
          WHERE pesanan.id_pelanggan = '$id_pelanggan' 
          ORDER BY pesanan.tanggal_pemesanan DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="../style/styleTable.css"> 
</head>
<body>
    <h1>Pesanan Saya</h1>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Menu</th>  <!-- Kolom baru untuk nama makanan -->
                <th>Kuantitas</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id_pesanan'] ?></td>
                <td><?= $row['nama_makanan'] ?></td>  <!-- Tampilkan nama menu -->
                <td><?= $row['kuantitas'] ?></td>
                <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td><?= $row['status_pesanan'] ?></td>
                <td><?= $row['tanggal_pemesanan'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <div class="tombol">
        <a href="pemesanan.php">Buat Pesanan Baru</a>
    </div>
    
</body>
</html>
