<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$data_kelas = mysqli_query($conn, "SELECT * FROM kelas");
if (isset($_GET['edit'])){
  $id = mysqli_escape_string($conn, $_GET['edit']);
  $datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa where id_siswa = '$id'"));
  if(isset($_POST['submit'])){
    $nama = mysqli_escape_string($conn, $_POST['nama']);
    $wali = mysqli_escape_string($conn, $_POST['wali']);
    $jk = mysqli_escape_string($conn, $_POST['jk']);
    $kelas = mysqli_escape_string($conn, $_POST['kelas']);
    $alamat = mysqli_escape_string($conn, $_POST['alamat']);
    $notelp = mysqli_escape_string($conn, $_POST['notelp']);
    if (empty($nama) || empty($jk) || empty($wali) || empty($notelp) || empty($kelas) || empty($alamat)) {
      echo "<script>alert('Kolom Inputan Data Tidak Boleh Kosong!');</script>";
    } else {
      $query = mysqli_query($conn, "UPDATE siswa SET nama_siswa='$nama', jk_siswa='$jk', alamat_siswa='$alamat', nama_wali='$wali', no_hp='$notelp', id_kelas='$kelas' WHERE id_siswa='$id'");
      if ($query) {
        tambah_log("Mengedit data siswa $nama");
        $_SESSION['sukses'] = true;
        $_SESSION['msg'] = "Data $nama Berhasil Diedit";
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Data $nama Gagal Diedit";
      }
      header('location:../Admin/daftar-siswa.php');
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
              <h1 class="m-0">Edit Data Siswa</h1>
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
                  <h3 class="card-title">Data Siswa</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="ni">NISN</label>
                      <input type="text" name="nisn" class="form-control" id="ni" placeholder="NISN Siswa" readonly
                        value="<?=$datas['nisn_siswa']?>">
                    </div>
                    <div class="form-group">
                      <label for="nama">Nama</label>
                      <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Siswa"
                        value="<?=$datas['nama_siswa']?>">
                    </div>
                    <div class="form-group">
                      <label for="wali">Wali Murid</label>
                      <input type="text" name="wali" class="form-control" id="wali" placeholder="Nama Wali Murid"
                        value="<?=$datas['nama_wali']?>">
                    </div>
                    <div class="form-group">
                      <label for="jk">Jenis Kelamin</label>
                      <select id="jk" name="jk" class="form-control">
                        <option <?= ($datas['jk_siswa'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                        <option <?= ($datas['jk_siswa'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="kelas">Kelas</label>
                      <select id="kelas" name="kelas" class="form-control">
                        <option value="">Tidak Ada Kelas</option>
                        <?php foreach($data_kelas as $data) : ?>
                        <option <?= ($datas['id_kelas'] == $data['id_kelas']) ? 'selected' : '' ?>
                          value="<?=$data['id_kelas']?>"><?=$data['nama_kelas']?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Alamat</label>
                      <textarea class="form-control" name="alamat" rows="3"
                        placeholder="Alamat Guru"><?=$datas['alamat_siswa']?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="no_telp">Nomor Telepon</label>
                      <input type="text" name="notelp" class="form-control" id="no_telp"
                        placeholder="Nomor Telepon Siswa" value="<?=$datas['no_hp']?>">
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
  var konfirmasi = confirm("Apakah Anda yakin ingin menyimpan data?");
  if (konfirmasi) {
    return true;
  } else {
    return false;
  }
}
</script>