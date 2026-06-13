<?php
$host = "endpoint-rds.amazonaws.com";
$user = "admin";
$pass = "password";
$db   = "db_pulsa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal : " . $conn->connect_error);
}

// Tambah Data
if(isset($_POST['tambah'])){
    $operator = $_POST['operator'];
    $nominal  = $_POST['nominal'];
    $harga    = $_POST['harga'];
    $stok     = $_POST['stok'];

    $sql = "INSERT INTO pulsa
            (operator_seluler, nominal, harga, stok)
            VALUES
            ('$operator','$nominal','$harga','$stok')";

    $conn->query($sql);

    header("Location: pulsa.php");
    exit;
}

// Hapus Data
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    $conn->query("DELETE FROM pulsa WHERE id='$id'");

    header("Location: pulsa.php");
    exit;
}

// Ambil Data Edit
$edit = false;
$id = "";
$operator = "";
$nominal = "";
$harga = "";
$stok = "";

if(isset($_GET['edit'])){
    $edit = true;

    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM pulsa WHERE id='$id'"
    );

    $row = $result->fetch_assoc();

    $operator = $row['operator_seluler'];
    $nominal  = $row['nominal'];
    $harga    = $row['harga'];
    $stok     = $row['stok'];
}

// Update Data
if(isset($_POST['update'])){

    $id       = $_POST['id'];
    $operator = $_POST['operator'];
    $nominal  = $_POST['nominal'];
    $harga    = $_POST['harga'];
    $stok     = $_POST['stok'];

    $sql = "UPDATE pulsa SET
            operator_seluler='$operator',
            nominal='$nominal',
            harga='$harga',
            stok='$stok'
            WHERE id='$id'";

    $conn->query($sql);

    header("Location: pulsa.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Aplikasi Penjualan Pulsa</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
    margin:0;
}

.header{
    background:#0f766e;
    color:white;
    text-align:center;
    padding:20px;
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
    box-shadow:0 0 10px rgba(0,0,0,0.1);
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
    background:#0f766e;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:5px;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#115e59;
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
    text-decoration:none;
    padding:5px 10px;
    border-radius:4px;
}

.hapus{
    background:red;
    color:white;
    text-decoration:none;
    padding:5px 10px;
    border-radius:4px;
}

</style>

</head>
<body>

<div class="header">
<h1>APLIKASI PENJUALAN PULSA</h1>
</div>

<div class="container">

<div class="card">

<h2>
<?php echo $edit ? "Edit Data Pulsa" : "Input Data Pulsa"; ?>
</h2>

<form method="POST">

<input type="hidden" name="id"
value="<?php echo $id; ?>">

<label>Operator</label>

<select name="operator" required>
<option value="Telkomsel" <?php if($operator=="Telkomsel") echo "selected"; ?>>Telkomsel</option>
<option value="Indosat" <?php if($operator=="Indosat") echo "selected"; ?>>Indosat</option>
<option value="XL" <?php if($operator=="XL") echo "selected"; ?>>XL</option>
<option value="Tri" <?php if($operator=="Tri") echo "selected"; ?>>Tri</option>
<option value="Smartfren" <?php if($operator=="Smartfren") echo "selected"; ?>>Smartfren</option>
</select>

<label>Nominal</label>
<input type="number"
name="nominal"
value="<?php echo $nominal; ?>"
required>

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

<h2>Data Penjualan Pulsa</h2>

<table>

<tr>
<th>ID</th>
<th>Operator</th>
<th>Nominal</th>
<th>Harga</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php

$data = $conn->query(
"SELECT * FROM pulsa ORDER BY id DESC"
);

while($row = $data->fetch_assoc()){

echo "<tr>

<td>".$row['id']."</td>

<td>".$row['operator_seluler']."</td>

<td>".$row['nominal']." K</td>

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
