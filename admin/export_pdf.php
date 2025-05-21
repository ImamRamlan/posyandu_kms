<?php
require '../vendor/autoload.php';

use TCPDF;

// Cek apakah sesi login ada
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Ambil data dari formulir
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Nama Posyandu
$posyandu_name = 'Posyandu Melati';

// Buat instance baru dari TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set metadata PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($posyandu_name);
$pdf->SetTitle('Laporan Data KMS');
$pdf->SetSubject('Data KMS');
$pdf->SetKeywords('TCPDF, PDF, Laporan, KMS');

// Hapus header dan footer default
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// Set header
$pdf->SetHeaderData('', 0, $posyandu_name, 'Laporan Data KMS');
$pdf->SetHeaderFont(Array('helvetica', '', 12));
$pdf->SetHeaderMargin(10); // Kurangi margin header
$pdf->SetTopMargin(25); // Kurangi margin atas

// Set footer
$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->setFooterFont(Array('helvetica', '', 8));
$pdf->SetFooterMargin(10); // Kurangi margin footer

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add title with styling
$pdf->SetTextColor(0, 0, 0); // Black color for title
$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 10, 'Laporan Data KMS', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Tanggal: ' . htmlspecialchars($start_date) . ' hingga ' . htmlspecialchars($end_date), 0, 1, 'C');
$pdf->Ln(10); // Kurangi jarak antara judul dan periode

// Connect to database
require_once '../koneksi.php';

// Query untuk mengambil data berdasarkan tanggal
$query = "SELECT k.*, b.nama_balita FROM tb_kms k JOIN tb_balita b ON k.id_balita = b.id_balita WHERE k.tanggal BETWEEN ? AND ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Generate table with improved styling
$html = '
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        background: #ffffff;
    }
    th, td {
        text-align: left;
        padding: 8px; /* Kurangi padding */
        border: 1px solid #cccccc;
    }
    th {
        font-weight: bold;
        text-align: center;
        background-color: #f0f0f0; /* Light gray background for header */
        border-bottom: 2px solid #999999; /* Darker border bottom for header */
    }
    tbody tr:nth-child(even) td {
        background-color: #f9f9f9; /* Light gray for even rows */
    }
    tbody tr:hover td {
        background-color: #f1f1f1; /* Light gray for hover */
    }
    .table-title {
        font-weight: bold;
        font-size: 16px; /* Kurangi ukuran font */
        padding: 5px 0; /* Kurangi padding */
        border-bottom: 1px solid #cccccc;
        margin-bottom: 10px; /* Kurangi margin bawah */
    }
    .header-info {
        margin-bottom: 5px; /* Kurangi margin bawah */
    }
    .header-info p {
        margin: 2px 0; /* Kurangi margin p */
    }
</style>
<div class="header-info">
    <p><strong>Nama Posyandu:</strong> ' . $posyandu_name . '</p>
    <p><strong>Periode:</strong> ' . htmlspecialchars($start_date) . ' hingga ' . htmlspecialchars($end_date) . '</p>
</div>
<div class="table-container">
    <p class="table-title">Data KMS</p>
    <table>
        <thead>
            <tr>
                <th>Nama Balita</th>
                <th>Tanggal</th>
                <th>Umur</th>
                <th>Tinggi Badan</th>
                <th>Berat Badan</th>
                <th>Status Gizi BB Umur</th>
                <th>Status Gizi BB Tinggi</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['nama_balita']) . '</td>
        <td>' . htmlspecialchars($row['tanggal']) . '</td>
        <td>' . htmlspecialchars($row['umur']) . '</td>
        <td>' . htmlspecialchars($row['tinggi_badan']) . '</td>
        <td>' . htmlspecialchars($row['berat_badan']) . '</td>
        <td>' . htmlspecialchars($row['status_gizi_bb_umur']) . '</td>
        <td>' . htmlspecialchars($row['status_gizi_bb_tinggi']) . '</td>
    </tr>';
}

$html .= '</tbody></table></div>';

// Write HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('Laporan_Data_KMS.pdf', 'I'); // 'I' untuk menampilkan di browser, 'D' untuk download

// Tutup koneksi database
$stmt->close();
$koneksi->close();
?>
