<?php
session_start();
if(empty($_SESSION['username'])){
	header("location:index.php");
}
else
{
	if(!empty($_SESSION['level_user']))
	{
		if($_SESSION["level_user"]==0)
		{
			header("location:index.php");
		}
	}
}

date_default_timezone_set('Asia/Jakarta');

$supplier=$_POST["supplier"];
$address=$_POST["address"];
$phone=$_POST["phone"];


include 'koneksi.php';
$sql="INSERT INTO tb_supplier(nm_supplier, address, no_hp) VALUES('$supplier', '$address', '$phone')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message']="Insert Successfully";
} else {
    $_SESSION['message']="Insert Failed".$conn->error;
}
header("location:supplier.php");
?>