<?php
require_once '../../koneksi.php';
session_start(); // Pastikan session dimulai jika menggunakan session

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    try {
        // Query untuk menghapus data
        $stmt = $koneksi->prepare("DELETE FROM tb_user WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);

        if ($stmt->execute()) {
            $_SESSION['delete'] = "Data user berhasil dihapus.";
            header('Location: ../data_user.php');
            exit();
        } else {
            // Tangani kesalahan umum
            $_SESSION['error'] = "Gagal menghapus data. Silahkan coba lagi.";
            header('Location: ../data_user.php');
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Tangani error untuk foreign key constraint
        if ($e->getCode() == 1451) { // Kode error untuk foreign key constraint
            $_SESSION['error'] = "Data user sedang digunakan di data balita atau KMS. Silahkan hapus data terkait terlebih dahulu.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }
        header('Location: ../data_user.php');
        exit();
    }
} else {
    // Jika tidak ada ID yang dikirim, redirect ke halaman user
    header('Location: ../data_user.php');
    exit();
}
?>
