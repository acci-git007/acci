<?php
$rds_endpoint = "dbpenjuanceo.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$username = "latihan";
$password = "latihan2026";
$database = "db_handphone";

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

    $merk  = $_POST['merk'];
    $tipe  = $_POST['tipe'];
    $ram   = $_POST['ram'];
    $harga = $_POST['harga'];

    $sql = "INSERT INTO handphone
            (merk, tipe, ram, harga)
            VALUES
            ('$merk','$tipe','$ram','$harga')";

    $conn->query($sql);
}

/* DELETE */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $conn->query("DELETE FROM handphone WHERE id='$id'");
}

/* UPDATE */
if(isset($_POST['update'])){

    $id    = $_POST['id'];
    $merk  = $_POST['merk'];
    $tipe  = $_POST['tipe'];
    $ram   = $_POST['ram'];
    $harga = $_POST['harga'];

    $conn->query("
        UPDATE handphone
        SET
        merk='$merk',
        tipe='$tipe',
        ram='$ram',
        harga='$harga'
        WHERE id='$id'
    ");
}

$edit = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM handphone WHERE id='$id'"
    );

    $edit = $result->fetch_assoc();
}

$data = $conn->query("SELECT * FROM handphone");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penjualan Handphone AWS RDS</title>
</head>
<body>

<h2>CRUD Penjualan Handphone</h2>

<form method="POST">

<input type="hidden"
name="id"
value="<?= $edit['id'] ?? '' ?>">

<table>

<tr>
<td>Merk</td>
<td>
<input type="text"
name="merk"
value="<?= $edit['merk'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>Tipe</td>
<td>
<input type="text"
name="tipe"
value="<?= $edit['tipe'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>RAM</td>
<td>
<input type="text"
name="ram"
value="<?= $edit['ram'] ?? '' ?>"
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

<a href="handphone.php">
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
<th>Merk</th>
<th>Tipe</th>
<th>RAM</th>
<th>Harga</th>
<th>Aksi</th>
</tr>

<?php while($row = $data->fetch_assoc()){ ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['merk'] ?></td>
<td><?= $row['tipe'] ?></td>
<td><?= $row['ram'] ?></td>
<td>Rp <?= number_format($row['harga']) ?></td>

<td>

<a href="?edit=<?= $row['id'] ?>">
Edit
</a>

|

<a href="?hapus=<?= $row['id'] ?>"
onclick="return confirm('Yakin hapus?')">
Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>
