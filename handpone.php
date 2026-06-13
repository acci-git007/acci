<?php

$host = "penjualan-db.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$password = "admin2026";
$database = "db_penjualan_handphone";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$aksi = $_GET['aksi'] ?? '';

switch ($aksi) {

    case 'create':

        $nama_hp = $_POST['nama_hp'];
        $merk = $_POST['merk'];
        $harga = $_POST['harga'];
        $jumlah = $_POST['jumlah'];
        $tanggal = $_POST['tanggal_penjualan'];

        $sql = "INSERT INTO penjualan_handphone
                (nama_hp, merk, harga, jumlah, tanggal_penjualan)
                VALUES
                ('$nama_hp','$merk','$harga','$jumlah','$tanggal')";

        if ($conn->query($sql)) {
            echo "Data berhasil ditambahkan";
        } else {
            echo $conn->error;
        }

        break;

    case 'read':

        $result = $conn->query("SELECT * FROM penjualan_handphone");

        $data = [];

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);

        break;

    case 'update':

        $id = $_POST['id'];

        $sql = "UPDATE penjualan_handphone SET
                nama_hp='{$_POST['nama_hp']}',
                merk='{$_POST['merk']}',
                harga='{$_POST['harga']}',
                jumlah='{$_POST['jumlah']}'
                WHERE id='$id'";

        if ($conn->query($sql)) {
            echo "Data berhasil diupdate";
        } else {
            echo $conn->error;
        }

        break;

    case 'delete':

        $id = $_GET['id'];

        if ($conn->query("DELETE FROM penjualan_handphone WHERE id='$id'")) {
            echo "Data berhasil dihapus";
        } else {
            echo $conn->error;
        }

        break;

    default:
        echo "Gunakan aksi create, read, update atau delete";
}

$conn->close();
?>
