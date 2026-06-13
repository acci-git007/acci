<?php
$conn = new mysqli(
    "dblatihan.c83ya4kmsi7u.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "dbsiswa"
);

if(isset($_POST['simpan'])){
    $nis=$_POST['nis'];
    $nama=$_POST['nama'];
    $kelas=$_POST['kelas'];
    $status=$_POST['status'];
    $tanggal=$_POST['tanggal'];

    $conn->query("INSERT INTO tbsiswa
    (nis,nama,kelas,status_hadir,tanggal)
    VALUES('$nis','$nama','$kelas','$status','$tanggal')");
}

if(isset($_GET['hapus'])){
    $id=$_GET['hapus'];
    $conn->query("DELETE FROM tbsiswa WHERE id='$id'");
}

$data=$conn->query("SELECT * FROM tbsiswa");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Absensi Siswa</title>
</head>
<body>

<h2>Absensi Siswa</h2>

<form method="post">

NIS:
<input type="text" name="nis" required><br><br>

Nama:
<input type="text" name="nama" required><br><br>

Kelas:
<input type="text" name="kelas" required><br><br>

Status:
<select name="status">
<option>Hadir</option>
<option>Izin</option>
<option>Sakit</option>
<option>Alpa</option>
</select><br><br>

Tanggal:
<input type="date" name="tanggal" required><br><br>

<button name="simpan">Simpan</button>

</form>

<hr>

<table border="1">
<tr>
<th>ID</th>
<th>NIS</th>
<th>Nama</th>
<th>Kelas</th>
<th>Status</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>

<?php while($r=$data->fetch_assoc()){ ?>
<tr>
<td><?= $r['id']; ?></td>
<td><?= $r['nis']; ?></td>
<td><?= $r['nama']; ?></td>
<td><?= $r['kelas']; ?></td>
<td><?= $r['status_hadir']; ?></td>
<td><?= $r['tanggal']; ?></td>
<td>
<a href="?hapus=<?= $r['id']; ?>">Hapus</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>
