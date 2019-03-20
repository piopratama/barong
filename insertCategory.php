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

$category=$_POST["category"];
$description=$_POST["description"];

$date_insert = date('Y-m-d h:i:s', time());

include 'koneksi.php';
$sql="INSERT INTO tb_kategori(date_insert, nm_kategori, `description`) VALUES('$date_insert', '$category', '$description')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message']="Insert Successfully";
} else {
    $_SESSION['message']="Insert Failed".$conn->error;
}
header("location:kategori.php");
?>