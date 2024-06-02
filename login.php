<?php
session_start();
include('config/conn.php');
if (isset($_POST['submit'])) {
  $username = mysqli_escape_string($conn, $_POST['username']);
  $password = mysqli_escape_string($conn, $_POST['password']);
  $role = mysqli_escape_string($conn, $_POST['kelas']);
  if (empty($username) || empty($password) || empty($role)) {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Username, Password atau Jenis Tidak Boleh Kosong";
    header('location:login.php');
    exit();
  } else {
    if ($role == 'guru'){
      $check_users = mysqli_query($conn, "SELECT * FROM guru WHERE username_guru = '$username'");
      if (mysqli_num_rows($check_users) > 0) {
        $query = mysqli_query($conn, "SELECT * FROM guru WHERE username_guru = '$username' AND password_guru = '$password'");
        if (mysqli_num_rows($query) > 0) {
          $row = mysqli_fetch_assoc($query);
          $_SESSION['id_guru'] = $row['id_guru'];
          $_SESSION['nama_guru'] = $row['nama_guru'];
          tambah_log($row['nama_guru'] . "Login");
          echo '<script>alert("Anda Berhasil Login. Redirecting..."); window.location.href="Guru/index.php";</script>';
          exit();
        } else {
          $_SESSION['gagal'] = true;
          $_SESSION['msg'] = "Password Salah";
          header('location:login.php');
          exit();
        }
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Identitas Guru Salah!!!";
        header('location:login.php');
        exit();
      }
    }else{
      $check_users = mysqli_query($conn, "SELECT * FROM siswa WHERE username_siswa = '$username'");
      if (mysqli_num_rows($check_users) > 0) {
        $query = mysqli_query($conn, "SELECT * FROM siswa WHERE username_siswa = '$username' AND password_siswa = '$password'");
        if (mysqli_num_rows($query) > 0) {
          $row = mysqli_fetch_assoc($query);
          $_SESSION['id_siswa'] = $row['id_siswa'];
          $_SESSION['nama_siswa'] = $row['nama_siswa'];
          tambah_log($row['nama_siswa'] . "Login");
          echo '<script>alert("Anda Berhasil Login. Redirecting..."); window.location.href="Siswa/index.php";</script>';
          exit();
        } else {
          $_SESSION['gagal'] = true;
          $_SESSION['msg'] = "Password Salah";
          header('location:login.php');
          exit();
        }
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Identitas Siswa Salah!!!";
        header('location:login.php');
        exit();
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Sistem</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="Assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="Assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="Assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-heade  r text-center">
        <a href="login.php" class="h1">BANK SDN<br><b>Ambunten</b> Barat 1</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Login Untuk Memulai Aplikasi</p>
        <?php if (isset($_SESSION['gagal']) && $_SESSION['gagal']) : ?>
        <div class="alert alert-danger alert-dismissible fade show" id="myAlert" role="alert">
          <strong>Gagal Login</strong> <?= $_SESSION['msg'] ?> .
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php
          unset($_SESSION['gagal']);
          unset($_SESSION['msg']);
        endif; ?>
        <form action="" method="POST">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username..." name="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user-alt"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password..." name="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <select name="kelas" class="form-control" id="kelas">
              <option value="">--Pilih Jenis--</option>
              <option value="guru">Guru</option>
              <option value="siswa">Siswa</option>
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user-lock"></span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <a href="Admin/login_admin.php">Login disini untuk admin..</a>
          <div class="col-12 mt-2">
            <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
          </div>
          <!-- /.col -->
      </div>
      </form>
      <!-- <p class="mb-2 ml-2">
        <a href="index.php" class="text-center">Kembali Ke Halaman Depan</a>
      </p> -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="Assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="Assets/dist/js/adminlte.min.js"></script>
</body>

</html>