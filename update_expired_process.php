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

$id=$_POST["id_update"];
$item=$_POST["item"];
$qty=$_POST["qty"];
$expired_date=$_POST["expired_date"];

$sql="UPDATE tb_expired set id_item=".$id_item.", qty=".$qty.", expired_date=".$expired_date." where id=".$id.";";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message']="Update Successfully";
} else {
    $_SESSION['message']="Insert Failed".$conn->error;
}
header("location:expired.php");

?>