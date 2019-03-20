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

include 'koneksi.php';

$year=$_POST['year'];

$sql="SELECT MONTHNAME(tnggl) as `labels`,SUM(total_price)/100 as `series` FROM tb_transaksi WHERE YEAR(tnggl)=".$year." and tb_transaksi.statuss=1 GROUP BY MONTH(tnggl) order by MONTH(tnggl)";
$result= mysqli_query($conn, $sql);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

echo json_encode($data);
?>