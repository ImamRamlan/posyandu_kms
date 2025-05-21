<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil ID balita dari parameter URL
$id_balita = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_balita <= 0) {
    $_SESSION['error'] = "ID balita tidak valid.";
    header('Location: ../data_balita.php');
    exit();
}

try {
    // Prepare SQL statement
    $stmt = $koneksi->prepare("DELETE FROM tb_balita WHERE id_balita = ?");
    
    // Bind parameter
    $stmt->bind_param('i', $id_balita);
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['delete'] = "Data balita berhasil dihapus.";
        header('Location: ../data_balita.php');
    } else {
        $_SESSION['error'] = "Gagal menghapus data balita: " . $stmt->error;
        header('Location: ../data_balita.php');
    }
    
    // Close statement
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    // Tangani error untuk foreign key constraint
    if ($e->getCode() == 1451) { // Kode error untuk foreign key constraint
        $_SESSION['error'] = "Data balita sedang digunakan di data lain. Silahkan hapus data terkait terlebih dahulu.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    }
    header('Location: ../data_balita.php');
    exit();
}

$koneksi->close();
?>
