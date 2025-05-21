<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Detail KMS | Posyandu Melati";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';

if (isset($_GET['id_balita'])) {
    $id_balita = $_GET['id_balita'];

    try {
        // Mengambil nama balita
        $stmt_balita = $koneksi->prepare("SELECT nama_balita,nama_ibu,jenis_kelamin FROM tb_balita WHERE id_balita = ?");
        $stmt_balita->bind_param("i", $id_balita);
        $stmt_balita->execute();
        $result_balita = $stmt_balita->get_result();
        $row_balita = $result_balita->fetch_assoc();

        // Mengambil semua data KMS untuk balita tertentu
        $stmt_kms = $koneksi->prepare("
            SELECT tb_kms.id_kms, tb_kms.umur, tb_kms.tinggi_badan, tb_kms.berat_badan, tb_kms.tanggal, tb_kms.status_gizi_bb_umur, tb_kms.status_gizi_bb_tinggi
            FROM tb_kms
            WHERE tb_kms.id_balita = ?
        ");
        $stmt_kms->bind_param("i", $id_balita);
        $stmt_kms->execute();
        $result_kms = $stmt_kms->get_result();
        $kms_data = $result_kms->fetch_all(MYSQLI_ASSOC); // Ambil semua data sekaligus ke dalam array

        // Extract unique status values for BB Umur and BB Tinggi
        $status_gizi_umur = [];
        $status_gizi_tinggi = [];

        foreach ($kms_data as $row_kms) {
            $status_gizi_umur[] = $row_kms['status_gizi_bb_umur'];
            $status_gizi_tinggi[] = $row_kms['status_gizi_bb_tinggi'];
        }

        // Remove duplicate status values
        $status_gizi_umur = array_unique($status_gizi_umur);
        $status_gizi_tinggi = array_unique($status_gizi_tinggi);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID Balita tidak ditemukan.";
    exit();
}
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail KMS Balita</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Detail KMS</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data KMS untuk Balita</h3><br>
                            <ul class="list-group col-md-5">
                                <li class="list-group-item">Nama Anak : <b><?php echo htmlspecialchars($row_balita['nama_balita']); ?></b></li>
                                <li class="list-group-item">Nama Ibu : <b><?php echo htmlspecialchars($row_balita['nama_ibu']); ?></b></li>
                                <li class="list-group-item">Jenis Kelamin : <b><?php echo htmlspecialchars($row_balita['jenis_kelamin']); ?></b></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Umur</th>
                                        <th>Tinggi Badan</th>
                                        <th>Berat Badan</th>
                                        <th>Status Gizi BB Umur</th>
                                        <th>Status Gizi BB Tinggi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($kms_data as $row_kms) { ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row_kms['tanggal']); ?></td>
                                            <td><?php echo htmlspecialchars($row_kms['umur']); ?></td>
                                            <td><?php echo htmlspecialchars($row_kms['tinggi_badan']); ?></td>
                                            <td><?php echo htmlspecialchars($row_kms['berat_badan']); ?></td>
                                            <td><?php echo htmlspecialchars($row_kms['status_gizi_bb_umur']); ?></td>
                                            <td><?php echo htmlspecialchars($row_kms['status_gizi_bb_tinggi']); ?></td>
                                            <td>
                                                <a href="edit_kms.php?id_kms=<?php echo $row_kms['id_kms']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="proses_admin/p_delete_kms.php?id_kms=<?php echo $row_kms['id_kms']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php
                                    } ?>
                                </tbody>
                            </table>
                            <span>Noted :</span>
                            <ul class="list-group col-md-7">
                                <?php
                                // Tampilkan catatan berdasarkan status_gizi_bb_umur yang unik
                                foreach ($status_gizi_umur as $status) {
                                    echo '<li class="list-group-item">';
                                    switch ($status) {
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
                                    echo '</li>';
                                }
                                ?>

                                <?php
                                // Tampilkan catatan berdasarkan status_gizi_bb_tinggi yang unik
                                foreach ($status_gizi_tinggi as $status) {
                                    echo '<li class="list-group-item">';
                                    switch ($status) {
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
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'include/footer.php'; ?>