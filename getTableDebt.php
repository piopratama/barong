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
	
    $customerName=$_POST['customerName'];

    require 'koneksi.php';
    //echo json_encode($debt);
    $sql = "SELECT nm_transaksi, SUM(total_price) AS total_price, deposit FROM tb_transaksi tt INNER JOIN tb_deposit td ON tt.invoice=td.invoice WHERE statuss=0 AND nm_transaksi='$customerName'";
    //echo json_encode($sql);
    
    $result = $conn->query($sql);
    $data=array();
    if ($result->num_rows > 0) {
        /*$i=0;
        //$html="<option value=''>-- Select Customer --</option>";
        while($row = $result->fetch_assoc()){
            $data[$i]["nm_transaksi"]=$row["nm_transaksi"];
            $data[$i]["total_price"]=rupiah($row["total_price"]);
            $data[$i]["deposit"]=rupiah($row["deposit"]);
            $data[$i]["debt"]=rupiah($row["total_price"]-$row["deposit"]);
            $i=$i+1;
        }
        echo json_encode($data);*/
        while($row = $result->fetch_assoc()){
        $debt=$row['total_price']-$row['deposit'];
        $html="<thead>";
        $html=$html."<tr>";
        $html=$html."<th>Customer</th>";
        $html=$html."<th>Total Price</th>";
        $html=$html."<th>Deposit</th>";
        $html=$html."<th>Debt</th>";
        $html=$html."</tr>";
        $html=$html."</thead>";
        $html=$html."<tbody>"; 
            
        $html=$html."<tr>";
        $html=$html."<td class='nm_transaksi'>".$row['nm_transaksi']."</td>";
        $html=$html."<td class='total_price'>".$row['total_price']."</td>";
        $html=$html."<td class='deposit'>".$row['deposit']."</td>";
        $html=$html."<td class='debt'>".$debt."</td>";
        $html=$html."</tr>";							
        $html=$html."</tbody>";
        }
        echo $html;
    }
    else
    {
        echo json_encode($data);
    }
    
?>