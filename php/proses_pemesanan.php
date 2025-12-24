<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Silakan login untuk memesan.');
            window.location.href = '../form/login.html';
          </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_SESSION['user_id'];
    $alamat = $_POST['alamat'];
    $pembayaran = $_POST['pembayaran'];
    $makanan = $_POST['makanan'];
    $jumlah = $_POST['jumlah'];

    $total_harga = 0;
    $berhasil = true;

    foreach ($makanan as $id_menu => $value) {
        $kuantitas = $jumlah[$id_menu];

        // Ambil harga dan stok dari database
        $query = "SELECT harga, stok FROM menu WHERE id_menu = '$id_menu'";
        $result = mysqli_query($koneksi, $query);
        $menu = mysqli_fetch_assoc($result);
        $harga_per_item = $menu['harga'];
        $stok_sekarang = $menu['stok'];

        // Hitung total harga
        $total_harga_item = $harga_per_item * $kuantitas;
        $total_harga += $total_harga_item;

        // Cek stok
        if ($stok_sekarang >= $kuantitas) {
            // Masukkan pesanan ke database
            $query_insert = "INSERT INTO pesanan (id_pelanggan, id_menu, kuantitas, alamat, metode_pembayaran, total_harga) 
                             VALUES ('$id_pelanggan', '$id_menu', '$kuantitas', '$alamat', '$pembayaran', '$total_harga_item')";
            mysqli_query($koneksi, $query_insert);

            // Kurangi stok di tabel menu
            $stok_baru = $stok_sekarang - $kuantitas;
            $query_update = "UPDATE menu SET stok = '$stok_baru' WHERE id_menu = '$id_menu'";
            mysqli_query($koneksi, $query_update);
        } else {
            $berhasil = false;
            echo "<script>
                    alert('Stok tidak mencukupi untuk beberapa item.');
                  </script>";
        }
    }

    if ($berhasil) {
        echo "<script>
                alert('Pesanan berhasil dibuat!');
                window.location.href = '../php/pesanan_saya.php';
              </script>";
    } else {
        echo "<script>
                alert('Ada pesanan yang gagal karena stok tidak mencukupi.');
              </script>";
    }
}
?>
