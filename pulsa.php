<?php

$host = "dbpenjuanceo.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "latihan";
$pass = "latihan2026";
$db   = "db_pulsa";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi gagal : ".mysqli_connect_error());
}

/* TAMBAH DATA */
if(isset($_POST['simpan'])){

    $operator = $_POST['operator'];
    $nomor    = $_POST['nomor'];
    $nominal  = $_POST['nominal'];

    switch($nominal){

        case 5000:
            $harga = 6000;
            break;

        case 10000:
            $harga = 11000;
            break;

        case 25000:
            $harga = 26000;
            break;

        case 50000:
            $harga = 51000;
            break;

        case 100000:
            $harga = 101000;
            break;

        default:
            $harga = 0;
    }

    mysqli_query($conn,"
        INSERT INTO pulsa
        (operator_seluler,nomor_hp,nominal,harga)
        VALUES
        ('$operator','$nomor','$nominal','$harga')
    ");
}

/* HAPUS */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM pulsa
        WHERE id='$id'
    ");
}

$data = mysqli_query($conn,"
SELECT * FROM pulsa
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Penjualan Pulsa</title>
</head>
<body>

<h2>Aplikasi Penjualan Pulsa</h2>

<form method="POST">

<table>

<tr>
<td>Operator</td>
<td>
<select name="operator">

<option value="Telkomsel">
Telkomsel
</option>

<option value="Indosat">
Indosat
</option>

<option value="XL">
XL
</option>

<option value="Tri">
Tri
</option>

<option value="Smartfren">
Smartfren
</option>

</select>
</td>
</tr>

<tr>
<td>Nomor HP</td>
<td>
<input type="text"
name="nomor"
placeholder="08xxxxxxxxxx"
required>
</td>
</tr>

<tr>
<td>Nominal Pulsa</td>
<td>

<select name="nominal">

<option value="5000">
5.000
</option>

<option value="10000">
10.000
</option>

<option value="25000">
25.000
</option>

<option value="50000">
50.000
</option>

<option value="100000">
100.000
</option>

</select>

</td>
</tr>

<tr>
<td></td>
<td>

<button type="submit"
name="simpan">
Jual Pulsa
</button>

</td>
</tr>

</table>

</form>

<hr>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Operator</th>
<th>Nomor HP</th>
<th>Nominal</th>
<th>Harga</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= $row['operator_seluler']; ?></td>

<td><?= $row['nomor_hp']; ?></td>

<td>
Rp <?= number_format($row['nominal']); ?>
</td>

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
