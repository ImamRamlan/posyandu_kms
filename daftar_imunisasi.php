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
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Imunisasi Dasar Lengkap</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Daftar Imunisasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Jadwal Imunisasi Bayi Usia 0-9 Bulan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Usia</th>
                                        <th>Imunisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Bayi baru lahir (usia kurang dari 24 jam)</td>
                                        <td>Hepatitis B (HB-1)</td>
                                    </tr>
                                    <tr>
                                        <td>Usia 0-1 bulan</td>
                                        <td>Polio 0, BCG</td>
                                    </tr>
                                    <tr>
                                        <td>Usia 2 bulan</td>
                                        <td>DP-HiB 1, Polio 1, Hepatitis B 2, Rotavirus, PCV</td>
                                    </tr>
                                    <tr>
                                        <td>Usia 3 bulan</td>
                                        <td>DPT-HiB 2, Polio 2, Hepatitis B 3</td>
                                    </tr>
                                    <tr>
                                        <td>Usia 4 bulan</td>
                                        <td>DPT-HiB 3, Polio 3 (IPV atau polio suntik), Hepatitis B 4, Rotavirus 2</td>
                                    </tr>
                                    <tr>
                                        <td>Usia 6 bulan</td>
                                        <td>PCV 3, Influenza 1, Rotavirus 3 (pentavalen)</td>
                                    </tr>
                                    <tr>
                                        <td>Usia 9 bulan</td>
                                        <td>Campak atau MR, Japanese Encephalitis 1</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <h5>Jadwal Imunisasi Kombinasi</h5>
                            <ul>
                                <li>DPT-HiB-HB (Difteri, Pertusis, Haemofilus Influenza, Hepatitis)</li>
                                <li>DPTa-HB-HiB-IPV (Difteri, Pertusis, Tetanus, Hepatitis, Haemofilus Influenza, dan Polio)</li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
