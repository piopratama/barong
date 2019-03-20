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

include_once 'koneksi.php';

$item=$_POST["item"];
$qty=$_POST["qty"];
$expired_date=$_POST["expired_date"];

$sql="INSERT INTO tb_expired(id_item, qty, expired_date) VALUES(".$item.",".$qty.",'".$expired_date."')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message']="Insert Successfully";
} else {
    $_SESSION['message']="Insert Failed".$conn->error;
}
header("location:expired.php");

?>