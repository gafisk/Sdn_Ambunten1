<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_guru']) || empty($_SESSION['id_guru'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="../login.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$id_guru = $_SESSION['id_guru'];
$data_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru WHERE id_guru = '$id_guru'"));
$kelas_guru = $data_guru['id_kelas'];
$data_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE id_kelas = '$kelas_guru'");

if(isset($_POST['submit'])){
  $siswa = mysqli_escape_string($conn, $_POST['siswa']);
  $tanggal = mysqli_escape_string($conn, $_POST['tanggal']);
  $jumlah = mysqli_escape_string($conn, $_POST['jumlah']);
  $catatan = mysqli_escape_string($conn, $_POST['catatan']);
  if (empty($siswa) || empty($tanggal) || empty($jumlah) || empty($catatan)) {
    echo "<script>alert('Kolom Inputan Data Tidak Boleh Kosong!');</script>";
  } else {
    $query = mysqli_query($conn, "INSERT INTO transaksi_penyimpanan VALUES (NULL, '$id_guru', '$siswa', '$tanggal', '$jumlah', '$catatan')");
    if ($query) {
      $saldo_terkini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT total_saldo FROM mutasi WHERE id_siswa = '$siswa' ORDER BY id_mutasi DESC LIMIT 1"));
      $saldo_terkini_value = $saldo_terkini ? (int)$saldo_terkini['total_saldo'] : 0;
      $saldo_baru = $saldo_terkini_value + (int)$jumlah;
      $insert_mutasi = mysqli_query($conn, "INSERT INTO mutasi VALUES (NULL, '$siswa', NOW(), 'Penyimpanan', '$jumlah', '$saldo_baru', '$catatan')");
      if ($insert_mutasi) {
        tambah_log(mysqli_fetch_assoc($data_siswa)['nama_siswa'] . " Menabung");
        $_SESSION['sukses'] = true;
        $_SESSION['msg'] = "Data Berhasil Disimpan";
      }else{
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Data Gagal Disimpan";
      }
    } else {
      echo "<script>alert('Gagal Menambahkan Data!');</script>";
    }
  }
  header('location:tambah-penyimpanan.php');
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
              <h1 class="m-0">Tambah Penyimpanan Tambungan</h1>
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
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Data Penyimpanan</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="siswa">Pilih Siswa</label>
                      <select id="siswa" name="siswa" class="form-control select2bs4">
                        <option value="">-Pilih Siswa-</option>
                        <?php foreach($data_siswa as $data): ?>
                        <option value="<?=$data['id_siswa']?>"><?=$data['nisn_siswa']?> - <?=$data['nama_siswa']?>
                        </option>
                        <?php endforeach;?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Tanggal</label>
                      <input type="date" name="tanggal" class="form-control" id="tanggal">
                    </div>
                    <div class="form-group">
                      <label>Jumlah Uang</label>
                      <input type="number" name="jumlah" class="form-control" id="jumlah">
                    </div>
                    <div class="form-group">
                      <label>Catatan</label>
                      <textarea name="catatan" id="catatan" class="form-control" rows="4"></textarea>
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-primary"
                      onclick="return konfirmSubmit()">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
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
  var konfirmasi = confirm("Apakah Anda yakin ingin menyimpan Uang?");
  if (konfirmasi) {
    return true;
  } else {
    return false;
  }
}
</script>