<!doctype html>
<html>
	<?php
	session_start();
	$title="Add User";

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
	?>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/directPayStyle.css">
	<body>
		
		<div class="container-fluid" style="margin-right: -15px; margin-left: -15px;">

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
								<!-- <ul class="nav navbar-nav">
									<li class="active"><a href="#">Link</a></li>
									<li><a href="#">Link</a></li>
								</ul> -->
								
								<ul class="nav navbar-nav navbar-right">
									<li><a type="button" class="btn btn-danger" style="margin: 10px; padding: 10px;" href="logout.php">Logout</a></li>
									<li><a href=""><!-- <?php  echo $_SESSION['username'];  ?> --> </a></li>
									
									<!-- <li class="">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li><a href="#">Separated link</a></li>
										</ul>
									</li> -->
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
					<a type="button" href="user.php" class="btn btn-danger glyphicon glyphicon-arrow-left"></a>
				</div>
				
				<div class="col-md-8 articles">
					<div class="row">
						<div class="col-md-12" style="margin: 10px 0px">
							<form action="proses_adduser.php" method="POST" role="form" id="directPay_div">
								<table>
									
									<tr>
										
										<td>	<div class="form-group">
											<label for="usr">Name :</label>
											<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="name" id="usr" required="required">
										</div></td>
									</tr>
									<tr>
										
										<td>	<div class="form-group">
											<label for="usr">Address :</label>
											<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="address" id="usr" required="required">
										</div></td>
									</tr>
									<tr>
										
										<td>	<div class="form-group">
											<label for="usr">Sallary :</label>
											<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="sallary" id="usr" required="">
										</div></td>
									</tr>
									<tr>
										
										<td>	<div class="form-group">
											<label for="usr">Phone</label>
											<input type="text" style="width: 200%; margin-bottom: 5px;" class="form-control" name="phone" id="usr" required="required">
										</div></td>
									</tr>
									<tr>
										
										<td><label for="usr">Username</label>
										<input type="text" style="width: 200%; margin-bottom: 10px;" class="form-control" name="username" id="usr" required="required"></td>
									</tr>
									<tr>
										<td><label for="usr">Password</label>
										<input type="text" style="width: 200%; margin-bottom: 10px;" class="form-control" name="password" id="usr" required="required"></td>
									</tr>
									<tr>
										<td><label for="usr">Level</label>
										<select name="level" style="width: 200%; margin-bottom: 10px;" class="form-control" >
											<option value="1">Admin</option>
											<option value="0">Casier</option>
										</select> </td>
									</tr>
									<tr>
										<td><button type="submit" class="btn btn-success" id="add_item_btn" name=Submit>Submit</button></td>
									</tr>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>