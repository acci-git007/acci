<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

/* =====================
   KONEKSI RDS
===================== */

$host = "dbsiswa.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "dbsiswa";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi gagal : ".mysqli_connect_error());
}

/* =====================
   KONEKSI S3
===================== */

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

/* =====================
   SIMPAN DATA
===================== */

if(isset($_POST['simpan']))
{
    $nis    = $_POST['nis'];
    $nama   = $_POST['nama'];
    $kelas  = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    $urlFoto = '';

    if(!empty($_FILES['foto']['name']))
    {
        $namaFile = time().'_'.basename($_FILES['foto']['name']);

        try{

            $result = $s3->putObject([
                'Bucket'     => 'foto-siswa-bucket',
                'Key'        => 'siswa/'.$namaFile,
                'SourceFile' => $_FILES['foto']['tmp_name']
            ]);

            $urlFoto = $result['ObjectURL'];

        }catch(Exception $e){

            die("Upload S3 gagal : ".$e->getMessage());
        }
    }

    mysqli_query($conn,"
        INSERT INTO siswa
        (nis,nama,kelas,alamat,foto)
        VALUES
        (
            '$nis',
            '$nama',
            '$kelas',
            '$alamat',
            '$urlFoto'
        )
    ");

    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Data Siswa</title>

    <style>
        body{
            font-family:Arial;
            margin:30px;
        }

        table{
            border-collapse:collapse;
            width:100%;
        }

        table,th,td{
            border:1px solid #ccc;
        }

        th,td{
            padding:10px;
            text-align:left;
        }

        input,textarea{
            width:100%;
            padding:8px;
        }

        button{
            padding:10px 20px;
        }
    </style>

</head>
<body>

<h2>Aplikasi Data Siswa</h2>

<form method="POST" enctype="multipart/form-data">

    <label>NIS</label><br>
    <input type="text" name="nis" required>
    <br><br>

    <label>Nama</label><br>
    <input type="text" name="nama" required>
    <br><br>

    <label>Kelas</label><br>
    <input type="text" name="kelas" required>
    <br><br>

    <label>Alamat</label><br>
    <textarea name="alamat"></textarea>
    <br><br>

    <label>Foto</label><br>
    <input type="file" name="foto" required>
    <br><br>

    <button type="submit" name="simpan">
        Simpan Data
    </button>

</form>

<hr>

<h3>Data Siswa</h3>

<table>

<tr>
    <th>ID</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Alamat</th>
    <th>Foto</th>
</tr>

<?php

$data = mysqli_query($conn,"SELECT * FROM siswa ORDER BY id DESC");

while($d = mysqli_fetch_assoc($data))
{
?>

<tr>

<td><?= $d['id']; ?></td>
<td><?= $d['nis']; ?></td>
<td><?= $d['nama']; ?></td>
<td><?= $d['kelas']; ?></td>
<td><?= $d['alamat']; ?></td>

<td>
<?php if($d['foto']) { ?>
<img src="<?= $d['foto']; ?>" width="100">
<?php } ?>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>
