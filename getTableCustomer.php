<?php
    session_start();
    function rupiah($angka){
	
        $hasil_rupiah = number_format($angka,0,'','.');
        return $hasil_rupiah;
     
    }
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
    
	
    $startDate=$_POST['dateStart'];
    $stopDate=$_POST['dateStop'];
    require 'koneksi.php';
    //echo json_encode($startDate." ".$stopDate." ".$status);
    if($startDate!="" && $stopDate!="")
    {
        $sql = "SELECT nm_transaksi, SUM(total_price) AS total_price FROM tb_transaksi WHERE statuss=1 AND DATE(tnggl)>='".$startDate."' AND DATE(tnggl)<='".$stopDate."' GROUP BY nm_transaksi;";
    }
    else if($startDate=="" && $stopDate!="")
    {
        $sql = "SELECT nm_transaksi, SUM(total_price) AS total_price FROM tb_transaksi WHERE statuss=1 AND DATE(tnggl)<='".$stopDate."' GROUP BY nm_transaksi;";
    }
    else if($startDate!="" && $stopDate=="")
    {
        $sql = "SELECT nm_transaksi, SUM(total_price) AS total_price FROM tb_transaksi WHERE statuss=1 AND DATE(tnggl)>='".$startDate."' GROUP BY nm_transaksi;";
    }
    else 
    {
        $sql = "SELECT nm_transaksi, SUM(total_price) AS total_price FROM tb_transaksi WHERE statuss=1 GROUP BY nm_transaksi;";
    }
    //echo json_encode($sql);
    
    $result = $conn->query($sql);
    $data=array();
    if ($result->num_rows > 0) {
        $i=0;
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            $data[$i]["nm_transaksi"]=$row["nm_transaksi"]=="" ? "Direct Pay": $row["nm_transaksi"];
            $data[$i]["total_price"]=rupiah($row["total_price"]);
            $i=$i+1;
        }
        echo json_encode($data);
    }
    else
    {
        echo json_encode($data);
    }
    
?>