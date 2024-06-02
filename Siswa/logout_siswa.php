<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_siswa']) || empty($_SESSION['id_siswa'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="../login.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}
$id_siswa = $_SESSION['id_siswa'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa where id_siswa = '$id_siswa'"));
tambah_log($data['nama_siswa'], "Logout");
session_unset();
session_destroy();
echo '<script>alert("Anda Logout. Redirecting..."); window.location.href="../index.php";</script>';
exit();