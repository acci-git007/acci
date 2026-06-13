<?php
$host = "penjualan-db.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "db_pakaian";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal : " . $conn->connect_error);
}

// Simpan Data
if(isset($_POST['simpan'])){
    $nama = $_POST['nama_pakaian'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $conn->query("INSERT INTO pakaian
    (nama_pakaian, ukuran, harga, stok)
    VALUES
    ('$nama','$ukuran','$harga','$stok')");

    header("Location: pakaian.php");
}

// Hapus Data
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    $conn->query("DELETE FROM pakaian WHERE id='$id'");

    header("Location: pakaian.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sistem Penjualan Pakaian</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f6f9;
    margin:0;
    padding:0;
}

.header{
    background:#2c3e50;
    color:white;
    padding:20px;
    text-align:center;
}

.container{
    width:90%;
    margin:auto;
    margin-top:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
}

button{
    background:#27ae60;
    color:white;
    border:none;
    padding:10px 20px;
    cursor:pointer;
}

button:hover{
    background:#219150;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#34495e;
    color:white;
    padding:10px;
}

table td{
    padding:10px;
    border:1px solid #ddd;
}

.hapus{
    background:red;
    color:white;
    padding:5px 10px;
    text-decoration:none;
    border-radius:5px;
}
</style>

</head>
<body>

<div class="header">
    <h1>SISTEM PENJUALAN PAKAIAN</h1>
</div>

<div class="container">

<div class="card">

<h3>Input Data Pakaian</h3>

<form method="POST">

<label>Nama Pakaian</label>
<input type="text" name="nama_pakaian" required>

<label>Ukuran</label>
<select name="ukuran">
    <option>S</option>
    <option>M</option>
    <option>L</option>
    <option>XL</option>
</select>

<label>Harga</label>
<input type="number" name="harga" required>

<label>Stok</label>
<input type="number" name="stok" required>

<button type="submit" name="simpan">
    Simpan Data
</button>

</form>

</div>

<div class="card">

<h3>Data Pakaian</h3>

<table>

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
<td>Rp <?php echo number_format($row['harga']); ?></td>
<td><?php echo $row['stok']; ?></td>

<td>
<a class="hapus"
href="?hapus=<?php echo $row['id']; ?>"
onclick="return confirm('Hapus data?')">
Hapus
</a>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>
