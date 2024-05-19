<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}


$datas = mysqli_query($conn, "SELECT transaksi.*, users.*, buku.*, (SELECT denda FROM denda) AS denda_per_hari, CASE WHEN CURDATE() > transaksi.tanggal_kembali THEN DATEDIFF(CURDATE(), transaksi.tanggal_kembali) ELSE 0 END AS telat_hari, CASE WHEN CURDATE() > transaksi.tanggal_kembali THEN DATEDIFF(CURDATE(), transaksi.tanggal_kembali) * (SELECT denda FROM denda) ELSE 0 END AS total_denda FROM transaksi, users, buku WHERE transaksi.id_users = users.id_user AND transaksi.id_buku = buku.id_buku AND transaksi.status = 'Pinjam'");

if (isset($_GET['kembali'])) {
  $id_transaksi = mysqli_escape_string($conn, $_GET['kembali']);
  $data_ket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT transaksi.*, buku.*, users.* FROM transaksi INNER JOIN buku ON transaksi.id_buku = buku.id_buku INNER JOIN users ON transaksi.id_users = users.id_user WHERE id_transaksi = '$id_transaksi'"));
  $update_transaksi = mysqli_query($conn, "UPDATE transaksi SET status = 'Kembali' WHERE id_transaksi = '$id_transaksi'");
  if ($update_transaksi) {
    $id_buku = $data_ket['id_buku'];
    $update_buku = mysqli_query($conn, "UPDATE buku SET jumlah_buku = jumlah_buku + 1 WHERE id_buku = '$id_buku'");
    if ($update_buku) {
      $data_denda = mysqli_fetch_assoc(mysqli_query($conn, "SELECT transaksi.id_transaksi, transaksi.tanggal_kembali, CASE WHEN CURDATE() > transaksi.tanggal_kembali THEN (DATEDIFF(CURDATE(), transaksi.tanggal_kembali) * denda.denda) ELSE 0 END AS total_denda FROM transaksi, denda WHERE transaksi.id_transaksi = '$id_transaksi'"));
      $denda = $data_denda['total_denda'];
      $judul_buku = $data_ket['judul_buku'];
      $nama_user = $data_ket['nama_user'];
      $insert_pengembalian = mysqli_query($conn, "INSERT INTO pengembalian VALUES (NULL,'$id_transaksi','$judul_buku','$nama_user',NOW(),'$denda')");
      if ($insert_pengembalian) {
        $n_log_buku = $data_ket['judul_buku'];
        $k_log_buku = $data_ket['kode_buku'];
        $n_log_user = $data_ket['nama_user'];
        $i_log_user = $data_ket['ni_user'];
        tambah_log($_SESSION['id_admin'], "$i_log_user - $n_log_user Mengembalikan Buku $k_log_buku - $n_log_buku");
        $_SESSION['sukses'] = true;
        $_SESSION['msg'] = "Buku Sudah Dikembalikan";
      } else {
        $_SESSION['gagal'] = true;
        $_SESSION['msg'] = "Data Gagal Ditambahkan";
      }
      header('location:../Admin/daftar-peminjaman.php');
      exit();
    } else {
      $_SESSION['gagal'] = true;
      $_SESSION['msg'] = "Data Gagal Ditambahkan";
      header('location:../Admin/daftar-peminjaman.php');
      exit();
    }
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Data Gagal Ditambahkan";
    header('location:../Admin/daftar-peminjaman.php');
    exit();
  }
}

