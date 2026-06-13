<?php
$host = "endpoint-rds.amazonaws.com";
$user = "admin";
$pass = "password";
$db   = "db_pakaian";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// TAMBAH DATA
if(isset($_POST['tambah'])){
    $nama   = $_POST['nama_pakaian'];
    $ukuran = $_POST['ukuran'];
    $harga  = $_POST['harga'];
    $stok   = $_POST['stok'];

    $sql = "INSERT INTO pakaian
            (nama_pakaian, ukuran, harga, stok)
            VALUES
            ('$nama','$ukuran','$harga','$stok')";

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

// EDIT DATA
$edit = false;
$id = "";
$nama = "";
$ukuran = "";
$harga = "";
$stok = "";

if(isset($_GET['edit'])){
    $edit = true;
    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM pakaian WHERE id='$id'"
    );

    $row = $result->fetch_assoc();

    $nama   = $row['nama_pakaian'];
    $ukuran = $row['ukuran'];
    $harga  = $row['harga'];
    $stok   = $row['stok'];
}

// UPDATE DATA
if(isset($_POST['update'])){

    $id     = $_POST['id'];
    $nama   = $_POST['nama_pakaian'];
    $ukuran = $_POST['ukuran'];
    $harga  = $_POST['harga'];
    $stok   = $_POST['stok'];

    $sql = "UPDATE pakaian SET
            nama_pakaian='$nama',
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
<title>Aplikasi Penjualan Pakaian</title>

<style>

body{
    font-family: Arial;
    background:#eef2f7;
    margin:0;
}

.header{
    background:#1f2937;
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
    box-shadow:0px 2px 10px rgba(0,0,0,0.2);
    margin-bottom:20px;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:5px;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#374151;
    color:white;
    padding:12px;
}

table td{
    padding:10px;
    border:1px solid #ddd;
}

.edit{
    background:orange;
    color:white;
    padding:5px 10px;
    text-decoration:none;
    border-radius:4px;
}

.hapus{
    background:red;
    color:white;
    padding:5px 10px;
    text-decoration:none;
    border-radius:4px;
}

</style>

</head>

<body>

<div class="header">
<h1>APLIKASI PENJUALAN PAKAIAN</h1>
</div>

<div class="container">

<div class="card">

<h2>
<?php echo $edit ? "Edit Data Pakaian" : "Input Data Pakaian"; ?>
</h2>

<form method="POST">

<input type="hidden" name="id"
value="<?php echo $id; ?>">

<label>Nama Pakaian</label>
<input type="text"
name="nama_pakaian"
value="<?php echo $nama; ?>"
required>

<label>Ukuran</label>
<select name="ukuran">

<option value="S"
<?php if($ukuran=="S") echo "selected"; ?>>
S
</option>

<option value="M"
<?php if($ukuran=="M") echo "selected"; ?>>
M
</option>

<option value="L"
<?php if($ukuran=="L") echo "selected"; ?>>
L
</option>

<option value="XL"
<?php if($ukuran=="XL") echo "selected"; ?>>
XL
</option>

</select>

<label>Harga</label>
<input type="number"
name="harga"
value="<?php echo $harga; ?>"
required>

<label>Stok</label>
<input type="number"
name="stok"
value="<?php echo $stok; ?>"
required>

<?php if($edit){ ?>

<button type="submit" name="update">
Update Data
</button>

<?php } else { ?>

<button type="submit" name="tambah">
Simpan Data
</button>

<?php } ?>

</form>

</div>

<div class="card">

<h2>Data Penjualan Pakaian</h2>

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

echo "<tr>

<td>".$row['id']."</td>

<td>".$row['nama_pakaian']."</td>

<td>".$row['ukuran']."</td>

<td>Rp ".number_format($row['harga'],0,',','.')."</td>

<td>".$row['stok']."</td>

<td>

<a class='edit'
href='?edit=".$row['id']."'>
Edit
</a>

<a class='hapus'
href='?hapus=".$row['id']."'
onclick=\"return confirm('Yakin hapus data?')\">
Hapus
</a>

</td>

</tr>";
}
?>

</table>

</div>

</div>

</body>
</html>
