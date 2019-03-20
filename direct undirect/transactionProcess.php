<?php
	session_start();
	if(empty($_SESSION['username'])){
		header("location:index.php");
	}
	
	$item=$_POST["item"];
	$qty=$_POST["qty"];
	$payment=$_POST["payment"];
	$method= $_POST["method"];
	if(count($item)>0)
	{
		$where_in=$item[0];
		for($i=1;$i<count($item);$i++)
		{
			$where_in=$where_in.",".$item[$i];
		}
	}
	//echo $where_in;
	require 'koneksi.php';
	$sql = "SELECT id,price FROM tb_barang WHERE id in(".$where_in.");";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		
		$id_kasir=$_SESSION["id_kasir"];
		$date=date('Y-m-d H:i:s');
		$invoice=$date."".$id_kasir;
		$k=0;
		$grand_total=0;
		while($row = $result->fetch_assoc()) {
			for($j=0;$j<count($item);$j++)
			{
				if($row["id"]==$item[$j])
				{
					$data[$k]["id_item"]=$item[$j];
					$data[$k]["price"]=$row["price"];
					$data[$k]["qty"]=$qty[$j];
					$data[$k]["invoice"]=$invoice;
					$data[$k]["tnggl"]=$date;
					$data[$k]["id_employee"]=$id_kasir;
					$data[$k]["method"]=$method;
					$data[$k]["total_price"]=$qty[$j]*$row["price"];
					$data[$k]["rest_total"]=0;
					$data[$k]["description"]="";
					$data[$k]["statuss"]=1;
					$grand_total=$grand_total+$qty[$j]*$row["price"];
					$k=$k+1;
				}
			}
		}
		
		if($payment<$grand_total)
		{
			$_SESSION["message"]="Payment must be equal or bigger than grand total";
			header("location:directPay.php");
			return;
		}

		$check=0;

		for($i=0;$i<count($data);$i++)
		{
			$sql = "INSERT INTO tb_transaksi (invoice, tnggl, id_employee, id_item, qty, total_price, rest_total, description, method, statuss) VALUES ('".$data[$i]["invoice"]."', '".$data[$i]["tnggl"]."', ".$data[$i]["id_employee"].", ".$data[$i]["id_item"].", ".$data[$i]["qty"].", ".$data[$i]["total_price"].", ".$data[$i]["rest_total"].", '".$data[$i]["description"]."', '".$data[$i]["method"]."', ".$data[$i]["statuss"].")";
			if ($conn->query($sql) === TRUE) {
			    $last_id = $conn->insert_id;
			    //header("location:paymentDIrect.php");
			    //echo "New record created successfully. Last inserted ID is: " . $last_id;
			} else {
			    echo "Error: " . $sql . "<br>" . $conn->error;
			    $check=1;
			}
		}

		if($check==0)
		{
			$_SESSION["invoice"]=$invoice;
			$_SESSION["message"]="Insert Successfully";
		}
		else
		{
			$_SESSION["message"]="Error";
		}
	}
	$conn->close();
	header("location:directPay.php");
?>