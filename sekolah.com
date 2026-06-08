<?php

$host = "dbtraining.c83ya4kmsi7u.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "admin2026";
$db   = "kelas";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Berhasil terhubung ke Amazon RDS";
?>
INSERT INTO pendaftaran
(
    nama,
    tanggal_lahir,
    alamat,
    no_hp,
    kelas,
    jurusan
)
VALUES
(
    'Andi Saputra',
    '2008-05-10',
    'Surabaya',
    '081234567890',
    'X',
    'RPL'
),
(
    'Budi Santoso',
    '2007-08-15',
    'Sidoarjo',
    '081234567891',
    'XI',
    'TKJ'
);
