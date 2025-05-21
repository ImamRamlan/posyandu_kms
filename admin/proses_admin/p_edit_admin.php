<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Cek apakah ID admin ada di POST request
if (!isset($_POST['id_admin']) || empty($_POST['id_admin'])) {
    $_SESSION['error'] = 'ID admin tidak ditemukan.';
    header('Location: ../data_admin.php');
    exit();
}

$id_admin = intval($_POST['id_admin']);
$username = $_POST['username'];
$password = isset($_POST['password']) ? $_POST['password'] : '';
$nama_lengkap = $_POST['nama_lengkap'];
$role = $_POST['role'];

// Validasi input
if (empty($username) || empty($nama_lengkap) || empty($role)) {
    $_SESSION['error'] = 'Semua field harus diisi.';
    header('Location: ../edit_admin.php?id_admin=' . $id_admin);
    exit();
}

// Hash password jika diisi
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE tb_admin SET username = ?, password = ?, nama_lengkap = ?, role = ? WHERE id_admin = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('ssssi', $username, $hashed_password, $nama_lengkap, $role, $id_admin);
} else {
    $sql = "UPDATE tb_admin SET username = ?, nama_lengkap = ?, role = ? WHERE id_admin = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('sssi', $username, $nama_lengkap, $role, $id_admin);
}

try {
    $stmt->execute();
    $_SESSION['message'] = 'Data admin berhasil diperbarui.';
    header('Location: ../data_admin.php');
} catch (Exception $e) {
    $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header('Location: ../edit_admin.php?id_admin=' . $id_admin);
} finally {
    $stmt->close();
    $koneksi->close();
}
?>
