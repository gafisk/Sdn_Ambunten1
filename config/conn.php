<?php
date_default_timezone_set('Asia/Jakarta');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank_sdnambunten1";
$conn = mysqli_connect($servername, $username, $password, $dbname);


function tambah_log($keterangan)
{
global $conn;
$query = mysqli_query($conn, "INSERT INTO log VALUES (NULL, '$keterangan', NOW())");
}

?>