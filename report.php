<?php
session_start();
$title="Report";

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
include_once 'koneksi.php';

$barang = mysqli_query($conn, "SELECT tb_transaksi.invoice, nm_transaksi, Date(tnggl) as tnggl, (SELECT nama FROM tb_employee WHERE id=id_employee) AS nama_pegawai, (SELECT item FROM tb_barang WHERE id=id_item ) AS item, qty, discount, total_price, statuss FROM tb_transaksi;");

$kategori= mysqli_query($conn, "SELECT TK.nm_kategori, ROUND(SUM(TB.pur_price*TT.qty)) AS pur_price, ROUND(SUM(TT.total_price)) AS income, ROUND(SUM(TT.total_price-TB.pur_price*TT.qty)) AS profit FROM tb_transaksi TT 
INNER JOIN tb_barang TB ON TT.id_item=TB.id INNER JOIN tb_kategori TK ON TB.kategori=TK.id 
WHERE TT.statuss=1 GROUP BY TK.nm_kategori;");

$depositArr= mysqli_query($conn, "SELECT SUM(deposit) AS deposit FROM tb_deposit WHERE invoice IN (SELECT invoice FROM tb_transaksi WHERE tb_transaksi.statuss=0);");

$method= mysqli_query($conn, "SELECT method,SUM(payment+deposit) AS payment FROM tb_deposit GROUP BY method;");

$customer= mysqli_query($conn, "SELECT nm_transaksi, SUM(total_price) AS total_price FROM tb_transaksi WHERE statuss=1 GROUP BY nm_transaksi;");

$debt = mysqli_query($conn, "SELECT nm_transaksi, total_price, deposit FROM tb_transaksi tt INNER JOIN tb_deposit td ON tt.invoice=td.invoice WHERE statuss=0 GROUP BY nm_transaksi;");

$debtCustomerOption = mysqli_query($conn, "SELECT id, nm_transaksi, invoice FROM tb_transaksi where statuss='0' group by invoice;");

$purchase= mysqli_query($conn, "SELECT tb_kategori.`nm_kategori`, SUM(tb_barang.`pur_price`) FROM tb_barang INNER JOIN tb_kategori ON tb_barang.`kategori`=tb_kategori.`id` GROUP BY tb_kategori.`nm_kategori`;");

$deposit=0;
$total_no_deposit=0;
foreach ($depositArr as $depo){
	$deposit=$deposit+$depo["deposit"];
}

$user = mysqli_query($conn, "SELECT * FROM tb_employee");
?>
<!DOCTYPE html>
<html>


	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/stockStyle.css">
		
	<link rel="stylesheet" href="./assets/jquery-ui.css">
	<style>
	.ct-label {
		font-size: 12px;
	}

	#chart-div {
		margin-top: 50px;
	}
	</style>

	<body>
		
		<form action="export_excel_admin.php" method="POST" accept-charset="utf-8">
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
					<div class="col-md-12" id="mytable">
					<a href="administrator.php" style="margin-left: 5px; margin-bottom: 10px;" type="button" class="btn btn-danger glyphicon glyphicon-arrow-left" ></a><br>
					<div style="border-bottom:1px solid #bcbaba; margin-bottom:10px; background-color:#b5b2ac; padding:0 0 0 10px">
						Start: <input style="margin:10px; " type="date" name="dateStart" id="date_start">
						Until: <input style="margin:10px;" type="date" name="dateStop" id="date_end">
						Status:
						<select name="status" id="status">
							<option value="">Select Status</option>
							<option value="1">Paid</option>
							<option value="0">Unpaid</option>
						</select>
						Debt Customer:
						<select name="debt" id="debt">
							<?php 
							foreach ($debtCustomerOption as $dc){
							?>
							<option value="<?php echo $dc['nm_transaksi']?>"><?php echo $dc['nm_transaksi']?></option>
							<?php } ?>
						</select>
						<button type="submit" class="btn btn-success" style="margin-left:10px;">Print</button>
					</div>
					<div id ="table">
						<h1> TABEL REPORT</h1>
						<table id="example" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>ID</th>
									<th>Invoice</th>
									<th>Name</th>
									<th>Date</th>
									<th>Employee</th>
									<th>Item</th>
									<th>QTY</th>
									<th>DSC</th>
									<th>Total Price</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no=1;
								foreach ($barang as $data) {?>
								<tr>
									<td><?php echo $no ?></td>
									<td><?php echo $data["invoice"];?></td>
									<td><?php echo ($data["nm_transaksi"]=="" ? "Direct Pay": $data["nm_transaksi"]);?></td>
									<td><?php echo $data["tnggl"];?></td>
									<td><?php echo $data["nama_pegawai"];?></td>
									<td><?php echo $data["item"];?></td>
									<td><?php echo $data["qty"];?></td>
									<td><?php echo $data["discount"];?></td>
									<td><?php echo rupiah($data["total_price"]);?></td>
									<td><?php if($data["statuss"]==0)
									{
										echo("not paid");
									}
									else{
										echo("paid");
									}
									?></td>
								</tr>
								<?php $no++; }?>							
							</tbody>
						</table><br>
					</div>
					<div id="table2">
						<h1> TABEL REPORT CATEGORY</h1>
						<table id="example2" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>Category</th>
									<th>Purchase Price</th>
									<th>Income</th>
									<th>Profit</th>
									
								</tr>
							</thead>
							<tbody>
								<?php 
								$no=1;
								foreach ($kategori as $i) {?>
								<tr>
									<td><?php echo $i["nm_kategori"];?></td>
									<td><?php echo rupiah($i["pur_price"]);?></td>
									<td><?php echo rupiah($i["income"]);?></td>
									<td><?php echo rupiah($i["profit"]);?></td>
									<?php $total_no_deposit=$total_no_deposit+$i["income"]; ?>
								</tr>
								<?php $no++; }?>							
							</tbody>
						</table><br>
					</div>
					<div id="table3">
						<h1 > TABEL REPORT METHOD</h1>
						<table id="example3" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>Method</th>
									<th>Income</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no=1;
								foreach ($method as $j) {?>
								<tr>
									<td><?php echo $j["method"];?></td>
									<td><?php echo rupiah($j["payment"]);?></td>
								</tr>
								<?php $no++; }?>							
							</tbody>
						</table>
					</div>
					<div id="table4">
						<h1> TABEL REPORT CUSTOMER</h1>
						<table id="example4" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>Customer</th>
									<th>Income</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no=1;
								foreach ($customer as $k) {?>
								<tr>
									<td><?php echo ($k["nm_transaksi"]=="" ? "Direct Pay": $k["nm_transaksi"]);?></td>
									<td><?php echo rupiah($k["total_price"]);?></td>
								</tr>
								<?php $no++; }?>							
							</tbody>
						</table><br>
					</div>
					<div id="table5">
						<h1> TABEL DEBT CUSTOMER</h1>
						<table id="example5" class="table table-bordered" style="width: 100%;">
							
						</table><br>
					</div>
					</div>
					<br><br>
					<!--<div id="chart-div">
						<div class="form-group">
							<label for="year">Year:</label>
							<input type="text" class="form-control" id="year">
						</div>
						<button class="btn btn-primary" id="chartBtn">Submit</button>
						<h3 class="text-center" id="title-chart" style="display: none;">Transaction Chart (in K Rupiah)</h3>
						<div class="ct-chart ct-perfect-fourth"></div>
					</div>-->
				</div>
				<div class="row">
				<div class="col-md-8"></div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Category (Category doesn't include deposit)</label>
						<div class="form-control" id="categoryIncome"><?php echo "Rp.".rupiah($total_no_deposit); ?></div>
					</div>
					<div class="form-group">
						<label for="">Deposit</label>
						<div class="form-control" id="depositIncome"><?php echo "Rp.".rupiah($deposit); ?></div>
					</div>
					<div class="form-group">
						<label for="">Total Income</label>
						<input type="text" class="form-control" readonly="readonly" id="totalIncome" value="<?php echo "Rp.".rupiah($total_no_deposit+$deposit); ?>">
					</div>
				</div>
			</div>
			</div>
		</form>
		<?php 
			$session_value=(isset($_SESSION['message']))?$_SESSION['message']:'';
			unset($_SESSION['message']);
		?>

		<?php include("./templates/footer.php"); ?>
		<script src="./assets/jquery-ui.js"></script>
		<script>
			$(document).ready(function() {
				$('#year').datepicker({
					changeYear: true,
					showButtonPanel: true,
					dateFormat: 'yy',
					onClose: function(dateText, inst) { 
						var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						$(this).datepicker('setDate', new Date(year, 1));
					}
				});
				$("#year").focus(function () {
					$(".ui-datepicker-month").hide();
					$(".ui-datepicker-calendar").hide();
				});
				
				$("#chartBtn").click(function(event){
					event.preventDefault();
					createGraph();
				});

				function createGraph()
				{
					$("#title-chart").show();
					
					$.ajax({
						url: "grafik.php",
						method: 'POST',
						data: {year : $("#year").val()},
						dataType: 'json',
						success: function(result){
							var labels = [];
							var series = [];

							for(var i in result) {
								labels.push(result[i].labels);
								series.push(parseFloat(result[i].series));
							}

							var data = {
							labels: labels,
								series: [
								series
							]};

							var options = {
								seriesBarDistance: 15,
								height: '400px'
							};

							var responsiveOptions = [
							['screen and (min-width: 641px) and (max-width: 1024px)', {
								seriesBarDistance: 10,
								axisX: {
								labelInterpolationFnc: function (value) {
									return value;
								}
								},
							}],
							['screen and (max-width: 640px)', {
								seriesBarDistance: 5,
								axisX: {
								labelInterpolationFnc: function (value) {
									return value[0];
								}
								}
							}]
							];

							new Chartist.Bar('.ct-chart', data, options, responsiveOptions);
						}
					});
				}


				var message='<?php echo $session_value;?>';
				if(message!="")
				{
					alert(message);
				}
				var oTable=$("#example").dataTable();
				var oTable2=$("#example2").dataTable();
				var oTable3=$("#example3").dataTable();
				var oTable4=$("#example4").dataTable();
				
				var html=$("#parent_item_container").html();
				$("#add_item_btn").click(function(event) {
					$("#parent_item_container").append(html);
				});
				$("#parent_item_container").on('click','.glyphicon-trash',function(event){
					$(this).parent().remove();
					$('.total').each(function(i, obj) {
						if(isNaN($(this).val())==false && $(this).val()!="")
						{
							total=total+parseFloat($(this).val());
							total=total+0.1*total;
							$("#grandTotal").val(total);
							var payment=parseFloat($("#deposit").val());
							if(isNaN(payment)==false)
							{
								var change=payment-total;
								$("#change").val(change);
							}
						}
					});
				});
				$("#parent_item_container").on('change','.myItem',function(event) {
					var id=$(this).val();
					var price_field=$(this).parent().next().next().find(".price");
					$.ajax({
							url: 'checkItemPrice.php',
							type: 'post',
							data: {id_item:id},
							success: function (data) {
								//alert(data);
								price_field.val(data);
							}
						});
				});
				$("#parent_item_container").on('keyup','.qtyItem',function(event) {
					var qty=$(this).val();
					var price_field=$(this).parent().next().find(".price");
					var total=qty*price_field.val();
					var price_total=$(this).parent().next().next().find('.total');
					price_total.val(total);
					var total=0;
					$('.total').each(function(i, obj) {
						if(isNaN($(this).val())==false && $(this).val()!="")
						{
							total=total+parseFloat($(this).val());
							total=total+0.1*total;
							$("#grandTotal").val(total);
							var payment=parseFloat($("#deposit").val());
							/*if(isNaN(payment)==false)
							{
								var change=payment-total;
								$("#change").val(change);
							}*/
						}
					});
				});
				$("#payment").keyup(function(event) {
					var grandTotal=parseFloat($("#grandTotal").val());
					var deposit=parseFloat($(this).val());
					/*if(isNaN(payment)==false)
					{
						var change=payment-grandTotal;
						$("#change").val(change);
					}
					else
					{
						$("#change").val("");
					}*/
				});

				$("#printBtn").click(function (e) { 
					var status=$("#status").val();
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					$.ajax({
						type: "post",
						url: "export_excel_admin.php",
						data: {status: status, startDate: startDate, stopDate: stopDate},
						success: function (data) {
							
						}
					});
				});

				$("#status").change(function(){
					getTableCustomerStatus();
					getDataIncome();
					getDataDeposit();
					$("#table").show();
					$("#table2").show();
					$("#table3").show();
					$("#table4").show();
					$("#table5").hide();
				});
				
				$("#date_start").change(function(){
					getTableCategory();
					getTableCustomerStatus();
					getTableMethod();
					getTableCustomer();
					getDataIncome();
					getDataDeposit();
					getDataTotalIncome();
					$("#table").show();
					$("#table2").show();
					$("#table3").show();
					$("#table4").show();
					$("#table5").hide();
				});

				$("#date_end").change(function(){
					getTableCategory();
					getTableCustomerStatus();
					getTableMethod();
					getTableCustomer();
					getDataIncome();
					getDataDeposit();
					getDataTotalIncome();
					$("#table").show();
					$("#table2").show();
					$("#table3").show();
					$("#table4").show();
					$("#table5").hide();
				});

				$("#debt").change(function(){
					$("#table5").show();
					getTableDebt();
					$("#table").hide();
					$("#table2").hide();
					$("#table3").hide();
					$("#table4").hide();
				});

				$("#table5").hide();

				function getTableDebt()
				{
					var customerName=$("#debt").val();
					$.ajax({
							url: 'getTableDebt.php',
							type: 'post',
							data: {customerName:customerName},
							dataType: 'text',
							success: function (data) {
								console.log(data);
								$("#example5").html(data);
								$('#example5').dataTable();
								
								//oTable.fnClearTable();
								
								/*for(var i=0;i<data.length;i++)
								{
									oTable.fnAddData( [
										data[i].no,
										data[i].invoice,
										data[i].nm_transaksi,
										data[i].tnggl,
										data[i].nama_pegawai,
										data[i].item,
										data[i].qty,
										data[i].total_price,
										data[i].status
									]);
								}*/
							}
						});
				}

				function getCustomerStatus()
				{
					var status=$("#status_paid").val();
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					
					if(status!="" && startDate!="" && stopDate!="")
					{
						$.ajax({
							url: 'getStatusCustomer.php',
							type: 'post',
							data: {status: status, dateStart: startDate, dateStop: stopDate},
							dataType: 'text',
							success: function (data) {
								$("#customer").html(data);
								//oTable.fnClearTable();
								
								/*for(var i=0;i<data.length;i++)
								{
									oTable.fnAddData( [
										data[i].no,
										data[i].invoice,
										data[i].nm_transaksi,
										data[i].tnggl,
										data[i].nama_pegawai,
										data[i].item,
										data[i].qty,
										data[i].total_price,
										data[i].status
									]);
								}*/
							}
						});
					}
				}

				function getTableCustomerStatus()
				{
					var status=$("#status").val();
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					//var customer=$("#customer").val();
					$.ajax({
						url: 'getTableStatusCustomer.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate, status: status},
						dataType: 'json',
						success: function (data) {
							oTable.fnClearTable();
							if(data!=[])
							{
								for(var i=0;i<data.length;i++)
								{
									oTable.fnAddData( [
										data[i].no,
										data[i].invoice,
										data[i].nm_transaksi,
										data[i].tnggl,
										data[i].nama_pegawai,
										data[i].item,
										data[i].qty,
										data[i].discount,
										data[i].total_price,
										data[i].status
									]);
								}
							}
						}
					});
				}

				function getTableCategory()
				{
					var status=$("#status").val();
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					$.ajax({
						url: 'getTableCategory.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate, status: status},
						dataType: 'json',
						success: function (data) {
							//console.log(data);
							oTable2.fnClearTable();
							if(data!=[])
							{
								for(var j=0;j<data.length;j++)
								{
									oTable2.fnAddData( [
										data[j].nm_kategori,
										data[j].pur_price,
										data[j].income,
										data[j].profit
									]);
								}
							}
						}
					});
				}

				function getTableMethod()
				{
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					$.ajax({
						url: 'getTableMethod.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate},
						dataType: 'json',
						success: function (data) {
							oTable3.fnClearTable();
							if(data!=[])
							{
								for(var j=0;j<data.length;j++)
								{
									oTable3.fnAddData( [
										data[j].method,
										data[j].payment,
									]);
								}
							}
						}
					});
				}

				function getTableCustomer()
				{
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					$.ajax({
						url: 'getTableCustomer.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate},
						dataType: 'json',
						success: function (data) {
							//console.log(data);
							oTable4.fnClearTable();
							if(data!=[])
							{
								for(var i=0;i<data.length;i++)
								{
									oTable4.fnAddData( [
										data[i].nm_transaksi,
										data[i].total_price,
									]);
								}
							}
						}
					});
				}

				function getDataIncome()
				{
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					var status=$("#status").val();
					$.ajax({
						url: 'getDataIncomeCategory.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate, status: status},
						success: function (data) {
							//console.log(data);
							$("#categoryIncome").text(data);
							if($("#categoryIncome").text()=="")
							{
								$("#categoryIncome").text("0");
							}
						}
					});
				}

				function getDataDeposit()
				{
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					var status=$("#status").val();
					$.ajax({
						url: 'getDataDeposit.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate},
						success: function (data) {
							//console.log(data);
							$("#depositIncome").text(data);
						}
					});
				}

				function getDataTotalIncome()
				{
					var startDate=$("#date_start").val();
					var stopDate=$("#date_end").val();
					var status=$("#status").val();
					$.ajax({
						url: 'getDataTotalIncome.php',
						type: 'post',
						data: {dateStart: startDate, dateStop: stopDate, status: status},
						success: function (data) {
							//console.log(data);
							//$("#totalIncome").val(data);
						}
					});
				}
			});
		</script>
	</body>
</html>