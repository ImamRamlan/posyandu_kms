<?php
session_start();
include 'koneksi.php';

// Hapus sesi
session_unset();
session_destroy();

// Hapus cookie "Remember Me"
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, "/");
}

// Arahkan pengguna ke halaman login
header("Location: login.php");
exit();
?>
