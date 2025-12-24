<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ambil data admin berdasarkan email
    $query = "SELECT * FROM operator WHERE email='$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan data ke sesi
            $_SESSION['user_id'] = $row['id_admin'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            
            // Redirect ke dashboard atau halaman utama
            echo "<script>
                    alert('Login berhasil!');
                    window.location.href = '../menuAdmin.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Password salah!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Email atau password salah!');
                window.history.back();
              </script>";
    }
}
?>
