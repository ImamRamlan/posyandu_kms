<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil data dari form
$id_balita = isset($_POST['id_balita']) ? intval($_POST['id_balita']) : 0;
$nama_balita = $_POST['nama_balita'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$tempat_lahir = $_POST['tempat_lahir'];
$bb_lahir = $_POST['bb_lahir'];
$lingkar_kepala = $_POST['lingkar_kepala'];
$p_persalinan = $_POST['p_persalinan'];
$nama_ayah = $_POST['nama_ayah'];
$nama_ibu = $_POST['nama_ibu'];
$pekerjaan_ayah = $_POST['pekerjaan_ayah'];
$pekerjaan_ibu = $_POST['pekerjaan_ibu'];
$pendidikan_ayah = $_POST['pendidikan_ayah'];
$pendidikan_ibu = $_POST['pendidikan_ibu'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$jumlah_anak = $_POST['jumlah_anak'];
$anak_ke = $_POST['anak_ke'];
$id_ortu = $_POST['id_ortu'];

if ($id_balita <= 0) {
    echo "ID balita tidak valid.";
    exit();
}

// Validasi input jika diperlukan
$errors = [];
if (empty($nama_balita)) $errors[] = "Nama balita tidak boleh kosong.";
if (empty($tanggal_lahir)) $errors[] = "Tanggal lahir tidak boleh kosong.";
// Tambahkan validasi lain sesuai kebutuhan

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo '<a href="edit_balita.php?id=' . htmlspecialchars($id_balita) . '">Kembali</a>';
    exit();
}

try {
    // Prepare SQL statement
    $stmt = $koneksi->prepare("
        UPDATE tb_balita
        SET nama_balita = ?, jenis_kelamin = ?, tanggal_lahir = ?, tempat_lahir = ?, bb_lahir = ?, lingkar_kepala = ?, p_persalinan = ?, 
            nama_ayah = ?, nama_ibu = ?, pekerjaan_ayah = ?, pekerjaan_ibu = ?, pendidikan_ayah = ?, pendidikan_ibu = ?, 
            no_hp = ?, alamat = ?, jumlah_anak = ?, anak_ke = ?, id_ortu = ?
        WHERE id_balita = ?
    ");
    
    // Bind parameters (17 parameters)
    $stmt->bind_param(
        'sssssssssssssssssii',
        $nama_balita, $jenis_kelamin, $tanggal_lahir, $tempat_lahir, $bb_lahir, $lingkar_kepala,$p_persalinan,
        $nama_ayah, $nama_ibu, $pekerjaan_ayah, $pekerjaan_ibu, $pendidikan_ayah, $pendidikan_ibu,
        $no_hp, $alamat, $jumlah_anak, $anak_ke, $id_ortu, $id_balita
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Data user berhasil diupdate.";
        header('Location: ../data_balita.php');
    } else {
        echo "Gagal memperbarui data balita: " . $stmt->error;
    }
    
    // Close statement
    $stmt->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$koneksi->close();
?>
