<?php
$conn = new mysqli(
    "dblatihan.c83ya4kmsi7u.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "dbpenjualan"
);

if($conn->connect_error){
    die("Koneksi gagal: ".$conn->connect_error);
}

if(isset($_POST['simpan'])){
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    $conn->query("INSERT INTO tbpenjualan
    (nama_barang,harga,jumlah)
    VALUES('$nama','$harga','$jumlah')");
}

if(isset($_GET['hapus'])){
    $id=$_GET['hapus'];
    $conn->query("DELETE FROM tbpenjualan WHERE id='$id'");
}

$data=$conn->query("SELECT * FROM tbpenjualan");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Penjualan</title>
</head>
<body>

<h2>Data Penjualan</h2>

<form method="post">
Nama Barang:
<input type="text" name="nama_barang" required><br><br>

Harga:
<input type="number" name="harga" required><br><br>

Jumlah:
<input type="number" name="jumlah" required><br><br>

<button name="simpan">Simpan</button>
</form>

<hr>

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Nama Barang</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Aksi</th>
</tr>

<?php while($row=$data->fetch_assoc()){ ?>
<tr>
<td><?= $row['id']; ?></td>
<td><?= $row['nama_barang']; ?></td>
<td><?= $row['harga']; ?></td>
<td><?= $row['jumlah']; ?></td>
<td>
<a href="?hapus=<?= $row['id']; ?>">Hapus</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>
