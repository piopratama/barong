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
	$id=$_POST["id_item"];
	$qty=0;
	if(isset($_POST["qty"]))
	{
		$qty=$_POST["qty"];
	}
	$discount=0;
	if(isset($_POST["discount"]))
	{
		$discount=$_POST["discount"];
	}
	require 'koneksi.php';
	$sql = "SELECT * FROM tb_barang WHERE id=".$id.";";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		$i=0;
		while($row = $result->fetch_assoc()) {
			$data[$i]["id_item"]=$row["id"];
			$data[$i]["item"]=$row["item"];
			$data[$i]["price"]=$row["price"];
			$data[$i]["unit"]=$row["unit"];
			$data[$i]["qty"]=$qty;
			$data[$i]["discount"]=$discount;
			$data[$i]["total_price"]=$row["price"]*$qty-$row["price"]*$qty*$discount/100.0;
			$i=$i+1;
		}

		echo json_encode($data);
	}

	
?>