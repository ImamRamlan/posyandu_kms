<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="assets/dist/img/hospitalimg.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Posyandu Melati</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <?php
        // Include database connection
        include '../koneksi.php';

        // Ambil data admin berdasarkan username yang disimpan di session
        $username = $_SESSION['username'] ?? '';
        $role = $_SESSION['role'] ?? '';

        if ($username) {
            $stmt = $koneksi->prepare("SELECT nama_lengkap FROM tb_admin WHERE username = ?");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                // Cek apakah data ditemukan
                if ($result && $result->num_rows > 0) {
                    $admin = $result->fetch_assoc();
                    $namaLengkap = htmlspecialchars($admin['nama_lengkap']);
                } else {
                    $namaLengkap = "User";
                }

                $stmt->close();
            } else {
                echo "Error preparing statement: " . $koneksi->error;
                $namaLengkap = "User";
            }
        } else {
            $namaLengkap = "User";
            $role = "Guest"; // Default role jika tidak ada session
        }

        // Function to set 'active' class on the current menu item
        function setActive($page) {
            return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
        }
        ?>

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $namaLengkap; ?></a>
                <small class="d-block text-muted"><?php echo htmlspecialchars($role); ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Menu Dashboard -->
                <?php if ($role == 'Admin' || $role == 'Bidan' || $role == 'Kader') { ?>
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo setActive('index.php'); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php } ?>

                <!-- Menu Data User - Hanya untuk Admin -->
                <?php if ($role == 'Admin') { ?>
                <li class="nav-item">
                    <a href="data_admin.php" class="nav-link <?php echo setActive('data_admin.php'); ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Data Admin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="data_user.php" class="nav-link <?php echo setActive('data_user.php'); ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Data Orang Tua</p>
                    </a>
                </li>
                <?php } ?>

                <!-- Menu Data Balita - Bidan & Kader -->
                <?php if ($role == 'Admin' || $role == 'Bidan' || $role == 'Kader') { ?>
                <li class="nav-item">
                    <a href="data_balita.php" class="nav-link <?php echo setActive('data_balita.php'); ?>">
                        <i class="nav-icon fas fa-baby"></i>
                        <p>Data Balita</p>
                    </a>
                </li>
                <?php } ?>

                <?php if ($role == 'Admin' || $role == 'Bidan' || $role == 'Kader') { ?>
                <li class="nav-item">
                    <a href="data_kms.php" class="nav-link <?php echo setActive('data_kms.php'); ?>">
                        <i class="nav-icon fas fa-info"></i>
                        <p>Kartu Menuju Sehat</p>
                    </a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="data_kms.php" class="nav-link <?php echo setActive('data_kms.php'); ?>">
                        <i class="nav-icon fas fa-info"></i>
                        <p>Obat</p>
                    </a>
                </li>

                <!-- Menu Generate Laporan - Hanya untuk Admin & Bidan -->
                <?php if ($role == 'Admin' || $role == 'Bidan') { ?>
                <li class="nav-header">MAIN MORE</li>
                <li class="nav-item">
                    <a href="generate_laporan.php" class="nav-link <?php echo setActive('generate_laporan.php'); ?>">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Generate Laporan</p>
                    </a>
                </li>
                <?php } ?>

                <!-- Menu Logout -->
                <li class="nav-item">
                    <a href="logout.php" class="nav-link" data-toggle="modal" data-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>
