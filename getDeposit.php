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
	
	$invoice=$_POST["invoice"];
	require 'koneksi.php';
	$sql = "SELECT SUM(deposit) as deposit FROM tb_deposit WHERE invoice='".$invoice."';";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$sum=0;
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row['deposit']=="" || $row['deposit']==null){
				echo 0;
			}
			else{	
				echo $row['deposit'];
			}
		}
		//echo $sum;
	}
	else{
		echo 0;
	}
?>