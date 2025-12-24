<?php
include 'koneksi.php';
$query = "SELECT * FROM menu";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>
    <link rel="stylesheet" href="../style/styleTable.css"> 
</head>
<body>
    <h1 class="title">Daftar Menu</h1>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Makanan</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Kategori</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id_menu'] ?></td>
                <td><?= $row['nama_makanan'] ?></td>
                <td><?= $row['harga'] ?></td>
                <td><?= $row['stok'] ?></td>
                <td><?= $row['kategori'] ?></td>
                <td><img src="../../uploads/<?= $row['gambar'] ?>" width="100"></td>
                <td>
                    <a href="edit_menu.php?id=<?= $row['id_menu'] ?>">Edit</a> |
                    <a href="hapus_menu.php?id=<?= $row['id_menu'] ?>" onclick="return confirm('Hapus menu ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="tombol">
        <a href="../form/admin/ManajemenMenu.html">Tambah Menu</a>
    <a href="../menuAdmin.html">Kembali ke menu</a>
    </div>
</body>
</html>
