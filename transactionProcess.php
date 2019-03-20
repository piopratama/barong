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

	require ("koneksi.php");
	$invoice="";
	$name="";
	$item=$_POST["item"];
	$qty=$_POST["qty"];
	$payment=$_POST["payment"];
	$method= $_POST["method"];
	$discount=$_POST["discount"];
	$grand_total=0;
	$date=date('Y-m-d H:i:s');

	$new_transaction=0;
	
	if(count($item)>0)
	{
		$where_in=$item[0];
		for($i=1;$i<count($item);$i++)
		{
			$where_in=$where_in.",".$item[$i];
		}
	}
	if($where_in!="")
	{
		//require 'koneksi.php';
		$sql = "SELECT id,price FROM tb_barang WHERE id in(".$where_in.");";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			
			$id_kasir=$_SESSION["id_kasir"];

			if(trim($invoice)=="")
			{
				$new_transaction=1;
				$invoice=$date."".$id_kasir;
			}
			$k=0;
			while($row = $result->fetch_assoc()) {
				for($j=0;$j<count($item);$j++)
				{
					if($row["id"]==$item[$j])
					{
						$data[$k]["id_item"]=$item[$j];
						$data[$k]["nm_transaksi"]=$name;
						$data[$k]["price"]=$row["price"];
						$data[$k]["qty"]=$qty[$j];
						$data[$k]["invoice"]=$invoice;
						$data[$k]["tnggl"]=$date;
						$data[$k]["id_employee"]=$id_kasir;
						$data[$k]["total_price"]=$qty[$j]*$row["price"]-($qty[$j]*$row["price"]*$discount[$j]/100.0);
						$data[$k]["discount"]=$discount[$j];
						$data[$k]["description"]="";
						$data[$k]["statuss"]=1;
						$grand_total=$grand_total+($qty[$j]*$row["price"]-$qty[$j]*$row["price"]*$discount[$j]/100.0);
						$k=$k+1;
					}
				}
			}
			
			$check=0;
			for($i=0;$i<count($data);$i++)
			{
				$sql = "INSERT INTO tb_transaksi (invoice, `nm_transaksi`, `tnggl`, id_employee, id_item, qty, discount, total_price, description, statuss) VALUES ('".$data[$i]["invoice"]."', '".$data[$i]["nm_transaksi"]."','".$data[$i]["tnggl"]."', ".$data[$i]["id_employee"].", ".$data[$i]["id_item"].", ".$data[$i]["qty"].", ".$data[$i]["discount"].", ".$data[$i]["total_price"].", '".$data[$i]["description"]."', ".$data[$i]["statuss"].")";
				if ($conn->query($sql) === TRUE) {
					$last_id = $conn->insert_id;
					//echo "New record created successfully. Last inserted ID is: " . $last_id;
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
					$check=1;
				}

				$sql2 = "UPDATE tb_barang SET stock = stock - ".$data[$i]["qty"]." where id =".$data[$i]["id_item"]."";
				if ($conn->query($sql2) === TRUE) {
					$last_id = $conn->insert_id;
				} else {
					echo "Error: " . $sql2s . "<br>" . $conn->error;
					$check=1;
				}
			}

			if($payment!="" && $check==0)
			{
				$sql="INSERT INTO tb_deposit (`date`,invoice, deposit, payment, method) VALUES ('".$date."','".$invoice."', 0, ".$grand_total.",'".$method."')";
				/*if($new_transaction==1)
				{
					$sql="INSERT INTO tb_deposit (invoice, deposit, payment, method, rest_total, history) VALUES ('".$invoice."', 0, ".$payment.", '".$method."', 0, '')";
				}
				/*else
				{
					$sql="UPDATE tb_payment set payment=payment+".$payment." where invoice='".$invoice."'";
				}*/
				if ($conn->query($sql) === TRUE) {
					//$last_id = $conn->insert_id;
					//echo "New record created successfully. Last inserted ID is: " . $last_id;
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
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
	}
	/*else if(trim($invoice)!="" && $payment!="")
	{
		$sql="UPDATE tb_payment set payment=payment+".$payment.", rest_total=".$payment."-".." where invoice='".$invoice."'";
		if ($conn->query($sql) === TRUE) {
			$_SESSION["message"]="Insert Successfully";
			//$last_id = $conn->insert_id;
			//echo "New record created successfully. Last inserted ID is: " . $last_id;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}*/
	
	$conn->close();
	header("location:directPay.php");
?>