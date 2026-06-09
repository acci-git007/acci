<?php
// =====================
// KONEKSI RDS
// =====================
$conn = mysqli_connect(
    "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com", // 
    "admin",
    "admin2026",
    "db_sekolah"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// =====================
// SIMPAN DATA
// =====================
if (isset($_POST['simpan_siswa'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $umur = $_POST['umur'];

    mysqli_query($conn, "INSERT INTO siswa (nama, kelas, umur)
    VALUES ('$nama', '$kelas', '$umur')");
}

if (isset($_POST['simpan_pendaftaran'])) {
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $tanggal = $_POST['tanggal'];

    mysqli_query($conn, "INSERT INTO pendaftaran (nama, jurusan, tanggal_daftar)
    VALUES ('$nama', '$jurusan', '$tanggal')");
}

// =====================
// HAPUS DATA
// =====================
if (isset($_GET['hapus_siswa'])) {
    $id = $_GET['hapus_siswa'];
    mysqli_query($conn, "DELETE FROM siswa WHERE id=$id");
}

if (isset($_GET['hapus_pendaftaran'])) {
    $id = $_GET['hapus_pendaftaran'];
    mysqli_query($conn, "DELETE FROM pendaftaran WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Sekolah</title>
</head>
<body>

<h2>FORM SISWA</h2>
<form method="POST">
    Nama: <input type="text" name="nama"><br>
    Kelas: <input type="text" name="kelas"><br>
    Umur: <input type="number" name="umur"><br>
    <button type="submit" name="simpan_siswa">Simpan Siswa</button>
</form>

<h3>Data Siswa</h3>
<table border="1">
<tr>
    <th>ID</th><th>Nama</th><th>Kelas</th><th>Umur</th><th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM siswa");
while ($row = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['nama']; ?></td>
    <td><?= $row['kelas']; ?></td>
    <td><?= $row['umur']; ?></td>
    <td>
        <a href="?hapus_siswa=<?= $row['id']; ?>">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>

<hr>

<h2>FORM PENDAFTARAN</h2>
<form method="POST">
    Nama: <input type="text" name="nama"><br>
    Jurusan: <input type="text" name="jurusan"><br>
    Tanggal: <input type="date" name="tanggal"><br>
    <button type="submit" name="simpan_pendaftaran">Simpan</button>
</form>

<h3>Data Pendaftaran</h3>
<table border="1">
<tr>
    <th>ID</th><th>Nama</th><th>Jurusan</th><th>Tanggal</th><th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM pendaftaran");
while ($row = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['nama']; ?></td>
    <td><?= $row['jurusan']; ?></td>
    <td><?= $row['tanggal_daftar']; ?></td>
    <td>
        <a href="?hapus_pendaftaran=<?= $row['id']; ?>">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
