<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$users = mysqli_query($conn, "SELECT * FROM users");
$bukus = mysqli_query($conn, "SELECT * FROM buku");

if (isset($_POST['submit'])) {
  $kode_transaksi = get_code_transaksi();
  $id_admin = $_SESSION['id_admin'];
  $id_user = mysqli_escape_string($conn, $_POST['peminjam']);
  $id_buku = mysqli_escape_string($conn, $_POST['buku']);
  $tanggal_kembali = mysqli_escape_string($conn, $_POST['tgl_kembali']);
  $status = 'Pinjam';
  $data_log_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = '$id_buku'"));
  $data_log_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'"));

  if (empty($id_user) || empty($id_buku)) {
    echo "<script>alert('Kolom Inputan Data Tidak Boleh Kosong!');</script>";
  } else {
    $query = mysqli_query($conn, "INSERT INTO transaksi VALUES (NULL, '$kode_transaksi', '$id_admin', '$id_user', '$id_buku', NOW(), '$tanggal_kembali', '$status')");
    if ($query) {
      $kurangi_buku = mysqli_query($conn, "UPDATE buku SET jumlah_buku = jumlah_buku - 1 WHERE id_buku = '$id_buku'");
      $j_log_buku = $data_log_buku['judul_buku'];
      $k_log_buku = $data_log_buku['kode_buku'];
      $n_log_user = $data_log_user['nama_user'];
      $i_log_user = $data_log_user['ni_user'];
      tambah_log($_SESSION['id_admin'], "Peminjaman Buku $k_log_buku - $j_log_buku Oleh $i_log_user - $n_log_user");
      if ($kurangi_buku) {
        $_SESSION['sukses'] = true;
        $_SESSION['msg'] = "Data Peminjaman Berhasil Disimpan";
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Data Peminjaman Gagal Disimpan";
      }
    }
    header('location:../Admin/daftar-peminjaman.php');
    exit();
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
              <h1 class="m-0">Tambah Peminjaman</h1>
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
                  <h3 class="card-title">Data Peminjaman</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Nama Peminjam</label>
                      <small class="text-muted">Identitas - Nama - Kelas</small>
                      <select name="peminjam" class="form-control select2bs4" style="width: 100%;">
                        <option value="">Pilih Data Peminjam</option>
                        <?php foreach ($users as $user) : ?>
                        <option value="<?= $user['id_user'] ?>">
                          <?= $user['ni_user'] ?> - <?= $user['nama_user'] ?> - <?= $user['kelas_user'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Nama Buku</label>
                      <small class="text-muted">Kode Buku - Nama Buku - Jumlah Buku</small>
                      <select name="buku" class="form-control select2bs4" style="width: 100%;">
                        <option value="">Pilih Data Buku</option>
                        <?php foreach ($bukus as $buku) : ?>
                        <option <?= ($buku["jumlah_buku"] == 0) ? "disabled='disabled'" : "" ?>
                          value="<?= $buku['id_buku'] ?>"><?= $buku['kode_buku'] ?> - <?= $buku['judul_buku'] ?> -
                          <?= $buku['jumlah_buku'] ?> </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="tgl_pinjam">Tanggal dan Waktu Pinjam</label>
                          <input name="tgl_pinjam" type="date" class="form-control" id="tgl_pinjam" disabled>
                        </div>
                        <div class="col-md-6">
                          <label for="tgl_kembali">Tanggal dan Waktu Kembali</label>
                          <input name="tgl_kembali" type="date" class="form-control" id="tgl_kembali">
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-primary" onclick="konfirmSubmit()">Submit</button>
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
var nBulan = 6;

function formatDate(date) {
  var dd = String(date.getDate()).padStart(2, '0');
  var mm = String(date.getMonth() + 1).padStart(2, '0'); // January is 0!
  var yyyy = date.getFullYear();
  return yyyy + '-' + mm + '-' + dd;
}

function setTanggal() {
  var today = new Date();
  var tgl_pinjam = formatDate(today); // 'YYYY-MM-DD'

  if (document.getElementById('tgl_pinjam')) {
    document.getElementById('tgl_pinjam').value = tgl_pinjam;
  }

  var nextDate = new Date(today);
  nextDate.setMonth(nextDate.getMonth() + nBulan);
  var tgl_kembali = formatDate(nextDate); // 'YYYY-MM-DD'

  if (document.getElementById('tgl_kembali')) {
    document.getElementById('tgl_kembali').value = tgl_kembali;
  }
}

setTimeout(setTanggal, 100);

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('tgl_pinjam').addEventListener('change', function() {
    var selectedDate = new Date(this.value);
    var nextDate = new Date(selectedDate);
    nextDate.setMonth(nextDate.getMonth() + nBulan);
    var tgl_kembali = formatDate(nextDate); // 'YYYY-MM-DD'

    if (document.getElementById('tgl_kembali')) {
      document.getElementById('tgl_kembali').value = tgl_kembali;
    }
  });
});



function konfirmSubmit() {
  var konfirmasi = confirm("Apakah Anda yakin ingin menyimpan data?");
  if (konfirmasi) {
    return true;
  } else {
    return false;
  }
}
</script>