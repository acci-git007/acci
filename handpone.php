<?php
$host = "penjualan-db.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "db_handphone";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Simpan Data
if(isset($_POST['simpan'])){
    $nama_hp = $_POST['nama_hp'];
    $merk    = $_POST['merk'];
    $harga   = $_POST['harga'];
    $stok    = $_POST['stok'];

    $sql = "INSERT INTO handphone (nama_hp, merk, harga, stok)
            VALUES ('$nama_hp', '$merk', '$harga', '$stok')";

    $conn->query($sql);
    header("Location: handphone.php");
}

// Hapus Data
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    $conn->query("DELETE FROM handphone WHERE id='$id'");
    header("Location: handphone.php");
}

// Ambil Data Edit
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $hasil = $conn->query("SELECT * FROM handphone WHERE id='$id'");
    $edit = $hasil->fetch_assoc();
}

// Update Data
if(isset($_POST['update'])){
    $id      = $_POST['id'];
    $nama_hp = $_POST['nama_hp'];
    $merk    = $_POST['merk'];
    $harga   = $_POST['harga'];
    $stok    = $_POST['stok'];

    $sql = "UPDATE handphone
            SET nama_hp='$nama_hp',
                merk='$merk',
                harga='$harga',
                stok='$stok'
            WHERE id='$id'";

    $conn->query($sql);
    header("Location: handphone.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Penjualan Handphone</title>
</head>
<body>

<h2>Data Penjualan Handphone</h2>

<form method="POST">

<input type="hidden" name="id"
value="<?php echo $edit['id'] ?? ''; ?>">

Nama HP :
<input type="text" name="nama_hp"
value="<?php echo $edit['nama_hp'] ?? ''; ?>" required>
<br><br>

Merk :
<input type="text" name="merk"
value="<?php echo $edit['merk'] ?? ''; ?>" required>
<br><br>

Harga :
<input type="number" name="harga"
value="<?php echo $edit['harga'] ?? ''; ?>" required>
<br><br>

Stok :
<input type="number" name="stok"
value="<?php echo $edit['stok'] ?? ''; ?>" required>
<br><br>

<?php if($edit){ ?>
    <button type="submit" name="update">Update</button>
<?php } else { ?>
    <button type="submit" name="simpan">Simpan</button>
<?php } ?>

</form>

<hr>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Nama HP</th>
    <th>Merk</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php
$data = $conn->query("SELECT * FROM handphone ORDER BY id DESC");

while($row = $data->fetch_assoc()){
?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['nama_hp']; ?></td>
    <td><?php echo $row['merk']; ?></td>
    <td><?php echo $row['harga']; ?></td>
    <td><?php echo $row['stok']; ?></td>
    <td>
        <a href="?edit=<?php echo $row['id']; ?>">Edit</a>
        |
        <a href="?hapus=<?php echo $row['id']; ?>"
           onclick="return confirm('Yakin hapus data?')">
           Hapus
        </a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
