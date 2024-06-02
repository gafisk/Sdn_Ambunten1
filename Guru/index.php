<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_guru']) || empty($_SESSION['id_guru'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="../login.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$id_guru = $_SESSION['id_guru'];
$data_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru WHERE id_guru = '$id_guru'"));
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id_siswa) as total_siswa FROM siswa where id_kelas =".$data_guru['id_kelas']));
$total_penarikan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(COUNT(*), 0) AS total_count FROM transaksi_penarikan WHERE id_guru = $id_guru AND MONTH(tgl_tarik) = MONTH(CURRENT_DATE) AND YEAR(tgl_tarik) = YEAR(CURRENT_DATE);"));
$total_penyimpanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(COUNT(*), 0) AS total_count FROM transaksi_penyimpanan WHERE id_guru = $id_guru AND MONTH(tgl_simpan) = MONTH(CURRENT_DATE) AND YEAR(tgl_simpan) = YEAR(CURRENT_DATE);"));

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
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>Total Penyimpanan</h3>
                  <h3><?=$total_penyimpanan['total_count']?></h3>
                </div>
                <div class="icon">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>Total Penarikan</h3>
                  <h3><?=$total_penarikan['total_count']?></h3>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-12">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>Total Siswa</h3>
                  <h3><?=$total_siswa['total_siswa']?></h3>
                </div>
                <div class="icon">
                  <i class="ion ion-person"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include('layouts/footer.php') ?>
</body>

</html>