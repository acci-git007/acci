<?php

/* =========================
   KONFIGURASI AWS RDS
   ========================= */

$host = "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "db_sekolah";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

/* =========================
   CREATE
   ========================= */

if(isset($_POST['simpan'])){

    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO siswa(nis,nama,jenis_kelamin,alamat)
            VALUES('$nis','$nama','$jk','$alamat')";

    $conn->query($sql);

    header("Location:index.php");
    exit;
}

/* =========================
   DELETE
   ========================= */

if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];

    $conn->query("DELETE FROM siswa WHERE id=$id");

    header("Location:index.php");
    exit;
}

/* =========================
   UPDATE
   ========================= */

if(isset($_POST['update'])){

    $id = (int)$_POST['id'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $alamat = $_POST['alamat'];

    $sql = "UPDATE siswa
            SET
            nis='$nis',
            nama='$nama',
            jenis_kelamin='$jk',
            alamat='$alamat'
            WHERE id=$id";

    $conn->query($sql);

    header("Location:index.php");
    exit;
}

/* =========================
   AMBIL DATA EDIT
   ========================= */

$edit = false;
$dataEdit = null;

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $result = $conn->query("SELECT * FROM siswa WHERE id=$id");

    if($result->num_rows > 0){
        $dataEdit = $result->fetch_assoc();
        $edit = true;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Pendaftaran Siswa</title>

    <style>

        body{
            font-family:Arial;
            margin:30px;
            background:#f5f5f5;
        }

        .container{
            background:white;
            padding:20px;
            border-radius:10px;
        }

        input, textarea, select{
            width:100%;
            padding:10px;
            margin-bottom:10px;
        }

        button{
            padding:10px 20px;
            background:#007bff;
            color:white;
            border:none;
            cursor:pointer;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid #ccc;
        }

        th, td{
            padding:10px;
            text-align:left;
        }

        a{
            text-decoration:none;
            margin-right:10px;
        }

    </style>

</head>
<body>

<div class="container">

<h2>Pendaftaran Siswa</h2>

<form method="POST">

<?php if($edit){ ?>

<input type="hidden" name="id" value="<?= $dataEdit['id']; ?>">

<?php } ?>

<label>NIS</label>
<input
type="text"
name="nis"
required
value="<?= $edit ? $dataEdit['nis'] : ''; ?>"
>

<label>Nama</label>
<input
type="text"
name="nama"
required
value="<?= $edit ? $dataEdit['nama'] : ''; ?>"
>

<label>Jenis Kelamin</label>

<select name="jk" required>

<option value="">Pilih</option>

<option value="L"
<?= ($edit && $dataEdit['jenis_kelamin']=='L')?'selected':''; ?>
>
Laki-laki
</option>

<option value="P"
<?= ($edit && $dataEdit['jenis_kelamin']=='P')?'selected':''; ?>
>
Perempuan
</option>

</select>

<label>Alamat</label>

<textarea name="alamat"><?= $edit ? $dataEdit['alamat'] : ''; ?></textarea>

<?php if($edit){ ?>

<button type="submit" name="update">
Update Data
</button>

<a href="index.php">Batal</a>

<?php } else { ?>

<button type="submit" name="simpan">
Simpan Data
</button>

<?php } ?>

</form>

<h2>Data Siswa</h2>

<table>

<tr>
    <th>ID</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>JK</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>

<?php

$result = $conn->query(
"SELECT * FROM siswa ORDER BY id DESC"
);

while($row = $result->fetch_assoc()){

?>

<tr>

<td><?= $row['id']; ?></td>
<td><?= $row['nis']; ?></td>
<td><?= $row['nama']; ?></td>
<td><?= $row['jenis_kelamin']; ?></td>
<td><?= $row['alamat']; ?></td>

<td>

<a href="?edit=<?= $row['id']; ?>">
Edit
</a>

<a
href="?hapus=<?= $row['id']; ?>"
onclick="return confirm('Yakin hapus data?')"
>
Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
