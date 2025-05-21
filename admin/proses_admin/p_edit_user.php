<?php
ob_start();
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];

    try {
        $stmt = $koneksi->prepare("UPDATE tb_user SET username = ?, nama_lengkap = ?, email = ?, no_telepon = ? WHERE id_user = ?");
        $stmt->bind_param("ssssi", $username, $nama_lengkap, $email, $no_telepon, $id_user);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Data user berhasil diupdate.";
            header('Location: ../data_user.php');
            exit();
        } else {
            $_SESSION['error'] = "Gagal mengupdate data user.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header('Location: ../edit_user.php?id=' . $id_user);
exit();
