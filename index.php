<?php

// =====================
// KONEKSI AMAZON RDS
// =====================
$host = "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "sekolah";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// =====================
// CREATE
// =====================
if(isset($_POST['simpan'])){
    $nama = $_POST['nama'];
    $tgl  = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['no_hp'];

    $stmt = $conn->prepare("
        INSERT INTO pendaftaran
        (nama, tanggal_lahir, alamat, no_hp)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("ssss", $nama, $tgl, $alamat, $hp);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// =====================
// DELETE
// =====================
if(isset($_GET['hapus'])){
    $id = (int)$_GET['hapus'];

    $stmt = $conn->prepare("
        DELETE FROM pendaftaran
        WHERE id=?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// =====================
// UPDATE
// =====================
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $nama = $_POST['nama'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['no_hp'];

    $stmt = $conn->prepare("
        UPDATE pendaftaran
        SET nama=?,
            tanggal_lahir=?,
            alamat=?,
            no_hp=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssssi",
        $nama,
        $tgl,
        $alamat,
        $hp,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

// =====================
// AMBIL DATA EDIT
// =====================
$editData = null;

if(isset($_GET['edit'])){
    $id = (int)$_GET['edit'];

    $stmt = $conn->prepare("
        SELECT *
        FROM pendaftaran
        WHERE id=?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Pendaftaran Murid Baru</title>

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
        }

        table{
            width:100%;
            border-collapse: collapse;
            margin-top:20px;
        }

        th, td{
            border:1px solid #ddd;
            padding:8px;
        }

        th{
            background:#f0f0f0;
        }

        a{
            text-decoration:none;
        }
    </style>
</head>
<body>

<h2>Pendaftaran Murid Baru</h2>

<form method="post">

    <?php if($editData){ ?>

        <input type="hidden"
               name="id"
               value="<?= $editData['id'] ?>">

        <input type="text"
               name="nama"
               value="<?= htmlspecialchars($editData['nama']) ?>"
               placeholder="Nama Murid"
               required>

        <input type="date"
               name="tanggal_lahir"
               value="<?= $editData['tanggal_lahir'] ?>"
               required>

        <textarea name="alamat"
                  required><?= htmlspecialchars($editData['alamat']) ?></textarea>

        <input type="text"
               name="no_hp"
               value="<?= htmlspecialchars($editData['no_hp']) ?>"
               placeholder="Nomor HP"
               required>

        <button type="submit" name="update">
            Update Data
        </button>

    <?php } else { ?>

        <input type="text"
               name="nama"
               placeholder="Nama Murid"
               required>

        <input type="date"
               name="tanggal_lahir"
               required>

        <textarea name="alamat"
                  placeholder="Alamat"
                  required></textarea>

        <input type="text"
               name="no_hp"
               placeholder="Nomor HP"
               required>

        <button type="submit" name="simpan">
            Simpan
        </button>

    <?php } ?>

</form>

<hr>

<h3>Data Pendaftaran</h3>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Aksi</th>
    </tr>

    <?php

    $data = $conn->query("
        SELECT *
        FROM pendaftaran
        ORDER BY id DESC
    ");

    while($row = $data->fetch_assoc()){

    ?>

    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= $row['tanggal_lahir'] ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['no_hp']) ?></td>

        <td>
            <a href="?edit=<?= $row['id'] ?>">
                Edit
            </a>

            |

            <a href="?hapus=<?= $row['id'] ?>"
               onclick="return confirm('Yakin hapus data?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>
