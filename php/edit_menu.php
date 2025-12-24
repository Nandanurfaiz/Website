<?php
include 'koneksi.php';

// Ambil ID Menu dari URL
$id = $_GET['id'];

// Ambil data menu dari database
$query = "SELECT * FROM menu WHERE id_menu = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Jika menu tidak ditemukan
if (!$data) {
    echo "<script>
            alert('Menu tidak ditemukan!');
            window.location.href = '../views/daftar_menu.php';
          </script>";
    exit();
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    // Proses upload gambar (opsional)
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($gambar);

        // Hapus gambar lama
        unlink($target_dir . $data['gambar']);

        // Upload gambar baru
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);
    } else {
        $gambar = $data['gambar'];  // Jika gambar tidak diganti
    }

    // Update data di database
    $query_update = "UPDATE menu SET 
                        nama_makanan = '$nama', 
                        harga = '$harga', 
                        stok = '$stok', 
                        kategori = '$kategori', 
                        deskripsi = '$deskripsi', 
                        gambar = '$gambar' 
                    WHERE id_menu = '$id'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>
                alert('Menu berhasil diperbarui!');
                window.location.href = '../php/daftar_menu.php';
              </script>";
    } else {
        echo "Gagal mengupdate menu: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="../css/styleForm.css">
</head>
<body>
    <div class="container">
        <h1>Edit Menu</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="namaMakanan">Nama Makanan:</label>  
            <input type="text" name="nama" id="namaMakanan" value="<?= $data['nama_makanan'] ?>" required> 

            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" value="<?= $data['harga'] ?>" required>

            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" value="<?= $data['stok'] ?>" required>

            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
                <option value="Makanan" <?= $data['kategori'] == 'Makanan' ? 'selected' : '' ?>>Makanan</option>
                <option value="Minuman" <?= $data['kategori'] == 'Minuman' ? 'selected' : '' ?>>Minuman</option>
                <option value="Snack" <?= $data['kategori'] == 'Snack' ? 'selected' : '' ?>>Snack</option>
            </select>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" cols="50" rows="5"><?= $data['deskripsi'] ?></textarea>

            <label for="gambar">Gambar Saat Ini:</label><br>
            <img src="../../uploads/<?= $data['gambar'] ?>" width="150"><br><br>

            <label for="UploadGambar">Upload Gambar Baru (Opsional):</label>
            <input type="file" name="gambar" id="UploadGambar" accept="image/*">
                
            <button type="submit">Simpan Perubahan</button> 

        </form>
    </div>
</body>
</html>
