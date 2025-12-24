<?php
// Memulai sesi dan menyertakan koneksi database
include 'koneksi.php';

// Mendapatkan ID pesanan dari URL
if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan.");
}
$id_pesanan = $_GET['id'];

// Mendapatkan data pesanan berdasarkan ID
$query = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'";
$result = mysqli_query($koneksi, $query);
if (mysqli_num_rows($result) == 0) {
    die("Pesanan tidak ditemukan.");
}
$row = mysqli_fetch_assoc($result);

// Memproses data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $level_pedas = $_POST['level_pedas'];
    $kuantitas = $_POST['kuantitas'];
    $alamat = $_POST['alamat'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $total_harga = $_POST['total_harga'];
    $status_pesanan = $_POST['status_pesanan'];

    // Query update data pesanan
    $updateQuery = "UPDATE pesanan SET 
        level_pedas = '$level_pedas', 
        kuantitas = '$kuantitas', 
        alamat = '$alamat', 
        metode_pembayaran = '$metode_pembayaran', 
        total_harga = '$total_harga', 
        status_pesanan = '$status_pesanan'
        WHERE id_pesanan = '$id_pesanan'";

    if (mysqli_query($koneksi, $updateQuery)) {
        echo "<script>alert('Pesanan berhasil diperbarui!'); window.location='daftarTransaksi.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="styles.css"> <!-- Tambahkan file CSS jika ada -->
</head>
<body>
    <h1>Edit Pesanan - ID: <?php echo $row['id_pesanan']; ?></h1>
    <form method="POST" action="">
        <label>Level Pedas:</label>
        <input type="text" name="level_pedas" value="<?php echo $row['level_pedas']; ?>" required><br>

        <label>Kuantitas:</label>
        <input type="number" name="kuantitas" value="<?php echo $row['kuantitas']; ?>" required><br>

        <label>Alamat:</label>
        <textarea name="alamat" required><?php echo $row['alamat']; ?></textarea><br>

        <label>Metode Pembayaran:</label>
        <input type="text" name="metode_pembayaran" value="<?php echo $row['metode_pembayaran']; ?>" required><br>

        <label>Total Harga:</label>
        <input type="number" name="total_harga" value="<?php echo $row['total_harga']; ?>" required><br>

        <label>Status Pesanan:</label>
        <select name="status_pesanan" required>
            <option value="Diproses" <?php echo $row['status_pesanan'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
            <option value="Dikirim" <?php echo $row['status_pesanan'] == 'Dikirim' ? 'selected' : ''; ?>>Dikirim</option>
            <option value="Selesai" <?php echo $row['status_pesanan'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
        </select><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
