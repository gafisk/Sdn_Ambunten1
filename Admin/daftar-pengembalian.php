<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}
$datas = mysqli_query($conn, "SELECT pengembalian.*, transaksi.*, CASE WHEN pengembalian.tgl_serah > transaksi.tanggal_kembali THEN DATEDIFF(pengembalian.tgl_serah, transaksi.tanggal_kembali) ELSE 0 END AS hari_telat FROM pengembalian INNER JOIN transaksi ON pengembalian.id_transaksi = transaksi.id_transaksi INNER JOIN buku ON transaksi.id_buku = buku.id_buku;");

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
      <img class="animation__shake" src="../Assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" />
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

    <!-- Main Sidebar Container -->
    <?php include('layouts/main-user.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Daftar Pengembalian</h1>
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
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Pengembalian Perpustakaan SMK Negeri 2 Bangkalan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Kode Peminjaman</th>
                        <th>Nama Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Telat</th>
                        <th style="width: 80px;">Denda</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($datas as $data) : ?>
                        <tr>
                          <td><?= $data['kode_transaksi'] ?></td>
                          <td><?= $data['judul_buku'] ?></td>
                          <td><?= $data['nama_user'] ?></td>
                          <td><?= $data['tanggal_pinjam'] ?></td>
                          <td><?= $data['tanggal_kembali'] ?></td>
                          <td><?= $data['tgl_serah'] ?></td>
                          <td><?= $data['hari_telat'] ?> Hari </td>
                          <td>Rp. <?= $data['denda'] ?></td>
                          <td>
                            <button type="button" class="btn btn-danger btn-sm">Hapus</button>
                          </td>
                        <?php endforeach ?>
                        </tr>
                        </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>

    <?php include('layouts/footer.php'); ?>
</body>

</html>