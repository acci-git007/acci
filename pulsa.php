<?php

$rds_endpoint = "YOUR_RDS_ENDPOINT";
$username = "admin";
$password = "PASSWORD_RDS";
$database = "db_pulsa";

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

    $operator = $_POST['operator'];
    $nomor_hp = $_POST['nomor_hp'];
    $nominal  = $_POST['nominal'];
    $harga    = $_POST['harga'];

    $sql = "INSERT INTO pulsa
            (operator_seluler, nomor_hp, nominal, harga)
            VALUES
            ('$operator','$nomor_hp','$nominal','$harga')";

    $conn->query($sql);
}

/* DELETE */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $conn->query("DELETE FROM pulsa WHERE id='$id'");
}

/* UPDATE */
if(isset($_POST['update'])){

    $id       = $_POST['id'];
    $operator = $_POST['operator'];
    $nomor_hp = $_POST['nomor_hp'];
    $nominal  = $_POST['nominal'];
    $harga    = $_POST['harga'];

    $conn->query("
        UPDATE pulsa
        SET
        operator_seluler='$operator',
        nomor_hp='$nomor_hp',
        nominal='$nominal',
        harga='$harga'
        WHERE id='$id'
    ");
}

$edit = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM pulsa WHERE id='$id'"
    );

    $edit = $result->fetch_assoc();
}

$data = $conn->query("SELECT * FROM pulsa");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penjualan Pulsa AWS RDS</title>
</head>
<body>

<h2>CRUD Penjualan Pulsa</h2>

<form method="POST">

<input type="hidden"
name="id"
value="<?= $edit['id'] ?? '' ?>">

<table>

<tr>
<td>Operator</td>
<td>
<input type="text"
name="operator"
value="<?= $edit['operator_seluler'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>Nomor HP</td>
<td>
<input type="text"
name="nomor_hp"
value="<?= $edit['nomor_hp'] ?? '' ?>"
required>
</td>
</tr>

<tr>
<td>Nominal</td>
<td>
<input type="text"
name="nominal"
value="<?= $edit['nominal'] ?? '' ?>"
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

<a href="pulsa.php">Batal</a>

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
<th>Operator</th>
<th>Nomor HP</th>
<th>Nominal</th>
<th>Harga</th>
<th>Aksi</th>
</tr>

<?php while($row = $data->fetch_assoc()){ ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['operator_seluler'] ?></td>
<td><?= $row['nomor_hp'] ?></td>
<td><?= $row['nominal'] ?></td>
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
