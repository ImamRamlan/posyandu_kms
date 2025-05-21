<?php
ob_start();
session_start();
include 'koneksi.php';  // Koneksi ke database
include 'header.php';   // Header halaman
include 'navbar.php';   // Navbar halaman
include 'remember_me.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php'); // Alihkan ke halaman login jika belum login
    exit();
}

// Ambil ID KMS dari parameter URL
$id_kms = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil data KMS dan data balita terkait berdasarkan ID KMS
$query = "
    SELECT tb_kms.*, tb_balita.nama_balita, tb_balita.jenis_kelamin, tb_balita.tanggal_lahir
    FROM tb_kms
    JOIN tb_balita ON tb_kms.id_balita = tb_balita.id_balita
    WHERE tb_kms.id_kms = ?
";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_kms);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Redirect ke halaman yang sesuai jika data tidak ditemukan
    header('Location: kartu_menuju_sehat.php'); // Alihkan ke halaman kartu_menuju_sehat.php jika data tidak ditemukan
    exit();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Kartu Menuju Sehat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="kartu_menuju_sehat.php">Kartu Menuju Sehat</a></li>
                        <li class="breadcrumb-item active">Detail KMS</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><?= htmlspecialchars($row['nama_balita']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Jenis Kelamin:</strong> <?= htmlspecialchars($row['jenis_kelamin']); ?></p>
                            <p><strong>Tanggal Lahir:</strong> <?= date('d M Y', strtotime($row['tanggal_lahir'])); ?></p>
                            <p><strong>Umur:</strong> <?= htmlspecialchars($row['umur']); ?> bulan</p>
                            <p><strong>Tinggi Badan:</strong> <?= htmlspecialchars($row['tinggi_badan']); ?> cm</p>
                            <p><strong>Berat Badan:</strong> <?= htmlspecialchars($row['berat_badan']); ?> kg</p>
                            <p><strong>Status Gizi BB Umur:</strong> <?= htmlspecialchars($row['status_gizi_bb_umur']); ?></p>
                            <p><strong>Status Gizi BB Tinggi:</strong> <?= htmlspecialchars($row['status_gizi_bb_tinggi']); ?></p>
                            <p><strong>Tanggal:</strong> <?= date('d M Y', strtotime($row['tanggal'])); ?></p>
                            <p><strong>Terakhir Diperbarui:</strong> <?= date('d M Y H:i:s', strtotime($row['tanggal_update'])); ?></p>

                            <!-- Catatan untuk Status Gizi BB Umur -->
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <?php
                                    switch ($row['status_gizi_bb_umur']) {
                                        case 'Berat Badan Sangat Kurang':
                                            echo 'Berat Badan Sangat Kurang - Pemberian makanan tambahan (PMT) yaitu makanan tambahan berkalori tinggi dan bergizi.';
                                            break;
                                        case 'Berat Badan Kurang':
                                            echo 'Berat Badan Kurang - Konsumsi makanan bergizi seimbang yang kaya akan protein, lemak sehat, vitamin, dan mineral.';
                                            break;
                                        case 'Berat Badan Normal':
                                            echo 'Berat Badan Normal - Pertahankan pola makan bergizi seimbang.';
                                            break;
                                        case 'Resiko Berat Badan Lebih':
                                            echo 'Resiko Berat Badan Lebih - Pemberian makanan sehat dengan membatasi makanan tinggi gula, lemak, dan kalori serta tingkatkan konsumsi buah, sayur, dan makanan tinggi serat.';
                                            break;
                                        case 'Obesitas':
                                            echo 'Obesitas - Batasi makanan yang tinggi lemak dan gula serta anjurkan konsumsi makanan sehat.';
                                            break;
                                        default:
                                            echo 'Status berat badan tidak diketahui.';
                                    }
                                    ?>
                                </li>
                            </ul>

                            <!-- Catatan untuk Status Gizi BB Tinggi -->
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <?php
                                    switch ($row['status_gizi_bb_tinggi']) {
                                        case 'Gizi Buruk':
                                            echo 'Gizi Buruk - Mendapatkan makanan tambahan yang bergizi tinggi dan Pemberian Makanan Terapi (PMT) khusus, seperti formula F-75 atau F-100.';
                                            break;
                                        case 'Gizi Kurang':
                                            echo 'Gizi Kurang - Konseling gizi kepada orang tua mengenai pola makan bergizi seimbang, termasuk pemberian ASI eksklusif (jika usia anak <6 bulan) atau MP-ASI berkualitas dan Pemberian PMT pemulihan (biasanya tinggi kalori dan protein).';
                                            break;
                                        case 'Gizi Baik':
                                            echo 'Gizi Baik - Mempertahankan pola makan bergizi seimbang sesuai kebutuhan anak dan pemberian makanan yang beragam, termasuk sayur, buah, sumber protein, dan karbohidrat.';
                                            break;
                                        case 'Berisiko Gizi Lebih':
                                            echo 'Berisiko Gizi Lebih - Konsumsi makanan tinggi gula, lemak, atau garam dan pengurangan konsumsi makanan olahan yang tidak sehat.';
                                            break;
                                        case 'Gizi Lebih':
                                            echo 'Gizi Lebih - Pengurangan porsi makanan yang tinggi kalori dan lemak serta lebih memperbanyak makan sayuran dan buah.';
                                            break;
                                        case 'Obesitas':
                                            echo 'Obesitas - Pengurangan porsi makan.';
                                            break;
                                        default:
                                            echo 'Status gizi tidak diketahui.';
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <a href="kartu_menuju_sehat.php" class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>