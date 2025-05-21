<?php
ob_start();
session_start();
require_once '../../koneksi.php'; // Sesuaikan path jika diperlukan

// Fungsi untuk validasi format tanggal
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu.";
    header('Location: ../login.php');
    exit();
}

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi data dari form
    $id_balita = isset($_POST['id_balita']) ? trim($_POST['id_balita']) : '';
    $umur = isset($_POST['umur']) ? trim($_POST['umur']) : '';
    $tinggi_badan = isset($_POST['tinggi_badan']) ? trim($_POST['tinggi_badan']) : '';
    $berat_badan = isset($_POST['berat_badan']) ? trim($_POST['berat_badan']) : '';
    $tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
    $nama_ibu = isset($_POST['nama_ibu']) ? trim($_POST['nama_ibu']) : '';
    $status_gizi_bb_umur = isset($_POST['status_gizi_bb_umur']) ? trim($_POST['status_gizi_bb_umur']) : '';
    $status_gizi_bb_tinggi = isset($_POST['status_gizi_bb_tinggi']) ? trim($_POST['status_gizi_bb_tinggi']) : '';

    // Inisialisasi array untuk menampung error
    $errors = [];

    // Validasi data
    if (empty($id_balita)) {
        $errors[] = "ID Balita wajib diisi.";
    } elseif (!ctype_digit($id_balita)) {
        $errors[] = "ID Balita harus berupa angka.";
    }

    if (empty($umur)) {
        $errors[] = "Umur wajib diisi.";
    } elseif (!preg_match('/^\d+$/', $umur)) {
        $errors[] = "Umur harus berupa angka.";
    }

    if (empty($tinggi_badan)) {
        $errors[] = "Tinggi Badan wajib diisi.";
    } elseif (!is_numeric($tinggi_badan)) {
        $errors[] = "Tinggi Badan harus berupa angka.";
    }

    if (empty($berat_badan)) {
        $errors[] = "Berat Badan wajib diisi.";
    } elseif (!is_numeric($berat_badan)) {
        $errors[] = "Berat Badan harus berupa angka.";
    }

    if (empty($tanggal)) {
        $errors[] = "Tanggal wajib diisi.";
    } elseif (!validateDate($tanggal)) {
        $errors[] = "Format tanggal tidak valid.";
    }

    if (empty($nama_ibu)) {
        $errors[] = "Nama Ibu wajib diisi.";
    }

    if (empty($status_gizi_bb_umur)) {
        $errors[] = "Status Gizi BB Umur wajib diisi.";
    }

    if (empty($status_gizi_bb_tinggi)) {
        $errors[] = "Status Gizi BB Tinggi wajib diisi.";
    }

    // Jika ada error, set pesan error dan redirect kembali ke form
    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: ../tambah_kms.php');
        exit();
    }

    // Jika validasi berhasil, lanjutkan memasukkan data ke database
    try {
        // Persiapkan query insert
        $stmt = $koneksi->prepare("INSERT INTO tb_kms (id_balita, umur, tinggi_badan, berat_badan, tanggal, nama_ibu, status_gizi_bb_umur, status_gizi_bb_tinggi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            throw new Exception("Prepare statement gagal: " . $koneksi->error);
        }

        // Bind parameter
        $stmt->bind_param('issdssss', $id_balita, $umur, $tinggi_badan, $berat_badan, $tanggal, $nama_ibu, $status_gizi_bb_umur, $status_gizi_bb_tinggi);

        // Eksekusi statement
        if ($stmt->execute()) {
            $_SESSION['message'] = "Data KMS berhasil ditambahkan.";
            header('Location: ../data_kms.php');
            exit();
        } else {
            throw new Exception("Eksekusi statement gagal: " . $stmt->error);
        }

    } catch (Exception $e) {
        // Set pesan error dan redirect kembali ke form
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        header('Location: ../tambah_kms.php');
        exit();
    }

} else {
    // Jika metode request bukan POST, redirect ke form
    $_SESSION['error'] = "Akses tidak diizinkan.";
    header('Location: ../tambah_kms.php');
    exit();
}
?>
