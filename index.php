<?php
require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

/* =========================
   DATABASE RDS CONFIG
========================= */

$host = "db-siswa.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$db   = "db_siswa";
$user = "admin";
$pass = "admin2026";

$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

/* =========================
   S3 CONFIG
========================= */

$bucket = "crud-siswa-foto";

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

/* =========================
   CREATE
========================= */

if(isset($_POST['create'])){

    $fotoUrl = null;
    $fotoKey = null;

    if($_FILES['foto']['size'] > 0){

        $filename = time().'_'.$_FILES['foto']['name'];

        $upload = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => 'siswa/'.$filename,
            'SourceFile' => $_FILES['foto']['tmp_name'],
            'ACL' => 'public-read'
        ]);

        $fotoUrl = $upload['ObjectURL'];
        $fotoKey = 'siswa/'.$filename;
    }

    $stmt = $pdo->prepare("
        INSERT INTO siswa (nis,nama,kelas,alamat,foto_url,foto_key)
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['nis'],
        $_POST['nama'],
        $_POST['kelas'],
        $_POST['alamat'],
        $fotoUrl,
        $fotoKey
    ]);

    header("Location:index.php");
    exit;
}

/* =========================
   DELETE
========================= */

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if($row){

        if($row['foto_key']){

            $s3->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $row['foto_key']
            ]);
        }

        $pdo->prepare("DELETE FROM siswa WHERE id=?")
            ->execute([$id]);
    }

    header("Location:index.php");
    exit;
}

/* =========================
   EDIT DATA
========================= */

$edit = null;

if(isset($_GET['edit'])){

    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* =========================
   UPDATE
========================= */

if(isset($_POST['update'])){

    $id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id=?");
    $stmt->execute([$id]);
    $old = $stmt->fetch();

    $fotoUrl = $old['foto_url'];
    $fotoKey = $old['foto_key'];

    if($_FILES['foto']['size'] > 0){

        if($fotoKey){
            $s3->deleteObject([
                'Bucket' => $bucket,
                'Key' => $fotoKey
            ]);
        }

        $filename = time().'_'.$_FILES['foto']['name'];

        $upload = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => 'siswa/'.$filename,
            'SourceFile' => $_FILES['foto']['tmp_name'],
            'ACL' => 'public-read'
        ]);

        $fotoUrl = $upload['ObjectURL'];
        $fotoKey = 'siswa/'.$filename;
    }

    $pdo->prepare("
        UPDATE siswa
        SET nis=?, nama=?, kelas=?, alamat=?, foto_url=?, foto_key=?
        WHERE id=?
    ")->execute([
        $_POST['nis'],
        $_POST['nama'],
        $_POST['kelas'],
        $_POST['alamat'],
        $fotoUrl,
        $fotoKey,
        $id
    ]);

    header("Location:index.php");
    exit;
}

/* =========================
   READ DATA
========================= */

$data = $pdo->query("SELECT * FROM siswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD SISWA AWS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>CRUD SISWA AWS (EC2 + RDS + S3)</h3>

<!-- FORM -->
<form method="POST" enctype="multipart/form-data" class="card p-3 mb-4">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<input name="nis" class="form-control mb-2" placeholder="NIS" value="<?= $edit['nis'] ?? '' ?>">

<input name="nama" class="form-control mb-2" placeholder="Nama" value="<?= $edit['nama'] ?? '' ?>">

<input name="kelas" class="form-control mb-2" placeholder="Kelas" value="<?= $edit['kelas'] ?? '' ?>">

<textarea name="alamat" class="form-control mb-2" placeholder="Alamat"><?= $edit['alamat'] ?? '' ?></textarea>

<input type="file" name="foto" class="form-control mb-2">

<?php if($edit): ?>
<button name="update" class="btn btn-warning">Update</button>
<a href="index.php" class="btn btn-secondary">Batal</a>
<?php else: ?>
<button name="create" class="btn btn-primary">Simpan</button>
<?php endif; ?>

</form>

<!-- TABLE -->
<table class="table table-bordered">

<tr>
<th>NIS</th>
<th>Nama</th>
<th>Kelas</th>
<th>Foto</th>
<th>Aksi</th>
</tr>

<?php while($r = $data->fetch()): ?>
<tr>
<td><?= $r['nis'] ?></td>
<td><?= $r['nama'] ?></td>
<td><?= $r['kelas'] ?></td>
<td>
<?php if($r['foto_url']) : ?>
<img src="<?= $r['foto_url'] ?>" width="80">
<?php endif; ?>
</td>
<td>
<a href="?edit=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
