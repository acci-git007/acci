<?php

$rds_endpoint = "YOUR_RDS_ENDPOINT";
$username = "admin";
$password = "PASSWORD_RDS";
$database = "db_pakaian";

$conn = new mysqli(
    $rds_endpoint,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

/* CREATE */
if(isset($_POST['tambah'])){

    $nama_barang = $_POST['nama_barang'];
    $ukuran      = $_POST['ukuran'];
    $warna       = $_POST['warna'];
    $harga       = $_POST['harga'];

    $sql = "INSERT INTO pakaian
            (nama_barang, ukuran, warna, harga)
            VALUES
            ('$nama_barang','$ukuran','$warna','$harga')";

    $conn->query($sql);
}

/* DELETE */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $conn->query("DELETE FROM pakaian WHERE id='$id'");
}

/* UPDATE */
if(isset($_POST['update'])){

    $id          = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $ukuran      = $_POST['ukuran'];
    $warna       = $_POST['warna'];
    $harga       = $_POST['harga'];

    $conn->query("
        UPDATE pakaian
        SET
        nama_barang='$nama_barang',
        ukuran='$ukuran',
        warna='$warna',
        harga='$harga'
        WHERE id='$id'
    ");
}

$edit = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM pakaian WHERE id='$id'"
    );

    $edit = $result->fetch_assoc();
}

$data = $conn->query("SELECT * FROM pakaian");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penjualan Pakaian AWS RDS</title>
</head>
<body>

<h2>CRUD Penjualan Pakaian</h2>

<form method="POST">

<input type="hidden"
name="id"
value="<?= $edit['id'] ?? '' ?>">

<table>

<tr>
<td>Nama Barang</td>
<td>
<input type="text"
name="nama_barang"
value="<?= $edit['nama_barang'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>Ukuran</td>
<td>
<input type="text"
name="ukuran"
value="<?= $edit['ukuran'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>Warna</td>
<td>
<input type="text"
name="warna"
value="<?= $edit['warna'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>Harga</td>
<td>
<input type="number"
name="harga"
value="<?= $edit['harga'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td colspan="2">

<?php if($edit){ ?>

<button type="submit" name="update">
Update
</button>

<a href="pakaian.php">
Batal
</a>

<?php } else { ?>

<button type="submit" name="tambah">
Simpan
</button>

<?php } ?>

</td>
</tr>

</table>

</form>

<hr>

<table border="1" cellpadding="8">

<tr>
<th>ID</th>
<th>Nama Barang</th>
<th>Ukuran</th>
<th>Warna</th>
<th>Harga</th>
<th>Aksi</th>
</tr>

<?php while($row = $data->fetch_assoc()){ ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['nama_barang'] ?></td>
<td><?= $row['ukuran'] ?></td>
<td><?= $row['warna'] ?></td>
<td>Rp <?= number_format($row['harga']) ?></td>

<td>

<a href="?edit=<?= $row['id'] ?>">
Edit
</a>

|

<a href="?hapus=<?= $row['id'] ?>"
onclick="return confirm('Yakin hapus data ini?')">
Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>
