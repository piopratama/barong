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

$id=$_POST["id_delete"];

include 'koneksi.php';
$sql="DELETE FROM tb_supplier WHERE id_supplier = $id";
if ($conn->query($sql) === TRUE) {
    $_SESSION['message']="Delete Successfully";
} else {
    $_SESSION['message']="Delete Failed".$conn->error;
}
header("location:supplier.php");
?>