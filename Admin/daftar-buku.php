<?php
session_start();
include('../config/conn.php');

$bukus = mysqli_query($conn, "SELECT * FROM buku");

if (isset($_GET['hapus'])) {
  $id_buku = mysqli_escape_string($conn, $_GET['hapus']);
  $data_log_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = '$id_buku'"));
  $kode_buku = $data_log_buku['kode_buku'];
  $judul_buku = $data_log_buku['judul_buku'];
  tambah_log($_SESSION['id_admin'], "Menghapus buku $kode_buku - $judul_buku");
  $query = mysqli_query($conn, "DELETE FROM buku WHERE id_buku = '$id_buku'");
  if ($query) {
    $_SESSION['sukses'] = true;
    $_SESSION['msg'] = "Data Berhasil Dihapus";
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Data Gagal Dihapus";
  }
  header('location: ../Admin/daftar-buku.php');
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
              <h1 class="m-0">Daftar Buku</h1>
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
            <strong>Sukses</strong> Data Berhasil di Simpan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
            unset($_SESSION['sukses']);
          endif; ?>

          <?php if (isset($_SESSION['edit']) && $_SESSION['edit']) : ?>
          <div class="alert alert-success alert-dismissible fade show" id="myAlert" role="alert">
            <strong>Sukses</strong> Data Berhasil di Edit.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
            unset($_SESSION['edit']);
          endif; ?>

          <?php if (isset($_SESSION['gagal']) && $_SESSION['gagal']) : ?>
          <div class="alert alert-danger alert-dismissible fade show" id="myAlert" role="alert">
            <strong>Gagal</strong> Data Gagal di Simpan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
            unset($_SESSION['gagal']);
          endif; ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Buku Perpustakaan SMK Negeri 2 Bangkalan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Kelas</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Penerbit</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($bukus as $buku) :
                      ?>
                      <tr>
                        <td><?= $buku['kode_buku'] ?></td>
                        <td><?= $buku['kategori_buku'] ?></td>
                        <td><?= $buku['kelas_buku'] ?></td>
                        <td><?= $buku['judul_buku'] ?></td>
                        <td><?= $buku['pengarang'] ?></td>
                        <td><?= $buku['tahun_terbit'] ?></td>
                        <td><?= $buku['penerbit'] ?></td>
                        <td><?= $buku['jumlah_buku'] ?></td>
                        <td>
                          <a type="button" href="edit-buku.php?id=<?= $buku['id_buku'] ?>"
                            class="btn btn-primary btn-sm">Edit</a>
                          <a href="?hapus=<?= $buku['id_buku'] ?>" type="button" class="btn btn-danger btn-sm"
                            onclick="return confirm('Anda Yakin Akan Menghapus Data?')">Hapus</a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Kelas</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Penerbit</th>
                        <th>Jumlah</th>
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