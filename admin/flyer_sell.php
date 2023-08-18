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
function getTotal($flayer_id)
{

	$sql_t = "Select * from flayer_orders WHERE flayer_order_index = ".$flayer_id;
	global $con;
	$query11=mysqli_query($con,$sql_t);
	$total = 0;
	while($fetch12=mysqli_fetch_array($query11))
	{
		$total += $fetch12['total_price'];
	}
	return $total;
}


function getCustomer($customer_id)
{

	$sql_c = "Select * from customers WHERE id = ".$customer_id;
	global $con; 
	$query13=mysqli_query($con,$sql_c);
	$fetch13=mysqli_fetch_array($query13);
	return $fetch13;
	
}
  	
?>
<body data-ng-app>
<?php
	include "includes/sidebar.php";
?>

<style>
  .panel-primary > .panel-heading, .panel-primary > .panel-footer {
    border-color: #1e2c59 !important;
    background-color: #1e2c59 !important;
    box-shadow: none !important;
}
.center  form{
	margin-bottom: 0 !important;
}
#basic-datatable button {
    width: auto !important;
}
#basic-datatable form {
    display: inline;
}
</style>
    <!-- Aside Ends-->
<section class="content">
        <?php 
	include "includes/header2.php"; ?>
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
            <div class="row" >
            	<div class="col-lg-12">
            		<a style="    margin-bottom: 10px;" href="new_flyer_sell.php" class="btn btn-info"><?php echo getLange('addnewsellflyer'); ?></a>
            		<div class="panel panel-default"> 
						<div class="panel-heading"><?php echo getLange('sellflyer'); ?> </div>

							<div class="panel-body" id="same_form_layout"  style="padding: 11px;">

								<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

									<div class="row">

										<div class="col-sm-12 table-responsive">

											<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

												<thead>

													<tr role="row">

													   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10% !important;" ><?php echo getLange('srno'); ?></th>
			  			
													   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"  style="width: 15% !important;" ><?php echo getLange('date'); ?></th>
													   

													   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  style="width: 15% !important;" ><?php echo getLange('invoiceno'); ?></th>
													   	<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"  style="width: 15% !important;" ><?php echo getLange('description'); ?></th>
													   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  style="width: 30% !important;"  ><?php echo getLange('customername'); ?></th>
													   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  style="width: 30% !important;"  ><?php echo getLange('customercompany'); ?></th>

													   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  style="width: 15% !important;"  ><?php echo getLange('totalamount'); ?></th>

													   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  style="width: 15% !important;"  ><?php echo getLange('action'); ?></th>
			  
													</tr>

												</thead>

												<tbody>

													<?php 

															$query1=mysqli_query($con,"Select * from flayer_order_index ");
															$counting = 1;
															while($fetch1=mysqli_fetch_array($query1))
															{
																$flayer_order_query = mysqli_query($con,"SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=".$fetch1['id']." ");

																$customer_data = getCustomer($fetch1['customer']); 
																?>

																	<tr class="gradeA odd" role="row">

																		<td class="sorting_1"><?php echo $counting; ?></td>
				 
																		
																		<td><?php echo date("d/m/Y",strtotime($fetch1['order_date'])); ?></td>
																		<td class="center"><?php echo sprintf("%04d",$fetch1['id']); ?></td>
																		<td><?php 
													                      while($rec2 = mysqli_fetch_array($flayer_order_query)){
													                        ?>
													                      <p><b>Flayer: </b><?php echo $rec2['flayer_name']; ?>, <b>Qty: </b><?php echo $rec2['qty']; ?></p>
													                      <?php } ?></td>
																		<td class="center"><?php echo $customer_data['fname']; ?></td>
																		<td class="center"><?php echo $customer_data['bname']; ?></td>
																		
																		<td class="center"> <?php echo getTotal($fetch1['id']); ?></td> 


																		<td class="center">
																			<form action="edit_flyer.php" method="post">
																				<input type="hidden" name="flayer_order_id" value="<?php echo $fetch1['id']; ?>">
																				<button type="submit" name="edit" class="btn_stye_custom"> <span class="glyphicon glyphicon-edit"></span>  </button>
																			</form>

																			<form  target="_blank" action="flayerview.php" method="post">
																				<input type="hidden" name="flayer_order_id" value="<?php echo $fetch1['id']; ?>">
																				<button type="submit" name="view" class="btn_stye_custom"> <span class="glyphicon glyphicon-print"></span>  </button>
																			</form>

																			<form action="sell_flyer_del.php" method="post">
																				<input type="hidden" name="flayer_order_id" value="<?php echo $fetch1['id']; ?>">
																				<button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are you sure you want to delete this flyer??')"><span class="glyphicon glyphicon-trash"></span> </button>
																			</form>

	 																	</td>
				 
																	</tr>

																<?php
																$counting++;

															}
				 
													?>

												</tbody>

											</table>

								

									</div>

								</div>

							</div> 
						</div> 
					</div> 
            	</div> 
            </div>
            
             	
            
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