<?php
require_once('../vendor/autoload.php');  // Pastikan TCPDF diinstal melalui composer
use TCPDF;

// Cek sesi login
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Koneksi ke database
require '../koneksi.php';

// Query untuk mengambil semua data balita
$query = "SELECT * FROM tb_balita";
$result = $koneksi->query($query);

if ($result->num_rows == 0) {
    echo "Tidak ada data yang tersedia.";
    exit();
}

// Membuat PDF baru
$pdf = new TCPDF('L', 'mm', 'A4');  // Mengubah orientasi menjadi Landscape ('L')

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Posyandu Melati');
$pdf->SetTitle('Data Semua Balita');

// Set header dan footer
$pdf->setHeaderData('', 0, 'Data Semua Balita', 'Posyandu Melati', array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// Set margin
$pdf->SetMargins(5, 10, 5); // Mengurangi margin untuk memaksimalkan ruang
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// Tambahkan halaman
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 8); // Menggunakan font lebih kecil untuk menyesuaikan dengan lebih banyak data

// Mulai membuat tabel HTML
$html = '
<h2 style="text-align:center;">Data Semua Balita</h2>
<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse; width:100%;">
    <thead>
        <tr style="background-color:#f2f2f2; font-weight:bold;">
            <th style="text-align:center;">Nama</th>
            <th style="text-align:center;">JK</th>
            <th style="text-align:center;">Tgl Lahir</th>
            <th style="text-align:center;">Tempat Lahir</th>
            <th style="text-align:center;">BB Lahir</th>
            <th style="text-align:center;">Persalinan</th>
            <th style="text-align:center;">Ayah</th>
            <th style="text-align:center;">Ibu</th>
            <th style="text-align:center;">Pekerjaan Ayah</th>
            <th style="text-align:center;">Pekerjaan Ibu</th>
            <th style="text-align:center;">No HP</th>
            <th style="text-align:center;">Alamat</th>
            <th style="text-align:center;">Jumlah Anak</th>
            <th style="text-align:center;">Anak Ke</th>
        </tr>
    </thead>
    <tbody>';

// Loop melalui semua data balita dan tambahkan ke dalam tabel
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td style="text-align:left;">' . htmlspecialchars($row['nama_balita']) . '</td>
        <td style="text-align:center;">' . htmlspecialchars($row['jenis_kelamin']) . '</td>
        <td style="text-align:center;">' . date('d M Y', strtotime($row['tanggal_lahir'])) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['tempat_lahir']) . '</td>
        <td style="text-align:center;">' . htmlspecialchars($row['bb_lahir']) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['p_persalinan']) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['nama_ayah']) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['nama_ibu']) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['pekerjaan_ayah']) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['pekerjaan_ibu']) . '</td>
        <td style="text-align:center;">' . htmlspecialchars($row['no_hp']) . '</td>
        <td style="text-align:left;">' . htmlspecialchars($row['alamat']) . '</td>
        <td style="text-align:center;">' . htmlspecialchars($row['jumlah_anak']) . '</td>
        <td style="text-align:center;">' . htmlspecialchars($row['anak_ke']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Tulis HTML ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Tutup dan output PDF
$pdf->Output('data_semua_balita.pdf', 'I');
?>
