<?php

$conn = mysqli_connect(
    "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "db_penjualan"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// INSERT
if(isset($_POST['simpan'])){

    $nama_produk = $_POST['nama_produk'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $total = $jumlah * $harga;
    $tanggal = $_POST['tanggal'];

    mysqli_query($conn,"
        INSERT INTO penjualan
        (nama_produk, jumlah, harga, total, tanggal_penjualan)
        VALUES
        ('$nama_produk','$jumlah','$harga','$total','$tanggal')
    ");
}

// DELETE
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn,"DELETE FROM penjualan WHERE id_penjualan='$id'");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Penjualan</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-4">

    <h2 class="text-center mb-4">📊 Data Penjualan</h2>

    <!-- FORM -->
    <div class="card shadow p-3 mb-4">
        <form method="POST">

            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" required>
                </div>

                <div class="col-md-2">
                    <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
                </div>

                <div class="col-md-2">
                    <input type="number" name="harga" class="form-control" placeholder="Harga" required>
                </div>

                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control" required>
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
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $data = mysqli_query($conn,"SELECT * FROM penjualan ORDER BY id_penjualan DESC");

            while($row = mysqli_fetch_assoc($data)){
            ?>

                <tr>
                    <td><?= $row['id_penjualan']; ?></td>
                    <td><?= $row['nama_produk']; ?></td>
                    <td><?= $row['jumlah']; ?></td>
                    <td>Rp <?= number_format($row['harga']); ?></td>
                    <td><b>Rp <?= number_format($row['total']); ?></b></td>
                    <td><?= $row['tanggal_penjualan']; ?></td>
                    <td>
                        <a href="?hapus=<?= $row['id_penjualan']; ?>" class="btn btn-danger btn-sm">
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
