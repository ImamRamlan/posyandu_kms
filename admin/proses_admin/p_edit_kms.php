<?php
session_start();
// Pastikan file ini terhubung ke database
include '../../koneksi.php'; // Ganti dengan nama file koneksi database Anda

// Ambil data dari form
$id_kms = $_POST['id_kms'];
$umur = $_POST['umur'];
$tinggi_badan = $_POST['tinggi_badan'];
$berat_badan = $_POST['berat_badan'];
$status_gizi_bb_umur = $_POST['status_gizi_bb_umur'];
$status_gizi_bb_tinggi = $_POST['status_gizi_bb_tinggi'];

// Validasi input
if (empty($id_kms) || empty($umur) || empty($tinggi_badan) || empty($berat_badan)) {
    die('Data tidak lengkap.');
}

// Update data di database
$query = "UPDATE tb_kms SET
            umur = ?, 
            tinggi_badan = ?, 
            berat_badan = ?, 
            status_gizi_bb_umur = ?, 
            status_gizi_bb_tinggi = ?
          WHERE id_kms = ?";

$stmt = $koneksi->prepare($query);

if ($stmt === false) {
    die('Error preparing statement: ' . $koneksi->error);
}

// Pastikan tipe data parameter sesuai: umur (i), tinggi_badan (d), berat_badan (d), status_gizi_bb_umur (s), status_gizi_bb_tinggi (s), id_kms (i)
$stmt->bind_param("iddssi", $umur, $tinggi_badan, $berat_badan, $status_gizi_bb_umur, $status_gizi_bb_tinggi, $id_kms);

if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error);
}

// Pesan sukses
$_SESSION['message'] = "Data KMS berhasil diperbarui.";

// Pastikan sesi sudah dimulai


// Redirect ke halaman data_kms.php
header("Location: ../data_kms.php");
exit();

// Tutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>
