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
	$sql = "SELECT tb_barang.id,tb_barang.item,tb_barang.price,tb_transaksi.qty, tb_transaksi.discount, tb_transaksi.total_price FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item WHERE invoice='".$invoice."';";
	$result = $conn->query($sql);
	
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
	
?>