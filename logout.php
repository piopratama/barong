<?php
session_start();
require 'koneksi.php';
$a=$_SESSION['username'];

$sql = mysqli_query($conn, "update tb_employee set online_status='0' where username='$a'");
session_destroy();
header("location:index.php");

?>