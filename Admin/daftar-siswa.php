<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$datas = mysqli_query($conn, "SELECT * FROM siswa INNER JOIN kelas using(id_kelas)");

if(isset($_GET['hapus'])){
  $id_siswa = mysqli_escape_string($conn,$_GET['hapus']);
  $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"));
  $nama_siswa = $data_siswa['nama_siswa'];
  $query = mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = '$id_siswa'");
  tambah_log("Menghapus $nama_siswa dari Data Siswa");
  if ($query) {
    $_SESSION['sukses'] = true;
    $_SESSION['msg'] = "Data $nama_siswa Berhasil Dihapus";
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Data $nama_siswa Gagal Dihapus";
  }

  header('location:../Admin/daftar-siswa.php');
  exit();
}

if(isset($_GET['reset'])){
  $id_siswa = mysqli_escape_string($conn,$_GET['reset']);
  $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'"));
  $nama_siswa = $data_siswa['nama_siswa'];
  $username_siswa  = $data_siswa['username_siswa'];
  $query = mysqli_query($conn, "UPDATE siswa set password_siswa = '$username_siswa' WHERE id_siswa = '$id_siswa'");
  tambah_log("Mereset Password $nama_siswa dari Data Siswa");
  if ($query) {
    $_SESSION['sukses'] = true;
    $_SESSION['msg'] = "Data Password $nama_siswa Berhasil Di Reset";
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Data Password $nama_siswa Gagal Di Reset";
  }

  header('location:../Admin/daftar-siswa.php');
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

    <!-- Main Sidebar Container -->
    <?php include('layouts/main-user.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Daftar Siswa</h1>
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
                  <h3 class="card-title">Data Siswa SDN Ambunten Barat 1</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Wali</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($datas as $data) : ?>
                      <tr>
                        <td><?= $data['nisn_siswa'] ?></td>
                        <td><?= $data['nama_siswa'] ?></td>
                        <td><?= $data['nama_wali'] ?></td>
                        <td><?= $data['alamat_siswa'] ?></td>
                        <td><?= $data['jk_siswa'] ?></td>
                        <td><?= $data['nama_kelas'] ?></td>
                        <td><?= $data['no_hp'] ?></td>
                        <td style="width: 100px;">
                          <a type="button" href="edit-siswa.php?edit=<?=$data['id_siswa']?>"
                            class=" btn btn-primary btn-sm d-inlinse"><i class='fas fa-pencil-alt'></i></a>
                          <a href="?hapus=<?= $data['id_siswa'] ?>"
                            onclick="return confirm(`Anda Yakin Ingin Menghapus Data <?= $data['nama_siswa'] ?>`)"
                            type="button" class="btn btn-danger btn-sm d-inline"><i class='fas fa-trash-alt'></i></a>
                          <a href="?reset=<?= $data['id_siswa'] ?>"
                            onclick="return confirm(`Anda Yakin Ingin Mereset Password <?= $data['nama_siswa'] ?>`)"
                            type="button" class="btn btn-warning btn-sm d-inline"><i class='fas fa-key'></i></a>
                        </td>
                      </tr>
                      <?php
                      endforeach ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Wali</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
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