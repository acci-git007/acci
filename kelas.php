<?php

// =============================
// KONEKSI AMAZON RDS MYSQL
// =============================
$host = "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$port = 3306;
$user = "admin";
$pass = "admin2026!";
$db   = "kelas";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// =============================
// SIMPAN DATA
// =============================
if(isset($_POST['simpan'])){

    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];

    $stmt = $conn->prepare("
        INSERT INTO pendaftaran
        (
            nama,
            tanggal_lahir,
            alamat,
            no_hp,
            kelas,
            jurusan
        )
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssss",
        $nama,
        $tanggal_lahir,
        $alamat,
        $no_hp,
        $kelas,
        $jurusan
    );

    $stmt->execute();

    header("Location:index.php");
    exit;
}

// =============================
// UPDATE DATA
// =============================
if(isset($_POST['update'])){

    $id = $_POST['id'];

    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];

    $stmt = $conn->prepare("
        UPDATE pendaftaran
        SET
            nama=?,
            tanggal_lahir=?,
            alamat=?,
            no_hp=?,
            kelas=?,
            jurusan=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssssssi",
        $nama,
        $tanggal_lahir,
        $alamat,
        $no_hp,
        $kelas,
        $jurusan,
        $id
    );

    $stmt->execute();

    header("Location:index.php");
    exit;
}

// =============================
// HAPUS DATA
// =============================
if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];

    $stmt = $conn->prepare("
        DELETE FROM pendaftaran
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location:index.php");
    exit;
}

// =============================
// EDIT DATA
// =============================
$edit = null;

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $stmt = $conn->prepare("
        SELECT *
        FROM pendaftaran
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();

    $result = $stmt->get_result();
    $edit = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Pendaftaran Murid Baru</title>

<style>

body{
    font-family: Arial;
    margin: 30px;
}

input, textarea, select{
    width:100%;
    padding:10px;
    margin-bottom:10px;
}

button{
    padding:10px 20px;
}

table{
    width:100%;
    border-collapse: collapse;
    margin-top:20px;
}

th,td{
    border:1px solid #ddd;
    padding:8px;
}

th{
    background:#f2f2f2;
}

a{
    text-decoration:none;
}

</style>

</head>
<body>

<h2>Pendaftaran Murid Baru</h2>

<form method="POST">

<?php if($edit){ ?>
<input type="hidden" name="id" value="<?= $edit['id']; ?>">
<?php } ?>

<label>Nama Lengkap</label>
<input
    type="text"
    name="nama"
    required
    value="<?= $edit['nama'] ?? ''; ?>"
>

<label>Tanggal Lahir</label>
<input
    type="date"
    name="tanggal_lahir"
    required
    value="<?= $edit['tanggal_lahir'] ?? ''; ?>"
>

<label>Alamat</label>
<textarea
    name="alamat"
    required><?= $edit['alamat'] ?? ''; ?></textarea>

<label>No HP</label>
<input
    type="text"
    name="no_hp"
    required
    value="<?= $edit['no_hp'] ?? ''; ?>"
>

<label>Kelas</label>
<select name="kelas" required>
    <option value="">Pilih Kelas</option>

    <?php
    $kelasList = ["X","XI","XII"];

    foreach($kelasList as $k){

        $selected =
        (($edit['kelas'] ?? '') == $k)
        ? "selected"
        : "";

        echo "<option value='$k' $selected>$k</option>";
    }
    ?>
</select>

<label>Jurusan</label>
<select name="jurusan" required>

    <option value="">Pilih Jurusan</option>

    <?php

    $jurusanList = [
        "RPL",
        "TKJ",
        "DKV",
        "AKL",
        "OTKP"
    ];

    foreach($jurusanList as $j){

        $selected =
        (($edit['jurusan'] ?? '') == $j)
        ? "selected"
        : "";

        echo "<option value='$j' $selected>$j</option>";
    }

    ?>

</select>

<?php if($edit){ ?>

<button type="submit" name="update">
Update Data
</button>

<a href="index.php">
Batal
</a>

<?php } else { ?>

<button type="submit" name="simpan">
Simpan Data
</button>

<?php } ?>

</form>

<hr>

<h2>Data Pendaftaran</h2>

<table>

<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Tanggal Lahir</th>
    <th>Kelas</th>
    <th>Jurusan</th>
    <th>No HP</th>
    <th>Aksi</th>
</tr>

<?php

$data = $conn->query("
SELECT *
FROM pendaftaran
ORDER BY id DESC
");

while($row = $data->fetch_assoc()){

?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['nama']); ?></td>

<td><?= $row['tanggal_lahir']; ?></td>

<td><?= htmlspecialchars($row['kelas']); ?></td>

<td><?= htmlspecialchars($row['jurusan']); ?></td>

<td><?= htmlspecialchars($row['no_hp']); ?></td>

<td>
    <a href="?edit=<?= $row['id']; ?>">
        Edit
    </a>
    |
    <a
        href="?hapus=<?= $row['id']; ?>"
        onclick="return confirm('Yakin hapus data?')"
    >
        Hapus
    </a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>
