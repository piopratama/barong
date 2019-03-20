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
	
    $status=$_POST['status'];
    $startDate=$_POST['dateStart'];
    $stopDate=$_POST['dateStop'];
    //$nm_transaksi=$_POST['customer'];
    require 'koneksi.php';
    //echo json_encode($startDate." ".$stopDate." ".$status);
    if($startDate!="" && $stopDate!="")
    {
        $sql ="SELECT TK.nm_kategori, ROUND(SUM(TB.pur_price*TT.qty)) AS pur_price, ROUND(SUM(TT.total_price)) AS income, ROUND(SUM(TT.total_price-TB.pur_price*TT.qty)) AS profit FROM tb_transaksi TT 
        INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id 
        WHERE TT.statuss=1 AND DATE(tnggl)>='".$startDate."' AND DATE(tnggl)<='".$stopDate."' GROUP BY TK.nm_kategori;";
    }
    else if($startDate=="" && $stopDate!="")
    {
        $sql ="SELECT TK.nm_kategori, ROUND(SUM(TB.pur_price*TT.qty)) AS pur_price, ROUND(SUM(TT.total_price)) AS income, ROUND(SUM(TT.total_price-TB.pur_price*TT.qty)) AS profit FROM tb_transaksi TT 
        INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id 
        WHERE TT.statuss=1 AND DATE(tnggl)<='".$stopDate."' GROUP BY TK.nm_kategori;";

    }
    else if($startDate!="" && $stopDate=="")
    {
        $sql ="SELECT TK.nm_kategori, ROUND(SUM(TB.pur_price*TT.qty)) AS pur_price, ROUND(SUM(TT.total_price)) AS income, ROUND(SUM(TT.total_price-TB.pur_price*TT.qty)) AS profit FROM tb_transaksi TT 
        INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id 
        WHERE TT.statuss=1 AND DATE(tnggl)>='".$startDate."' GROUP BY TK.nm_kategori;";
    }
    else 
    {
        $sql ="SELECT TK.nm_kategori, ROUND(SUM(TB.pur_price*TT.qty)) AS pur_price, ROUND(SUM(TT.total_price)) AS income, ROUND(SUM(TT.total_price-TB.pur_price*TT.qty)) AS profit FROM tb_transaksi TT 
        INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id 
        WHERE TT.statuss=1 GROUP BY TK.nm_kategori;";
    }
    //echo json_encode($sql);
    
    $result = $conn->query($sql);
    $data=array();
    if ($result->num_rows > 0) {
        $i=0;
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            $data[$i]["nm_kategori"]=$row["nm_kategori"];
            $data[$i]["pur_price"]=rupiah($row["pur_price"]);
            $data[$i]["income"]=rupiah($row["income"]);
            $data[$i]["profit"]=rupiah($row["profit"]);
            $i=$i+1;
        }
        echo json_encode($data);
    }
    else
    {
        echo json_encode($data);
    }
    
?>