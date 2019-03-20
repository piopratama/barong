<?php
require('mc_table.php');

function GenerateWord()
{
    //Get a random word
    $nb=rand(3,10);
    $w='';
    for($i=1;$i<=$nb;$i++)
        $w.=chr(rand(ord('a'),ord('z')));
    return $w;
}

function GenerateSentence()
{
    //Get a random sentence
    $nb=rand(1,10);
    $s='';
    for($i=1;$i<=$nb;$i++)
        $s.=GenerateWord().' ';
    return substr($s,0,-1);
}

$invoice='2019-01-22 14:49:004';
$deposit=10000;
$remaining_payment=26000;
$grand_total=126000;
$method='transfer';
$payment=26000;
$change=0;
$date=date('d/m/Y');
$customer='';

require 'koneksi.php';

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
    srand(microtime()*1000000);
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

/*session_start();
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
	require('./fpdf181/fpdf.php');

	class PDF extends FPDF
	{
        var $widths;
        var $aligns;

        function SetWidths($w)
        {
            //Set the array of column widths
            $this->widths=$w;
        }

        function SetAligns($a)
        {
            //Set the array of column alignments
            $this->aligns=$a;
        }

        function Row($data)
        {
            //Calculate the height of the row
            $nb=0;
            for($i=0;$i<count($data);$i++)
                $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
            $h=5*$nb;
            //Issue a page break first if needed
            $this->CheckPageBreak($h);
            //Draw the cells of the row
            for($i=0;$i<count($data);$i++)
            {
                $w=$this->widths[$i];
                $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                //Draw the border
                $this->Rect($x,$y,$w,$h);
                //Print the text
                $this->MultiCell($w,5,$data[$i],0,$a);
                //Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            //Go to the next line
            $this->Ln($h);
        }

        function CheckPageBreak($h)
        {
            //If the height h would cause an overflow, add a new page immediately
            if($this->GetY()+$h>$this->PageBreakTrigger)
                $this->AddPage($this->CurOrientation);
        }

        function NbLines($w,$txt)
        {
            //Computes the number of lines a MultiCell of width w will take
            $cw=&$this->CurrentFont['cw'];
            if($w==0)
                $w=$this->w-$this->rMargin-$this->x;
            $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
            $s=str_replace("\r",'',$txt);
            $nb=strlen($s);
            if($nb>0 and $s[$nb-1]=="\n")
                $nb--;
            $sep=-1;
            $i=0;
            $j=0;
            $l=0;
            $nl=1;
            while($i<$nb)
            {
                $c=$s[$i];
                if($c=="\n")
                {
                    $i++;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                    continue;
                }
                if($c==' ')
                    $sep=$i;
                $l+=$cw[$c];
                if($l>$wmax)
                {
                    if($sep==-1)
                    {
                        if($i==$j)
                            $i++;
                    }
                    else
                        $i=$sep+1;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                }
                else
                    $i++;
            }
            return $nl;
        }

		// Colored table
		function FancyTable($header, $data, $sum, $invoice, $nama, $remaining_payment, $method, $payment, $deposit, $change, $date, $customer)
		{
            $this->SetAutoPageBreak(true,10);
		    // Colors, line width and bold font
		    $this->SetFillColor(255,0,0);
		    $this->SetTextColor(255);
		    $this->SetDrawColor(128,0,0);
		    $this->SetLineWidth(.3);
            $this->SetFont('','B', 6);
		    $this->SetTextColor(0);
		    // Header
		    // Move to the right
		    //$this->Cell(80);
            // Title
            //Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
            //$this->Image('logo.jpg',10,10,10);
            $this->Cell(0,4,'Barong',0,1,'C');
            $this->Cell(0,4,'Jalan Puncak Waringin',0,1,'C');
            $this->Cell(0,4,'+62 812 3605 8607',0,1,'C');
            $this->Cell(0,4,'delipointkomodo@gmail.com',0,1,'C');
            $this->Cell(0,4,'---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
            $this->Cell(0,4,"Invoice : ".$invoice,0,1,'L');
            $this->Cell(0,4,"Date : ".$date,0,1,'L');
            $this->Cell(0,4,"Cashier : ".$nama,0,0,'L');
            $this->Cell(0,4,"To : ".$customer,0,1,'C');
			
		    // Line break
		    $this->Ln();
		    $w = array(10, 20, 80, 9, 9, 25, 25, 13);
		    for($i=0;$i<count($header);$i++)
		        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		    $this->Ln();
		    // Color and font restoration
		    $this->SetFillColor(224,235,255);
		    $this->SetTextColor(0);
		    $this->SetFont('');
		    // Data
		    //$fill = false;
		    foreach($data as $row)
		    {
                $this->Row(array($row[0],$row[1],$row[2],$row[3],$row[4],number_format($row[5]),number_format($row[6]),$row[7]));
		        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
				$this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
				$this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
		        $this->Cell($w[5],6,number_format($row[5]),'LR',0,'R',$fill);
		        $this->Cell($w[6],6,number_format($row[6]),'LR',0,'R',$fill);
		        $this->Cell($w[7],6,$row[7],'LR',0,'L',$fill);
		        $this->Ln();
		        $fill = !$fill;
		    }
		    // Closing line
		    $sum=$sum+$sum*0;
            //$this->Cell(100,10,'Grand Total : '.$sum,0,0,'R');
            
            $this->Cell($w[0],6,'Grand Total','T',0,'L');
            $this->Cell($w[1],6,'','T',0,'L');
            $this->Cell($w[2],6,'','T',0,'L');
            $this->Cell($w[3],6,'','T',0,'L');
            $this->Cell($w[4],6,'','T',0,'L');
            $this->Cell($w[5],6,'','T',0,'R');
            $this->Cell($w[6],6,'','T',0,'R');
            $this->Cell($w[7],6,'Rp. '.number_format($deposit),'T',0,'L');
            $this->Ln();
            $this->Cell($w[0],6,'Deposit',0,0,'L');
            $this->Cell($w[1],6,'',0,0,'L');
            $this->Cell($w[2],6,'',0,0,'L');
            $this->Cell($w[3],6,'',0,0,'L');
            $this->Cell($w[4],6,'',0,0,'L');
            $this->Cell($w[5],6,'',0,0,'R');
            $this->Cell($w[6],6,'',0,0,'R');
            $this->Cell($w[7],6,'Rp. '.number_format($remaining_payment),0,0,'L');
            $this->Ln();
            $this->Cell($w[0],6,'Remaining Payment','B',0,'L');
            $this->Cell($w[1],6,'','B',0,'L');
            $this->Cell($w[2],6,'','B',0,'L');
            $this->Cell($w[3],6,'','B',0,'L');
            $this->Cell($w[4],6,'','B',0,'L');
            $this->Cell($w[5],6,'','B',0,'R');
            $this->Cell($w[6],6,'','B',0,'R');
            $this->Cell($w[7],6,'Rp. '.number_format($remaining_payment),'B',0,'L');
            $this->Ln(10);
            $this->Cell($w[0],6,'',0,0,'');
            $this->Cell($w[1],6,'Supplier',0,0,'R');
            $this->Cell($w[2],6,'',0,0,'L');
            $this->Cell($w[3],6,'',0,0,'L');
            $this->Cell($w[4],6,'',0,0,'L');
            $this->Cell($w[5],6,'',0,0,'R');
            $this->Cell($w[6],6,'Buyer',0,0,'C');
            $this->Cell($w[7],6,'',0,0,'L');
            $this->Ln(15);
            $this->Cell($w[0],6,'',0,0,'C');
            $this->Cell($w[1],6,'(___________________________)',0,0,'L');
            $this->Cell($w[2],6,'',0,0,'L');
            $this->Cell($w[3],6,'',0,0,'L');
            $this->Cell($w[4],6,'',0,0,'L');
            $this->Cell($w[5],6,'',0,0,'R');
            $this->Cell($w[6],6,'(___________________________)',0,0,'C');
            $this->Cell($w[7],6,'',0,0,'L');

		}
	}


$invoice='2019-01-22 14:49:004';
$deposit=10000;
$remaining_payment=26000;
$grand_total=126000;
$method='transfer';
$payment=26000;
$change=0;
$date=date('d/m/Y');
$customer='';
$pdf = new PDF();
$header = array('No', 'Date', 'Item', 'Qty', 'Dsc', 'Price (Rp.)', 'Total Price (Rp.)', 'Status');
require 'koneksi.php';
$sql = "SELECT * FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee WHERE invoice='".$invoice."';";
//echo $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) 
{
    $i=0;
    $sum=0;
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
    $pdf->SetFont('Arial','',9);
    $pdf->AddPage();
    $pdf->FancyTable($header,$data, $sum, $invoice, $nama, $remaining_payment, $method, $payment, $deposit, $change, $date, $customer);
    $pdf->Output();
}
else 
{
    echo "Error";
}*/
?>