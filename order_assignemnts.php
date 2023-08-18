<?php
	session_start();
	include_once "includes/conn.php";
$id = $_SESSION['customers'];
$filter_query = "";
$active_tracking = "";
$selected_branch = '';
if(isset($_SESSION['customers'])){
	$_GET['id'] = $_SESSION['customers'];
	include "includes/header.php";


if (isset($_POST['submit'])) {
	if (isset($_POST['assignment_no']) && !empty($_POST['assignment_no'])) {
		$filter_query .= " AND assignment_no = '" . $_POST['assignment_no'] . "' ";
		$active_tracking = $_POST['assignment_no'];
	}

	$from = date('Y-m-d', strtotime($_POST['from']));
	$to = date('Y-m-d', strtotime($_POST['to']));

	$query1 = mysqli_query($con, "SELECT * FROM assignments WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "'  AND business_ids='".$_SESSION['customers']."' AND assignment_type='Pickup' $filter_query order by id desc ");
} else {
	$from = date('Y-m-d');
	$to = date('Y-m-d');
	// echo  "SELECT * FROM assignments WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "'  AND business_ids='".$id."' $filter_query  order by id desc ";
	// die;
	$query1 = mysqli_query($con, "SELECT * FROM assignments WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "'  AND business_ids='".$id."' AND assignment_type='Pickup' $filter_query  order by id desc ");
}
?>
<style>
		@media (max-width: 1199px){
			.container{
				width: 1000px;
			}
		}
		@media (max-width: 1024px){
			.container{
				width: 740px;
			}
			.padding30 .dashboard {
		        margin-top: 20px !important;
		    }
		    .dashboard .white{
		      padding: 0 !important;
		    }
		    section .dashboard .white{
		    	box-shadow:none !important;
		    	/*display: none;*/
		    }
		}
		@media (max-width: 767px){
			.container{
				width: auto;
			}
			
		}
</style>
<section class="bg padding30">
  <div class="container-fluid dashboard">
   <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
      <!--sidebar come here!-->
	  <?php
	  $page_title = 'Payments';
$is_profile_page = true;
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
    	<form method="POST" action="">
    		<div class="row">
			<div class="col-md-3">
    			<div class="form-group">
    				<label>Assignment No</label>
    				<input type="text" value="<?php echo $active_tracking; ?>" class="form-control" name="assignment_no">
    			</div>
    		</div>
    		<div class="col-md-3">
    			<div class="form-group">
    				<label>From</label>
    				<input type="text" value="<?php echo $from; ?>" class="form-control datepicker" name="from">
    			</div>
    		</div>
    		<div class="col-md-3">
    			<div class="form-group">
    				<label>To</label>
    				<input type="text" value="<?php echo $to; ?>" class="form-control datepicker" name="to">
    			</div>
    		</div>
    		<div class="col-md-2">
    			<input type="submit" style="margin-top: 23px; color: #fff !important;" name="submit" class="btn btn-info" value="Submit">
    		</div>
    	</div>
    	</form>
      <div class="white hide-on-tab">
      <table class=" table table-striped table-bordered dataTable no-footer">
      	<thead>
      		<tr role="row">

				<th><?php echo getLange('srno'); ?></th>
				<th><?php echo getLange('assignmentno'); ?> </th>
				<th><?php echo getLange('rider'); ?></th>
				<th><?php echo getLange('date'); ?></th>
				<th><?php echo getLange('action'); ?></th>
			</tr>
      	</thead>
      	<tbody>

							<?php

							$sr = 1;
							while ($fetch1 = mysqli_fetch_array($query1)) {
								$rider_id = $fetch1['rider_id'];

								$courier_query = mysqli_query($con, "SELECT Name FROM users WHERE type='driver' AND id='" . $rider_id . "' ");
								$fetch_row = mysqli_fetch_array($courier_query);

								$rider_name = isset($fetch_row['Name']) ? $fetch_row['Name'] : '';

								
							?>
								<tr class="gradeA odd" role="row">

									<td><?php echo $sr; ?></td>
									<td class="sorting_1"><?php echo $fetch1['assignment_no']; ?></td>
									<!-- <td class="sorting_1"><?php echo $fetch1['assignment_type']; ?></td> -->
									<td class="sorting_1"><?php echo $rider_name; ?></td>
									<td class="sorting_1"><?php echo date('Y-m-d', strtotime($fetch1['created_on'])); ?></td>
									<td class="">

										<?php if ($fetch1['assignment_type'] == 'Pickup') { ?>
											<a target="_blank" title="view assignment" href="customer_pickup_sheet.php?assignment_no=<?php echo $fetch1['assignment_no']; ?>" class="btn btn-info"> <i class="fa fa-print"></i></a>
										<?php }  ?>
									</td>

								</tr>

							<?php
								$sr++;
							}
							?>

						</tbody>
      </table>
      </div>
      <div class="order_info-details">
      	
      	<!-- <h4 class="Order_list" style="color:#000;">Customer Detail Report</h4> -->
			<ul id="results"></ul>
		</div>
    </div>
  </div>
</section>
</div>
<?php
}
else{
	header("location:index.php");
						
}
?>
	 <script type="text/javascript" src="js/ajax_load_data.js"></script>
	 <script type="text/javascript">
	 	$('.datepicker').datepicker({
	 		format: 'yyyy/mm/dd',
	 	});
	 (function($){	
	 $("body").on('click', ".open_first_order a", function(){
		    $(this).closest('li').find('.down_box_order').slideToggle();
	 });
	 
	if($('#results').length > 0) {
		 $("#results").loaddata({
		 	data_url: 'payments.php',
		 	end_record_text: ''
		 });
	}
	})(jQuery);
	 </script>
	 <?php include 'includes/footer.php'; ?>