<!doctype html>
<html>
<?php
session_start();

$title="Add Expired";

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

$barang = mysqli_query($conn, "SELECT te.id, tb.item, te.qty, te.expired_date from tb_expired te inner join tb_barang tb on te.id_item=tb.id");

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
            <?php
                require 'koneksi.php';
                $sql="SELECT * FROM tb_barang;";
                $result2 = $conn->query($sql);
            ?>
			<div class="container">
				<div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <form action="add_expired_process.php" method="POST">
                            <div class="form-group">
                                <label>Item</label>
                                <select class="form-control item" name="item" theme="google" data-search="true">
									<option value="">-- Select Item --</option>
									<?php
									if ($result2->num_rows > 0) {
										// output data of each row
										while($row2 = $result2->fetch_assoc()) {
											if($row2['stock']==0)
											{
									?>
												<option value="<?php echo $row2['id'] ?>" disabled="disabled"><?php echo $row2['item']."*(".$row2['stock']." ".$row2['unit'].")"; ?></option>
									<?php
											}
											else
											{
									?>
												<option value="<?php echo $row2['id'] ?>"><?php echo $row2['item']."*(".$row2['stock']." ".$row2['unit'].")"; ?></option>
									<?php
											}
										}
									}
									$conn->close();
									?>
								</select>
                            </div>
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number" name="qty" step="any" min="0" class="form-control" id="qty" placeholder="Quantity">
                            </div>
                            <div class="form-group">
                                <label>Expired Date</label>
                                <input type="date" name="expired_date" class="form-control" id="expired_date" placeholder="Expired Date">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="col-md-4">
                    </div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<a href="administrator.php" style="margin:0 5px 10px 0" type="button" class="btn btn-danger glyphicon glyphicon-arrow-left"></a>
						<table id="example" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>Item</th>
									<th>Quantity</th>
									<th>Date Expired</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no=1;
								foreach ($barang as $data) {?>
								<tr>
									<td><?php echo $data["item"]; ?></td>
									<td><?php echo $data["qty"];?></td>
									<td><?php echo ($data["expired_date"]);?></td>
									<td>
										<!--<a type="button" id="updateBtn_<?php echo $data['id']; ?>" class="btn btn-success updateBtn"><span class="glyphicon glyphicon-pencil"></span></a>-->
										<a type="button" id="deleteBtn_<?php echo $data['id']; ?>" class="btn btn-danger deleteBtn"><span class="glyphicon glyphicon-trash"></span></a>
									</td>
								</tr>
								<?php $no++; }?>							
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<form action="update_expired_process.php" method="POST">
				<div id="myModalUpdate" class="modal fade" role="dialog">0
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Update</h4>
							</div>
							<div class="modal-body">
								<input type="text" name="id_update" id="id_update" value="<?php echo $data['id']; ?>" style="visibility:hidden;">
								<div class="form-group">
									<label>Item</label>
									<select class="form-control item" id="itemUpdate" name="item" theme="google" data-search="true" style="width: 100% !important;">
										<option value="">-- Select Item --</option>
										<?php
										mysqli_data_seek($result2,0);
										if ($result2->num_rows > 0) {
											// output data of each row
											while($row2 = $result2->fetch_assoc()) {
												if($row2['stock']==0)
												{
										?>
													<option value="<?php echo $row2['id'] ?>" disabled="disabled"><?php echo $row2['item']."*(".$row2['stock']." ".$row2['unit'].")"; ?></option>
										<?php
												}
												else
												{
										?>
													<option value="<?php echo $row2['id'] ?>"><?php echo $row2['item']."*(".$row2['stock']." ".$row2['unit'].")"; ?></option>
										<?php
												}
											}
										}
										$conn->close();
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Qty</label>
									<input type="number" name="qty" id="qtyUpdate" step="any" min="0" class="form-control" placeholder="Quantity">
								</div>
								<div class="form-group">
									<label>Expired Date</label>
									<input type="date" name="expired_date" id="expired_dateUpdate" class="form-control" placeholder="Expired Date">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-default" data-dismiss="modal">Submit</button>
							</div>
						</div>

					</div>
				</div>
			</form>

			<form action="delete_expired_process.php" method="POST">
				<div id="myModalDelete" class="modal fade" role="dialog">
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Delete</h4>
							</div>
							<div class="modal-body">
								<input type="text" name="id_delete" id="id_delete" style="visibility:hidden;">
								<p>Are you sure want to delete this data ?</p>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-default" data-dismiss="modal">Submit</button>
							</div>
						</div>

					</div>
				</div>
			</form>

			<?php include("./templates/footer.php"); ?>
			<script>
				$(document).ready(function() {
					$(".item").select2();
					var oTable=$("#example").dataTable();

					$(".deleteBtn").click(function(){
						var str=$(this).attr("id");
						$("#myModalDelete").modal('show');
						var id_delete=str.split("_")[1];
						$("#id_delete").val(id_delete);
					});

					/*$(".updateBtn").click(function(){
						$.ajax({
							url: 'getUpdateExpired.php',
							type: 'post',
							data: {id_item:id},
							dataType: 'json',
							success: function (data) {
								$("#expired_dateUpdate").val(data)
							}
						});
						var str=$(this).attr("id");
						$("#myModalUpdate").modal('show');
						var id_update=str.split("_")[1];
						$("#id_update").val(id_update);
					});*/
				});
			</script>
	</body>
</html>
