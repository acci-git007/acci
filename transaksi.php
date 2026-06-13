<?php
$conn = new mysqli(
    "your-rds-endpoint.us-east-1.rds.amazonaws.com",
    "admin",
    "password",
    "dbpenjualan"
);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if(isset($_POST['simpan'])){
    $kode = $_POST['kode'];
    $pelanggan = $_POST['pelanggan'];
    $total = $_POST['total'];

    $conn->query("INSERT INTO transaksi
    (kode_transaksi,pelanggan,total_bayar)
    VALUES('$kode','$pelanggan','$total')");
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM transaksi WHERE id='$id'");
}

$data = $conn->query("SELECT * FROM transaksi ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
        }
        .card{
            border:none;
            border-radius:15px;
        }
        .header{
            background:#0d6efd;
            color:white;
            padding:20px;
            border-radius:15px;
            margin-bottom:20px;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="header">
        <h2>💰 Aplikasi Data Transaksi</h2>
        <p>CRUD Transaksi AWS RDS MySQL</p>
    </div>

    <div class="row">

        <div class="col-md-4">

            <div class="card shadow p-4">

                <h4>Tambah Transaksi</h4>

                <form method="POST">

                    <div class="mb-3">
                        <label>Kode Transaksi</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="pelanggan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Total Bayar</label>
                        <input type="number" name="total" class="form-control" required>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary w-100">
                        Simpan Data
                    </button>

                </form>

            </div>

        </div>

        <div class="col-md-8">

            <div class="card shadow p-4">

                <h4>Data Transaksi</h4>

                <table class="table table-bordered table-striped mt-3">

                    <thead class="table-primary">
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

                    <?php while($row = $data->fetch_assoc()){ ?>

                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['kode_transaksi']; ?></td>
                            <td><?= $row['pelanggan']; ?></td>
                            <td>Rp <?= number_format($row['total_bayar'],0,',','.'); ?></td>
                            <td><?= $row['tanggal']; ?></td>

                            <td>
                                <a href="?hapus=<?= $row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus data?')">
                                   Hapus
                                </a>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
