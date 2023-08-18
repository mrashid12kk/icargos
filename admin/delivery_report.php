<style>
.form-control {
    width: 93% !important;
}
.nav-toolbar{
	display: none;
}
table.table-bordered.dataTable th, table.table-bordered.dataTable td {
    border-left-width: 0;
    font-size: 11px;
    padding: 8px 7px;
}
.panel-default{
	margin-top: 18px;
}
.booked_packge{
        max-width: 100%;
    margin: 24px auto 0;
}
.pacecourier_logo {
    float: left;
    width: 20%;
    margin-right: 22px;
}
.pacecourier_logo img {
}
.booked_packges {
    float: right;
    width: 76%;
}
.booked_packges h4 {
    margin: 0;
    font-size: 18px;
}
.booked_packges ul{
        padding: 0;
    margin: 9px 0 0;
}
.booked_packges ul li {
    list-style: none;
    margin-bottom: 1px;
    display: inline-block;
    width: 44%;
    font-size: 14px;
}
.booked_packges li b{
	font-size: 12px;
}
.buttons-print,.buttons-pdf{
	display: none !important;
}
/*.booked_packges ul li b {
    float: left;
    width: 25%;
}*/

</style>

<style>
	@media print {
     
.shipment_report{
	padding:0;
}
}
</style>
<?php
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	session_start();
	require 'includes/conn.php';
	require 'includes/functions.php';

	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
	include "includes/header.php";
	$cities1 = mysqli_query($con,"SELECT * FROM cities WHERE city_name='Islamabad' ");
	$cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
	$drivers = mysqli_query($con,"SELECT * FROM users WHERE type='driver' order by id desc ");
	$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$active_customer_query = "";
if(isset($_GET['active_customer'])){
	$active_customer = $_GET['active_customer'];
	if(empty($active_customer)){
		$active_customer_query = "";
	}else{
		$active_customer_query = " AND customer_id=".$active_customer." ";
	}
}
?>
<body data-ng-app>
 	
    
	<?php
	if(!isset($_GET['print'])){
	include "includes/sidebar.php";
	}
	?>
    <!-- Aside Ends-->
    <?php if(isset($_GET['print'])){ ?>
    <section class="">
    <?php }else{ ?>
    	 <section class="content">
    <?php } ?>	 
	<?php
	if(!isset($_GET['print'])){
	include "includes/header2.php";
}
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            
            <?php
//pickup rider orders

$pickup_rider_search = "";
$delivery_rider = "";
$is_assigned = "0";
$assigned_check = "";
if(isset($_GET['getriders'])){
	$delivery_rider = $_GET['rider_id'];
	$is_assigned = $_GET['is_assigned'];
	if($is_assigned !=""){
		if($is_assigned == 2){
			$assigned_check = " AND assign_driver IS NULL ";
		}elseif($is_assigned == 1){
			$assigned_check = " AND assign_driver != '' ";
		}
	}
	$delivery_rider_search = " AND delivery_zone IN( SELECT id FROM zones WHERE riders='".$delivery_rider."' ) $assigned_check  ";

}



            $active_origin = '';
            $active_destination = '';
            $active_courier = 'All';
