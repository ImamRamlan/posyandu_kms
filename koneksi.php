<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$koneksi = mysqli_connect("localhost", "root", "", "db_posyandu");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>
