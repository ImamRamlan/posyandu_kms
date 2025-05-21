   <!-- Navbar -->
   <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
       <div class="container">
           <a href="index.php" class="navbar-brand">
               <img src="admin/assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
               <span class="brand-text font-weight-light">Posyandu Melati</span>
           </a>

           <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
           </button>

           <div class="collapse navbar-collapse order-3" id="navbarCollapse">
               <!-- Left navbar links -->
               <ul class="navbar-nav">
               <li class="nav-item">
                       <a href="informasi_balita.php" class="nav-link">Informasi Balita</a>
                   </li>
                   <li class="nav-item">
                       <a href="kartu_menuju_sehat.php" class="nav-link">Kartu Menuju Sehat</a>
                   </li>
                   <li class="nav-item">
                       <a href="daftar_imunisasi.php" class="nav-link">Daftar Imunisasi</a>
                   </li>
               </ul>
           </div>

           <!-- Right navbar links -->
           <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
               <li class="nav-item dropdown">
                   <a class="nav-link" href="logout_pengguna.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                       <i class="fas fa-sign-out-alt"></i> Keluar
                   </a>
               </li>
           </ul>

       </div>
   </nav>
   <!-- /.navbar -->