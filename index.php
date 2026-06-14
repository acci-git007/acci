<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

$host = "dbsiswa.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "dbsiswa";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi Database Gagal");
}

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

if(isset($_POST['simpan']))
{
    $nis    = $_POST['nis'];
    $nama   = $_POST['nama'];
    $kelas  = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    $urlFoto = '';

    if($_FILES['foto']['name'] != '')
    {
        $namaFile = time().'_'.$_FILES['foto']['name'];

        $result = $s3->putObject([
            'Bucket'     => 'foto-siswa-bucket',
            'Key'        => 'siswa/'.$namaFile,
            'SourceFile' => $_FILES['foto']['tmp_name']
        ]);

        $urlFoto = $result['ObjectURL'];
    }

    mysqli_query($conn,"
    INSERT INTO siswa(nis,nama,kelas,alamat,foto)
    VALUES('$nis','$nama','$kelas','$alamat','$urlFoto')
    ");

    header("Location:index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Aplikasi Data Siswa AWS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">
<div class="card-header bg-primary text-white">
<h3>Aplikasi Data Siswa</h3>
<p class="mb-0">RDS MySQL + S3 Storage</p>
</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
<label>NIS</label>
<input type="text" name="nis" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Nama</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Kelas</label>
<input type="text" name="kelas" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Foto</label>
<input type="file" name="foto" class="form-control" required>
</div>

<div class="col-md-12 mb-3">
<label>Alamat</label>
<textarea name="alamat" class="form-control"></textarea>
</div>

</div>

<button type="submit" name="simpan" class="btn btn-success">
Simpan Data
</button>

</form>

</div>
</div>

<div class="card shadow mt-4">

<div class="card-header">
<h4>Data Siswa</h4>
</div>

<div class="card-body">

<table class="table table-bordered table-striped">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Foto</th>
<th>NIS</th>
<th>Nama</th>
<th>Kelas</th>
<th>Alamat</th>
</tr>
</thead>

<tbody>

<?php

$data = mysqli_query($conn,"SELECT * FROM siswa ORDER BY id DESC");

while($d = mysqli_fetch_assoc($data))
{
?>

<tr>

<td><?= $d['id']; ?></td>

<td>
<?php if($d['foto']) { ?>
<img src="<?= $d['foto']; ?>"
width="80"
height="80"
style="object-fit:cover;border-radius:10px;">
<?php } ?>
</td>

<td><?= $d['nis']; ?></td>
<td><?= $d['nama']; ?></td>
<td><?= $d['kelas']; ?></td>
<td><?= $d['alamat']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>
</div>

</div>

</body>
</html>
