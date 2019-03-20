<!doctype html>
<html>
<?php
session_start();

$title="Update Supplier";

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
$id=$_GET['id'];
echo $id;
$supplier = mysqli_query($conn, "SELECT * FROM tb_supplier WHERE id_supplier=$id;");

?>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/directPayStyle.css">

	<body>
		
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12 header">
						<nav class="navbar navbar-default" role="navigation">
							<div class="container-fluid">
								<!-- Brand and toggle get grouped for better mobile display -->
								<div class="navbar-header">
									<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
									<a class="navbar-brand" style="font-size: 40px;" href="#">Barong</a>
								</div>
						
								<!-- Collect the nav links, forms, and other content for toggling -->
								<div class="collapse navbar-collapse navbar-ex1-collapse">
					
									<ul class="nav navbar-nav navbar-right">
										<li><a type="button" class="btn btn-danger" style="margin: 10px; padding: 10px;" href="logout.php">Logout</a></li>
									</ul>
								</div><!-- /.navbar-collapse -->
							</div>
						</nav>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">

					
					<div class="col-md-4 sidebar">
						<a type="button" href="supplier.php" class="btn btn-danger glyphicon glyphicon-arrow-left"></a>
					</div>
					
					<div class="col-md-8 articles">
						<div class="row">
							<div class="col-md-12" style="margin: 10px 0px">
								<?php while($d=mysqli_fetch_array($supplier)){?>
								<form action="update_supplier.php" method="POST" role="form" id="directPay_div">
								<input type="hidden" class="form-control" name="id" value="<?php echo $d['id_supplier'];?>">
									<table>
											<tr>
												
												<td>	<div class="form-group">
											      <label for="usr">Supplier :</label>
											      <input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="supplier" id="usr" value="<?php echo$d['nm_supplier'];?>">
											    </div></td>
											</tr>
											<tr>
												
												<td>	
													<div class="form-group">
														<label for="usr">Address :</label>
														<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="address" id="usr" value="<?php echo $d['address'];?>">
													</div>
												</td>
											</tr>
                                            <tr>
												
												<td>	
													<div class="form-group">
														<label for="usr">Phone :</label>
														<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="phone" id="usr" value="<?php echo $d['no_hp'];?>">
													</div>
												</td>
											</tr>

											<tr>
												<td>
													<button type="submit" class="btn btn-success" id="add_item_btn" name=Submit>Submit</button>
												</td>
											</tr>
										
									</table>
								</form>

								<?php }?>
							</div>
						</div>
					

					</div>
				</div>
			</div>
	</body>
</html>
