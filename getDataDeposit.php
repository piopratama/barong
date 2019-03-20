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
    //$startDate="2019-01-01";
    //$stopDate="2019-01-31";
    $status="";
    if(isset($_POST['status']))
    {
        $status=$_POST['status'];
    }
    $startDate="";
    if(isset($_POST['dateStart']))
    {
        $startDate=$_POST['dateStart'];
    }
    $stopDate="";
    if(isset($_POST['dateStart']))
    {
        $stopDate=$_POST['dateStop'];
    }
    
    //$nm_transaksi=$_POST['customer'];
    require 'koneksi.php';
    //echo json_encode($startDate." ".$stopDate." ".$status);

    if($startDate!="" && $stopDate!="")
    {
        $sql = "SELECT SUM(deposit) AS deposit FROM tb_deposit WHERE invoice 
        IN (SELECT invoice FROM tb_transaksi WHERE tb_transaksi.statuss=0 
        AND date(tnggl)>='".$startDate."' AND DATE(tnggl)<='".$stopDate."');";
    }
    else if($startDate=="" && $stopDate!="")
    {
        $sql = "SELECT SUM(deposit) AS deposit FROM tb_deposit WHERE invoice 
        IN (SELECT invoice FROM tb_transaksi WHERE tb_transaksi.statuss=0 AND DATE(tnggl)<='".$stopDate."');";
    }
    else if($startDate!="" && $stopDate=="")
    {
        $sql = "SELECT SUM(deposit) AS deposit FROM tb_deposit WHERE invoice 
        IN (SELECT invoice FROM tb_transaksi WHERE tb_transaksi.statuss=0 AND date(tnggl)>='".$startDate."');";
    }
    else 
    {
        $sql = "SELECT SUM(deposit) AS deposit FROM tb_deposit WHERE invoice 
        IN (SELECT invoice FROM tb_transaksi WHERE tb_transaksi.statuss=0);";
    }

    //echo json_encode($sql);
    //echo $sql;
   $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            if($row["deposit"]=="" || $row["deposit"]==null)
            {
                echo 0;
            }
            else
            {
                echo $row["deposit"];
            }
        }
    }
    else
    {
        echo 0;
    }
    
?>