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
	require 'koneksi.php';
	$sql = "SELECT * FROM tb_barang WHERE id=".$id.";";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		$i=0;
		while($row = $result->fetch_assoc()) {
			$data[$i]["price"]=$row["price"];
			$data[$i]["unit"]=$row["unit"];
			$i=$i+1;
		}

		echo json_encode($data);
	}

	
?>