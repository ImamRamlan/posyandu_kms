<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil data dari form
$nama_balita = $_POST['nama_balita'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$tempat_lahir = $_POST['tempat_lahir'];
$bb_lahir = $_POST['bb_lahir'];
$lingkar_kepala = $_POST['lingkar_kepala'];
$p_persalinan = $_POST['p_persalinan'];
$nama_ayah = $_POST['nama_ayah'];
$nama_ibu = $_POST['nama_ibu'];
$pekerjaan_ayah = $_POST['pekerjaan_ayah'];
$pekerjaan_ibu = $_POST['pekerjaan_ibu'];
$pendidikan_ayah = $_POST['pendidikan_ayah'];
$pendidikan_ibu = $_POST['pendidikan_ibu'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$jumlah_anak = $_POST['jumlah_anak'];
$anak_ke = $_POST['anak_ke'];
$id_ortu = $_POST['id_ortu'];

// Siapkan query untuk memasukkan data
$query = "INSERT INTO tb_balita (
    nama_balita, jenis_kelamin, tanggal_lahir, tempat_lahir, bb_lahir, lingkar_kepala, p_persalinan, nama_ayah, nama_ibu, 
    pekerjaan_ayah, pekerjaan_ibu, pendidikan_ayah, pendidikan_ibu, no_hp, alamat, jumlah_anak, anak_ke, id_ortu
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);

// Periksa apakah prepare berhasil
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($koneksi->error));
}

// Bind parameter sesuai dengan tipe data
$stmt->bind_param(
    'sssssssssssssssssi',
    $nama_balita, $jenis_kelamin, $tanggal_lahir, $tempat_lahir, $bb_lahir, $lingkar_kepala, $p_persalinan, $nama_ayah,
    $nama_ibu, $pekerjaan_ayah, $pekerjaan_ibu, $pendidikan_ayah, $pendidikan_ibu, $no_hp, $alamat, $jumlah_anak,
    $anak_ke, $id_ortu
);

// Eksekusi statement
if ($stmt->execute()) {
    header('Location: ../data_balita.php');
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

// Tutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>
