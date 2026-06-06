<?php
// ======================
// KONFIGURASI AWS RDS
// ======================
$host = "dblatihan.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$dbname = "sekolah";
$username = "admin";
$password = "admin2026";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// ======================
// CREATE
// ======================
if(isset($_POST['tambah'])) {
    $stmt = $pdo->prepare("
        INSERT INTO siswa(nama, kelas, alamat)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([
        $_POST['nama'],
        $_POST['kelas'],
        $_POST['alamat']
    ]);

    header("Location: index.php");
    exit;
}

// ======================
// UPDATE
// ======================
if(isset($_POST['update'])) {
    $stmt = $pdo->prepare("
        UPDATE siswa
        SET nama=?, kelas=?, alamat=?
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['nama'],
        $_POST['kelas'],
        $_POST['alamat'],
        $_POST['id']
    ]);

    header("Location: index.php");
    exit;
}

// ======================
// DELETE
// ======================
if(isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM siswa WHERE id=?");
    $stmt->execute([$_GET['hapus']]);

    header("Location: index.php");
    exit;
}

// ======================
// EDIT DATA
// ======================
$edit = null;

if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("
        SELECT * FROM siswa WHERE id=?
    ");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ======================
// READ DATA
// ======================
$data = $pdo->query("
    SELECT * FROM siswa
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Data Siswa AWS RDS</title>

    <style>
        body{
            font-family: Arial;
            margin:40px;
        }

        input, textarea{
            width:100%;
            padding:8px;
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
            border:1px solid #ddd;
        }

        th, td{
            padding:10px;
        }

        a{
            text-decoration:none;
        }
    </style>
</head>
<body>

<h2>CRUD Data Siswa (AWS RDS MySQL)</h2>

<form method="post">

    <input
        type="hidden"
        name="id"
        value="<?= $edit['id'] ?? '' ?>"
    >

    <label>Nama</label>
    <input
        type="text"
        name="nama"
        required
        value="<?= $edit['nama'] ?? '' ?>"
    >

    <label>Kelas</label>
    <input
        type="text"
        name="kelas"
        required
        value="<?= $edit['kelas'] ?? '' ?>"
    >

    <label>Alamat</label>
    <textarea
        name="alamat"
    ><?= $edit['alamat'] ?? '' ?></textarea>

    <?php if($edit): ?>
        <button type="submit" name="update">
            Update Data
        </button>
        <a href="index.php">Batal</a>
    <?php else: ?>
        <button type="submit" name="tambah">
            Simpan Data
        </button>
    <?php endif; ?>

</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($data as $row): ?>

        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['kelas']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= $row['created_at'] ?></td>

            <td>
                <a href="?edit=<?= $row['id'] ?>">
                    Edit
                </a>

                |

                <a
                    href="?hapus=<?= $row['id'] ?>"
                    onclick="return confirm('Hapus data?')"
                >
                    Hapus
                </a>
            </td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>

</body>
</html>
