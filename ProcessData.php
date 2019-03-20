<?php
session_start();
function select(){
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
	
	require 'koneksi.php';
	$data="SELECT * FROM tb_barang";
	$result=$conn->query($data);
}
?>