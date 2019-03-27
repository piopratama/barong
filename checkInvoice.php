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
	$mode=0;
	$deposit=0;
	$grand_total=0;
	if(isset($_POST["mode"]))
	{
		$mode=1;
		$deposit=$_POST["deposit"];
	}
	require 'koneksi.php';
	$sql = "SELECT tb_barang.id,tb_barang.item,tb_barang.price,tb_transaksi.qty, tb_transaksi.discount, tb_transaksi.total_price, tb_barang.unit FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item WHERE invoice='".$invoice."';";
	$result = $conn->query($sql);
	
	if($mode!=0)
	{
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$html="<div class='col-md-4' style='position: relative;'>";
				$html=$html."<div class='itemForm'>";
				$html=$html."<div class='form-group'>";
				$html=$html."<label for=''>Item</label>";
				$html=$html."<select class='form-control' name='item[]' readonly='readonly'><option value='".$row['id']."'>".$row['item']."</option></select>";
				$html=$html."</div>";
				$html=$html."<div class='form-group'>";
				$html=$html."<label for=''>Quantity</label>";
				$html=$html."<input type='text' class='form-control' value='".$row['qty']."' name='qty[]'' placeholder='Quantity' readonly='readonly'>";
				$html=$html."</div>";
				$html=$html."<div class='form-group'>";
				$html=$html."<label for=''>Price</label>";
				$html=$html."<input type='text' class='form-control' value='".$row['price']."' name='price[]'' placeholder='Price' readonly='readonly'>";
				$html=$html."</div>";
				$html=$html."<div class='form-group'>";
				$html=$html."<label for='' class='label_discount'>Discount (%)</label>";
				$html=$html."<input type='text' value='".$row['discount']."' class='form-control' name='discount[]' placeholder='Discount' readonly='readonly'>";
				$html=$html."</div>";
				$html=$html."<div class='form-group'>";
				$html=$html."<label for=''>Total</label>";
				$html=$html."<input type='text' class='form-control' value='".$row['total_price']."' placeholder='Total' readonly='readonly'>";
				$html=$html."</div>";
				$html=$html."</div>";
				$html=$html."</div>";
				echo $html;
			}
		}
	}
	else
	{
		$html="";
		if ($result->num_rows > 0) {
			// output data of each row
			$no=1;
			$html=$html."<div style='height: 300px !important;overflow: scroll;'>";
			$html=$html."<table class='table table-bordered'>";
			$html=$html."<thead>";
			$html=$html."<tr>";
			$html=$html."<th scope='col'>#</th>";
			$html=$html."<th scope='col'>Item</th>";
			$html=$html."<th scope='col'>Jum</th>";
			$html=$html."<th scope='col'>Unit</th>";
			$html=$html."<th scope='col'>Harga</th>";
			$html=$html."<th scope='col'>Discount (%)</th>";
			$html=$html."<th scope='col'>Total</th>";
			$html=$html."</tr>";
			$html=$html."</thead>";
			$html=$html."<tbody id='table_body'>";
			while($row = $result->fetch_assoc()) {
				$html=$html."<tr>";
				$html=$html."<td>".$no."</td>";
				$html=$html."<td>".$row["item"]."</td>";
				$html=$html."<td>".$row["qty"]."</td>";
				$html=$html."<td>".$row["unit"]."</td>";
				$html=$html."<td>".$row["price"]."</td>";
				$html=$html."<td>".$row["discount"]."</td>";
				$html=$html."<td>".$row['total_price']."</td>";
				$html=$html."</tr>";
				$no=$no+1;
				$grand_total=$grand_total+$row['total_price'];
			}
			$html=$html."</tbody>";
			$html=$html."</table>";
			$html=$html."</div>";
			$html=$html."<div class='form-group'>";
			$html=$html."<label for=''>Total Bayar</label>";
			$html=$html."<input type='text' class='form-control' readonly='readonly' value='".$grand_total."'>";
			$html=$html."</div>";
			$html=$html."<div class='form-group'>";
			$html=$html."<label for=''>Deposit</label>";
			$html=$html."<input type='text' class='form-control' readonly='readonly' value='".$deposit."'>";
			$html=$html."</div>";
			echo $html;
		}
	}
	
?>