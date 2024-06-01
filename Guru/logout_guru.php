<?php
session_start();
include('../config/conn.php');
if (!isset($_SESSION['id_guru']) || empty($_SESSION['id_guru'])) {
  echo '<script>alert("Silahkan Login Dahulu"); window.location.href="../login.php";</script>';
  exit(); // Hentikan eksekusi script setelah mengarahkan ke halaman login
}

$id_guru = $_SESSION['id_guru'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru where id_guru = '$id_guru'"));
tambah_log($data['nama_guru'], "Logout");
session_unset();
session_destroy();
echo '<script>alert("Anda Logout. Redirecting..."); window.location.href="../login.php";</script>';
exit();