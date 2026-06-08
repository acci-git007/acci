<?php

// =====================
// KONEKSI RDS
// =====================
$host = "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "kelas";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Koneksi gagal: ".$conn->connect_error);
}

// =====================
// CREATE
// =====================
if(isset($_POST['simpan'])){
    $stmt = $conn->prepare("
        INSERT INTO pendaftaran (nama,tanggal_lahir,alamat,no_hp,kelas,jurusan)
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssss",
        $_POST['nama'],
        $_POST['tanggal_lahir'],
        $_POST['alamat'],
        $_POST['no_hp'],
        $_POST['kelas'],
        $_POST['jurusan']
    );

    $stmt->execute();
    header("Location: index.php");
    exit;
}

// =====================
// DELETE
// =====================
if(isset($_GET['hapus'])){
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM pendaftaran WHERE id=$id");
    header("Location: index.php");
    exit;
}

// =====================
// AMBIL DATA EDIT
// =====================
$edit = null;

if(isset($_GET['edit'])){
    $id = (int)$_GET['edit'];
    $edit = $conn->query("SELECT * FROM pendaftaran WHERE id=$id")->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Murid</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<h3 class="text-center mb-4">Pendaftaran Murid Baru</h3>

<!-- FORM -->
<div class="card shadow p-3 mb-4">

<form method="POST">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<div class="row">

<div class="col-md-6">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control"
    value="<?= $edit['nama'] ?? '' ?>" required>
</div>

<div class="col-md-6">
    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" class="form-control"
    value="<?= $edit['tanggal_lahir'] ?? '' ?>" required>
</div>

<div class="col-md-12 mt-2">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control" required><?= $edit['alamat'] ?? '' ?></textarea>
</div>

<div class="col-md-6 mt-2">
    <label>No HP</label>
    <input type="text" name="no_hp" class="form-control"
    value="<?= $edit['no_hp'] ?? '' ?>" required>
</div>

<div class="col-md-3 mt-2">
    <label>Kelas</label>
    <select name="kelas" class="form-control" required>
        <option value="">Pilih</option>
        <?php foreach(["X","XI","XII"] as $k){ ?>
        <option value="<?= $k ?>" <?= ($edit['kelas'] ?? '')==$k?'selected':'' ?>>
            <?= $k ?>
        </option>
        <?php } ?>
    </select>
</div>

<div class="col-md-3 mt-2">
    <label>Jurusan</label>
    <select name="jurusan" class="form-control" required>
        <option value="">Pilih</option>
        <?php foreach(["RPL","TKJ","DKV","AKL"] as $j){ ?>
        <option value="<?= $j ?>" <?= ($edit['jurusan'] ?? '')==$j?'selected':'' ?>>
            <?= $j ?>
        </option>
        <?php } ?>
    </select>
</div>

</div>

<div class="mt-3">

<?php if($edit){ ?>
    <button class="btn btn-warning" name="update">Update</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
<?php } else { ?>
    <button class="btn btn-primary" name="simpan">Simpan</button>
<?php } ?>

</div>

</form>

</div>

<!-- TABLE -->
<div class="card shadow p-3">

<h5>Data Pendaftaran</h5>

<table class="table table-striped">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Tanggal</th>
    <th>Kelas</th>
    <th>Jurusan</th>
    <th>No HP</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

<?php
$data = $conn->query("SELECT * FROM pendaftaran ORDER BY id DESC");

while($row = $data->fetch_assoc()){
?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= $row['tanggal_lahir'] ?></td>
    <td><?= $row['kelas'] ?></td>
    <td><?= $row['jurusan'] ?></td>
    <td><?= $row['no_hp'] ?></td>
    <td>
        <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Hapus data?')">
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
