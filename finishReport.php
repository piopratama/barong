<?php
function rupiah($angka){
	
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
 
}
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
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//require_once './vendor/test.php';
require_once './vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$status=$_POST['status'];
$startDate=$_POST['dateStart'];
$stopDate=$_POST['dateStop'];
$nm_transaksi=$_POST['customer'];

require 'koneksi.php';
if($_POST['Submit']=='Print'){
	if($nm_transaksi!="" && $startDate!="" && $stopDate!="" && $status!="")
    {
        $sql = "SELECT invoice, nm_transaksi, Date(tnggl) as tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item )AS item, qty, discount, total_price, statuss, method FROM tb_transaksi WHERE statuss=".$status." and Date(tnggl)>='".$startDate."' and Date(tnggl)<='".$stopDate."' and nm_transaksi='".$nm_transaksi."';";
    }
    else if($startDate!="" && $stopDate!="" && $status!="")
    {
        $sql = "SELECT invoice, nm_transaksi, Date(tnggl) as tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item )AS item, qty, discount, total_price, statuss, method FROM tb_transaksi WHERE statuss=".$status." and Date(tnggl)>='".$startDate."' and Date(tnggl)<='".$stopDate."';";
    }
    else if($startDate!="" && $stopDate!="")
    {
        $sql = "SELECT invoice, nm_transaksi, Date(tnggl) as tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item )AS item, qty, discount, total_price, statuss, method FROM tb_transaksi WHERE Date(tnggl)>='".$startDate."' and Date(tnggl)<='".$stopDate."';";
    }
    else if($status!="")
    {
        $sql = "SELECT invoice, nm_transaksi, Date(tnggl) as tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item )AS item, qty, discount, total_price, statuss, method FROM tb_transaksi WHERE statuss=".$status.";";
    }
    else
    {
        $sql = "SELECT invoice, nm_transaksi, Date(tnggl) as tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item )AS item, qty, discount, total_price, statuss, method FROM tb_transaksi;";
    }
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$i=0;
		$sum=0;
		$html="<h1>Transaction Deli Shop</h1><table border='1'><tr><td>Date</td><td>Invoice</td><td>Employee</td><td>Customer</td><td>Item</td><td>Qty</td><td>Dsc (10%)</td><td>Total Price</td><td>Method</td><td>Status</td></tr>";
		while($row = $result->fetch_assoc()) {
			$html=$html."<tr>";
			$html=$html."<td>".$row["tnggl"]."</td>";
			$html=$html."<td>".$row["invoice"]."</td>";
			$html=$html."<td>".$row["nama_pegawai"]."</td>";
			$html=$html."<td>".($row["nm_transaksi"]=="" ? "Direct Pay":$row["nm_transaksi"])."</td>";
			$html=$html."<td>".$row["item"]."</td>";
			$html=$html."<td>".$row["qty"]."</td>";
			$html=$html."<td>".$row["discount"]."</td>";
			$html=$html."<td>".$row["total_price"]."</td>";
			$html=$html."<td>".$row["method"]."</td>";
			$html=$html."<td>".($row["statuss"]==1 ? "paid":"not paid")."</td>";
			$html=$html."</tr>";
			$sum=$sum+$row["total_price"];
			$i=$i+1;
		}
		$html=$html."</table>";
		$html=$html."<h1 style='text-align:center'>".rupiah($sum)."</h1>";
		// Write some HTML code:
		$filename=date('Y-m-d').".pdf";
		$mpdf->WriteHTML($html);

		// Output a PDF file directly to the browser
		$mpdf->Output($filename,'I');
		header("location:report.php");
	} 
	else 
	{
		$_SESSION["message"]="No Data to Report";
		header("location:report.php");
	}
}
else if($_POST['Submit']=='Pajak')
{
	if($startDate&&$stopDate!=null){
		$sql = "SELECT tb_kategori.`nm_kategori`, SUM(tb_transaksi.`total_price`) AS total FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.`id`=tb_transaksi.`id_item` 
		LEFT JOIN tb_kategori ON tb_kategori.`id`=tb_barang.`kategori` WHERE DATE(tnggl) BETWEEN '$startDate' AND '$stopDate' and tb_transaksi.statuss=1 GROUP BY tb_kategori.`id`";
	}
	else{
		$sql = "SELECT tb_kategori.`nm_kategori`, SUM(tb_transaksi.`total_price`) AS total FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.`id`=tb_transaksi.`id_item` 
		LEFT JOIN tb_kategori ON tb_kategori.`id`=tb_barang.`kategori` where tb_transaksi.statuss=1 GROUP BY tb_kategori.`id`";
	}

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$i=0;
		$sum=0;
		$html="<h1>Tax Deli Shop</h1><table border='1'><tr><td>Type</td><td>Total</td></tr>";
		while($row = $result->fetch_assoc()) {
			$html=$html."<tr>";
			$html=$html."<td>".$row["nm_kategori"]."</td>";
			$html=$html."<td>".$row["total"]."</td>";
			$html=$html."</tr>";
			$sum=$sum+$row["total"];
			$i=$i+1;
		}
		$html=$html."</table>";
		$html=$html."<h1 style='text-align:center'>".rupiah($sum)."</h1>";
		// Write some HTML code:
		$mpdf->WriteHTML($html);
		$filename="Pajak".date('Y-m-d').".pdf";
		// Output a PDF file directly to the browser
		$mpdf->Output($filename, 'I');
		header("location:report.php");
	}
	else {
		$_SESSION["message"]="No Data to Report";
		header("location:report.php");
	}
}

