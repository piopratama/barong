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
$id=$_POST['id'];
$category=$_POST['category'];
$name=addslashes($_POST['name']);
$price=$_POST['price'];
$stock=$_POST['stock'];
$unit=$_POST['unit'];
$supplier=$_POST['supplier'];
$purchase=$_POST['purchase_price'];

mysqli_query($conn, "UPDATE tb_barang SET item='$name', price='$price', stock='$stock', unit='$unit', supplier='$supplier', kategori='$category', pur_price='$purchase' WHERE id='$id'");
header("location:stock.php");
?>