<!doctype html>
<html>
<?php
session_start();

$title="Update Category";

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
$kategori = mysqli_query($conn, "SELECT * FROM tb_kategori WHERE id=$id;");

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
						<a type="button" href="kategori.php" class="btn btn-danger glyphicon glyphicon-arrow-left"></a>
					</div>
					
					<div class="col-md-8 articles">
						<div class="row">
							<div class="col-md-12" style="margin: 10px 0px">
								<?php while($d=mysqli_fetch_array($kategori)){?>
								<form action="update_kategori.php" method="POST" role="form" id="directPay_div">
								<input type="hidden" class="form-control" name="id" value="<?php echo $d['id'];?>">
									<table>
											<tr>
												
												<td>	<div class="form-group">
											      <label for="usr">Catagory :</label>
											      <input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="catagory" id="usr" value="<?php echo$d['nm_kategori'];?>">
											    </div></td>
											</tr>
											<tr>
												
												<td>	
													<div class="form-group">
														<label for="usr">Description :</label>
														<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="description" id="usr" value="<?php echo $d['description'];?>">
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
