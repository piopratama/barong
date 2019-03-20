<?php
	session_start();
	if(empty($_SESSION['username'])){
		header("location:index.php");
	}
	else
	{
		if(!empty($_SESSION['level_user']))
		{
			if($_SESSION["level_user"]==1)
			{
				header("location:index.php");
			}
		}
	}
	
	$invoice=$_POST['invoice'];
	require 'koneksi.php';
	$sql = "SELECT * FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee WHERE invoice='".$invoice."';";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$sum=0;
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$sum=$sum+$row['total_price'];

		}
		$sum=$sum+0*$sum;
		echo $sum;
	}
?>