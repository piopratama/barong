<!doctype html>
<html>
<?php
session_start();

$title="Add Stock";

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

require('koneksi.php');
$sql = "SELECT * FROM tb_kategori";
$result = $conn->query($sql);
$supplier = "SELECT * FROM tb_supplier";
$result2 = $conn->query($supplier);
?>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/directPayStyle.css">

	<body>
		
			<div class="container-fluid" style="margin-right: -15px; margin-left: -15px;">
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
									<!-- <ul class="nav navbar-nav">
										<li class="active"><a href="#">Link</a></li>
										<li><a href="#">Link</a></li>
									</ul> -->
									
									<ul class="nav navbar-nav navbar-right">
										<li><a ype="button" class="btn btn-danger" style="margin: 10px; padding: 10px;" href="logout.php">Logout</a></li>
										<!-- <li><a href=""><?php  echo $_SESSION['username'];  ?> </a></li> -->
										
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
								</div>
							</div>
						</nav>
					</div>
				</div>
			</div>

			<div class="container">
				<div class="row">

					
					<div class="col-md-4 sidebar">
						
						<a type="clear" class="btn btn-danger glyphicon glyphicon-arrow-left" href="stock.php" ></a>
					</div>
					
					<div class="col-md-8 articles">
						<div class="row">
							<div class="col-md-12" style="margin: 10px 0px">
								<form action="proses_adddata.php" method="POST" role="form" id="directPay_div">
									<table>
										<tr>
											
											<td>
												<div class="form-group">
										      
										      <label>Kategori* :</label>
										      <select class="form-control myItem2" name="category" style="width: 200%;" required="required">
													<option value="">-- Pilih Kategori --</option>
													<?php
														if ($result->num_rows > 0) {
														// output data of each row
														while($row = $result->fetch_assoc()) {
														?>
													<option value="<?php echo $row['id']?>"><?php echo $row['nm_kategori'];?></option>
													<?php
														}
													}
													$conn->close();
													?>
												</select>
												</div>
										</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
										      <label for="barcode">Barcode :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="barcode" id="barcode">
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
										      <label for="usr">Nama Item* :</label>
										      <input type="text" style="width: 200%;" class="form-control" name="name" id="usr" required="required">
												</div>
											</td>
										</tr>
										<tr>
											
											<td>	<div class="form-group">
										      <label for="usr">Harga* :</label>
										      <input type="text" class="form-control" name="price" id="rupiah" style="width: 200%;" required="required" onkeypress="return isNumberKey(event)">
										    </div></td>
										</tr>
										<tr>
											
											<td>	<div class="form-group">
										      <label for="usr">Stok* :</label>
										      <input type="text" class="form-control" name="stock" id="usr" style="width: 200%;" required="required" onkeypress="return isNumberKey(event)">
										    </div></td>
										</tr>
										<tr>
											
											<td>	<div class="form-group">
										      <label for="usr">Satuan*:</label>
										      <input type="text" class="form-control" name="unit" id="usr" style="width: 200%;" required="required">
										    </div></td>
										</tr>
										<tr>
											
											<td>	<div class="form-group">
										      <label for="usr">Harga Pembelian* :</label>
										      <input type="text" class="form-control" name="purchase_price" id="rupiah" style="width: 200%;" required="required" onkeypress="return isNumberKey(event)">
										    </div></td>
										</tr>
										<tr>
										<td>	
										<div class="form-group">
										      
										      <label>Suplaiyer :</label>
										      <select class="form-control myItem2" name="supplier" style="width: 200%;">
													<option value="">-- Pilih Suplaiyer --</option>
													<?php
														if ($result2->num_rows > 0) {
														// output data of each row
														while($row = $result2->fetch_assoc()) {
														?>
													<option value="<?php echo $row['id_supplier']?>"><?php echo $row['nm_supplier'];?></option>
													<?php
														}
													}
														$conn->close();
													?>
												</select>
												</div>
												</td>
										</tr>
										<tr>
											<td><button type="submit" style="margin-top: 10px;" class="btn btn-success" id="add_item_btn" name=Submit>Selesai</button></td>
											
										</tr>
									</table>
								</form>

								
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