if(isset($_GET['submit'])){
		$from = date('Y-m-d',strtotime($_GET['from']));
		$to = date('Y-m-d',strtotime($_GET['to']));
		$origin = $_GET['origin'];
		$destination = $_GET['destination'];
		$status = $_GET['status'];
		$origin_check = '';
		$destination_check = '';
		$active_status = '';

		if(!empty($_GET['origin'])){
			$origin_check = " AND origin = '{$origin}' ";
			 $active_origin = $origin;
		}
		if(!empty($_GET['destination'])){
			$destination_check = " AND destination = '{$destination}' ";
			$active_destination = $destination;
		}
		
		if(!empty($_GET['status'])){
			$status_check = " AND status = '".$status."' ";
			$active_status = $status;
		}
		
		$query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' $origin_check $destination_check $status_check $delivery_rider_search $active_customer_query AND is_received=1 order by id desc ");
	}else{
		$from = date('Y-m-d', strtotime('today - 30 days'));
		$to = date('Y-m-d');
		
		$query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' $delivery_rider_search $active_customer_query AND is_received=1  order by id desc ");
	}
	////////////////////////////////////////////////////////////////////
	$pickup_rider_query = mysqli_query($con,"SELECT GROUP_CONCAT(DISTINCT riders) as riders FROM zones");
	$pickup_rider_rec = mysqli_fetch_array($pickup_rider_query);
	$riders = explode(',', $pickup_rider_rec['riders']);
	$riders_arr = array_unique($riders); 
	$rider_imp = implode(',', $riders_arr);
	$rider_query = mysqli_query($con,"SELECT * FROM users WHERE type='driver' AND id IN(".$rider_imp.")  ");
	///////////////////////////////////////////////////////////////////
 ?>
 <?php 
 if(!isset($_GET['print'])){ ?>
 <div class="row">
    <a target="_blank" style="margin-left: 0;margin-bottom: 10px;" href="<?php echo 'delivery_report.php?'.http_build_query(array_merge($_GET, ['print' => 1])); ?>"  class="btn btn-info" >Print</a>
</div>
<?php } ?>
<?php 
if(!isset($_GET['print'])){
 ?>
  <div id="same_form_layout">
  	<form method="POST" action="savezone.php" id="assign_delivery_rider">
  		<input type="hidden" name="assign_delivery_orders" value="" id="assign_delivery_orders">
  		<input type="hidden" name="rider_id" class="pickup_rider" >
  	</form>
 <div class="zones_main" style="padding: 0;">		
	<form method="GET" action="" >
		
		<div class="row">
		<div class="col-sm-2 left_right_none">
			<label>Select Delivery Rider</label>
			<select name="rider_id"  class="form-control js-example-basic-single rider_id">
				<option value="0" selected disabled>Select Delivery Rider</option>
				<?php while($rec = mysqli_fetch_array($rider_query)){ ?>
				<option value="<?php echo $rec['id']; ?>" <?php if($delivery_rider == $rec['id']){ echo "selected"; } ?> ><?php echo $rec['Name']; ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="col-sm-2 left_right_none"  >
			<select class="form-control" name="is_assigned" style="margin-top: 24px;">
				<option value="" <?php if($is_assigned == 0 ) { echo "selected"; } ?>>All</option>
				<option value="2" <?php if($is_assigned == 2){ echo "selected"; } ?>>Not Assigned</option>
			    <option value="1" <?php if($is_assigned == 1){ echo "selected"; } ?>>Assigned</option>
			</select>
		</div>
		<div class="col-sm-2 left_right_none" style="margin-top: 24px;" >
			<input type="submit" name="getriders" class="btn btn-info" value="Search">
		</div>
		
	</div>
	</form>
	</div>
	</div>
<?php } ?>
<?php if(isset($_GET['print'])){ ?>
	
	<?php 
			$stat_origin = '';
			$stat_destination = '';
			$stat_status = '';
			if($active_origin == ''){
				$stat_origin = 'All';
			}else{
				$stat_origin = $active_origin;
			}
			if($active_destination == ''){
				$stat_destination = 'All';
			}else{
				$stat_destination = $active_destination;
			}
			if($active_status == ''){
				$stat_status = 'All';
			}else{
				$stat_status = $active_status;
			}
			?>
	
<div class="clearfix booked_packge">
  <div class="pacecourier_logo">
   <img <?=isset($_GET['print']) ? '' : 'style="display:none;"';?> src="<?php echo BASE_URL ?>assets/img/logo/logo.png"  alt="..." style="height: 66px;">
  </div>
  <div class="booked_packges ">
    <h4>Delivery Report</h4>
    <ul>
      <li><b>Origin:</b> Islamabad</li>
      <li><b>Destination:</b> <?php echo $stat_destination; ?></li>
      <li><b>Status:</b> <?php echo isset($stat_status) ? $stat_status : ''; ?></li>
      <li><b>From:</b> <?php echo isset($from) ? date('Y-m-d h:i',strtotime($from)) : ''; ?></li>
      <li><b>To:</b> <?php echo isset($to) ? date('Y-m-d h:i',strtotime($to)) : ''; ?></li>
    </ul>
  </div>
</div>
<?php } ?>
<div class="panel panel-default" style="margin-top:0;">
	<?php if(!isset($_GET['print'])){ ?>
	<div class="panel-heading shipment_report order_box" >Delivery Report
		
	</div>
<?php } ?>
		<div class="panel-body" id="same_form_layout">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				
				<div class="row">
					<div class="col-sm-12 table-responsive gap-none bordernone" style="padding:0;">
<?php 
if(!isset($_GET['print'])){
 ?>
 <form method="GET" action="">
				    		<div class="row" >
				    		  <div class="col-sm-2 left_right_none">
				    			<div class="form-group">
										<label>Origin</label>
										<select class="form-control origin js-example-basic-single" name="origin">
											<?php while($row = mysqli_fetch_array($cities1)){ ?>
											<option <?php if($row['city_name'] == 'Islamabad'){ echo "selected"; } ?> <?php if($active_origin == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
										<?php } ?>
										</select>
									</div>
				    		  </div>
				    		  <div class="col-sm-2 left_right_none">
				    			<div class="form-group">
										<label>Destination</label>
										<select class="form-control destination js-example-basic-single" name="destination">
											<option value="" <?php if($active_destination == ''){ echo "selected"; } ?>>All</option>
											<?php while($row = mysqli_fetch_array($cities2)){ ?>
											<option <?php if($active_destination == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
										<?php } ?>
										</select>
									</div>
				    		  </div>
				    		
				    		  <div class="col-sm-1 left_right_none">
				    			<div class="form-group">
										<label>Status</label>
										<select class="form-control status js-example-basic-single" name="status">
											<option value="" <?php if($active_status == ''){ echo "selected"; } ?>>All</option>
											<option value="booked" <?php if($active_status == 'booked'){ echo "selected"; } ?>>Booked</option>
											<option value="received" <?php if($active_status == 'received'){ echo "selected"; } ?>>Received</option>
											<option value="dispatch" <?php if($active_status == 'dispatch'){ echo "selected"; } ?>>Dispatch</option>
											<option value="assigned" <?php if($active_status == 'assigned'){ echo "selected"; } ?>>Assigned</option>
											<option value="pending" <?php if($active_status == 'pending'){ echo "selected"; } ?>>Pending</option>
											<option value="delivered" <?php if($active_status == 'delivered'){ echo "selected"; } ?>>Delivered</option>
											<option value="returned" <?php if($active_status == 'returned'){ echo "selected"; } ?>>Returned</option>
										</select>
									</div>
				    		  </div>
				    		<div class="col-sm-1 left_right_none">
				    			<div class="form-group">
				    				<label>From</label>
				    				<input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
				    			</div>
				    		</div>
				    		<div class="col-sm-1 left_right_none">
				    			<div class="form-group">
				    				<label>To</label>
				    				<input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
				    			</div>
				    		</div>
				    		<div class="col-sm-1 sidegapp-submit left_right_none">
				    			<input type="submit"  name="submit" class="btn btn-info" value="Submit">
				    		</div>
				    		
				    	</div>
				    	</form>
				    <?php } ?>
				    <?php if(isset($_GET['print'])){ ?>
				    	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered no-footer"  role="grid" > 
				    <?php }else{ ?>
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
						<?php } ?>
							<thead>
								<tr role="row">
									<?php if(!isset($_GET['print'])){ ?>
									<th class="center"><input type="checkbox" class="main_select" name=""></th>
								<?php } ?>
									<th style="  font-size: 11px !important;
    padding: 4px 4px;text-align:center;">Sr.NO#</th>
								<th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;">Order#</th>
								    <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;">Order Date#</th>
								  
								   <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Company</th>
   
     <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Address</th>

								   <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Consignee Name</th>
   
    <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Consignee Address</th>

    <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Consignee Phone</th>
								   <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Weight (kg)</th>
								   <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">COD Amount</th>
     <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Zone</th>
      <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Rider</th>
								   
								 
								  <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Received By</th>
								    <?php if(!isset($_GET['print'])){ ?>
								   
								    	 
								    	 <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Action</th>
								  <?php } ?>
								    <?php if(isset($_GET['print'])){ ?>
								    	 <th style="  font-size: 11px !important;
    padding: 8px 4px;text-align:center;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Signature</th>
								    <?php } ?>
								   
								</tr>
							</thead>
							<tbody>
							<?php
							$sr=1;
								// $query1 = '';
									// $query1=mysqli_query($con,"Select * from orders where 1 order by id desc");
								
								while($fetch1=mysqli_fetch_array($query1)){
								
									$get_driver = mysqli_query($con,"SELECT Name FROM users WHERE id =".$fetch1['assign_driver']." "); 
									$customer_data = mysqli_fetch_array($get_driver);
									$customer = mysqli_query($con,"SELECT * FROM customers WHERE id =".$fetch1['customer_id']." "); 
									$cdata = mysqli_fetch_array($customer);

									$delivery_zone_query = mysqli_query($con,"SELECT zone FROM zones WHERE id=".$fetch1['delivery_zone']." ");
									$pickup_zone_array = mysqli_fetch_array($delivery_zone_query);
									$pickup_rider_q = mysqli_query($con,"SELECT * FROM users WHERE id=".$fetch1['assign_driver']." ");
									$pickup_rider_data = mysqli_fetch_array($pickup_rider_q);
							?>
								<tr class="gradeA odd" role="row">
									<?php if(!isset($_GET['print'])){ ?>
									<td class="center"><input type="checkbox" class="order_check" data-id="<?php echo $fetch1['id']; ?>" name=""></td>
								<?php } ?>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="sorting_1"><?php echo $sr; ?></td>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
    <td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="sorting_1"><?php echo date('d M Y',strtotime($fetch1['order_date'])); ?></td>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="center">
									<?php echo $fetch1['sbname']; ?>
										
									</td>
									
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="center">
										 <?php echo $fetch1['sender_address']; ?>
									</td>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="center">
									 <?php echo $fetch1['rname']; ?>
										
									</td>
									
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="center">
										 <?php echo $fetch1['receiver_address']; ?>
										
									</td>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="center">
									 
									 <?php echo $fetch1['rphone']; ?>
									</td>


									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;" class="center">
										<?php echo $fetch1['weight']; ?>
									</td>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;">
						Rs <?php echo $fetch1['collection_amount']; ?>
					</td>
					<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;">
						<?php echo $pickup_zone_array['zone']; ?>
					</td>
					<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;">
						<?php echo $pickup_rider_data['Name']; ?>
					</td>
									
								
								<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;"><?php if($fetch1['status'] == 'delivered'){
    	echo $fetch1['received_by'];
    } ?></td>
								<?php if(!isset($_GET['print'])){ ?>
									<td class="center action_btns" >
										<a title="view order" href="order.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info"> <i class="fa fa-eye"></i></a>
										<a  target="_blank" title="track order" href="<?php echo BASE_URL ?>track-details.php?track_code=<?php echo $fetch1['track_no'] ?>" class="track_order btn btn-success btn-sm track_order" class="btn btn-success"> <i class="fa fa-truck"></i> </a>

										

										

									</td>
								<?php } ?>
								<?php if(isset($_GET['print'])){ ?>
									<td style="font-size: 11px !important;
    padding: 5px 7px;text-align:left;"></td>
								<?php } ?>
								</tr>
								<?php
								$sr++;
								}
								
								?>
							</tbody>
						</table>
						<?php if(!isset($_GET['print'])){ ?>
						<div class="col-sm-2 upate_Btn left_right_none">
			<a href="#" class="assign_delivery_rider btn btn-info">Assign Delivery Rider</a>
		</div>
	<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>	
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	if(!isset($_GET['print'])){
	include "includes/footer.php";
}
	}
	else{
		header("location:index.php");
	}
	?>
	<?php 
	if(isset($_GET['print'])){ ?>
		  <script type="text/javascript">window.print(); </script>
	<?php } ?>
	<script type="text/javascript">
		$('body').on('click','.main_select',function(e){
		var check = $('#basic-datatable').find('tbody > tr > td:first-child .order_check');
		if($('.main_select').prop("checked") == true){
			$('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked',true);
		}else{
			$('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked',false);
		}
		
		$('#basic-datatable').find('tbody > tr > td:first-child .order_check').val();
	})
	</script>
		<script type="text/javascript">
            $(function () {
                $('.datetimepicker4').datetimepicker({
                	format: 'YYYY/MM/DD',
                });
            });
        </script>