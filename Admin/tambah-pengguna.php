<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

if (isset($_POST['submit'])) {

  $ni_user = mysqli_escape_string($conn, $_POST['identitas']);
  $nama_user = mysqli_escape_string($conn, $_POST['nama']);
  $jk_user = mysqli_escape_string($conn, $_POST['jk']);
  $kelas_user = mysqli_escape_string($conn, $_POST['kelas']);
  $alamat_user = mysqli_escape_string($conn, $_POST['alamat']);
  $telp_user = mysqli_escape_string($conn, $_POST['notelp']);
  $username = mysqli_escape_string($conn, $_POST['identitas']);
  $password = mysqli_escape_string($conn, $_POST['identitas']);
  $role_user = mysqli_escape_string($conn, $_POST['status']);
  if (empty($ni_user) || empty($nama_user) || empty($jk_user) || empty($alamat_user) || empty($telp_user) || empty($username) || empty($password) || empty($role_user)) {
    echo "<script>alert('Kolom Inputan Data Tidak Boleh Kosong!');</script>";
  } else {
    $query = mysqli_query($conn, "INSERT INTO users VALUES (NULL, '$ni_user', '$nama_user', '$jk_user', '$kelas_user', '$alamat_user', '$telp_user', '$username', '$password', '$role_user')");
    if ($query) {
      tambah_log($_SESSION['id_admin'], "Menambahkan $role_user $ni_user - $nama_user");
      $_SESSION['sukses'] = true;
      $_SESSION['msg'] = "Data Berhasil di Tambahkan ke Database";
    } else {
      $_SESSION['gagal'] = true;
      $_SESSION['msg'] = "Data Gagal di Tambahkan ke Database";
    }
    header('location:../Admin/daftar-pengguna.php');
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
              <h1 class="m-0">Tambah Pengguna</h1>
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
                  <h3 class="card-title">Data Pengguna</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="role">Status</label>
                      <select id="role" name="status" class="form-control">
                        <option>Guru</option>
                        <option>Siswa</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="ni">Nomor Identitas</label>
                      <input type="text" name="identitas" class="form-control" id="ni" placeholder="Identitas Pengguna">
                    </div>
                    <div class="form-group">
                      <label for="nama">Nama</label>
                      <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Pengguna">
                    </div>
                    <div class="form-group">
                      <label for="jk">Jenis Kelamin</label>
                      <select id="jk" name="jk" class="form-control">
                        <option>Laki - laki</option>
                        <option>Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="kelas">Kelas</label>
                      <select id="kelas" name="kelas" class="form-control">
                        <option value="">Tidak Ada Kelas</option>
                        <option>X</option>
                        <option>XI</option>
                        <option>XII</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Alamat</label>
                      <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat Pengguna"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="no_telp">Nomor Telepon</label>
                      <input type="text" name="notelp" class="form-control" id="no_telp" placeholder="Nomor Telepon Pengguna">
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
    var konfirmasi = confirm("Apakah Anda yakin ingin menyimpan data?");
    if (konfirmasi) {
      return true;
    } else {
      return false;
    }
  }
</script>