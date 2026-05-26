<?php
$conn = mysqli_connect("localhost", "root", "", "sekolah");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

/*
SQL DATABASE

CREATE DATABASE sekolah;

USE sekolah;

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    kelas VARCHAR(20),
    alamat TEXT
);

*/

# TAMBAH DATA
if (isset($_POST['tambah'])) {

    $nama   = $_POST['nama'];
    $kelas  = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    mysqli_query($conn, "INSERT INTO siswa VALUES('', '$nama', '$kelas', '$alamat')");

    header("Location: index.php");
}

# HAPUS DATA
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM siswa WHERE id='$id'");

    header("Location: index.php");
}

# EDIT DATA
if (isset($_POST['update'])) {

    $id      = $_POST['id'];
    $nama    = $_POST['nama'];
    $kelas   = $_POST['kelas'];
    $alamat  = $_POST['alamat'];

    mysqli_query($conn, "UPDATE siswa SET
        nama='$nama',
        kelas='$kelas',
        alamat='$alamat'
        WHERE id='$id'
    ");

    header("Location: index.php");
}

# AMBIL DATA EDIT
$edit = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $query = mysqli_query($conn, "SELECT * FROM siswa WHERE id='$id'");

    $edit = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Data Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <h2 class="mb-4">CRUD Data Siswa</h2>

    <div class="card p-4 mb-4">

        <form method="POST">

            <input type="hidden" name="id"
            value="<?= $edit['id'] ?? '' ?>">

            <div class="mb-3">
                <label>Nama</label>

                <input type="text"
                name="nama"
                class="form-control"
                required
                value="<?= $edit['nama'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Kelas</label>

                <input type="text"
                name="kelas"
                class="form-control"
                required
                value="<?= $edit['kelas'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Alamat</label>

                <textarea
                name="alamat"
                class="form-control"><?= $edit['alamat'] ?? '' ?></textarea>
            </div>

            <?php if ($edit) { ?>

                <button type="submit"
                name="update"
                class="btn btn-warning">
                Update
                </button>

                <a href="index.php"
                class="btn btn-secondary">
                Batal
                </a>

            <?php } else { ?>

                <button type="submit"
                name="tambah"
                class="btn btn-primary">
                Simpan
                </button>

            <?php } ?>

        </form>

    </div>

    <table class="table table-bordered table-striped">

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>

        <?php

        $data = mysqli_query($conn, "SELECT * FROM siswa");

        $no = 1;

        while ($row = mysqli_fetch_assoc($data)) {

        ?>

        <tr>

            <td><?= $no++ ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['kelas'] ?></td>
            <td><?= $row['alamat'] ?></td>

            <td>

                <a href="?edit=<?= $row['id'] ?>"
                class="btn btn-sm btn-warning">
                Edit
                </a>

                <a href="?hapus=<?= $row['id'] ?>"
                class="btn btn-sm btn-danger"
                onclick="return confirm('Yakin hapus data?')">
                Hapus
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>