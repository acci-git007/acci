<?php
$conn = new mysqli(
    "penjualan-db.c83ya4kmsi7u.us-east-1.rds.amazonaws.com",
    "admin",
    "admin2026",
    "db_motor"
);

if(isset($_POST['simpan'])){
    $nama = $_POST['nama_motor'];
    $merk = $_POST['merk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $conn->query("INSERT INTO motor(nama_motor,merk,harga,stok)
                  VALUES('$nama','$merk','$harga','$stok')");
}

if(isset($_GET['hapus'])){
    $id=$_GET['hapus'];
    $conn->query("DELETE FROM motor WHERE id=$id");
}
?>

<form method="POST">
Nama Motor <input type="text" name="nama_motor"><br>
Merk <input type="text" name="merk"><br>
Harga <input type="number" name="harga"><br>
Stok <input type="number" name="stok"><br>
<button name="simpan">Simpan</button>
</form>

<hr>

<table border="1">
<tr>
<th>ID</th>
<th>Motor</th>
<th>Merk</th>
<th>Harga</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php
$data=$conn->query("SELECT * FROM motor");
while($row=$data->fetch_assoc()){
echo "<tr>
<td>{$row['id']}</td>
<td>{$row['nama_motor']}</td>
<td>{$row['merk']}</td>
<td>{$row['harga']}</td>
<td>{$row['stok']}</td>
<td><a href='?hapus={$row['id']}'>Hapus</a></td>
</tr>";
}
?>
</table>
