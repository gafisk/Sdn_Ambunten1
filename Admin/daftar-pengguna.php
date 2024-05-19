<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}


$penggunas = mysqli_query($conn, 'SELECT * FROM users');

if (isset($_GET['reset'])) {
  $id_user = mysqli_escape_string($conn, $_GET['reset']);
  $datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users where id_user = '$id_user'"));
  $ni_user = $datas['ni_user'];
  $nama_user = $datas['nama_user'];
  tambah_log($_SESSION['id_admin'], "Mereset Password $ni_user - $nama_user");
  $query = mysqli_query($conn, "UPDATE users SET password = '$ni_user' where id_user = '$id_user'");
  if ($query) {
    $_SESSION['sukses'] = true;
    $_SESSION['msg'] = "Password $nama_user Berhasil di Reset Menjadi No Identitas";
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Password $nama_user Gagal di Reset";
  }
  header('location:../Admin/daftar-pengguna.php');
  exit();
}
if (isset($_GET['hapus'])) {
  $id_user = mysqli_escape_string($conn, $_GET['hapus']);
  $datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'"));
  $ni_user = $datas['ni_user'];
  $nama_user = $datas['nama_user'];
  $role_user = $datas['role_user'];
  tambah_log($_SESSION['id_admin'], "Menghapus $role_user  $ni_user - $nama_user");
  $query = mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id_user'");
  if ($query) {
    $_SESSION['sukses'] = true;
    $_SESSION['msg'] = "Data $nama_user Berhasil Dihapus";
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Data $nama_user Gagal Dihapus";
  }

  header('location:../Admin/daftar-pengguna.php');
  exit();
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
              <h1 class="m-0">Daftar Pengguna</h1>
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
          <?php if (isset($_SESSION['sukses']) && $_SESSION['sukses']) : ?>
            <div class="alert alert-success alert-dismissible fade show" id="myAlert" role="alert">
              <strong>Sukses</strong> <?= $_SESSION['msg'] ?>.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            unset($_SESSION['sukses']);
            unset($_SESSION['msg']);
          endif; ?>

          <?php if (isset($_SESSION['gagal']) && $_SESSION['gagal']) : ?>
            <div class="alert alert-danger alert-dismissible fade show" id="myAlert" role="alert">
              <strong>Gagal</strong> <?= $_SESSION['msg'] ?>.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            unset($_SESSION['gagal']);
            unset($_SESSION['msg']);
          endif; ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Pengguna Perpustakaan SMK Negeri 2 Bangkalan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Identitas</th>
                        <th>Status</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($penggunas as $pengguna) : ?>
                        <tr>
                          <td><?= $pengguna['ni_user'] ?></td>
                          <td><?= $pengguna['role_user'] ?></td>
                          <td><?= $pengguna['nama_user'] ?></td>
                          <td><?= $pengguna['jk_user'] ?></td>
                          <td><?= $pengguna['kelas_user'] ?></td>
                          <td><?= $pengguna['alamat_user'] ?></td>
                          <td><?= $pengguna['telp_user'] ?></td>
                          <td style="width: 100px;">
                            <a type="button" href="edit-pengguna.php?id=<?= $pengguna['id_user'] ?>" class=" btn btn-primary btn-sm d-inlinse"><i class='fas fa-pencil-alt'></i></a>
                            <a href="?hapus=<?= $pengguna['id_user'] ?>" onclick="return confirm(`Anda Yakin Ingin Menghapus Data <?= $pengguna['nama_user'] ?>`)" type="button" class="btn btn-danger btn-sm d-inline"><i class='fas fa-trash-alt'></i></a>
                            <a href="?reset=<?= $pengguna['id_user'] ?>" onclick="return confirm(`Anda Yakin Ingin Mereset Password <?= $pengguna['nama_user'] ?>`)" type="button" class="btn btn-warning btn-sm d-inline"><i class='fas fa-key'></i></a>
                          </td>
                        </tr>
                      <?php
                      endforeach ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Identitas</th>
                        <th>Status</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Aksi</th>
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
<script>
  // Ambil elemen alert
  var alert = document.getElementById('myAlert');

  // Tutup alert setelah 3 detik
  setTimeout(function() {
    alert.style.display = 'none';
  }, 10000);
</script>