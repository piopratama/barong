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
		$i=0;		
		// output data of each row
		while($row = $result->fetch_assoc()) {	
			$data[$i]['date']=$row['date'];
			$data[$i]['invoice']=$row['invoice'];
			$data[$i]['name']=$row['name'];
			$data[$i]['item']=$row['item'];
			$data[$i]['qty']=$row['qty'];
			$data[$i]['price']=$row['price'];
			$data[$i]['total']=$row['total_price'];
			$i=$i+1;
		}
		echo json_encode($data);
	}
?>