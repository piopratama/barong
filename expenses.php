<?php
session_start();

$title="Expense";

if(empty($_SESSION['username'])){
	header("location:index.php");
}
include_once 'koneksi.php';
$expenses = mysqli_query($conn, "SELECT tb_expenses.*, tb_employee.nama as buyer FROM tb_expenses LEFT JOIN tb_employee on tb_expenses.buyer=tb_employee.id;");
$employee = mysqli_query($conn, "SELECT tb_employee.id, tb_employee.nama FROM tb_employee");

$category = mysqli_query($conn, "SELECT * FROM tb_kategori");

?>
<!DOCTYPE html>
<html>

    <?php include("./templates/header.php"); ?>
	<link rel="stylesheet" type="text/css" href="./css/stockStyle.css">
	<body>
        <div class="container-fluid" style="">
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
                <div class="col-md-12" id="mytable">
                <table id="example" class="table table-bordered" style="width: 100%;">
                    <h1>EXPENSES</h1>
                    
                    <a href="administrator.php" style="margin:0 5px 10px 0" type="button" class="btn btn-danger glyphicon glyphicon-arrow-left" ></a>
                    <button class="btn btn-success glyphicon glyphicon-plus" style="margin-bottom:10px" data-toggle="modal" data-target="#exampleModal"></button>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date Insert</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Buyer</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no=1;
                            foreach ($expenses as $data) {?>
                            <tr>
                                <td><?php echo $no ?></td>
                                <td><?php echo $data["date_insert"];?></td>
                                <td><?php echo $data["date"];?></td>
                                <td><?php echo $data["category"];?></td>
                                <td><?php echo $data["item"];?></td>
                                <td><?php echo $data["buyer"];?></td>
                                <td><?php echo $data["qty"]." ".$data["unit"];?></td>
                                <td><?php echo $data["price"];?></td>
                                <td><?php echo $data["total"];?></td>
                                
                                <td>
                                    <button class="btn btn-danger deleteExpense" id="<?php echo $data['id']; ?>"><span class="glyphicon glyphicon-trash"></span></button>
                                    <a type="button" class="btn btn-success glyphicon glyphicon-pencil" href="edit_expenses.php?id=<?php echo $data['id']?>"></a>
                                </td>
                            </tr>
                            <?php 
                            $no++; 
                            }
                        ?>							
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="insertExpense.php" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Date* :</label>
                                <input type="date" name="date_buy" class="form-control" placeholder="Date Buy" require="required">
                            </div>
                            <div class="form-group">
                                <label>Buyer* :</label>
                                <select class="form-control" name="buyer" require="required">
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
                            <div class="form-group">
                                <label>Category* :</label>
                                <select class="form-control" name="category" require="required">
                                    <option>-- Select Category --</option>
                                    <?php
                                    foreach($category as $emp)
                                    {
                                    ?>
                                    <option value="<?php echo $emp["nm_kategori"]; ?>"> <?php echo $emp["nm_kategori"]; ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Item* :</label>
                                <input type="text" name="item" class="form-control" placeholder="Item" require="required">
                            </div>
                            <div class="form-group">
                                <label>Qty* :</label>
                                <input type="text" name="qty" class="form-control" placeholder="Quantity"  require="required" onkeypress="return isNumberKey(event)">
                            </div>
                            <div class="form-group">
                                <label>Unit :</label>
                                <select class="form-control" name="unit">
                                    <option>-- Select Unit --</option>
                                    <option value="kg">kg</option>
                                    <option value="gr">gr</option>
                                    <option value="pcs">pcs</option>
                                    <option value="krat">krat</option>
                                    <option value="botol">botol</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Price* :</label>
                                <input type="text" name="price" class="form-control" placeholder="Price"  require="required" onkeypress="return isNumberKey(event)">
                            </div>
                            <div class="form-group">
                                <label>Total* :</label>
                                <input type="text" name="total" class="form-control" placeholder="Total"  require="required" onkeypress="return isNumberKey(event)">
                            </div>
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Save Changes">
                        </div>
                    </div>
                </form>
            </div>
        </div>
                                    
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="deleteExpense.php" method="POST">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Delete Expense</h5>
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

		<?php 
			$session_value=(isset($_SESSION['message']))?$_SESSION['message']:'';
			unset($_SESSION['message']);
		?>

		<?php include("./templates/footer.php"); ?>

		<script>
			$(document).ready(function() {
				var message='<?php echo $session_value;?>';
				if(message!="")
				{
					alert(message);
				}
				$("#example").DataTable();

                $("#example").on('click','.deleteExpense', function(){
					$("#id_delete").val($(this).attr('id'));
                    $("#exampleModal2").modal('show');
				});
			});
		</script>
	</body>
</html>