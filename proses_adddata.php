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
			if($_SESSION["level_user"]==0)
			{
				header("location:index.php");
			}
		}
	}
	
	// Check If form submitted, insert form data into users table.
	$name = addslashes($_POST['name']);
	$price = $_POST['price'];
	$stock = $_POST['stock'];
	$unit = $_POST['unit'];
	$kategori = $_POST['category'];
	$supplier = $_POST['supplier'];
	$purchase = $_POST['purchase_price'];
	$barcode=$_POST['barcode'];
	$date=date('Y-m-d H:i:s');

	// include database connection file
	include 'koneksi.php';
											
	// Insert user data into table
	$result = mysqli_query($conn, "INSERT INTO tb_barang(item,price,stock,unit,kategori,supplier,pur_price,barcode, `date`) VALUES('$name','$price','$stock','$unit','$kategori','$supplier','$purchase','$barcode', '$date')");
	if(!$result)
	{
		$_SESSION["message"]="Transaksi gagal, silahkan ulangi transaksi";
	}			
	header("location:stock.php");
?>