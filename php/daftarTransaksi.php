<?php
// Memulai sesi dan menyertakan koneksi database
include 'koneksi.php';

// Pagination - menentukan jumlah data per halaman
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Filter Tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Tambahkan kondisi tanggal ke query
$date_condition = "";
if ($start_date && $end_date) {
    $date_condition = "AND pesanan.tanggal_pemesanan BETWEEN '$start_date' AND '$end_date'";
} elseif ($start_date) {
    $date_condition = "AND pesanan.tanggal_pemesanan >= '$start_date'";
} elseif ($end_date) {
    $date_condition = "AND pesanan.tanggal_pemesanan <= '$end_date'";
}

$totalQuery = "
    SELECT COUNT(*) AS total 
    FROM pesanan 
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
    JOIN menu ON pesanan.id_menu = menu.id_menu
    WHERE (menu.nama_makanan LIKE '%$search%' 
        OR pelanggan.nama LIKE '%$search%' 
        OR pelanggan.alamat LIKE '%$search%') 
        $date_condition
";
$totalResult = mysqli_query($koneksi, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];

// Query Data Pesanan (Dengan Filter)
$query = "
    SELECT 
        pesanan.id_pelanggan, 
        pelanggan.nama AS nama_pelanggan, 
        pelanggan.alamat AS alamat_pelanggan,
        pesanan.tanggal_pemesanan,
        GROUP_CONCAT(CONCAT(menu.nama_makanan, ' (', pesanan.kuantitas, ')') SEPARATOR ', ') AS daftar_menu,
        GROUP_CONCAT(pesanan.id_pesanan SEPARATOR ', ') AS daftar_id_pesanan, -- ID pesanan di kolom terpisah
        SUM(pesanan.total_harga) AS total_harga
    FROM pesanan
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
    JOIN menu ON pesanan.id_menu = menu.id_menu
    WHERE (menu.nama_makanan LIKE '%$search%' 
        OR pelanggan.nama LIKE '%$search%' 
        OR pelanggan.alamat LIKE '%$search%') 
        $date_condition
    GROUP BY pesanan.id_pelanggan, pesanan.tanggal_pemesanan
    LIMIT $start, $perPage
";

$result = mysqli_query($koneksi, $query);


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link rel="stylesheet" href="../style/styleTable.css"> 
    <style>
        .container {
            margin-top: 120px;
        }

        .navbar {
            margin-top: -120px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <h3 class="title">WARUNG SKIBIDI</h3>
    
        <ul class="navbar-kanan">
            <li><a href="../menuAdmin.html" class="beranda">Beranda</a></li>
            <li><a href="../form/admin/ManajemenMenu.html">Tambah Menu</a></li>
            <li><a href="daftar_menu.php">Daftar Menu</a></li>
            <li><a href="daftarTransaksi.php">Daftar Pesanan</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Daftar Transaksi</h1>

        <!-- Form Pencarian dan Filter -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Cari menu/nama pelanggan/alamat" value="<?php echo htmlspecialchars($search); ?>">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
            <label for="end_date">Sampai Tanggal:</label>
            <input type="date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
            <button type="submit">Filter</button>
        </form>


        <!-- Tabel Daftar Transaksi -->
        <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Tanggal Pemesanan</th>
                <th>Daftar Menu (Kuantitas)</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['daftar_id_pesanan']; ?></td>
                <td><?= $row['nama_pelanggan']; ?></td>
                <td><?= $row['alamat_pelanggan']; ?></td>
                <td><?= $row['tanggal_pemesanan']; ?></td>
                <td><?= $row['daftar_menu']; ?></td>
                <td>Rp<?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>
                    <?php 
                    $ids = explode(',', $row['daftar_id_pesanan']); 
                    foreach ($ids as $id_pesanan): ?>
                    <div class="action">
                        <a href="editPesanan.php?id=<?= trim($id_pesanan); ?>">Edit <?= trim($id_pesanan); ?></a> |
                        <a href="hapusPesanan.php?id=<?= trim($id_pesanan); ?>" onclick="return confirm('Hapus pesanan ini?')">Hapus <?= trim($id_pesanan); ?></a><br>
                    </div>
                        
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= ceil($totalData / $perPage); $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
    
</body>
</html>
