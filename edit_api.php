<!doctype html>
<html>
<?php
session_start();

$title="Update Stock";

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

include "koneksi.php";
$id=$_GET['id'];

$sql = "SELECT * FROM tb_api WHERE id=$id";
$result = $conn->query($sql);
?>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/directPayStyle.css">

	<body>
		
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 header">
						<nav class="navbar navbar-default" role="navigation">
							<div class="container-fluid">
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
										<li><a ype="button" class="btn btn-danger" style="margin: 10px; padding: 10px;" href="logout.php">Logout</a></li>
									</ul>
								</div>
							</div>
						</nav>
					</div>
				</div>
			</div>

			<div class="container">
				<div class="row">

					
					<div class="col-md-4 sidebar">
						<a type="button" class="btn btn-danger glyphicon glyphicon-arrow-left"  href="stock.php"></a>
					</div>
					
					<div class="col-md-8 articles">
						<div class="row">
							<div class="col-md-12" style="margin: 10px 0px">
								<?php 

								while($d=mysqli_fetch_array($result)) 
								{
								?>
								<form action="updateApi.php" method="POST" role="form" id="directPay_div">
									<table>
										<tr>												
											<td>	<div class="form-group">
										      <label for="usr">API* :</label>	
										      <input type="text" style="width: 200%;" class="form-control" name="api" id="api" value="<?php echo $d['api'];?>" onkeypress="return isNumberKey(event)">
										    </div></td>
										</tr>
										<tr>												
											<td>	<div class="form-group">
										      <label for="usr">Name* :</label>
										      <input type="hidden" name="id" value="<?php echo $d['id']?>">
										      <input type="text" style="width: 200%;" class="form-control" name="name" id="name" value="<?php echo $d['name'];?>" required="required">
										    </div></td>
										</tr>
										<tr>
											<td>	<div class="form-group">
										      <label for="usr">Port* :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="port" id="port" value="<?php echo $d['port'];?>"  required="required" onkeypress="return isNumberKey(event)">
										    </div></td>
										</tr>
                                        <tr>
											<td>	<div class="form-group">
										      <label for="usr">Address* :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="address" id="address" value="<?php echo $d['address'];?>"  required="required">
										    </div></td>
										</tr>
                                        <tr>
											<td>	<div class="form-group">
										      <label for="usr">Address2 :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="address2" id="address2" value="<?php echo $d['address2'];?>">
										    </div></td>
										</tr>
                                        <tr>
											<td>	<div class="form-group">
										      <label for="usr">Phone :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="phone" id="phone" value="<?php echo $d['phone'];?>" >
										    </div></td>
										</tr>
                                        <tr>
											<td>	<div class="form-group">
										      <label for="usr">Email :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="email" id="email" value="<?php echo $d['email'];?>" >
										    </div></td>
										</tr>
										<tr>
											<td><button type="submit" class="btn btn-success" id="add_item_btn" style="margin-top: 10px;" name=Submit>Update</button></td>
											
										</tr>
										
									</table>
								</form>
								<?php 
								}
								?>
								
							</div>
						</div>
					

					</div>
				</div>
			</div>
			<?php include("./templates/footer.php"); ?>
			<script>
				$(document).ready(function() {
					$(".myItem2").select2();
				});

				/*var rupiah = document.getElementById('rupiah');
				rupiah.addEventListener('keyup', function(e){
					rupiah.value = formatRupiah(this.value);
				})

				function formatRupiah(angka, prefix){
					var number_string = angka.replace(/[^,\d]/g, '').toString(),
					split = number_string.split(','),
					sisa = split[0].length % 3,
					rupiah = split[0].substr(0, sisa),
					ribuan = split[0].substr(sisa).match(/\d{3}/gi);

					if(ribuan){
						separator = sisa ? '.' : '';
						rupiah += separator + ribuan.join('.');
					}

					rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
					return prefix == undefined ? rupiah : (rupiah ?  rupiah : '');
				}*/
			</script>
	</body>
</html>