if (isset($_GET['hapus'])) {
  $id_transaksi = mysqli_escape_string($conn, $_GET['hapus']);
  $datas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi INNER JOIN buku ON transaksi.id_buku = buku.id_buku INNER JOIN users ON transaksi.id_users = users.id_user WHERE id_transaksi = '$id_transaksi' AND status = 'Pinjam'"));
  $id_buku = $datas['id_buku'];
  $update_buku = mysqli_query($conn, "UPDATE buku SET jumlah_buku = jumlah_buku + 1 WHERE id_buku = '$id_buku'");
  if ($update_buku) {
    $i_log_user = $datas['ni_user'];
    $n_log_user = $datas['nama_user'];
    $k_log_buku = $datas['kode_buku'];
    $n_log_buku = $datas['judul_buku'];
    tambah_log($_SESSION['id_admin'], "Menghapus Data Peminjaman Oleh $i_log_user - $n_log_user Untuk $k_log_buku - $n_log_buku");
    $hapus_data = mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id_transaksi'");
    if ($hapus_data) {
      $_SESSION['sukses'] = true;
      $_SESSION['msg'] = "Data Berhasil Dihapus";
    } else {
      $_SESSION['gagal'] = true;
      $_SESSION['msg'] = "Data Gagal Dihapus";
    }
    header('location:../Admin/daftar-peminjaman.php');
    exit();
  } else {
    $_SESSION['gagal'] = true;
    $_SESSION['msg'] = "Data Gagal Dihapus";
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
              <h1 class="m-0">Daftar Peminjaman</h1>
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
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Peminjaman Perpustakaan SMK Negeri 2 Bangkalan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Kode Peminjaman</th>
                        <th>Nama Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($datas as $data) : ?>
                      <tr>
                        <td><?= $data['kode_transaksi'] ?></td>
                        <td><?= $data['judul_buku'] ?></td>
                        <td><?= $data['nama_user'] ?></td>
                        <td><?= $data['tanggal_pinjam'] ?></td>
                        <td><?= $data['tanggal_kembali'] ?></td>
                        <td>
                          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#data<?= $data['id_transaksi'] ?>">
                            Pengembalian
                          </button>
                          <div class="modal fade" id="data<?= $data['id_transaksi'] ?>" data-backdrop="static"
                            data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="staticBackdropLabel">Kode Peminjaman
                                    <?= $data['kode_transaksi'] ?></h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <!-- Tabel di dalam modal -->
                                  <table class="table">
                                    <tr>
                                      <td colspan="2" style="text-align: center;"> <strong> Identitas Peminjam</strong>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><strong>Nama</strong></td>
                                      <td><?= $data['nama_user'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>No Identitas</strong></td>
                                      <td><?= $data['ni_user'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>Kelas</strong></td>
                                      <td><?= $data['kelas_user'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>No Telp</strong></td>
                                      <td><?= $data['telp_user'] ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" style="text-align: center;"><strong>Keterangan Buku</strong></td>
                                    </tr>
                                    <tr>
                                      <td><strong>Nama Buku</strong></td>
                                      <td><?= $data['judul_buku'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>Kode Buku</strong></td>
                                      <td><?= $data['kode_buku'] ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" style="text-align: center;"><strong>Keterangan Transaksi</strong>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><strong>Kode Transaksi</strong></td>
                                      <td><?= $data['kode_transaksi'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>Telat</strong></td>
                                      <td><?= $data['telat_hari'] ?> Hari</td>
                                    </tr>
                                    <tr>
                                      <td><strong>Denda</strong></td>
                                      <td>Rp. <?= $data['total_denda'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>Tanggal Pinjam</strong></td>
                                      <td><?= $data['tanggal_pinjam'] ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>Tanggal Kembali</strong></td>
                                      <td><?= $data['tanggal_kembali'] ?></td>
                                    </tr>
                                  </table>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <a href="?kembali=<?= $data['id_transaksi'] ?>"
                                    onclick="return confirm('Perhatikan Denda dan kembalikan Buku?')" type="button"
                                    class="btn btn-primary">Simpan</a>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Button Satunya -->
                          <a href="?hapus=<?= $data['id_transaksi'] ?>" type="button" class="btn btn-danger btn-sm"
                            onclick="return confirm('Anda Yakin Ingin Menghapus Data?')">Hapus</a>
                        </td>
                      </tr>
                      <!-- Modal -->
                      <?php endforeach ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Kode Peminjaman</th>
                        <th>Nama Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Pengembalian</th>
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