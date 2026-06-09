<?php

$conn = mysqli_connect(
    "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com",
    "admin", 
    "admin2026",
    "db_customer"
);

// CEK KONEKSI
if (!$conn) {
    die("❌ Koneksi gagal: " . mysqli_connect_error());
}

// =====================
// INSERT CUSTOMER
// =====================
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO customer (nama, email, no_hp, alamat)
            VALUES ('$nama','$email','$no_hp','$alamat')";

    if (!mysqli_query($conn, $sql)) {
        die("❌ ERROR INSERT: " . mysqli_error($conn));
    }
}

// =====================
// DELETE CUSTOMER
// =====================
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    $sql = "DELETE FROM customer WHERE id_customer='$id'";

    if (!mysqli_query($conn, $sql)) {
        die("❌ ERROR DELETE: " . mysqli_error($conn));
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Customer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-4">

    <h2 class="text-center mb-4">👤 DATA CUSTOMER</h2>

    <!-- FORM -->
    <div class="card shadow p-3 mb-4">

        <form method="POST">

            <div class="row g-2">

                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                </div>

                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="col-md-2">
                    <input type="text" name="no_hp" class="form-control" placeholder="No HP" required>
                </div>

                <div class="col-md-2">
                    <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $data = mysqli_query($conn, "SELECT * FROM customer ORDER BY id_customer DESC");

            if(!$data){
                die("❌ ERROR SELECT: " . mysqli_error($conn));
            }

            while($row = mysqli_fetch_assoc($data)){
            ?>

                <tr>
                    <td><?= $row['id_customer']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['no_hp']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td>
                        <a href="?hapus=<?= $row['id_customer']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus customer ini?')">
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
