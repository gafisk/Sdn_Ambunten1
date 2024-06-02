<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_guru']) || empty($_SESSION['id_guru'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="../login.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

// Periksa apakah form telah disubmit
if (isset($_POST['submit'])) {
  if (!empty($_POST['password_old']) && !empty($_POST['password_new'])) {
    $password_lama = mysqli_real_escape_string($conn, $_POST['password_old']);
    $password_baru = mysqli_real_escape_string($conn, $_POST['password_new']);
    $id_guru = $_SESSION['id_guru'];
    $query_select = "SELECT password_guru FROM guru WHERE id_guru = '$id_guru'";
    $result = mysqli_query($conn, $query_select);
    if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $password_database = $row['password_guru'];
      if ($password_lama == $password_database) {
        $query_update = "UPDATE guru SET password_guru = '$password_baru' WHERE id_guru = '$id_guru'";
        $update_result = mysqli_query($conn, $query_update);
        if ($update_result) {
          $_SESSION['sukses'] = true;
          $_SESSION['msg'] = "Password Berhasil diperbarui";
        } else {
          $_SESSION['gagal'] = true;
          $_SESSION['msg'] = "Gagal memperbarui password";
        }  
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Password lama yang Anda masukkan tidak sesuai";
      }
    } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Gagal mengambil data pengguna";
    }
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Pastikan Anda mengisi kedua kolom password";
  }
}

?>
<!DOCTYPE html>
<html lang="id">

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
              <h1 class="m-0">Ganti Password</h1>
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
              <div class="card p-3">
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="password_old" class="form-label">Password Lama</label>
                    <input type="text" name="password_old" class="form-control" id="password_old">
                  </div>
                  <div class="mb-3">
                    <label for="password_baru" class="form-label">Password</label>
                    <input type="password" name="password_new" class="form-control" id="password_baru">
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary"
                    onclick="return confirm('Anda Yakin Ingin Mengganti Password?')">Ganti Password</button>
                </form>
              </div>
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