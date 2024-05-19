<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

if (isset($_POST['submit'])) {
  include('../config/naive_bayes.php');
  $dataset = load_dataset($conn);
  $model = train_naive_bayes($dataset);
  $judul = mysqli_escape_string($conn, $_POST['judul']);
  $kat = classify_naive_bayes($model, $judul);
  
  $kode_buku = mysqli_escape_string($conn, get_code_buku(strtolower($kat))[0]);
  $kategori_buku = mysqli_escape_string($conn, get_code_buku(strtolower($kat))[1]);
  $kelas = mysqli_escape_string($conn, $_POST['kelas']);
  $pengarang = mysqli_escape_string($conn, $_POST['pengarang']);
  $thn_terbit = mysqli_escape_string($conn, $_POST['thn_terbit']);
  $penerbit = mysqli_escape_string($conn, $_POST['penerbit']);
  $jumlah_buku = mysqli_escape_string($conn, $_POST['jml_buku']);
  if (empty($judul) || empty($pengarang) || empty($thn_terbit) || empty($penerbit) || empty($jumlah_buku)) {
    echo "<script>alert('Kolom Inputan Data Buku Tidak Boleh Kosong!');</script>";
  } else {

    $query = mysqli_query($conn, "INSERT INTO buku VALUES (NULL, '$kode_buku', '$kategori_buku', '$kelas', '$judul', '$pengarang', '$thn_terbit', '$penerbit', '$jumlah_buku')");

    if ($query) {
      tambah_log($_SESSION['id_admin'], "Menambahkan Buku $kode_buku - $judul");
      $_SESSION['sukses'] = true;
    } else {
      $_SESSION['gagal'] = true;
    }
    header('location:../Admin/daftar-buku.php');
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
              <h1 class="m-0">Tambah Buku</h1>
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
                      <label>Kelas</label>
                      <select class="form-control" name="kelas">
                        <option value="">Tidak Ada Kelas</option>
                        <option>X</option>
                        <option>XI</option>
                        <option>XII</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="judul">Judul</label>
                      <input type="text" name="judul" class="form-control" id="judul" placeholder="Judul Buku">
                    </div>
                    <div class="form-group">
                      <label for="pengarang">Pengarang</label>
                      <input type="text" name="pengarang" class="form-control" id="pengarang"
                        placeholder="Pengarang Buku">
                    </div>
                    <div class="form-group">
                      <label for="tahun_terbit">Tahun Terbit</label>
                      <input type="number" name="thn_terbit" class="form-control" id="tahun_terbit"
                        placeholder="Tahun Terbit Buku">
                    </div>
                    <div class="form-group">
                      <label for="penerbit">Penerbit</label>
                      <input type="text" name="penerbit" class="form-control" id="penerbit" placeholder="Penerbit Buku">
                    </div>
                    <div class="form-group">
                      <label for="jumlah">Jumlah Buku</label>
                      <input type="number" name="jml_buku" class="form-control" id="jumlah" placeholder="Jumlah Buku">
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