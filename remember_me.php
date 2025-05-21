<?php
if (!isset($_SESSION['id_user'])) {
    if (isset($_COOKIE['remember_token'])) {
        // Token dari cookie
        $token = $_COOKIE['remember_token'];

        // Query untuk mendapatkan data pengguna berdasarkan token
        $stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE remember_token IS NOT NULL");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($user = $result->fetch_assoc()) {
            if (password_verify($token, $user['remember_token'])) {
                // Set session untuk pengguna yang berhasil login menggunakan token
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                break;
            }
        }

        // Hapus token dari cookie jika validasi gagal
        if (!isset($_SESSION['id_user'])) {
            setcookie('remember_token', '', time() - 3600, "/");
            header("Location: login.php");
            exit();
        }
    } else {
        // Arahkan pengguna ke halaman login jika tidak ada sesi aktif dan tidak ada cookie
        header("Location: login.php");
        exit();
    }
}
