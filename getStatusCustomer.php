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
	
    $status=$_POST['status'];
    $startDate=$_POST['dateStart'];
    $stopDate=$_POST['dateStop'];
	require 'koneksi.php';
    //$sql = "SELECT invoice, nm_transaksi, tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item )AS item, qty, total_price, statuss FROM tb_transaksi WHERE statuss=".$status." and nm_transaksi<>'';";
    $sql = "SELECT invoice, nm_transaksi FROM tb_transaksi WHERE Date(tnggl)>='".$startDate."' and Date(tnggl)<='".$stopDate."';";
    $result = $conn->query($sql);

    $data=array();
    if ($result->num_rows > 0) {
        $i=0;

        $html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            $html=$html."<option value='".$row['nm_transaksi']."'>".$row['nm_transaksi']."</option>";
            /*$data[$i]["invoice"]=$row["invoice"];
            $data[$i]["nm_transaksi"]=$row["nm_transaksi"];
            $data[$i]["tnggl"]=$row["tnggl"];
            $data[$i]["nama_pegawai"]=$row["nama_pegawai"];
            $data[$i]["item"]=$row["item"];
            $data[$i]["qty"]=$row["qty"];
            $data[$i]["total_price"]=$row["total_price"];
            if($row["statuss"]==1)
            {
                $data[$i]["status"]="paid";
            }
            else if($row["statuss"]==0){
                $data[$i]["status"]="not paid";
            }*/
        }
        echo $html;
    }
    else
    {
        $html="<option value=''>-- Select Customer --</option>";
        echo $html;
    }
?>