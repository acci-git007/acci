<?php

// Koneksi RDS MySQL
$host = "penjualan-db.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$password = "admin2026";
$database = "db_penjualan";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$aksi = $_GET['aksi'] ?? '';

switch ($aksi) {

    // CREATE
    case 'create':

        $nama_motor = $_POST['nama_motor'];
        $merk = $_POST['merk'];
        $harga = $_POST['harga'];
        $jumlah = $_POST['jumlah'];
        $tanggal = $_POST['tanggal_penjualan'];

        $sql = "INSERT INTO penjualan_motor
        (nama_motor, merk, harga, jumlah, tanggal_penjualan)
        VALUES
        ('$nama_motor','$merk','$harga','$jumlah','$tanggal')";

        if ($conn->query($sql)) {
            echo "Data berhasil ditambahkan";
        } else {
            echo $conn->error;
        }

        break;

    // READ
    case 'read':

        $result = $conn->query("SELECT * FROM penjualan_motor");

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);

        break;

    // UPDATE
    case 'update':

        $id = $_POST['id'];
        $nama_motor = $_POST['nama_motor'];
        $merk = $_POST['merk'];
        $harga = $_POST['harga'];
        $jumlah = $_POST['jumlah'];

        $sql = "UPDATE penjualan_motor SET
                nama_motor='$nama_motor',
                merk='$merk',
                harga='$harga',
                jumlah='$jumlah'
                WHERE id='$id'";

        if ($conn->query($sql)) {
            echo "Data berhasil diupdate";
        } else {
            echo $conn->error;
        }

        break;

    // DELETE
    case 'delete':

        $id = $_GET['id'];

        $sql = "DELETE FROM penjualan_motor WHERE id='$id'";

        if ($conn->query($sql)) {
            echo "Data berhasil dihapus";
        } else {
            echo $conn->error;
        }

        break;

    default:

        echo "
        <h2>CRUD Penjualan Motor</h2>

        <p>API yang tersedia:</p>

        <ul>
            <li>?aksi=create</li>
            <li>?aksi=read</li>
            <li>?aksi=update</li>
            <li>?aksi=delete&id=1</li>
        </ul>
        ";
}

$conn->close();

?>
