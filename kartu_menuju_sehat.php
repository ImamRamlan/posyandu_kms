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

// Ambil ID pengguna dari session
$id_user = $_SESSION['id_user'];

// Jumlah data per halaman
$limit = 6;

// Mengambil halaman saat ini dari query string, jika tidak ada, set ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total KMS
$query_count = "
    SELECT COUNT(*) AS total 
    FROM tb_kms 
    JOIN tb_balita ON tb_kms.id_balita = tb_balita.id_balita 
    WHERE tb_balita.id_ortu = ?
";
$stmt_count = $koneksi->prepare($query_count);
$stmt_count->bind_param('i', $id_user);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_data = $result_count->fetch_assoc()['total'];

// Query untuk mengambil data KMS berdasarkan id_user dengan limit dan offset
$query = "
    SELECT tb_kms.*, tb_balita.nama_balita 
    FROM tb_kms 
    JOIN tb_balita ON tb_kms.id_balita = tb_balita.id_balita 
    WHERE tb_balita.id_ortu = ? 
    ORDER BY tb_kms.tanggal DESC 
    LIMIT ? OFFSET ?
";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('iii', $id_user, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kartu Menuju Sehat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kartu Menuju Sehat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title"><?= htmlspecialchars($row['nama_balita']); ?></h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Umur:</strong> <?= htmlspecialchars($row['umur']); ?> bulan</p>
                                    <p><strong>Tinggi Badan:</strong> <?= htmlspecialchars($row['tinggi_badan']); ?> cm</p>
                                    <p><strong>Berat Badan:</strong> <?= htmlspecialchars($row['berat_badan']); ?> kg</p>
                                    <p><strong>Status Gizi BB Umur:</strong> <?= htmlspecialchars($row['status_gizi_bb_umur']); ?></p>
                                    <p><strong>Status Gizi BB Tinggi:</strong> <?= htmlspecialchars($row['status_gizi_bb_tinggi']); ?></p>
                                    <p><strong>Tanggal:</strong> <?= date('d M Y', strtotime($row['tanggal'])); ?></p>
                                </div>
                                <div class="card-footer">
                                    <a href="detail_kms.php?id=<?= $row['id_kms']; ?>" class="btn btn-info btn-sm">Detail KMS</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-warning" style="font-size: 24px;">Tidak ada data KMS</div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Paginasi -->
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php
                            $total_pages = ceil($total_data / $limit);
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $active = ($i == $page) ? 'active' : '';
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
