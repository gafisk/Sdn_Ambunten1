<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

if (isset($_GET['id'])) {
  $id_user = mysqli_escape_string($conn, $_GET['id']);
  $datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'"));
  if (isset($_POST['submit'])) {
    $nama_user = mysqli_escape_string($conn, $_POST['nama']);
    $jk_user = mysqli_escape_string($conn, $_POST['jk']);
    $kelas_user = mysqli_escape_string($conn, $_POST['kelas']);
    $alamat_user = mysqli_escape_string($conn, $_POST['alamat']);
    $telp_user = mysqli_escape_string($conn, $_POST['notelp']);

    if (empty($nama_user) || empty($jk_user) || empty($telp_user) || empty($alamat_user)) {
      echo "<script>alert('Kolom Inputan Data Tidak Boleh Kosong!');</script>";
    } else {
      $query = mysqli_query($conn, "UPDATE users SET  nama_user = '$nama_user', jk_user = '$jk_user', kelas_user = '$kelas_user', alamat_user = '$alamat_user', telp_user = '$telp_user' WHERE id_user='$id_user'");
      if ($query) {
        tambah_log($_SESSION['id_admin'], "Mengedit $role_user $ni_user - $nama_user");
        $_SESSION['sukses'] = true;
        $_SESSION['msg'] = "Data $nama_user Berhasil Diedit";
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Data $nama_user Gagal Diedit";
      }
      header('location:../Admin/daftar-pengguna.php');
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
              <h1 class="m-0">Edit Pengguna</h1>
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
                  <h3 class="card-title">Edit Data Pengguna</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="role">Status</label>
                      <input type="text" readonly name="role" class="form-control" id="ni"
                        value="<?= $datas['role_user'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="ni">Nomor Identitas</label>
                      <input type="text" readonly name="identitas" class="form-control" id="ni"
                        placeholder="Identitas Pengguna" value="<?= $datas['ni_user'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="nama">Nama</label>
                      <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Pengguna"
                        value="<?= $datas['nama_user'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="jk">Jenis Kelamin</label>
                      <select id="jk" name="jk" class="form-control">
                        <option <?= ($datas['jk_user'] == 'Laki - laki') ? 'selected' : '' ?>>Laki - laki</option>
                        <option <?= ($datas['jk_user'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="kelas">Kelas</label>
                      <select id="kelas" name="kelas" class="form-control">
                        <option <?= ($datas['role_user'] == '') ? 'selected' : '' ?> value="">Tidak Ada Kelas</option>
                        <option <?= ($datas['kelas_user'] == 'X') ? 'selected' : '' ?>>X</option>
                        <option <?= ($datas['kelas_user'] == 'XI') ? 'selected' : '' ?>>XI</option>
                        <option <?= ($datas['kelas_user'] == 'XII') ? 'selected' : '' ?>>XII</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Alamat</label>
                      <textarea class="form-control" name="alamat" rows="3"
                        placeholder="Alamat Pengguna"><?= $datas['alamat_user'] ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="no_telp">Nomor Telepon</label>
                      <input type="text" name="notelp" class="form-control" id="no_telp"
                        placeholder="Nomor Telepon Pengguna" value="<?= $datas['telp_user'] ?>">
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
  var konfirmasi = confirm("Apakah Anda yakin ingin mengedit data?");
  if (konfirmasi) {
    return true;
  } else {
    return false;
  }
}
</script>