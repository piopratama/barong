<?php 
session_start();
date_default_timezone_set('Asia/Singapore');
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
$api=$_POST['api'];
$name=$_POST['name'];
$port=$_POST['port'];
$address=$_POST['address'];
$address2=$_POST['address2'];
$phone=$_POST['phone'];
$email=$_POST['email'];

mysqli_query($conn, "UPDATE tb_api SET api='$api', `name`='$name', port='$port', `address`='$address', address2='$address2', phone='$phone', email='$email' WHERE id='$id'");
header("location:api.php");
?>