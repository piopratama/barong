<?php
	session_start();
	date_default_timezone_set('Asia/Singapore');
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

	$mode=0;
	if(isset($_POST["mode"]))
	{
		$mode=1;
	}

	$new_transaction=0;
	
	if(count($item)>0)
	{
		$where_in=$item[0];
		for($i=1;$i<count($item);$i++)
		{
			if(trim($item[$i])!="" || $item[$i]!=null)
			{
				$where_in=$where_in.",".$item[$i];
			}
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
			
			$conn->autocommit(FALSE);
			$conn->query("START TRANSACTION");

			$check=0;
			for($i=0;$i<count($data);$i++)
			{
				if($i%2==0)
				{
					$sql = "INSERT INTO tb_transaksi (invoice, `nm_transaksi`, `tnggl`, id_employee, id_item, qty, discount, total_price, description, statuss) VALUES ('".$data[$i]["invoice"]."', '".$data[$i]["nm_transaksi"]."','".$data[$i]["tnggl"]."', ".$data[$i]["id_employee"].", ".$data[$i]["id_item"].", ".$data[$i]["qty"].", ".$data[$i]["discount"].", ".$data[$i]["total_price"].", '".$data[$i]["description"]."', ".$data[$i]["statuss"].")";
				}
				else
				{
					$sql = "INSERT INTO tb_transaksi (invoicex, `nm_transaksi`, `tnggl`, id_employee, id_item, qty, discount, total_price, description, statuss) VALUES ('".$data[$i]["invoice"]."', '".$data[$i]["nm_transaksi"]."','".$data[$i]["tnggl"]."', ".$data[$i]["id_employee"].", ".$data[$i]["id_item"].", ".$data[$i]["qty"].", ".$data[$i]["discount"].", ".$data[$i]["total_price"].", '".$data[$i]["description"]."', ".$data[$i]["statuss"].")";
				}
				if ($conn->query($sql) === TRUE) {
					$last_id = $conn->insert_id;
					//echo "New record created successfully. Last inserted ID is: " . $last_id;
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
					$check=1;
					break;
				}

				$sql2 = "UPDATE tb_barang SET stock = stock - ".$data[$i]["qty"]." where id =".$data[$i]["id_item"]."";
				if ($conn->query($sql2) === TRUE) {
				} else {
					echo "Error: " . $sql2s . "<br>" . $conn->error;
					$check=1;
					break;
				}
			}

			if($payment!="" && $check==0)
			{
				$sql="INSERT INTO tb_deposit (`date`,invoice, deposit, payment, method) VALUES ('".$date."','".$invoice."', 0, ".$grand_total.",'".$method."')";
				if ($conn->query($sql) === TRUE) {
					$check=0;
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
					$check=1;
				}
			}

			if($check==0)
			{
				$conn->commit();
				$_SESSION["invoice"]=$invoice;
				$_SESSION["message"]="Transaksi Berhasil";
			}
			else
			{
				$conn->rollback();
				$_SESSION["message"]="Transaksi gagal, silahkan ulangi transaksi";
			}
		}
		else
		{
			$_SESSION["message"]="Transaksi gagal, silahkan ulangi transaksi";
		}
	}
	
	$conn->close();
	if($mode!=1)
	{
		header("location:directPay.php");
	}
	else
	{
		echo json_encode("");
		return;
	}
?>