<?php

/* ==========================
   KONFIGURASI AMAZON RDS
========================== */

$host = "dbtraining01.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$dbname = "sekolah";
$user = "admin";
$pass = "admin2026";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e){
    die("Koneksi gagal : " . $e->getMessage());
}

/* ==========================
   TAMBAH DATA
========================== */

if(isset($_POST['tambah'])){

    $stmt = $pdo->prepare("
        INSERT INTO siswa(nis,nama,alamat)
        VALUES(?,?,?)
    ");

    $stmt->execute([
        $_POST['nis'],
        $_POST['nama'],
        $_POST['alamat']
    ]);

    header("Location: index.php");
    exit;
}

/* ==========================
   UPDATE DATA
========================== */

if(isset($_POST['update'])){

    $stmt = $pdo->prepare("
        UPDATE siswa
        SET nis=?,
            nama=?,
            alamat=?
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['nis'],
        $_POST['nama'],
        $_POST['alamat'],
        $_POST['id']
    ]);

    header("Location: index.php");
    exit;
}

/* ==========================
   HAPUS DATA
========================== */

if(isset($_GET['hapus'])){

    $stmt = $pdo->prepare(
        "DELETE FROM siswa WHERE id=?"
    );

    $stmt->execute([
        $_GET['hapus']
    ]);

    header("Location: index.php");
    exit;
}

/* ==========================
   DATA EDIT
========================== */

$edit = null;

if(isset($_GET['edit'])){

    $stmt = $pdo->prepare(
        "SELECT * FROM siswa WHERE id=?"
    );

    $stmt->execute([
        $_GET['edit']
    ]);

    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* ==========================
   LIST DATA
========================== */

$data = $pdo->query(
    "SELECT * FROM siswa ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CRUD Data Siswa</title>

<style>

body{
    font-family: Arial;
    margin:40px;
}

table{
    border-collapse: collapse;
    width:100%;
}

table,th,td{
    border:1px solid #ccc;
}

th,td{
    padding:10px;
}

input,textarea{
    width:100%;
    padding:8px;
}

button{
    padding:10px 20px;
    background:#007bff;
    color:white;
    border:none;
    cursor:pointer;
}

.btn-hapus{
    color:red;
}

.btn-edit{
    color:green;
}

</style>

</head>
<body>

<h2>CRUD Data Siswa</h2>

<h3>
<?= $edit ? 'Edit Data Siswa' : 'Tambah Data Siswa'; ?>
</h3>

<form method="POST">

    <?php if($edit): ?>
        <input type="hidden" name="id"
               value="<?= $edit['id']; ?>">
    <?php endif; ?>

    <p>
        <label>NIS</label><br>
        <input
            type="text"
            name="nis"
            required
            value="<?= $edit['nis'] ?? ''; ?>"
        >
    </p>

    <p>
        <label>Nama</label><br>
        <input
            type="text"
            name="nama"
            required
            value="<?= $edit['nama'] ?? ''; ?>"
        >
    </p>

    <p>
        <label>Alamat</label><br>
        <textarea name="alamat"><?= $edit['alamat'] ?? ''; ?></textarea>
    </p>

    <?php if($edit): ?>

        <button type="submit" name="update">
            Update Data
        </button>

        <a href="index.php">
            Batal
        </a>

    <?php else: ?>

        <button type="submit" name="tambah">
            Simpan Data
        </button>

    <?php endif; ?>

</form>

<hr>

<h3>Daftar Siswa</h3>

<table>

<tr>
    <th>ID</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>

<?php foreach($data as $row): ?>

<tr>

    <td><?= $row['id']; ?></td>

    <td><?= htmlspecialchars($row['nis']); ?></td>

    <td><?= htmlspecialchars($row['nama']); ?></td>

    <td><?= htmlspecialchars($row['alamat']); ?></td>

    <td>

        <a
            class="btn-edit"
            href="?edit=<?= $row['id']; ?>">
            Edit
        </a>

        |

        <a
            class="btn-hapus"
            href="?hapus=<?= $row['id']; ?>"
            onclick="return confirm('Yakin hapus data?')">
            Hapus
        </a>

    </td>

</tr>

<?php endforeach; ?>

</table>

</body>
</html>
