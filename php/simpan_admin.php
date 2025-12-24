<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $peran = $_POST['peran'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
   
    $query = "INSERT INTO operator (nama, peran, email, password) 
              VALUES ('$nama', '$peran', '$email', '$password')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Pendaftaran berhasil! Silakan login.');
                window.location.href = '../form/admin/loginAdmin.html';
              </script>";
    } else {
        echo "<script>
                alert('Pendaftaran gagal. Silakan coba lagi.');
                window.history.back();
              </script>";
    }
}
?>
