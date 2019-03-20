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

ini_set("session.auto_start", 0);

require 'koneksi.php';
require('mc_table.php');

date_default_timezone_set('Asia/Singapore');

$invoice=$_POST['invoice'];
$deposit=$_POST['deposit'];
$remaining_payment=$_POST['remaining_payment'];
$grand_total=$_POST['grand_total'];
$method=$_POST['method'];
$payment=$_POST['payment'];
$change=$_POST['change'];
//$date=date('d/m/Y H:i:s');
$date=date('Y-m-d H:i:s');
$date_db = date("Y-m-d", strtotime($date));
$customer='';

$sql = "SELECT * FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee WHERE invoice='".$invoice."';";
$result = $conn->query($sql);

if ($result->num_rows > 0) 
{
	$i=0;
	$sum=0;
	
	$header = array('No', 'Date', 'Item', 'Qty', 'Dsc', 'Price (Rp.)', 'Total Price (Rp.)', 'Status');
    $w=array(10, 20, 80, 9, 9, 25, 25, 13);

    $pdf=new PDF_MC_Table();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',6);
    $pdf->SetWidths($w);
    $pdf->SetAutoPageBreak(true,10);
    $pdf->SetFillColor(255,0,0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetTextColor(0);
    //srand(microtime()*1000000);
    while($row = $result->fetch_assoc()) {

        $data[$i][0]=$i+1;
        $data[$i][1]=date("d/m/Y", strtotime($row["tnggl"]));
        $customer=$row["nm_transaksi"];
        $data[$i][2]=$row["item"];
        $data[$i][3]=$row["qty"];
        $data[$i][4]=$row["discount"];
        $data[$i][5]=$row["price"];
        $data[$i][6]=$row["total_price"];
        if($row["statuss"]==1)
        {
            $data[$i][7]="paid";
        }
        else{
            $data[$i][7]="not paid";
        }
        $sum=$sum+$row["total_price"];
        $nama=$row["nama"];

        $i=$i+1;
    }
    
    $pdf->Cell(0,4,'Barong',0,1,'C');
    $pdf->Cell(0,4,'Jalan Puncak Waringin',0,1,'C');
    $pdf->Cell(0,4,'+62 812 3605 8607',0,1,'C');
    $pdf->Cell(0,4,'delipointkomodo@gmail.com',0,1,'C');
    $pdf->Cell(0,4,'---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
    $pdf->Cell(0,4,"Invoice : ".$invoice,0,1,'L');
    $pdf->Cell(0,4,"Date : ".$date,0,1,'L');
    $pdf->Cell(0,4,"Cashier : ".$nama,0,1,'L');
    $pdf->Cell(0,4,"To : ".$customer,0,1,'L');

    for($i=0;$i<count($header);$i++)
    {
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C', true);
    }

    $pdf->Ln();
    // Color and font restoration
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('');

    for($i=0;$i<count($data);$i++)
    {
        $pdf->Row(array($i+1,$data[$i][1],$data[$i][2],$data[$i][3],$data[$i][4],$data[$i][5],$data[$i][6],$data[$i][7]));
    }
    $sum=$sum+$sum*0;
    
    $pdf->Cell($w[0],6,'Grand Total','T',0,'L');
    $pdf->Cell($w[1],6,'','T',0,'L');
    $pdf->Cell($w[2],6,'','T',0,'L');
    $pdf->Cell($w[3],6,'','T',0,'L');
    $pdf->Cell($w[4],6,'','T',0,'L');
    $pdf->Cell($w[5],6,'','T',0,'R');
    $pdf->Cell($w[6],6,'','T',0,'R');
    $pdf->Cell($w[7],6,'Rp. '.number_format($deposit),'T',0,'L');
    $pdf->Ln();
    $pdf->Cell($w[0],6,'Deposit',0,0,'L');
    $pdf->Cell($w[1],6,'',0,0,'L');
    $pdf->Cell($w[2],6,'',0,0,'L');
    $pdf->Cell($w[3],6,'',0,0,'L');
    $pdf->Cell($w[4],6,'',0,0,'L');
    $pdf->Cell($w[5],6,'',0,0,'R');
    $pdf->Cell($w[6],6,'',0,0,'R');
    $pdf->Cell($w[7],6,'Rp. '.number_format($remaining_payment),0,0,'L');
    $pdf->Ln();
    $pdf->Cell($w[0],6,'Remaining Payment','B',0,'L');
    $pdf->Cell($w[1],6,'','B',0,'L');
    $pdf->Cell($w[2],6,'','B',0,'L');
    $pdf->Cell($w[3],6,'','B',0,'L');
    $pdf->Cell($w[4],6,'','B',0,'L');
    $pdf->Cell($w[5],6,'','B',0,'R');
    $pdf->Cell($w[6],6,'','B',0,'R');
    $pdf->Cell($w[7],6,'Rp. '.number_format($remaining_payment),'B',0,'L');
    $pdf->Ln(10);
    $pdf->Cell($w[0],6,'',0,0,'');
    $pdf->Cell($w[1],6,'Supplier',0,0,'R');
    $pdf->Cell($w[2],6,'',0,0,'L');
    $pdf->Cell($w[3],6,'',0,0,'L');
    $pdf->Cell($w[4],6,'',0,0,'L');
    $pdf->Cell($w[5],6,'',0,0,'R');
    $pdf->Cell($w[6],6,'Buyer',0,0,'C');
    $pdf->Cell($w[7],6,'',0,0,'L');
    $pdf->Ln(15);
    $pdf->Cell($w[0],6,'',0,0,'C');
    $pdf->Cell($w[1],6,'(___________________________)',0,0,'L');
    $pdf->Cell($w[2],6,'',0,0,'L');
    $pdf->Cell($w[3],6,'',0,0,'L');
    $pdf->Cell($w[4],6,'',0,0,'L');
    $pdf->Cell($w[5],6,'',0,0,'R');
    $pdf->Cell($w[6],6,'(___________________________)',0,0,'C');
    $pdf->Cell($w[7],6,'',0,0,'L');
    $pdf->Output();
}
else 
{
	echo "Error";
}


require 'koneksi.php';
$sql="select * from tb_transaksi where invoice='".$invoice."' and statuss=0;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$sql = "UPDATE tb_transaksi SET statuss=1, `tnggl`=NOW() WHERE invoice='".$invoice."' and statuss=0";
	if ($conn->query($sql) === TRUE) {
		$sql="INSERT INTO tb_deposit (`date`,invoice, deposit, payment, method) VALUES ('".$date_db."','".$invoice."', 0, ".$remaining_payment.",'".$method."')";
		if($conn->query($sql)===TRUE)
		{
			/*$sql = "SELECT * FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee WHERE invoice='".$invoice."';";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i=0;
				$sum=0;
				while($row = $result->fetch_assoc()) {
					$data[$i][0]=$row["date"];
					$data[$i][1]=$row["invoice"];
					$data[$i][2]=$row["name"];
					$data[$i][3]=$row["item"];
					$data[$i][4]=$row["qty"];
					$data[$i][5]=$row["discount"];
					$data[$i][6]=$row["price"];
					$data[$i][7]=$row["total_price"];
					if($row["status"]==1)
					{
						$data[$i][8]="paid";
					}
					else{
						$data[$i][8]="not paid";
					}
					$sum=$sum+$row["total_price"];
					$i=$i+1;
				}
			} else {
				echo "Error";
			}*/
		}
		else
		{
			echo "Error";
		}
	}
	else
	{
		echo "Error";
	}	
}
//header("location:paymentUnDirect.php")
?>