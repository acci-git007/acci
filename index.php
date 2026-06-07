<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;

/*
|--------------------------------------------------------------------------
| CONFIG DATABASE RDS
|--------------------------------------------------------------------------
*/

$host = "db-siswa.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "studentdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

/*
|--------------------------------------------------------------------------
| CONFIG S3
|--------------------------------------------------------------------------
*/

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$bucket = "crud-siswa-foto";

/*
|--------------------------------------------------------------------------
| CREATE TABLE (optional manual already OK)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| INSERT DATA
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan'])) {

    $nis    = $_POST['nis'];
    $nama   = $_POST['nama'];
    $kelas  = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    $foto_url = "";

    if (!empty($_FILES['foto']['name'])) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . "." . $ext;

        // UPLOAD KE S3
        $s3->putObject([
            'Bucket'     => $bucket,
            'Key'        => $fileName,
            'SourceFile' => $_FILES['foto']['tmp_name'],
            'ContentType'=> $_FILES['foto']['type']
        ]);

        // URL PUBLIC S3
        $foto_url = "https://{$bucket}.s3.amazonaws.com/{$fileName}";
    }

    $stmt = $conn->prepare("
        INSERT INTO students (nis,nama,kelas,alamat,foto)
        VALUES (?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sssss",
        $nis,
        $nama,
        $kelas,
        $alamat,
        $foto_url
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE DATA
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| GET DATA EDIT
|--------------------------------------------------------------------------
*/

$edit = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $edit = $stmt->get_result()->fetch_assoc();
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $id     = $_POST['id'];
    $nis    = $_POST['nis'];
    $nama   = $_POST['nama'];
    $kelas  = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    $foto_url = $_POST['old_foto'];

    if (!empty($_FILES['foto']['name'])) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . "." . $ext;

        // upload baru ke S3
        $s3->putObject([
            'Bucket'     => $bucket,
            'Key'        => $fileName,
            'SourceFile' => $_FILES['foto']['tmp_name'],
            'ContentType'=> $_FILES['foto']['type']
        ]);

        $foto_url = "https://{$bucket}.s3.amazonaws.com/{$fileName}";
    }

    $stmt = $conn->prepare("
        UPDATE students
        SET nis=?, nama=?, kelas=?, alamat=?, foto=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sssssi",
        $nis,
        $nama,
        $kelas,
        $alamat,
        $foto_url,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| GET ALL DATA
|--------------------------------------------------------------------------
*/

$data = $conn->query("SELECT * FROM students ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Siswa AWS + S3</title>

    <style>
        body { font-family: Arial; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px 20px; cursor: pointer; }
        img { width: 80px; border-radius: 5px; }
    </style>
</head>

<body>

<h2>CRUD Data Siswa + AWS S3</h2>

<form method="POST" enctype="multipart/form-data">

<?php if($edit): ?>
    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
    <input type="hidden" name="old_foto" value="<?= $edit['foto'] ?>">
<?php endif; ?>

NIS
<input type="text" name="nis" required value="<?= $edit['nis'] ?? '' ?>">

Nama
<input type="text" name="nama" required value="<?= $edit['nama'] ?? '' ?>">

Kelas
<input type="text" name="kelas" required value="<?= $edit['kelas'] ?? '' ?>">

Alamat
<textarea name="alamat"><?= $edit['alamat'] ?? '' ?></textarea>

Foto
<input type="file" name="foto">

<br><br>

<?php if($edit): ?>
    <button name="update">Update</button>
    <a href="index.php">Batal</a>
<?php else: ?>
    <button name="simpan">Simpan</button>
<?php endif; ?>

</form>

<hr>

<table>
<tr>
    <th>ID</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Foto</th>
    <th>Aksi</th>
</tr>

<?php while($row = $data->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nis'] ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['kelas'] ?></td>

    <td>
        <?php if($row['foto']): ?>
            <img src="<?= $row['foto'] ?>">
        <?php endif; ?>
    </td>

    <td>
        <a href="?edit=<?= $row['id'] ?>">Edit</a> |
        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
