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

// Ambil ID balita dari parameter URL
$id_balita = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil data balita berdasarkan ID
$query = "SELECT * FROM tb_balita WHERE id_balita = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_balita);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Redirect ke halaman yang sesuai jika data tidak ditemukan
    header('Location: informasi_balita.php'); // Alihkan ke halaman informasi_balita.php jika data tidak ditemukan
    exit();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Balita</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="informasi_balita.php">Informasi Balita</a></li>
                        <li class="breadcrumb-item active">Detail Balita</li>
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
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Lahir</th>
                                        <td><?= date('d M Y', strtotime($row['tanggal_lahir'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tempat Lahir</th>
                                        <td><?= htmlspecialchars($row['tempat_lahir']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Berat Badan Saat Lahir</th>
                                        <td><?= htmlspecialchars($row['bb_lahir']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Lingkar Kepala Saat Lahir</th>
                                        <td><?= htmlspecialchars($row['lingkar_kepala']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Persalinan</th>
                                        <td><?= htmlspecialchars($row['p_persalinan']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Ibu</th>
                                        <td><?= htmlspecialchars($row['nama_ibu']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Ayah</th>
                                        <td><?= htmlspecialchars($row['nama_ayah']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Pekerjaan Ayah</th>
                                        <td><?= htmlspecialchars($row['pekerjaan_ayah']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Pekerjaan Ibu</th>
                                        <td><?= htmlspecialchars($row['pekerjaan_ibu']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Pendidikan Ayah</th>
                                        <td><?= htmlspecialchars($row['pendidikan_ayah']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Pendidikan Ibu</th>
                                        <td><?= htmlspecialchars($row['pendidikan_ibu']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>No HP</th>
                                        <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Anak</th>
                                        <td><?= htmlspecialchars($row['jumlah_anak']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Anak Ke</th>
                                        <td><?= htmlspecialchars($row['anak_ke']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="informasi_balita.php" class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
