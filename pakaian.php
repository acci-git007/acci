<?php
$host = "penjualan-db.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "db_pakaian";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal : " . $conn->connect_error);
}

// SIMPAN DATA
if(isset($_POST['simpan'])){
    $nama_pakaian = $_POST['nama_pakaian'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO pakaian
            (nama_pakaian, ukuran, harga, stok)
            VALUES
            ('$nama_pakaian','$ukuran','$harga','$stok')";

    $conn->query($sql);

    header("Location: pakaian.php");
    exit;
}

// HAPUS DATA
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    $conn->query("DELETE FROM pakaian WHERE id='$id'");

    header("Location: pakaian.php");
    exit;
}

// AMBIL DATA EDIT
$edit = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM pakaian WHERE id='$id'"
    );

    $edit = $result->fetch_assoc();
}

// UPDATE DATA
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $nama_pakaian = $_POST['nama_pakaian'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $sql = "UPDATE pakaian SET
            nama_pakaian='$nama_pakaian',
            ukuran='$ukuran',
            harga='$harga',
            stok='$stok'
            WHERE id='$id'";

    $conn->query($sql);

    header("Location: pakaian.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Penjualan Pakaian</title>
</head>
<body>

<h2>Data Penjualan Pakaian</h2>

<form method="POST">

<input type="hidden" name="id"
value="<?php echo $edit['id'] ?? ''; ?>">

<label>Nama Pakaian</label><br>
<input type="text" name="nama_pakaian"
value="<?php echo $edit['nama_pakaian'] ?? ''; ?>" required>
<br><br>

<label>Ukuran</label><br>
<select name="ukuran" required>
    <option value="">Pilih Ukuran</option>
    <option value="S">S</option>
    <option value="M">M</option>
    <option value="L">L</option>
    <option value="XL">XL</option>
</select>
<br><br>

<label>Harga</label><br>
<input type="number" name="harga"
value="<?php echo $edit['harga'] ?? ''; ?>" required>
<br><br>

<label>Stok</label><br>
<input type="number" name="stok"
value="<?php echo $edit['stok'] ?? ''; ?>" required>
<br><br>

<?php if($edit){ ?>
    <button type="submit" name="update">
        Update Data
    </button>
<?php } else { ?>
    <button type="submit" name="simpan">
        Simpan Data
    </button>
<?php } ?>

</form>

<hr>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Nama Pakaian</th>
    <th>Ukuran</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php

$data = $conn->query(
    "SELECT * FROM pakaian ORDER BY id DESC"
);

while($row = $data->fetch_assoc()){
?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['nama_pakaian']; ?></td>
    <td><?php echo $row['ukuran']; ?></td>
    <td><?php echo $row['harga']; ?></td>
    <td><?php echo $row['stok']; ?></td>
    <td>
        <a href="?edit=<?php echo $row['id']; ?>">
            Edit
        </a>
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
