<?php
    session_start();
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
    
    $barcode=$_POST["barcode"];
    
    //$nm_transaksi=$_POST['customer'];
    require 'koneksi.php';
    //echo json_encode($startDate." ".$stopDate." ".$status);

    $sql = "select * from tb_barang where barcode='".$barcode."';";

    //echo json_encode($sql);
    //echo $sql;
   $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            echo $row["id"];
        }
    }
    else
    {
        echo "";
    }
    
?>