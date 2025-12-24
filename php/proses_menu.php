<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    // Proses Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($gambar);

    // Pastikan direktori uploads ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        // Simpan ke database
        $query = "INSERT INTO menu (nama_makanan, harga, stok, kategori, deskripsi, gambar) 
                  VALUES ('$nama', '$harga', '$stok', '$kategori', '$deskripsi', '$gambar')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Menu berhasil ditambahkan!');
                    window.location.href = '../php/daftar_menu.php';
                  </script>";
        } else {
            echo "Gagal menambahkan menu: " . mysqli_error($koneksi);
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}
?>
