<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Ambil ID KMS dari parameter URL
$id_kms = isset($_GET['id_kms']) ? intval($_GET['id_kms']) : 0;

if ($id_kms <= 0) {
    $_SESSION['delete'] = "ID KMS tidak valid.";
    header('Location: ../data_kms.php');
    exit();
}

try {
    // Prepare SQL statement untuk menghapus data
    $stmt = $koneksi->prepare("DELETE FROM tb_kms WHERE id_kms = ?");
    
    // Bind parameter
    $stmt->bind_param('i', $id_kms);
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['delete'] = "Data KMS berhasil dihapus.";
    } else {
        $_SESSION['delete'] = "Terjadi kesalahan saat menghapus data: " . $stmt->error;
    }
    
    // Close statement
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
}

$koneksi->close();

// Redirect ke halaman data KMS dengan pesan
header('Location: ../data_kms.php');
exit();
