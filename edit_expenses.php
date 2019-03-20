<!doctype html>
<html>
<?php
session_start();

$title="Update Expense";

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
$expenses = mysqli_query($conn, "SELECT * FROM tb_expenses WHERE id='$id'");
$employee = mysqli_query($conn, "SELECT tb_employee.id, tb_employee.nama FROM tb_employee;");
$category = mysqli_query($conn, "SELECT * FROM tb_kategori");
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
						<a type="button" href="expenses.php" class="btn btn-danger glyphicon glyphicon-arrow-left"></a>
					</div>
					
					<div class="col-md-8 articles">
						<div class="row">
							<div class="col-md-12" style="margin: 10px 0px">
								<?php while($d=mysqli_fetch_array($expenses)){?>
								<form action="update_expenses.php" method="POST" role="form" id="directPay_div">
									<table>
											<tr>
												<td>	
													<div class="form-group">
													<label for="usr">Buyer :</label>
													<input type="hidden" class="form-control" name="id" value="<?php echo $d['id'];?>">
													<select  name="buyer" class="form-control" require="required">
															<option>-- Select Buyer --</option>
															<?php
															foreach($employee as $emp)
															{
															?>
															<option value="<?php echo $emp["id"]; ?>"><?php echo $emp["nama"]; ?></option>
															<?php
															}
															?>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td>	
													<div class="form-group">
													<label for="usr">Category :</label>
													<input type="hidden" class="form-control" name="id" value="<?php echo $d['id'];?>">
													<select  name="category" class="form-control" require="required">
															<option>-- Select Category --</option>
															<?php
															foreach($category as $emp)
															{
															?>
															<option value="<?php echo $emp["nm_kategori"]; ?>"><?php echo $emp["nm_kategori"]; ?></option>
															<?php
															}
															?>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												
												<td>	<div class="form-group">
											      <label for="usr">Date :</label>
											      <input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="date" id="usr" value="<?php echo$d['date'];?>">
											    </div></td>
											</tr>
											<tr>
												
												<td>	<div class="form-group">
											      <label for="usr">item :</label>
											      <input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="item" id="usr" value="<?php echo $d['item'];?>">
											    </div></td>
											</tr>
											<tr>
												
												<td>	<div class="form-group">
											      <label for="usr">Quantity</label>
											      <input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="qty" id="usr" value="<?php echo $d['qty'];?>">
											    </div></td>
											</tr>
											<tr>
												
												<td><label for="usr">Unit</label>
													<input type="text" style="width: 200%; margin-bottom: 10px;" class="form-control" name="unit" id="usr" value="<?php echo $d['unit'];?>"></td>
											</tr>
											<tr>
												<td><label for="usr">Price</label>
													<input type="text" style="width: 200%; margin-bottom: 10px;" class="form-control" name="price" id="usr" value="<?php echo $d['price'];?>"></td>
											</tr>
											<tr>
												<td><label for="usr">Total</label>
													<input type="text" style="width: 200%; margin-bottom: 10px;" class="form-control" name="total" id="usr" value="<?php echo $d['total'];?>"></td>
											</tr>
											

											<tr>
												<td><button type="submit" class="btn btn-success" id="add_item_btn" name=Submit>Submit</button></td>
												
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
