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
        $sql = "SELECT SUM(TT.total_price) AS income FROM tb_transaksi TT INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id WHERE TT.statuss=1 AND DATE(tnggl)>='".$startDate."' AND DATE(tnggl)<='".$stopDate."'";
    }
    else if($startDate=="" && $stopDate!="")
    {
        $sql = "SELECT TK.nm_kategori, SUM(TT.total_price) AS income FROM tb_transaksi TT INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id WHERE TT.statuss=1 AND DATE(tnggl)<='".$stopDate."'";
    }
    else if($startDate!="" && $stopDate=="")
    {
        $sql = "SELECT TK.nm_kategori, SUM(TT.total_price) AS income FROM tb_transaksi TT INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id WHERE TT.statuss=1 AND DATE(tnggl) >='".$startDate."';";
    }
    else 
    {
        $sql = "SELECT TK.nm_kategori, SUM(TT.total_price) AS income FROM tb_transaksi TT INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id WHERE TT.statuss=1 ";
    }
    //echo json_encode($sql);
    
   $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            if($row=="" || $row==null)
            {
                echo 0;
            }
            else
            {
                echo rupiah($row["income"]);
            }
        }
    }
    else
    {
        echo 0;
    }

    
?>