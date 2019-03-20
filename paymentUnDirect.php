<?php
session_start();

$title="Payment Undirect";

if(empty($_SESSION['username'])){
	header("location:index.php");
}
else
{
	if(!empty($_SESSION['level_user']))
	{
		if($_SESSION["level_user"]==1)
		{
			header("location:index.php");
		}
	}
}
require 'koneksi.php';
$sql = "SELECT id, nm_transaksi, invoice FROM tb_transaksi where statuss='0' group by invoice;";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/paymentUnDirectStyle.css">
	<body>
		<form method="POST" action="finishTransaction.php" target="_blank">
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
								<div class="collapse navbar-collapse navbar-ex1-collapse">						
									<ul class="nav navbar-nav navbar-right">
										<li><a type="button" class="btn btn-danger" style="margin: 10px; padding: 10px;" href="logout.php">Logout</a></li>
										<li><a href=""><!-- <?php  echo $_SESSION['username'];  ?> --> </a></li>
									</ul>
								</div><!-- /.navbar-collapse -->
							</div>
						</nav>
					</div>
				</div>
			</div>
		<div class="container-fluid">
			<div class="row">
			<div class="col-md-4">
					<div class="col-md-4">
						<a class="btn btn-danger glyphicon glyphicon-arrow-left" href="mainMenu.php" ></a>	
					</div>
				</div>
				<div class="col-md-12">
				<h1 class="text-center">TRANSACTION PAGE</h1>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Date</label>
						<input type="date" class="form-control" id="curr_date" value="<?php echo date('Y-m-d'); ?>" placeholder="" readonly="readonly" name="date">
					</div>
					<div class="form-group">
						<select class="form-control" id="invoice" name="invoice" required="required">
							<option value="">-- Select Name --</option>
							<?php
							if ($result->num_rows > 0) {
								// output data of each row
								while($row = $result->fetch_assoc()) {
							?>
							<option value="<?php echo $row['invoice'] ?>"><?php echo $row['nm_transaksi']; ?></option>
							<?php
								}
							}
							$conn->close();
							?>
						</select>
					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="col-md-12" id="mytable">
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-8"></div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Grand Total</label>
						<input type="text" class="form-control" id="grandTotal" name="grand_total" placeholder="Grand Total" readonly="readonly">
					</div>
					<div class="form-group">
						<label for="">Deposit</label>
						<input type="text" class="form-control" name="deposit" id="deposit" placeholder="Deposit" readonly="readonly" required="required">
					</div>
					<div class="form-group">
						<label for="">Remaining Payment</label>
						<input type="text" class="form-control" name="remaining_payment" id="remainingPay" placeholder="Remaining Payment" readonly="readonly">
					</div>
					<div class="form-group">
						<label for="">Method</label>
						<select class="form-control" name="method" id="method">
							<option value="cash">Cash</option>
							<option value="transfer">Transfer</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Payment</label>
						<input type="text" class="form-control" id="payment" name="payment" placeholder="Payment">
					</div>
					<div class="form-group">
						<label for="">Change</label>
						<input type="text" class="form-control" id="change" name="change" placeholder="Change" readonly="readonly" value="0">
						
						<button type="submit" class="btn btn-primary" id="printBtn" style="margin-top: 20px;">Submit</button>
					</div>
				</div>
			</div>
		</div>
		</form>
		<?php include("./templates/footer.php"); ?>
		<script>
			$(document).ready(function() {
				var invoice='';
				$("#printBtn").attr('disabled', 'disabled');

				$(".form-group").on('keyup','#payment',function(event) {
					if(isNaN($(this).val())==false && $(this).val()!="")
					{
						var remainingPay=parseFloat($("#remainingPay").val());
						$("#change").val(parseFloat($(this).val())-remainingPay);

						if($("#change").val()>=0)
						{
							$("#printBtn").removeAttr('disabled');
						}
						else
						{
							$("#printBtn").attr('disabled', 'disabled');
						}
					}
					else
					{
						$("#printBtn").attr('disabled', 'disabled');
					}
				});
				$("#method").change(function(){
					if($(this).val().trim()=='transfer')
					{
						$("#payment").val($("#remainingPay").val());
						$("#change").val(0);
						$("#printBtn").removeAttr('disabled');
					}
					else
					{
						$("#printBtn").attr('disabled', 'disabled');
					}
				});
				$("#invoice").change(function(event) {
					invoice=$(this).val();
					var grand_total=$("#grandTotal");
					var deposit_pay=$("#deposit");
					var remainingPay=$("#remainingPay");
					$.ajax({
						url: 'getDataTransaction.php',
						type: 'post',
						data: {invoice:invoice},
						success: function (data) {
							//console.log(data);
							//var element=$(data);
							$("#mytable").html(data);
							$('#example').dataTable();
							//element.dataTable();
						}
					});
					$.ajax({
							url: 'getGrandTotal.php',
							type: 'post',
							data: {invoice:invoice},
							success: function (data) {
								grand_total.val(data);
								//$("#grandTotal").val(data);
								$.ajax({
										url: 'getDeposit.php',
										type: 'post',
										data: {invoice:invoice},
										success: function (data) {
											deposit_pay.val(data);
											//$("#deposit").val(data);
											var remaining=parseFloat(grand_total.val())-parseFloat(deposit_pay.val());
											remainingPay.val(remaining);
										}
								});
							}
						});
				});
			});
		</script>
	</body>
</html>