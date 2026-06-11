<?php

$host = "dbpenjuanceo.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "latihan";
$pass = "latihan2026";
$db   = "db_handphone";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi gagal : ".mysqli_connect_error());
}

/* TAMBAH DATA */
if(isset($_POST['simpan'])){

    $merk      = $_POST['merk'];
    $model_hp  = $_POST['model_hp'];
    $ram       = $_POST['ram'];
    $storage   = $_POST['storage'];
    $harga     = $_POST['harga'];

    mysqli_query($conn,"
        INSERT INTO handphone
        (merk,model_hp,ram,storage_hp,harga)
        VALUES
        ('$merk','$model_hp','$ram','$storage','$harga')
    ");
}

/* HAPUS DATA */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM handphone
        WHERE id='$id'
    ");
}

$data = mysqli_query($conn,"
SELECT *
FROM handphone
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Penjualan Handphone</title>
</head>
<body>

<h2>Aplikasi Penjualan Handphone</h2>

<form method="POST">

<table>

<tr>
<td>Merk</td>
<td>
<select name="merk">

<option value="Samsung">
Samsung
</option>

<option value="Xiaomi">
Xiaomi
</option>

<option value="Oppo">
Oppo
</option>

<option value="Vivo">
Vivo
</option>

<option value="iPhone">
iPhone
</option>

<option value="Realme">
Realme
</option>

</select>
</td>
</tr>

<tr>
<td>Model HP</td>
<td>
<input type="text"
name="model_hp"
required>
</td>
</tr>

<tr>
<td>RAM</td>
<td>

<select name="ram">

<option value="4 GB">
4 GB
</option>

<option value="6 GB">
6 GB
</option>

<option value="8 GB">
8 GB
</option>

<option value="12 GB">
12 GB
</option>

<option value="16 GB">
16 GB
</option>

</select>

</td>
</tr>

<tr>
<td>Storage</td>
<td>

<select name="storage">

<option value="64 GB">
64 GB
</option>

<option value="128 GB">
128 GB
</option>

<option value="256 GB">
256 GB
</option>

<option value="512 GB">
512 GB
</option>

<option value="1 TB">
1 TB
</option>

</select>

</td>
</tr>

<tr>
<td>Harga</td>
<td>
<input type="number"
name="harga"
required>
</td>
</tr>

<tr>
<td></td>
<td>

<button type="submit"
name="simpan">
Simpan Data
</button>

</td>
</tr>

</table>

</form>

<hr>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Merk</th>
<th>Model</th>
<th>RAM</th>
<th>Storage</th>
<th>Harga</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= $row['merk']; ?></td>

<td><?= $row['model_hp']; ?></td>

<td><?= $row['ram']; ?></td>

<td><?= $row['storage_hp']; ?></td>

<td>
Rp <?= number_format($row['harga']); ?>
</td>

<td>
<?= $row['tanggal']; ?>
</td>

<td>

<a href="?hapus=<?= $row['id']; ?>"
onclick="return confirm('Hapus data?')">
Hapus
</a>

</td>

</tr>

<?php
}
?>

</table>

</body>
</html>
