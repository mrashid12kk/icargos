<?php
	session_start();
	include_once "includes/conn.php";
$id = $_SESSION['customers'];
if(isset($_POST["page"])) {
	//sanitize post value
	$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	//throw HTTP error if page number is not valid
	if(!is_numeric($page_number)){
	    header('HTTP/1.1 500 Invalid page number!');
	    exit;
	}
	$item_per_page = 8;
	//get current starting point of records
	$position = (($page_number-1) * $item_per_page);
	$query2=mysqli_query($con,"SELECT * FROM payments WHERE customer_id=$id  AND status = 'Approved'  LIMIT $position, $item_per_page") or die(mysqli_error($con));
	if($query2) {
		$collection_amount = 0;
		$dquery=mysqli_query($con,"SELECT SUM(collection_amount) as total FROM orders WHERE is_amount_collected = 1 AND customer_id=".$_GET['id']);
		if($dquery) {
			$total = mysqli_fetch_object($dquery)->total;
			$collection_amount = ($total) ? $total : 0;
		}
		$balance = $collection_amount;
		while($fetch=mysqli_fetch_array($query2)){
			$balance -= (float)$fetch['amount']; ?>
				<li class="bdr-btm">
					<div class="open_first_order">
						<a href="#">
							<b><strong>Payment Type:</strong> <?php echo $fetch['type']; ?></b>
							<b><strong>Paid Amount:</strong> <?php echo $fetch['amount']; ?></b>
							<b><strong>Date:</strong> <?php echo $fetch['date']; ?><i class="fa fa-angle-down"></i></b>
						</a>
					</div>
					<div class="down_box_order">
						<ul>
							<li><i class="fa fa-check"></i> <strong>Payment Type:</strong> <?php echo $fetch['type']; ?></li>
							<li><i class="fa fa-check"></i> <strong>Date:</strong> <?php echo $fetch['date']; ?></li>
							<li><i class="fa fa-check"></i> <strong>Collection Amount:</strong> <?php echo $collection_amount; ?></li>
							<li><i class="fa fa-check"></i> <strong>Paid Amount:</strong> <?php echo $fetch['amount']; ?></li>
							<li><i class="fa fa-check"></i> <strong>Balance:</strong> <?php echo $balance; ?></li>
						</ul>
					</div>
				</li>
			<?php 
			$collection_amount = $balance;
		}
	}
	exit();
}	
	if(isset($_POST['submit'])){
		$from = date('Y-m-d',strtotime($_POST['from']));
		$to = date('Y-m-d',strtotime($_POST['to']));
		
		$row = mysqli_query($con,"SELECT * FROM ledger WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '".$to."' AND customer_id =".$id." ORDER BY id ASC ");
	}else{
		$from = date('Y-m-01');
		$to = date('Y-m-t');
		$row = mysqli_query($con,"SELECT * FROM ledger WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '".$to."' AND customer_id =".$id." ORDER BY id ASC ");
	}
if(isset($_SESSION['customers'])){
	$_GET['id'] = $_SESSION['customers'];
	include "includes/header.php";
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
      		<tr>
      			<th>Sr.No</th>
      			<th>Date</th>
      			<th>Type</th>
      			<th>Order No</th>
      			<th>Delivery Charges</th>
      			<th>Collected Amount</th>
      			<th>Paid</th>
      			<th>Balance</th>
      		</tr>
      	</thead>
      	<tbody>
      		<?php 
      		$sr=1;
      		while($record = mysqli_fetch_array($row)){
      			$balance -= (float)$record['delivery_charges'];
      			$balance += (float)$record['collected_amount'];
      			$balance -= (float)$record['paid'];
      		 ?>
      		
      		<tr>
      			<td><?php echo $sr; ?></td>
      			<td><?php echo date('d M Y',strtotime($record['created_on'])); ?></td>
      			<td><?php echo $record['ledger_type']; ?></td>
      			<td><?php echo $record['order_no']; ?></td>
      			<td><?php echo number_format($record['delivery_charges'],2); ?></td>
      			<td><?php echo number_format($record['collected_amount'],2); ?></td>
      			<td><?php echo number_format($record['paid'],2); ?></td>
      			<td><?php echo number_format($balance,2); ?></td>
      		</tr>
      	<?php $sr++; } ?>
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