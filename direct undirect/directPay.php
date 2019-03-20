<?php
session_start();
if(empty($_SESSION['username'])){
	header("location:index.php");
}
require 'koneksi.php';
$sql = "SELECT * FROM tb_barang";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/directPayStyle.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	</head>
	<body>
		
		<form action="transactionProcess.php" target="_blank" method="POST" accept-charset="utf-8">
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
									<a class="navbar-brand" style="font-size: 40px;" href="#">Deli Shop</a>
								</div>
								<div class="collapse navbar-collapse navbar-ex1-collapse">						
									<ul class="nav navbar-nav navbar-right">
										<li><a type="button" class="btn btn-danger" style="margin: 10px; padding: 10px; color: white" href="logout.php">Logout</a></li>
										<li><a href=""><!-- <?php  echo $_SESSION['username'];  ?> --> </a></li>
									</ul>
								</div><!-- /.navbar-collapse -->
							</div>
						</nav>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-4">
					</div>
					<div class="col-md-4">
						<div id="directPay_div">			
							<div class="form-group">
								<label for="">Date</label>
								<input type="date" class="form-control" id="date" value="<?php echo date('Y-m-d'); ?>" placeholder="" readonly="readonly">
							</div>
							<div class="form-group">
								<label for="">Method</label>
								<select class="form-control" name="method" id="method">
									<option value="cash">Cash</option>
									<option value="transfer">Transfer</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						
						<a class="btn btn-danger glyphicon glyphicon-arrow-left" href="mainMenu.php"></a>
						<a class="btn btn-primary glyphicon glyphicon-plus" id="add_item_btn"></a>
					</div>
					<div class="col-md-4">
						
					</div>
				</div>
				<div class="row" id="parent_item_container">
					<div class="col-md-4" style="position: relative;">
						<i class="glyphicon glyphicon-trash" style="position: absolute;right: 25px;top:20px;"></i>
						<div class="itemForm">
							<div class="form-group">
								<label for="">Item</label>
								
								<select class="form-control myItem" name="item[]">
									<option value="">-- Select Item --</option>
									<?php
									if ($result->num_rows > 0) {
										// output data of each row
										while($row = $result->fetch_assoc()) {
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['item']."(".$row['stock']." ".$row['unit'].")"; ?></option>
									<?php
										}
									}
									$conn->close();
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="">Quantity</label>
								<input type="number" step="any" min="1" class="form-control qtyItem" name="qty[]" placeholder="Quantity">
							</div>
							<div class="form-group">
								<label for="" class="label_price">Price</label>
								<input type="text" class="form-control price" placeholder="Price" readonly="readonly">
							</div>
							<div class="form-group">
								<label for="">Total</label>
								<input type="text" class="form-control total" placeholder="Total" readonly="readonly">
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="parent_price_total">
					<div class="col-md-4">
					</div>
					<div class="col-md-4">
						<div id="extraForm">
							<div class="form-group">
								<label for="">Discount</label>
								<input type="number" class="form-control discount" placeholder="%" readonly="readonly">
							</div>
							<div class="form-group">
								<label for="">PPN</label>
								<input type="number" class="form-control ppn" placeholder="10%" readonly="readonly">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						
						<div class="form-group">
							<label for="">Grand Total</label>
							<input type="text" class="form-control" id="grandTotal" placeholder="Grand Total" readonly="readonly">
						</div>
						<div class="form-group">
							<label for="">Payment</label>
							<input type="text" class="form-control" name="payment" id="payment" placeholder="Payment" required="required">
						</div>
						<div class="form-group">
							<label for="">Change</label>
							<input type="text" class="form-control" id="change" placeholder="Change" readonly="readonly">
						</div>
						<div class="form-group">
							<label for=""></label>
							<input type="text" class="form-control" id="grand">
						</div>
						
						<button type="submit"class="btn btn-primary" id="printBtn">End Transaction</button>

					</div>
				</div>
			</div>
		</form>
		<?php 
			$session_value=(isset($_SESSION['message']))?$_SESSION['message']:'';
			unset($_SESSION['message']);
		?>
		<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<script>
			$(document).ready(function() {
				var message='<?php echo $session_value;?>';
				if(message!="")
				{
					alert(message);
				}

				$("#end_transaction").hide();

				$("#printBtn").click(function(event) {
					var printer = new Recta('3245260761', '1811');
					printer.open().then(function () {
						var x=[];
				        printer.align('center')	
				        .text('DELI SHOP')
				        .bold(true)
				        .text($("#date").val())	
				        .text('------------------------------');
				        printer.align('left')
						.text()
				        .bold(true);
				        
				        $(".qtyItem").each(function() {
				        	x.push({qty:$(this).val(),item:"",price:"",total:""});
				        });
				        var i=0;
				        $(".myItem").each(function() {
				        	x[i].item=$(this).find('option:selected').text();
				        	i=i+1;
				        });
				        i=0;
				        $(".price").each(function() {
				        	x[i].price=$(this).val();
				        	i=i+1;
				        });
				        i=0;
				        $(".total").each(function() {
				        	x[i].total=$(this).val();
				        	i=i+1;
				        });
				        i=0;
				        printer.text("Item").bold(true);
				        printer.text("Qty     Price(Rp)     Total(Rp)")
				        .bold(true);
				        printer.text("");
				        for(var j=0;j<x.length;j++)
				        {
				        	printer.text(x[j].item);
				        	printer.text(x[j].qty+"       "+x[j].price+"     "+x[j].total);
				        	printer.text("");
				        }
				        
				        printer.bold(true);
				        printer.text("------------------------------")
				        printer.text("Grand Total : "+$("#grandTotal").val()).bold(true);
				        printer.text("Payment     : "+$("#payment").val()).bold(true);
				        printer.text("Change : "+$("#change").val()).bold(true)
				        .cut()
				        .print();
				    });
				});

				var html=$("#parent_item_container").html();
				$(".myItem").select2();
				$("#add_item_btn").click(function(event) {
					$("#parent_item_container").append(html);
					$(".myItem").select2();
				});
				$("#parent_item_container").on('click','.glyphicon-trash',function(event){
					$(this).parent().remove();
					$('.total').each(function(i, obj) {
						if(isNaN($(this).val())==false && $(this).val()!="")
						{
							total=total+parseFloat($(this).val());
							total=total+0.1*total;
							$("#grandTotal").val(total);
							var payment=parseFloat($("#payment").val());
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
					var label_price=$(this).parent().next().next().find(".label_price");
					$.ajax({
							url: 'checkItemPrice.php',
							type: 'post',
							data: {id_item:id},
							dataType: 'json',
							success: function (data) {
								//alert(data);
								//console.log(data);
								price_field.val(data[0].price);
								label_price.html("Price ("+data[0].unit+")");
							}
						});
				});
				$("#parent_item_container").on('keyup','.qtyItem',function(event) {
					var qty=$(this).val();
					var price_field=$(this).parent().next().find(".price");
					var total=qty*price_field.val();
					var price_total=$(this).parent().next().next().find('.total');
					var grand=$(this).parent().parent().parents().parents().next().children().next().next().children().next().next().next().find('#grand');
					var grand_total=$(this).parent().parent().parents().parents().next().children().next().next().children().find('#grandTotal');
					price_total.val(total);
					var total=0;
					$('.total').each(function(i, obj) {
						if(isNaN($(this).val())==false && $(this).val()!="")
						{
							total=total+parseFloat($(this).val());
							//total=total+0.1*total;
							grand.val(total);
							var grandtotal=parseFloat(grand.val())+parseFloat(grand.val())*0.1;
							grand_total.val(grandtotal);
							var payment=parseFloat($("#payment").val());
							if(isNaN(payment)==false)
							{
								var change=payment-total;
								$("#change").val(change);
							}
						}
						if(grandtotal>payment){
							alert("error");
						}
					});
				});
				$("#payment").keyup(function(event) {
					var grandTotal=parseFloat($("#grandTotal").val());
					var payment=parseFloat($(this).val());
						if(isNaN(payment)==false)
						{
							var change=payment-grandTotal;
							if(change>=0)
							{
								$("#end_transaction").show();
							}
							else
							{
								$("#end_transaction").hide();
							}
							$("#change").val(change);
						}
						else
						{
							$("#change").val("");
							$("#end_transaction").hide();
						}
				});
			});
		</script>
	</body>
</html>