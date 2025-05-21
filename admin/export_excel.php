<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

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

// Connect to database
require_once '../koneksi.php';

// Query untuk mengambil data berdasarkan tanggal
$query = "SELECT k.*, b.nama_balita FROM tb_kms k JOIN tb_balita b ON k.id_balita = b.id_balita WHERE k.tanggal BETWEEN ? AND ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom
$headers = [
    'Nama Balita',
    'Tanggal',
    'Umur',
    'Tinggi Badan',
    'Berat Badan',
    'Status Gizi BB Umur',
    'Status Gizi BB Tinggi'
];

// Menambahkan header ke spreadsheet
$columnLetter = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($columnLetter . '1', $header);
    $columnLetter++;
}

// Styling untuk header
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->setSize(12);
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB(Color::COLOR_YELLOW);

// Menambahkan data dari database ke spreadsheet
$rowNumber = 2; // Mulai dari baris kedua
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, htmlspecialchars($row['nama_balita']));
    $sheet->setCellValue('B' . $rowNumber, htmlspecialchars($row['tanggal']));
    $sheet->setCellValue('C' . $rowNumber, htmlspecialchars($row['umur']));
    $sheet->setCellValue('D' . $rowNumber, htmlspecialchars($row['tinggi_badan']));
    $sheet->setCellValue('E' . $rowNumber, htmlspecialchars($row['berat_badan']));
    $sheet->setCellValue('F' . $rowNumber, htmlspecialchars($row['status_gizi_bb_umur']));
    $sheet->setCellValue('G' . $rowNumber, htmlspecialchars($row['status_gizi_bb_tinggi']));
    $rowNumber++;
}

// Styling untuk data
$sheet->getStyle('A2:G' . $rowNumber)->applyFromArray([
    'font' => [
        'size' => 11,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'border' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => Color::COLOR_BLACK],
        ],
    ],
]);

// Atur lebar kolom
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Set header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Laporan_Data_KMS.xlsx"');
header('Cache-Control: max-age=0');

// Buat writer dan output file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Tutup koneksi database
$stmt->close();
$koneksi->close();
exit();
?>
