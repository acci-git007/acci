<?php

/* ==========================
   KONFIGURASI DATABASE RDS
   ========================== */

$host = "dblatihan.crq462eykeyv.ap-southeast-2.rds.amazonaws.com";
$user = "admin";
$password = "admin2026";
$database = "db_siswa";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

/* ==========================
   CREATE
   ========================== */

if (isset($_POST['simpan'])) {

    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $jk = trim($_POST['jenis_kelamin']);
    $alamat = trim($_POST['alamat']);

    $stmt = $conn->prepare("
        INSERT INTO siswa
        (nis,nama,jenis_kelamin,alamat)
        VALUES (?,?,?,?)
    ");

    $stmt->bind_param(
        "ssss",
        $nis,
        $nama,
        $jk,
        $alamat
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* ==========================
   UPDATE
   ========================== */

if (isset($_POST['update'])) {

    $id = intval($_POST['id']);
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $jk = trim($_POST['jenis_kelamin']);
    $alamat = trim($_POST['alamat']);

    $stmt = $conn->prepare("
        UPDATE siswa
        SET
        nis=?,
        nama=?,
        jenis_kelamin=?,
        alamat=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssssi",
        $nis,
        $nama,
        $jk,
        $alamat,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* ==========================
   DELETE
   ========================== */

if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    $stmt = $conn->prepare(
        "DELETE FROM siswa WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* ==========================
   EDIT MODE
   ========================== */

$edit = false;
$dataEdit = [];

if (isset($_GET['edit'])) {

    $edit = true;

    $id = intval($_GET['edit']);

    $stmt = $conn->prepare(
        "SELECT * FROM siswa WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    $dataEdit = $result->fetch_assoc();
}

/* ==========================
   READ
   ========================== */

$data = $conn->query(
    "SELECT * FROM siswa ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<title>CRUD Data Siswa</title>

<style>

body{
    font-family:Arial,sans-serif;
    margin:40px;
}

table{
    border-collapse:collapse;
    width:100%;
}

table,th,td{
    border:1px solid #ccc;
}

th,td{
    padding:10px;
}

input,textarea,select{
    width:100%;
    padding:8px;
    margin-top:5px;
}

button{
    padding:10px 15px;
    cursor:pointer;
}

.form-box{
    background:#f7f7f7;
    padding:20px;
    margin-bottom:20px;
    border-radius:5px;
}

.btn-edit{
    color:blue;
}

.btn-delete{
    color:red;
}

</style>

</head>

<body>

<h2>CRUD DATA SISWA</h2>

<div class="form-box">

<h3>
<?= $edit ? "Edit Siswa" : "Tambah Siswa" ?>
</h3>

<form method="POST">

<?php if($edit): ?>
<input
type="hidden"
name="id"
value="<?= $dataEdit['id']; ?>">
<?php endif; ?>

<label>NIS</label>

<input
type="text"
name="nis"
required
value="<?= $edit ? htmlspecialchars($dataEdit['nis']) : '' ?>">

<br><br>

<label>Nama</label>

<input
type="text"
name="nama"
required
value="<?= $edit ? htmlspecialchars($dataEdit['nama']) : '' ?>">

<br><br>

<label>Jenis Kelamin</label>

<select name="jenis_kelamin">

<option value="L"
<?= ($edit && $dataEdit['jenis_kelamin']=='L') ? 'selected' : '' ?>>
Laki-laki
</option>

<option value="P"
<?= ($edit && $dataEdit['jenis_kelamin']=='P') ? 'selected' : '' ?>>
Perempuan
</option>

</select>

<br><br>

<label>Alamat</label>

<textarea name="alamat"><?= $edit ? htmlspecialchars($dataEdit['alamat']) : '' ?></textarea>

<br><br>

<?php if($edit): ?>

<button type="submit" name="update">
Update Data
</button>

<a href="index.php">
Batal
</a>

<?php else: ?>

<button type="submit" name="simpan">
Simpan Data
</button>

<?php endif; ?>

</form>

</div>

<h3>Daftar Siswa</h3>

<table>

<tr>
    <th>ID</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>JK</th>
    <th>Alamat</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

<?php while($row = $data->fetch_assoc()) : ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['nis']); ?></td>

<td><?= htmlspecialchars($row['nama']); ?></td>

<td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>

<td><?= htmlspecialchars($row['alamat']); ?></td>

<td><?= $row['created_at']; ?></td>

<td>

<a
class="btn-edit"
href="?edit=<?= $row['id']; ?>">
Edit
</a>

|

<a
class="btn-delete"
href="?hapus=<?= $row['id']; ?>"
onclick="return confirm('Yakin hapus data?')">
Hapus
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>
