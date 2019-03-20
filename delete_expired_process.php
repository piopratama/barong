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

$id_delete=$_POST["id_delete"];

$sql="Delete from tb_expired where id=".$id_delete.";";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message']="Delete Successfully";
} else {
    $_SESSION['message']="Delete Failed".$conn->error;
}
header("location:expired.php");

?>