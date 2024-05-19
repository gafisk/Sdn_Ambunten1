<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_admin']) || empty($_SESSION['id_admin'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="login_admin.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}
$result = [];
if(isset($_POST['submit'])){
  $awal = $_POST['awal'];
  $akhir = $_POST['akhir'];
  if (empty($awal) || empty($akhir)){
    echo "<script>alert('Kolom Inputan Waktu Tidak Boleh Kosong!');</script>";
  }else{
    $query = "SELECT 
    DATE_FORMAT(months.month, '%Y') AS tahun,
    DATE_FORMAT(months.month, '%m') AS bulan,
    IFNULL(COUNT(CASE WHEN transaksi.status = 'Pinjam' THEN transaksi.tanggal_pinjam END), 0) AS jumlah_peminjaman,
    IFNULL(COUNT(CASE WHEN transaksi.status = 'Kembali' THEN transaksi.tanggal_pinjam END), 0) AS jumlah_kembali
FROM 
    (SELECT 
        DATE_ADD('$awal', INTERVAL (tens.i + units.i) MONTH) AS month
    FROM 
        (SELECT 0 AS i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS units
    CROSS JOIN 
        (SELECT 0 AS i UNION ALL SELECT 10 UNION ALL SELECT 20 UNION ALL SELECT 30 UNION ALL SELECT 40 UNION ALL SELECT 50 UNION ALL SELECT 60 UNION ALL SELECT 70 UNION ALL SELECT 80 UNION ALL SELECT 90) AS tens
    WHERE 
        DATE_ADD('$awal', INTERVAL (tens.i + units.i) MONTH) BETWEEN '$awal' AND '$akhir') AS months
LEFT JOIN 
    transaksi ON DATE_FORMAT(transaksi.tanggal_pinjam, '%Y-%m') = DATE_FORMAT(months.month, '%Y-%m')
GROUP BY 
    tahun, bulan;
";
  }
  $result = mysqli_query($conn, $query);
}


function name_month($angka){
  if ($angka == '01') {
    return 'Januari';
  } else if ($angka == '02') {
    return 'Februari';
  } else if ($angka == '03') {
    return 'Maret';
  } else if ($angka == '04') {
    return 'April';
  } else if ($angka == '05') {
    return 'Mei';
  } else if ($angka == '06') {
    return 'Juni';
  } else if ($angka == '07') {
    return 'Juli';
  } else if ($angka == '08') {
    return 'Agustus';
  } else if ($angka == '09') {
    return 'September';
  } else if ($angka == '10') {
    return 'Oktober';
  } else if ($angka == '11') {
    return 'November';
  } else if ($angka == '12') {
    return 'Desember';
  } else {
    return 'Bulan tidak valid';
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
              <h1 class="m-0">Laporan</h1>
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
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Pilih Waktu</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="">
              <div class="card-body">
                <div class="form-group">
                  <label>Pilih Awal</label>
                  <input class="form-control" type="date" name="awal" id="awal">
                </div>
                <div class="form-group">
                  <label>Pilih Akhir</label>
                  <input class="form-control" type="date" name="akhir" id="akhir">
                </div>
                <div class="form-group">
                  <button type="submit" name="submit" class="btn btn-primary"
                    onclick="return confirm('Waktu Sudah Benar?')">Submit</button>
                </div>
              </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Laporan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Jumlah Peminjaman</th>
                        <th>Jumlah Pengembalian</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($result as $data) : ?>
                      <tr>
                        <td><?=$data['tahun']?></td>
                        <td><?=name_month($data['bulan'])?></td>
                        <td><?=$data['jumlah_peminjaman']?></td>
                        <td><?=$data['jumlah_kembali']?></td>
                      </tr>
                      <?php endforeach ?>

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