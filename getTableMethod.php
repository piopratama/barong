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
        $sql = "SELECT method,SUM(payment+deposit) AS payment FROM tb_deposit WHERE DATE(`date`)>='".$startDate."' AND DATE(`date`)<='".$stopDate."' GROUP BY method;";
    }
    else if($startDate=="" && $stopDate!="")
    {
        $sql = "SELECT method,SUM(payment+deposit) AS payment FROM tb_deposit WHERE DATE(`date`)<='".$stopDate."' GROUP BY method;";
    }
    else if($startDate!="" && $stopDate=="")
    {
        $sql = "SELECT method,SUM(payment+deposit) AS payment FROM tb_deposit WHERE DATE(`date`)>='".$startDate."' GROUP BY method;";
    }
    else 
    {
        $sql = "SELECT method,SUM(payment+deposit) AS payment FROM tb_deposit GROUP BY method;";
    }
    //echo json_encode($sql);
    
    $result = $conn->query($sql);
    $data=array();
    if ($result->num_rows > 0) {
        $i=0;
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            $data[$i]["method"]=$row["method"];
            $data[$i]["payment"]=rupiah($row["payment"]);
            $i=$i+1;
        }
        echo json_encode($data);
    }
    else
    {
        echo json_encode($data);
    }
    
?>