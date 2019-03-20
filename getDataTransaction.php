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
		$html="<table id=\"example\" class=\"display\" style='width:100%'>";
		$html=$html."<thead>";
		$html=$html."<tr>";
		$html=$html."<th>Date</th>";
		$html=$html."<th>Invoice</th>";
		$html=$html."<th>Customer</th>";
		$html=$html."<th>Employee</th>";
		$html=$html."<th>Item</th>";
		$html=$html."<th>Quantity</th>";
		$html=$html."<th>Discount (%)</th>";
		$html=$html."<th>Price</th>";
		$html=$html."<th>Total Price</th>";
		$html=$html."<th>Status</th>";
		$html=$html."</tr>";
		$html=$html."</thead>";
		$html=$html."<tbody class='table_body'>";
						
		// output data of each row
		while($row = $result->fetch_assoc()) {	
			$html=$html."</tr>";
			$html=$html."<td class='date'>".$row['tnggl']."</td>";
			$html=$html."<td class='invoice'>".$row['invoice']."</td>";
			$html=$html."<td class='customer'>".$row['nm_transaksi']."</td>";
			$html=$html."<td class='employee'>".$row['nama']."</td>";
			$html=$html."<td class='item'>".$row['item']."</td>";
			$html=$html."<td class='qty'>".$row['qty']."</td>";
			$html=$html."<td class='discount'>".$row['discount']."</td>";
			$html=$html."<td class='price'>".$row['price']."</td>";
			$html=$html."<td class='total'>".$row['total_price']."</td>";
			if($row['statuss']==0)
			{
				$html=$html."<td>not paid</td>";
			}
			else
			{
				$html=$html."<td>paid</td>";
			}
			$html=$html."</tr>";
			$html=$html."</tbody>";
		}
		$html=$html."</tbody>";
		$html=$html."</table>";
		echo $html;
	}	
?>