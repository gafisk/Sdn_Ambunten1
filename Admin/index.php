<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$total_saldo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kelas.*, siswa.*, mutasi.total_saldo, ( SELECT SUM(m2.total_saldo) FROM mutasi m2 WHERE (m2.id_siswa, m2.id_mutasi) IN ( SELECT id_siswa, MAX(id_mutasi) FROM mutasi GROUP BY id_siswa ) ) AS total_saldo_sum FROM mutasi INNER JOIN siswa ON mutasi.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas WHERE (mutasi.id_siswa, mutasi.id_mutasi) IN (SELECT id_siswa, MAX(id_mutasi) FROM mutasi GROUP BY id_siswa);"));
$total_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id_guru) as total_guru FROM guru"));
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id_siswa) as total_siswa FROM siswa"));
$data_log = mysqli_query($conn, "SELECT * FROM log ORDER BY id_log DESC");


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
    <!-- /.navbar -->

    <?php include('layouts/main-user.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
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
            <div class="col-lg-6 col-12">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>Jumlah Duit</h3>
                  <h3> Rp. <?= number_format($total_saldo['total_saldo_sum'], 0, ',', '.') ?></h3>
                  </h3>
                </div>
                <div class="icon">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>Guru</h3>
                  <h3><?=$total_guru['total_guru']?></h3>
                </div>
                <div class="icon">
                  <i class="fas fa-user-graduate"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>Siswa</h3>
                  <h3><?=$total_siswa['total_siswa']?></h3>
                </div>
                <div class="icon">
                  <i class="fas fa-user"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-12 connectedSortable">
              <!-- DIRECT CHAT -->
              <div class="card direct-chat direct-chat-primary">
                <div class="card-header">
                  <h3 class="card-title">Log Aktivitas WEB</h3>

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
                    <?php foreach($data_log as $log) : ?>
                    <div class="direct-chat-msg">
                      <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left"><?=$log['waktu']?></span>
                      </div>
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        <?=$log['keterangan']?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <?php endforeach;?>
                  </div>
                  <!-- /.direct-chat-pane -->
                </div>
                <!-- /.card-footer-->
              </div>
              <!-- /.card -->
            </section>
          </div>
          <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include('layouts/footer.php') ?>
</body>

</html>