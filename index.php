<?php

/*
|--------------------------------------------------------------------------
| AMAZON RDS CONFIG
|--------------------------------------------------------------------------
*/

$rds_host = "dblatihan.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$rds_port = 3306;
$rds_user = "admin";
$rds_db   = "sekolah";

/*
|--------------------------------------------------------------------------
| CONNECT TO AMAZON RDS
|--------------------------------------------------------------------------
*/

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = new mysqli(
        $rds_host,
        $rds_user,
        $rds_pass,
        $rds_db,
        $rds_port
    );

    $conn->set_charset("utf8mb4");

} catch (Exception $e) {

    die("Koneksi ke Amazon RDS gagal : " . $e->getMessage());
}

/*
|--------------------------------------------------------------------------
| CREATE TABLE IF NOT EXISTS
|--------------------------------------------------------------------------
*/

$conn->query("
CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");

/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

if (isset($_POST['tambah'])) {

    $nis    = trim($_POST['nis']);
    $nama   = trim($_POST['nama']);
    $kelas  = trim($_POST['kelas']);
    $alamat = trim($_POST['alamat']);

    $stmt = $conn->prepare("
        INSERT INTO siswa
        (nis, nama, kelas, alamat)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssss",
        $nis,
        $nama,
        $kelas,
        $alamat
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    $stmt = $conn->prepare("
        DELETE FROM siswa
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $id     = (int)$_POST['id'];
    $nis    = trim($_POST['nis']);
    $nama   = trim($_POST['nama']);
    $kelas  = trim($_POST['kelas']);
    $alamat = trim($_POST['alamat']);

    $stmt = $conn->prepare("
        UPDATE siswa
        SET
            nis = ?,
            nama = ?,
            kelas = ?,
            alamat = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssssi",
        $nis,
        $nama,
        $kelas,
        $alamat,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT DATA
|--------------------------------------------------------------------------
*/

$editData = null;

if (isset($_GET['edit'])) {

    $id = (int)$_GET['edit'];

    $stmt = $conn->prepare("
        SELECT *
        FROM siswa
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>CRUD Data Siswa - Amazon RDS</title>

<style>

body{
    font-family:Arial, sans-serif;
    background:#f4f6f9;
    margin:30px;
}

.container{
    max-width:1200px;
    margin:auto;
}

.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
    margin-bottom:20px;
}

h1,h2,h3{
    margin-top:0;
}

input,
textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:5px;
}

button{
    background:#0d6efd;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#0b5ed7;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

table th{
    background:#0d6efd;
    color:white;
}

table th,
table td{
    border:1px solid #ddd;
    padding:10px;
    text-align:left;
}

.edit{
    color:green;
    text-decoration:none;
    font-weight:bold;
}

.delete{
    color:red;
    text-decoration:none;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<?php if($editData){ ?>

<h2>Edit Data Siswa</h2>

<form method="POST">

<input type="hidden"
       name="id"
       value="<?= $editData['id']; ?>">

<label>NIS</label>
<input type="text"
       name="nis"
       value="<?= htmlspecialchars($editData['nis']); ?>"
       required>

<label>Nama</label>
<input type="text"
       name="nama"
       value="<?= htmlspecialchars($editData['nama']); ?>"
       required>

<label>Kelas</label>
<input type="text"
       name="kelas"
       value="<?= htmlspecialchars($editData['kelas']); ?>"
       required>

<label>Alamat</label>
<textarea name="alamat"><?= htmlspecialchars($editData['alamat']); ?></textarea>

<button type="submit" name="update">
Update Data
</button>

<a href="index.php">
Batal
</a>

</form>

<?php } else { ?>

<h2>Tambah Data Siswa</h2>

<form method="POST">

<label>NIS</label>
<input type="text"
       name="nis"
       required>

<label>Nama</label>
<input type="text"
       name="nama"
       required>

<label>Kelas</label>
<input type="text"
       name="kelas"
       required>

<label>Alamat</label>
<textarea name="alamat"></textarea>

<button type="submit" name="tambah">
Simpan Data
</button>

</form>

<?php } ?>

</div>

<div class="card">

<h2>Daftar Siswa</h2>

<table>

<tr>
    <th>ID</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Alamat</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

<?php

$result = $conn->query("
    SELECT *
    FROM siswa
    ORDER BY id DESC
");

while($row = $result->fetch_assoc()) {

?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['nis']); ?></td>

<td><?= htmlspecialchars($row['nama']); ?></td>

<td><?= htmlspecialchars($row['kelas']); ?></td>

<td><?= htmlspecialchars($row['alamat']); ?></td>

<td><?= $row['created_at']; ?></td>

<td>

<a class="edit"
   href="?edit=<?= $row['id']; ?>">
   Edit
</a>

|

<a class="delete"
   href="?hapus=<?= $row['id']; ?>"
   onclick="return confirm('Yakin ingin menghapus data ini?')">
   Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>
