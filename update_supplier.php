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
$supplier=$_POST['supplier'];
$address=$_POST['address'];
$phone=$_POST['phone'];
mysqli_query($conn, "UPDATE tb_supplier SET nm_supplier='$supplier', address='$address', no_hp='$phone' WHERE id_supplier=$id;");

header("location:supplier.php");
?>