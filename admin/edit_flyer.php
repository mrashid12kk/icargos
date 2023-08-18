<?php

	session_start();

	require 'includes/conn.php';

	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){

		if(isset($_POST['getflayer']) && !empty($_POST['getflayer'])){



		$active_id = $_POST['productid'];

		$data_query = mysqli_query($con,"SELECT flayer_price FROM flayers WHERE id=".$active_id." ");

		$response_data = mysqli_fetch_array($data_query);

		echo $response_data['flayer_price']; exit();

	}

	include "includes/header.php";

	

?>

<style type="text/css">

	.picker-switch .btn{

		display: none;

	}



	input::-webkit-outer-spin-button,

	input::-webkit-inner-spin-button {

	    /* display: none; <- Crashes Chrome on hover */

	    -webkit-appearance: none;

	    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */

	}



	input[type=number] {

	    -moz-appearance:textfield; /* Firefox */

	}

</style>

<body data-ng-app>

<?php

	include "includes/sidebar.php";

?>

    <!-- Aside Ends-->

<section class="content">

          <?php 
	include "includes/header2.php"; ?>

        <!-- Header Ends -->

        

        

        <div class="warper container-fluid">

        		





<?php



$customer_query = mysqli_query($con,"SELECT * FROM customers WHERE status=1");





$flyer_order_id     =  $_POST['flayer_order_id'];

$sql_flyer_index    =  "SELECT * FROM  flayer_order_index WHERE id= ".$flyer_order_id;

$flyer_order_query  =  mysqli_query($con,$sql_flyer_index);

$customer_id        = 0;

$order_date         = "";

$flyer_detail_id    = "";

while($fetch33 = mysqli_fetch_array($flyer_order_query))

{

	$customer_id       = $fetch33['customer'];

	$order_date        = $fetch33['order_date'];

	$flyer_detail_id   = $fetch33['id'];

}





$sql_detail_fly        =  "SELECT * FROM  flayer_orders WHERE flayer_order_index= ".$flyer_detail_id." order by id asc ";

 

$sql_detail_fly_query  =  mysqli_query($con,$sql_detail_fly);



 ?>



<form method="post" action="sell_flyer_update.php"> 	

	<div class="row">

		<div class="col-md-3">

			<a href="add_flayer.php" style="    margin-bottom: 15px;" class="btn btn-info"><?php echo getLange('addnewflyer'); ?></a>

		</div>

	</div>

	<h3><?php echo getLange('sellflyer'); ?></h3>

	<hr></hr>

	

	<div class="right_main">

			

		<div class="row">

			<div class="col-md-8">

				

			</div>

			<div class="col-sm-4">

				<div class="row">

			<div class="col-md-12">

			<div class="form-group">

				<label><?php echo getLange('choosecustomer'); ?></label>

				<select class="form-control flyer_selecter" name="customer">



					<?php while($row = mysqli_fetch_array($customer_query)){ ?>

						<option <?php if($row['id'] == $customer_id){ echo "selected"; } ?> value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"><?php echo isset($row['fname']) ? $row['fname'] : ''; ?></option>

					<?php } ?>

				</select>

			</div>

		</div>

		</div>

		<div class="row">

			<div class="col-md-12">

			<div class="form-group">

				<label><?php echo getLange('date'); ?></label>

				<input type="text" name="order_date" class=" form-control datetimepicker4" value="<?php echo $order_date; ?>">

				<input type="hidden" name="flyer_order" value="<?php echo $flyer_order_id; ?>">

			</div>

		</div>

		</div>

			</div>

		</div>

	</div>

	

		

	



	<div class="row">

		<table class="table table-bordered " id="flayer_tbl">

			<thead>

				<tr>

					<th style="width: 35%;"><?php echo getLange('chooseflyer'); ?></th>

					<th style="width: 10%;"><?php echo getLange('price'); ?></th>

					<th style="width: 10%;"><?php echo getLange('qty'); ?></th>

					<th style="width: 20%;"><?php echo getLange('totalprice'); ?></th>

					<th style="width: 10%;"><?php echo getLange('action'); ?></th>

				</tr>

			</thead>

			<tbody>

				<?php 

					$counter = 0;

					$total_price = 0;

					while($fetch44 = mysqli_fetch_array($sql_detail_fly_query))

					{

						$total_price += $fetch44['total_price'];

						?>





						<tr>

							<?php $flayer_query   = mysqli_query($con,"SELECT * FROM flayers WHERE 1"); ?>

							<td>

								<select  name="flayer[<?php echo $counter; ?>][flayer_id]" class="form-control flyer_selecter choose_product triger_price">

										 

									<?php while($row=mysqli_fetch_array($flayer_query)){ ?>

										<option <?php if($fetch44['flayer'] == $row['id']){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['flayer_name']; ?></option>

									<?php } ?>

								</select>

							</td>

							<td><input type="number" name="flayer[<?php echo $counter; ?>][original_price]" value="<?php echo $fetch44['original_price'] ?>" class="form-control original_price triger_price"></td>





							<td><input type="number" name="flayer[<?php echo $counter; ?>][qty]"  value="<?php echo $fetch44['qty'] ?>"  class="form-control qty triger_price" value="1"></td>





							<td><input type="number" name="flayer[<?php echo $counter; ?>][total_price]"  value="<?php echo $fetch44['total_price'] ?>"   class="form-control total_price triger_price"></td>



							<?php if ($counter == 0){ ?>

								<td><a href="#" class="btn btn-success btn_update"><i class="fa fa-plus"></i></a>  </td>

							<?php }else { ?>



								<td><a href="#" class="btn btn-danger btn_trash"><i class="fa fa-trash"></i></a></td>



							<?php  } 



							$counter++;

							?>

							

						</tr>

				<?php 

					} 

						?>

				<tr>

					<td colspan="3"></td> 

					<td><strong><?php echo getLange('totalprice'); ?> : </strong>  <span id="sub_total"><?php echo $total_price; ?></span></td>

					<td></td>

				</tr>

			</tbody>

				

		</table>





		 

		<input type="submit" name="save_order" class="btn btn-info" value="Update Order">

	</div>

</form>







 </div>

        <!-- Warper Ends Here (working area) -->

        

        

      <?php

	

	include "includes/footer.php";

	}

	else{

		header("location:index.php");

	}

	?>



<script type="text/javascript">

    $(function () {

        $('.datetimepicker4').datetimepicker({

        	format: 'YYYY/MM/DD',

        });

    });





</script>