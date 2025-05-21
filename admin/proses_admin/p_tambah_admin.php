<?php
session_start();
require_once '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];

    // Validasi dan sanitasi input
    // Periksa apakah username sudah ada
    $stmt = $koneksi->prepare("SELECT id_admin FROM tb_admin WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username sudah ada
        $_SESSION['error'] = 'Username sudah terdaftar. Silahkan ganti username anda!';
        header('Location: ../tambah_admin.php');
        exit();
    }

    // Jika username belum ada, lanjutkan dengan penyimpanan data
    $stmt = $koneksi->prepare("INSERT INTO tb_admin (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $username, $password, $nama_lengkap, $role);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Admin berhasil ditambahkan.';
        header('Location: ../data_admin.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan admin.';
        header('Location: ../tambah_admin.php');
        exit();
    }
}
?>
