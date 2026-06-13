<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* KONEKSI RDS */
$host = "YOUR-RDS-ENDPOINT.rds.amazonaws.com";
$user = "admin";
$pass = "password";
$db   = "dbpenjualan";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

/* SIMPAN DATA */
if (isset($_POST['simpan'])) {

    $kode      = trim($_POST['kode']);
    $pelanggan = trim($_POST['pelanggan']);
    $total     = trim($_POST['total']);

    $stmt = $conn->prepare(
        "INSERT INTO transaksi
        (kode_transaksi,pelanggan,total_bayar)
        VALUES (?,?,?)"
    );

    $stmt->bind_param(
        "ssd",
        $kode,
        $pelanggan,
        $total
    );

    if($stmt->execute()){
        header("Location: transaksi.php");
        exit;
    } else {
        echo "Gagal menyimpan data";
    }
}

/* HAPUS DATA */
if(isset($_GET['hapus'])){

    $id = intval($_GET['hapus']);

    $stmt = $conn->prepare(
        "DELETE FROM transaksi WHERE id=?"
    );

    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location: transaksi.php");
    exit;
}

/* TAMPILKAN DATA */
$data = $conn->query(
    "SELECT * FROM transaksi ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CRUD Transaksi AWS RDS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f7fa;
}
.container{
    margin-top:40px;
}
.card{
    border-radius:15px;
}
</style>

</head>
<body>

<div class="container">

    <div class="card shadow p-4 mb-4">

        <h2 class="text-primary">
            Data Transaksi
        </h2>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">
                    Kode Transaksi
                </label>

                <input
                    type="text"
                    name="kode"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Nama Pelanggan
                </label>

                <input
                    type="text"
                    name="pelanggan"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Total Bayar
                </label>

                <input
                    type="number"
                    name="total"
                    class="form-control"
                    required>
            </div>

            <button
                type="submit"
                name="simpan"
                class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>

    <div class="card shadow p-4">

        <h3>Daftar Transaksi</h3>

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Total Bayar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

            <?php
            if($data->num_rows > 0){

                while($row = $data->fetch_assoc()){
            ?>

                <tr>

                    <td><?= $row['id']; ?></td>

                    <td><?= htmlspecialchars($row['kode_transaksi']); ?></td>

                    <td><?= htmlspecialchars($row['pelanggan']); ?></td>

                    <td>
                        Rp <?= number_format($row['total_bayar'],0,',','.'); ?>
                    </td>

                    <td><?= $row['tanggal']; ?></td>

                    <td>

                        <a
                        href="?hapus=<?= $row['id']; ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Hapus data ini?')">

                        Hapus

                        </a>

                    </td>

                </tr>

            <?php
                }
            } else {
            ?>

                <tr>
                    <td colspan="6" class="text-center">
                        Belum ada data
                    </td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
