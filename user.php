<?php
session_start();

$title="User Menu";

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
$barang = mysqli_query($conn, "SELECT * FROM tb_barang;");
$user = mysqli_query($conn, "SELECT id, nama, address, sallary, tlp, username, password FROM tb_employee");
?>
<!DOCTYPE html>
<html>
	<?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/stockStyle.css">
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
				

				<div class="col-md-12 articles">
					<div class="row">
					
					
						<table id="example" class="table table-bordered" style="width: 100%">
							<h1>USER</h1>
						
						<a type="button" class="btn btn-danger glyphicon glyphicon-arrow-left" href="administrator.php" style="margin:0 5px 10px 0"></a>
						<a type="button" class="btn btn-primary glyphicon glyphicon-plus" href="add_user.php" style="margin: 0 0 10px 0"></a>
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Address</th>
									<th>Sallary</th>
									<th>tlpn</th>
									<th>Username</th>
									<th>Password</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$no=1;
									foreach($user as $data) {?>
								<tr>
									<td><?php echo $no;?></td>
									<td><?php echo $data["nama"];?></td>
									<td><?php echo $data["address"];?></td>
									<td><?php echo $data["sallary"];?></td>
									<td><?php echo $data["tlp"];?></td>
									<td><?php echo $data["username"];?></td>
									<td><?php echo $data["password"];?></td>
									<td>
										<button class="btn btn-danger deleteUser" id="<?php echo $data['id']; ?>"><span class="glyphicon glyphicon-trash"></span></button>
										<a type="button" class="btn btn-success" href="edit_user.php?id=<?php echo $data['id']?>"><span class="glyphicon glyphicon-pencil"></span></a>
									</td>
								</tr>
								<?php $no++;}?>
								
							</tbody>
						</table>
					</div>
				</div>
		</div>
		
		<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="delete_user.php" method="POST">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Delete User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id_delete" id="id_delete" class="form-control" placeholder="id" require="required">
                        </div>
                        <p>Are you sure want to delete this data ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                    </div>
                </div>
            </form>
        </div>

		<?php include("./templates/footer.php"); ?>

		<script>
			$(document).ready(function() {
				$("#example").DataTable();

				$("#example").on('click','.deleteUser', function(){
					$("#id_delete").val($(this).attr('id'));
                    $("#exampleModal2").modal('show');
				});
			});
		</script>
	</body>
</html>