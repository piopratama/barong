<!DOCTYPE html>
<html>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="css/transactionListStyle.css">
	<body>
		
		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<form action="" method="POST" role="form">
					
						<div class="form-group">
							<label for="" class="text_center">Invoice</label>
							<input type="text" class="form-control" name="invoice" id="invoice" placeholder="Search">
						</div>									
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>

	</body>
</html>