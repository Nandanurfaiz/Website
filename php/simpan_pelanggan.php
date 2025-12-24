<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_hp = $_POST['nomor_hp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO pelanggan (nama, username, email, no_hp, password, alamat) 
              VALUES ('$nama', '$username', '$email', '$no_hp', '$password', '$alamat')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Pendaftaran berhasil! Silakan login.');
                window.location.href = '../form/pelanggan/login.html';
              </script>";
    } else {
        echo "<script>
                alert('Pendaftaran gagal. Silakan coba lagi.');
                window.history.back();
              </script>";
    }
}
?>
