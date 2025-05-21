<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Cek apakah ID admin ada di URL
if (!isset($_GET['id_admin']) || empty($_GET['id_admin'])) {
    $_SESSION['error'] = 'ID admin tidak ditemukan.';
    header('Location: ../data_admin.php');
    exit();
}

$id_admin = intval($_GET['id_admin']);

// Validasi ID admin
if ($id_admin <= 0) {
    $_SESSION['error'] = 'ID admin tidak valid.';
    header('Location: ../data_admin.php');
    exit();
}

// Cek apakah admin yang sedang login adalah admin yang ingin dihapus
$logged_in_admin_id = $_SESSION['user_id']; // Gunakan user_id dari sesi login

if ($id_admin === $logged_in_admin_id) {
    $_SESSION['error'] = 'Anda tidak dapat menghapus akun Anda sendiri.';
    header('Location: ../data_admin.php');
    exit();
}

// Hapus data admin dari database
try {
    $stmt = $koneksi->prepare("DELETE FROM tb_admin WHERE id_admin = ?");
    $stmt->bind_param('i', $id_admin);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $_SESSION['delete'] = 'Data admin berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Tidak ada data yang dihapus.';
    }
    
    header('Location: ../data_admin.php');
} catch (Exception $e) {
    $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header('Location: ../data_admin.php');
} finally {
    $stmt->close();
    $koneksi->close();
}
?>