/*require_once('./fpdf181/fpdf.php');
$start = $_POST['start'];
$end = $_POST['end'];

require 'koneksi.php';
if($_POST['Submit']=='Print'){
	
class PDF extends FPDF
{
	// Colored table
	function FancyTable($header, $data)
	{
	    // Colors, line width and bold font
	    $this->SetFillColor(255,0,0);
	    $this->SetTextColor(255);
	    $this->SetDrawColor(128,0,0);
	    $this->SetLineWidth(.3);
	    $this->SetFont('','B');
	    $this->SetTextColor(0);
	    // Header
	    // Move to the right
	    $this->Cell(80);
	    // Title
	    $this->Cell(30,10,'Deli Shop',0,0,'C');
	    // Line break
	    $this->Ln(20);
	    $w = array(20, 35, 18, 25, 35, 9, 13, 20, 20);
	    for($i=0;$i<count($header);$i++)
	        $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
	    $this->Ln();
	    // Color and font restoration
	    $this->SetFillColor(224,235,255);
	    $this->SetTextColor(0);
	    $this->SetFont('');
	    // Data
	    $fill = false;
	    foreach($data as $row)
	    {
	        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
	        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
	        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
	        $this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
			$this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
			$this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
	        $this->Cell($w[6],6,number_format($row[6]),'LR',0,'R',$fill);
	        $this->Cell($w[7],6,number_format($row[7]),'LR',0,'R',$fill);
	        $this->Cell($w[8],6,$row[8],'LR',0,'L',$fill);
	        $this->Ln();
	        $fill = !$fill;
	    }
	    // Closing line
	    $this->Cell(array_sum($w),0,'','T');
	    $this->Ln();
	    $this->Cell(80);
	}
}

$pdf = new PDF();
// Column headings
$header = array('Date', 'Invoice', 'Employee', 'Customer', 'Item', 'Qty', 'Price', 'Total Price', 'Status');
// Data loading


//$invoice=$_POST['invoice'];
	if($start&&$end!=null){
		$sql = "SELECT DATE(tnggl) as tnggl, invoice, tb_employee.nama, nm_transaksi, tb_barang.item, qty, tb_barang.price, total_price, statuss FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee WHERE DATE(tnggl) BETWEEN '".$start."' AND '".$end."' ";
	}elseif($start!=null&&$end==null){
		$sql = "SELECT DATE(tnggl) as tnggl, invoice, tb_employee.nama, nm_transaksi, tb_barang.item, qty, tb_barang.price, total_price, statuss FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee WHERE DATE(tnggl)='".$start."' ";
	}else{
		$sql = "SELECT DATE(tnggl) as tnggl, invoice, tb_employee.nama, nm_transaksi, tb_barang.item, qty, tb_barang.price, total_price, statuss FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee";
	}
	//$sql = "SELECT * FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$i=0;
		$sum=0;
		while($row = $result->fetch_assoc()) {
			$data[$i][0]=$row["tnggl"];
			$data[$i][1]=$row["invoice"];
			$data[$i][2]=$row["nama"];
			$data[$i][3]=$row["nm_transaksi"];
			$data[$i][4]=$row["item"];
			$data[$i][5]=$row["qty"];
			$data[$i][6]=$row["price"];
			$data[$i][7]=$row["total_price"];
			if($row["statuss"]==1)
			{
				$data[$i][8]="paid";
			}
			else{
				$data[$i][8]="not paid";
			}
			$i=$i+1;
		}
		$pdf->SetFont('Arial','',9);
		$pdf->AddPage();
		$pdf->FancyTable($header,$data);
		$filename=date('Y-m-d').".pdf";
		$pdf->Output('F',$filename);
		$file = $filename;
		$filename = $filename;
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		header('Accept-Ranges: bytes');
		@readfile($file);
		//header("location:report.php");
	} else {
		$_SESSION["message"]="No Data to Report";
		header("location:report.php");
		}
}elseif($_POST['Submit']=='Pajak'){
	class PDF extends FPDF
{
	// Colored table
	function FancyTable($header, $data)
	{
	    // Colors, line width and bold font
	    $this->SetFillColor(255,0,0);
	    $this->SetTextColor(255);
	    $this->SetDrawColor(128,0,0);
	    $this->SetLineWidth(.3);
	    $this->SetFont('','B');
	    $this->SetTextColor(0);
	    // Header
	    // Move to the right
	    $this->Cell(80);
	    // Title
	    $this->Cell(30,10,'Laporan Pajak Deli Shop',0,0,'C');
	    // Line break
	    $this->Ln(20);
	    $w = array(35, 35);
	    for($i=0;$i<count($header);$i++)
	        $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
	    $this->Ln();
	    // Color and font restoration
	    $this->SetFillColor(224,235,255);
	    $this->SetTextColor(0);
	    $this->SetFont('');
	    // Data
	    $fill = false;
	    foreach($data as $row)
	    {
	        
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
	        $this->Cell($w[1],6,number_format($row[1]),'LR',0,'R',$fill);
	        
	        $this->Ln();
	        $fill = !$fill;
	    }
	    // Closing line
	    $this->Cell(array_sum($w),0,'','T');
	    $this->Ln();
	    $this->Cell(80);
	}
}

$pdf = new PDF();
// Column headings
$header = array('Category', 'Total Penjualan' );
// Data loading


//$invoice=$_POST['invoice'];
	if($start&&$end!=null){
		$sql = "SELECT tb_kategori.`nm_kategori`, SUM(tb_transaksi.`total_price`) AS total FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.`id`=tb_transaksi.`id_item` 
		LEFT JOIN tb_kategori ON tb_kategori.`id`=tb_barang.`kategori` WHERE DATE(tnggl) BETWEEN '$start' AND '$end' GROUP BY tb_kategori.`id`";
	}elseif($start!=null&&$end==null){
		$sql = "SELECT tb_kategori.`nm_kategori`, SUM(tb_transaksi.`total_price`) AS total FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.`id`=tb_transaksi.`id_item` 
		LEFT JOIN tb_kategori ON tb_kategori.`id`=tb_barang.`kategori` WHERE DATE(tnggl)='$start' GROUP BY tb_kategori.`id`";
	}else{
		$sql = "SELECT tb_kategori.`nm_kategori`, SUM(tb_transaksi.`total_price`) AS total FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.`id`=tb_transaksi.`id_item` 
		LEFT JOIN tb_kategori ON tb_kategori.`id`=tb_barang.`kategori` GROUP BY tb_kategori.`id`";
	}
	//$sql = "SELECT * FROM tb_transaksi INNER JOIN tb_barang ON tb_barang.id=tb_transaksi.id_item INNER JOIN tb_employee ON tb_employee.id=tb_transaksi.id_employee";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$i=0;
		$sum=0;
		while($row = $result->fetch_assoc()) {
			$data[$i][0]=$row["nm_kategori"];
			$data[$i][1]=$row["total"];
			
			$i=$i+1;
		}
		$pdf->SetFont('Arial','',9);
		$pdf->AddPage();
		$pdf->FancyTable($header,$data);
		$filename="Pajak".date('Y-m-d').".pdf";
		$pdf->Output('F',$filename);
		$file = $filename;
		$filename = $filename;
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		header('Accept-Ranges: bytes');
		@readfile($file);
		//header("location:report.php");
	} 
	else {
		$_SESSION["message"]="No Data to Report";
		header("location:report.php");
	}
}*/
//header("location:mainMenu.php")
?>