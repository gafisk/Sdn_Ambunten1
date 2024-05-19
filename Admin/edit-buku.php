<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

if (isset($_GET['id'])) {
  $id_buku = mysqli_escape_string($conn, $_GET['id']);
  $datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = '$id_buku'"));
  if (isset($_POST['submit'])) {
    $kode_buku = mysqli_escape_string($conn, $_POST['kode_buku']);
    $kategori_buku = mysqli_escape_string($conn, $_POST['kategori_buku']);
    $kelas = mysqli_escape_string($conn, $_POST['kelas']);
    $judul = mysqli_escape_string($conn, $_POST['judul']);
    $pengarang = mysqli_escape_string($conn, $_POST['pengarang']);
    $thn_terbit = mysqli_escape_string($conn, $_POST['thn_terbit']);
    $penerbit = mysqli_escape_string($conn, $_POST['penerbit']);
    $jumlah_buku = mysqli_escape_string($conn, $_POST['jml_buku']);

    if (empty($judul) || empty($pengarang) || empty($thn_terbit) || empty($penerbit) || empty($jumlah_buku)) {
      echo "<script>alert('Kolom Inputan Data Buku Tidak Boleh Kosong!');</script>";
    } else {
      $query = mysqli_query($conn, "UPDATE buku SET kelas_buku = '$kelas', judul_buku = '$judul', pengarang = '$pengarang', tahun_terbit = '$thn_terbit', penerbit = '$penerbit', jumlah_buku = '$jumlah_buku' WHERE id_buku = '$id_buku'");
      if ($query) {
        tambah_log($_SESSION['id_admin'], "Mengedit Buku $kode_buku - $judul");
        $_SESSION['edit'] = true;
      } else {
        $_SESSION['gagal'] = true;
      }
      header('location:../Admin/daftar-buku.php');
    }
  }
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
              <h1 class="m-0">Edit Data Buku</h1>
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
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Data Buku</h3>
                </div>

                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="kode_buku">Kode Buku</label>
                      <input type="text" name="kode_buku" class="form-control" id="kode_buku" value="<?= $datas['kode_buku'] ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="kategori">Kategori Buku</label>
                      <input type="text" name="kategori_buku" class="form-control" id="kategori" value="<?= $datas['kategori_buku'] ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label>Kelas</label>
                      <select class="form-control" name="kelas">
                        <option value="" <?= ($datas['kelas_buku'] == '') ? 'selected' : ''; ?>>Tidak Ada Kelas</option>
                        <option <?= ($datas['kelas_buku'] == 'X') ? 'selected' : ''; ?>>X</option>
                        <option <?= ($datas['kelas_buku'] == 'XI') ? 'selected' : ''; ?>>XI</option>
                        <option <?= ($datas['kelas_buku'] == 'XII') ? 'selected' : ''; ?>>XII</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="judul">Judul</label>
                      <input type="text" name="judul" class="form-control" id="judul" placeholder="Judul Buku" value="<?= $datas['judul_buku'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="pengarang">Pengarang</label>
                      <input type="text" name="pengarang" class="form-control" id="pengarang" placeholder="Pengarang Buku" value="<?= $datas['pengarang'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="tahun_terbit">Tahun Terbit</label>
                      <input type="number" name="thn_terbit" class="form-control" id="tahun_terbit" placeholder="Tahun Terbit Buku" value="<?= $datas['tahun_terbit'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="penerbit">Penerbit</label>
                      <input type="text" name="penerbit" class="form-control" id="penerbit" placeholder="Penerbit Buku" value="<?= $datas['penerbit'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="jumlah">Jumlah Buku</label>
                      <input type="number" name="jml_buku" class="form-control" id="jumlah" placeholder="Jumlah Buku" value="<?= $datas['jumlah_buku'] ?>">
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-primary" onclick="return konfirmSubmit()">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->


              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include('layouts/footer.php') ?>
</body>

</html>
<script>
  function konfirmSubmit() {
    var konfirmasi = confirm("Apakah Anda yakin ingin Mengedit data?");
    if (konfirmasi) {
      return true;
    } else {
      return false;
    }
  }
</script>