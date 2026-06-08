<?php
// ========================
// KONFIGURASI RDS
// ========================
$host = "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$dbname = "penjualan_db";
$user = "admin";
$pass = "admin2026";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// ========================
// CREATE & UPDATE
// ========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_produk = $_POST['nama_produk'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $total = $jumlah * $harga;

    if (!empty($_POST['id'])) {

        $stmt = $pdo->prepare("
            UPDATE penjualan
            SET nama_produk=?, jumlah=?, harga=?, total=?
            WHERE id=?
        ");

        $stmt->execute([
            $nama_produk,
            $jumlah,
            $harga,
            $total,
            $_POST['id']
        ]);

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO penjualan
            (nama_produk, jumlah, harga, total)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $nama_produk,
            $jumlah,
            $harga,
            $total
        ]);
    }

    header("Location: index.php");
    exit;
}

// ========================
// DELETE
// ========================
if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("
        DELETE FROM penjualan WHERE id=?
    ");

    $stmt->execute([$_GET['delete']]);

    header("Location: index.php");
    exit;
}

// ========================
// EDIT DATA
// ========================
$edit = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("
        SELECT * FROM penjualan WHERE id=?
    ");

    $stmt->execute([$_GET['edit']]);

    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========================
// READ DATA
// ========================
$data = $pdo->query("
    SELECT * FROM penjualan
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Penjualan</title>
    <style>
        body{
            font-family: Arial;
            margin:40px;
        }

        table{
            border-collapse: collapse;
            width:100%;
        }

        th, td{
            border:1px solid #ddd;
            padding:10px;
        }

        th{
            background:#f2f2f2;
        }

        input{
            padding:8px;
            margin:5px;
        }

        button{
            padding:10px;
        }
    </style>
</head>
<body>

<h2>CRUD Penjualan</h2>

<form method="POST">

    <input type="hidden"
           name="id"
           value="<?= $edit['id'] ?? '' ?>">

    <input type="text"
           name="nama_produk"
           placeholder="Nama Produk"
           required
           value="<?= $edit['nama_produk'] ?? '' ?>">

    <input type="number"
           name="jumlah"
           placeholder="Jumlah"
           required
           value="<?= $edit['jumlah'] ?? '' ?>">

    <input type="number"
           step="0.01"
           name="harga"
           placeholder="Harga"
           required
           value="<?= $edit['harga'] ?? '' ?>">

    <button type="submit">
        <?= $edit ? 'Update' : 'Simpan' ?>
    </button>

</form>

<hr>

<table>

    <tr>
        <th>ID</th>
        <th>Produk</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Total</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($data as $row): ?>

    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
        <td><?= $row['jumlah'] ?></td>
        <td><?= number_format($row['harga'],2) ?></td>
        <td><?= number_format($row['total'],2) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
            <a href="?edit=<?= $row['id'] ?>">
                Edit
            </a>

            |

            <a href="?delete=<?= $row['id'] ?>"
               onclick="return confirm('Hapus data?')">
               Hapus
            </a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>

</body>
</html>
