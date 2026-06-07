<?php

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

/*
|--------------------------------------------------------------------------
| DATABASE RDS
|--------------------------------------------------------------------------
*/

$host = "db-siswa.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$dbname = "db_siswa";
$username = "admin";
$password = "admin2026";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Database Error : " . $e->getMessage());

}

/*
|--------------------------------------------------------------------------
| AWS S3
|--------------------------------------------------------------------------
*/

$bucket = "crud-siswa-photo";

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'East us-east-1'
]);

/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

if(isset($_POST['create'])){

    $fotoUrl = null;

    if(
        isset($_FILES['foto']) &&
        $_FILES['foto']['size'] > 0
    ){

        $filename =
            time() . "_" .
            basename($_FILES['foto']['name']);

        $upload = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => 'siswa/' . $filename,
            'SourceFile' =>
                $_FILES['foto']['tmp_name']
        ]);

        $fotoUrl = $upload['ObjectURL'];
    }

    $stmt = $pdo->prepare("
        INSERT INTO siswa
        (
            nis,
            nama,
            kelas,
            alamat,
            foto_url
        )
        VALUES
        (
            ?,?,?,?,?
        )
    ");

    $stmt->execute([
        $_POST['nis'],
        $_POST['nama'],
        $_POST['kelas'],
        $_POST['alamat'],
        $fotoUrl
    ]);

    header("Location:index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $stmt = $pdo->prepare(
        "SELECT * FROM siswa WHERE id=?"
    );

    $stmt->execute([$id]);

    $data = $stmt->fetch();

    if($data){

        if(!empty($data['foto_url'])){

            $key = parse_url(
                $data['foto_url'],
                PHP_URL_PATH
            );

            $key = ltrim($key,'/');

            try {

                $s3->deleteObject([
                    'Bucket' => $bucket,
                    'Key' => $key
                ]);

            } catch(Exception $e){}
        }

        $delete = $pdo->prepare(
            "DELETE FROM siswa WHERE id=?"
        );

        $delete->execute([$id]);
    }

    header("Location:index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT DATA
|--------------------------------------------------------------------------
*/

$edit = null;

if(isset($_GET['edit'])){

    $stmt = $pdo->prepare(
        "SELECT * FROM siswa WHERE id=?"
    );

    $stmt->execute([
        $_GET['edit']
    ]);

    $edit = $stmt->fetch();
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $id = $_POST['id'];

    $stmt = $pdo->prepare(
        "SELECT * FROM siswa WHERE id=?"
    );

    $stmt->execute([$id]);

    $old = $stmt->fetch();

    $fotoUrl = $old['foto_url'];

    if(
        isset($_FILES['foto']) &&
        $_FILES['foto']['size'] > 0
    ){

        if(!empty($old['foto_url'])){

            $oldKey = parse_url(
                $old['foto_url'],
                PHP_URL_PATH
            );

            $oldKey = ltrim($oldKey,'/');

            try {

                $s3->deleteObject([
                    'Bucket' => $bucket,
                    'Key' => $oldKey
                ]);

            } catch(Exception $e){}
        }

        $filename =
            time() . "_" .
            basename($_FILES['foto']['name']);

        $upload = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => 'siswa/' . $filename,
            'SourceFile' =>
                $_FILES['foto']['tmp_name']
        ]);

        $fotoUrl = $upload['ObjectURL'];
    }

    $update = $pdo->prepare("
        UPDATE siswa
        SET
        nis=?,
        nama=?,
        kelas=?,
        alamat=?,
        foto_url=?
        WHERE id=?
    ");

    $update->execute([
        $_POST['nis'],
        $_POST['nama'],
        $_POST['kelas'],
        $_POST['alamat'],
        $fotoUrl,
        $id
    ]);

    header("Location:index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| READ
|--------------------------------------------------------------------------
*/

$data = $pdo->query("
SELECT *
FROM siswa
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<title>CRUD SISWA AWS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2 class="mb-4">
CRUD SISWA AWS
</h2>

<div class="card mb-4">

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<input type="hidden"
name="id"
value="<?= $edit['id'] ?? '' ?>">

<div class="mb-3">
<label>NIS</label>
<input type="text"
name="nis"
required
class="form-control"
value="<?= $edit['nis'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Nama</label>
<input type="text"
name="nama"
required
class="form-control"
value="<?= $edit['nama'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Kelas</label>
<input type="text"
name="kelas"
required
class="form-control"
value="<?= $edit['kelas'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Alamat</label>
<textarea
name="alamat"
class="form-control"><?= $edit['alamat'] ?? '' ?></textarea>
</div>

<div class="mb-3">
<label>Foto</label>
<input type="file"
name="foto"
class="form-control">
</div>

<?php if($edit): ?>

<button
type="submit"
name="update"
class="btn btn-warning">
Update
</button>

<a href="index.php"
class="btn btn-secondary">
Batal
</a>

<?php else: ?>

<button
type="submit"
name="create"
class="btn btn-primary">
Simpan
</button>

<?php endif; ?>

</form>

</div>

</div>

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>NIS</th>
<th>Nama</th>
<th>Kelas</th>
<th>Foto</th>
<th>Aksi</th>
</tr>

</thead>

<tbody>

<?php while($row = $data->fetch(PDO::FETCH_ASSOC)): ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['nis'] ?></td>
<td><?= $row['nama'] ?></td>
<td><?= $row['kelas'] ?></td>

<td>

<?php if($row['foto_url']): ?>

<img
src="<?= $row['foto_url'] ?>"
width="80">

<?php endif; ?>

</td>

<td>

<a
href="?edit=<?= $row['id'] ?>"
class="btn btn-warning btn-sm">
Edit
</a>

<a
href="?delete=<?= $row['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus data?')">
Delete
</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</body>
</html>
