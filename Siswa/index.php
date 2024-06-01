<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_siswa']) || empty($_SESSION['id_siswa'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="../login.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}
$id_siswa = $_SESSION['id_siswa'];
$data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
$mutasi = mysqli_query($conn, "SELECT * FROM mutasi where id_siswa='$id_siswa' ORDER BY id_mutasi DESC");
$saldo_query = "SELECT * FROM mutasi WHERE id_siswa='$id_siswa' ORDER BY id_mutasi DESC LIMIT 1";
$saldo_result = mysqli_query($conn, $saldo_query);

if ($saldo_result && mysqli_num_rows($saldo_result) > 0) {
  $saldo = mysqli_fetch_assoc($saldo_result);
  $total_saldo = $saldo['total_saldo'];
} else {
  $total_saldo = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('layouts/head.php') ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="../Assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
        width="60" />
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>

    <?php include('layouts/main-user.php'); ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-12 col-12">
              <!-- small box -->
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3>Jumlah Saldo</h3>
                  <h3>Rp. <?= number_format($total_saldo, 0, ',', '.') ?></h3>
                </div>
                <div class="icon">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <section class="col-lg-12">
              <div class="card direct-chat direct-chat-primary">
                <div class="card-header">
                  <h3 class="card-title">Mutasi</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                    <?php foreach ($mutasi as $data_mutasi) :?>
                    <div class="direct-chat-msg">
                      <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left"><?=$data_mutasi['jenis_mutasi']?></span>
                        <span class="direct-chat-timestamp float-right"><?=$data_mutasi['tgl_mutasi']?></span>
                      </div>
                      <div class="direct-chat-text">
                        <?php
                          if($data_mutasi['jenis_mutasi']=='Penyimpanan'){
                            echo "Uang Masuk Sebesar Rp. ".number_format($data_mutasi['jumlah'], 0, ',', '.');  
                          }else{
                            echo "Uang Keluar Sebesar Rp. ".number_format($data_mutasi['jumlah'], 0, ',', '.'); 
                          }                       
                        ?>
                      </div>
                    </div>
                    <?php endforeach;?>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include('layouts/footer.php') ?>
</body>

</html>