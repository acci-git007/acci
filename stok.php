<?php

$conn = mysqli_connect(
    "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "db_stok_barang"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// INSERT
if(isset($_POST['simpan'])){

    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];

    mysqli_query($conn,"
        INSERT INTO barang
        (nama_barang, kategori, stok, harga_beli, harga_jual)
        VALUES
        ('$nama','$kategori','$stok','$harga_beli','$harga_jual')
    ");
}

// DELETE
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM barang
        WHERE id_barang='$id'
    ");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-4">

    <h2 class="text-center mb-4">📦 Data Stok Barang</h2>

    <!-- FORM -->
    <div class="card shadow p-3 mb-4">
        <form method="POST">

            <div class="row">

                <div class="col-md-3">
                    <input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required>
                </div>

                <div class="col-md-2">
                    <input type="text" name="kategori" class="form-control" placeholder="Kategori" required>
                </div>

                <div class="col-md-1">
                    <input type="number" name="stok" class="form-control" placeholder="Stok" required>
                </div>

                <div class="col-md-2">
                    <input type="number" name="harga_beli" class="form-control" placeholder="Harga Beli" required>
                </div>

                <div class="col-md-2">
                    <input type="number" name="harga_jual" class="form-control" placeholder="Harga Jual" required>
                </div>

                <div class="col-md-2">
                    <button type="submit" name="simpan" class="btn btn-primary w-100">
                        Simpan
                    </button>
                </div>

            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="card shadow p-3">

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $data = mysqli_query($conn,"SELECT * FROM barang ORDER BY id_barang DESC");

            while($row = mysqli_fetch_assoc($data)){
            ?>

                <tr>
                    <td><?= $row['id_barang']; ?></td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td><?= $row['kategori']; ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td>Rp <?= number_format($row['harga_beli']); ?></td>
                    <td>Rp <?= number_format($row['harga_jual']); ?></td>
                    <td>
                        <a href="?hapus=<?= $row['id_barang']; ?>" class="btn btn-danger btn-sm">
                            Hapus
                        </a>
                    </td>
                </tr>

            <?php } ?>

            </tbody>
        </table>

    </div>

</div>

</body>
</html>
