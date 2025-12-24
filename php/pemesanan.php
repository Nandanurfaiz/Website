<?php
include 'koneksi.php';
$query = "SELECT * FROM menu WHERE stok > 0";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Makanan</title>
    <link rel="stylesheet" href="../style/stylePemesanan.css">
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <h3 class="title">WARUNG SKIBIDI</h3>

        <ul class="navbar-kanan">
            <li><a href="../index.html" class="beranda">Beranda</a></li>
            <li><a href="../menu.html">Menu</a></li>
            <li><a href="pemesanan.php">Buat Pesanan</a></li>
            <li><a href="pesanan_saya.php">Pesanan Saya</a></li>
        </ul>
    </div>

    
    <div class="container">
        <form action="proses_pemesanan.php" method="post" class="form">
            <h1>Pesan Makanan</h1>
            <!-- Pilih Makanan (Multiple) -->
            <div>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div>
                        <input type="checkbox" name="makanan[<?= $row['id_menu'] ?>]" value="<?= $row['id_menu'] ?>">
                        <label><?= $row['nama_makanan'] ?> - Rp<?= number_format($row['harga'], 0, ',', '.') ?></label>
                        <br>
                        <img src="../../uploads/<?= $row['gambar'] ?>" width="100">
                        <input type="number" name="jumlah[<?= $row['id_menu'] ?>]" min="1" max="<?= $row['stok'] ?>" placeholder="Jumlah" class="jumlah">
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Metode Pembayaran -->
            <label for="pembayaran">Metode Pembayaran:</label>
            <select id="pembayaran" name="pembayaran" required>
                <option value="Transfer Bank">Bayar di Tempat (COD)</option>
                <option value="COD">Transfer Bank</option>
            </select>

            <button type="submit">Pesan Sekarang</button>
        </form>
    </div>
</body>

</html>
