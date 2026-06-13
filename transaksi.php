<?php
$conn = new mysqli(
    "<?php
$conn = new mysqli(
    "your-rds-endpoint.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "dbpenjualan"
);

if(isset($_POST['simpan'])){
    $kode=$_POST['kode'];
    $pelanggan=$_POST['pelanggan'];
    $total=$_POST['total'];

    $conn->query("INSERT INTO transaksi
    (kode_transaksi,pelanggan,total_bayar)
    VALUES('$kode','$pelanggan','$total')");
}

if(isset($_GET['hapus'])){
    $id=$_GET['hapus'];
    $conn->query("DELETE FROM transaksi WHERE id='$id'");
}

$data=$conn->query("SELECT * FROM transaksi");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Transaksi</title>
</head>
<body>

<h2>Data Transaksi</h2>

<form method="post">
Kode:
<input type="text" name="kode" required><br><br>

Pelanggan:
<input type="text" name="pelanggan" required><br><br>

Total:
<input type="number" name="total" required><br><br>

<button name="simpan">Simpan</button>
</form>

<hr>

<table border="1">
<tr>
<th>ID</th>
<th>Kode</th>
<th>Pelanggan</th>
<th>Total</th>
<th>Aksi</th>
</tr>

<?php while($r=$data->fetch_assoc()){ ?>
<tr>
<td><?= $r['id']; ?></td>
<td><?= $r['kode_transaksi']; ?></td>
<td><?= $r['pelanggan']; ?></td>
<td><?= $r['total_bayar']; ?></td>
<td>
<a href="?hapus=<?= $r['id']; ?>">Hapus</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>",
    "admin",
    "password",
    "dbpenjualan"
);

if(isset($_POST['simpan'])){
    $kode=$_POST['kode'];
    $pelanggan=$_POST['pelanggan'];
    $total=$_POST['total'];

    $conn->query("INSERT INTO transaksi
    (kode_transaksi,pelanggan,total_bayar)
    VALUES('$kode','$pelanggan','$total')");
}

if(isset($_GET['hapus'])){
    $id=$_GET['hapus'];
    $conn->query("DELETE FROM transaksi WHERE id='$id'");
}

$data=$conn->query("SELECT * FROM transaksi");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Transaksi</title>
</head>
<body>

<h2>Data Transaksi</h2>

<form method="post">
Kode:
<input type="text" name="kode" required><br><br>

Pelanggan:
<input type="text" name="pelanggan" required><br><br>

Total:
<input type="number" name="total" required><br><br>

<button name="simpan">Simpan</button>
</form>

<hr>

<table border="1">
<tr>
<th>ID</th>
<th>Kode</th>
<th>Pelanggan</th>
<th>Total</th>
<th>Aksi</th>
</tr>

<?php while($r=$data->fetch_assoc()){ ?>
<tr>
<td><?= $r['id']; ?></td>
<td><?= $r['kode_transaksi']; ?></td>
<td><?= $r['pelanggan']; ?></td>
<td><?= $r['total_bayar']; ?></td>
<td>
<a href="?hapus=<?= $r['id']; ?>">Hapus</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>
