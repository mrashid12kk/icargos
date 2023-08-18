<?php
	session_start();
	include_once "includes/conn.php";
$id = $_SESSION['customers'];
function encrypt($string) {
		$key="usmannnn";
		  $result = '';
		  for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		  }

		  return base64_encode($result);
	}
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
	$query2=mysqli_query($con,"SELECT * FROM orders WHERE customer_id=$id ORDER BY id DESC LIMIT $position, $item_per_page") or die(mysqli_error($con));
	if($query2) {
		while($row = mysqli_fetch_object($query2)) { 
			if(isset($row->invoice_status) || $row->invoice_status == ''){
			 $invoice_status='Not paid';
		 	}
		 	 $statusss = isset($row->status) ? $row->status : 'Pending';
			if($statusss == 'in process' || $statusss == 'accepted') {
				$statusss = 'On the Way';
			}
		?>
		<li class="bdr-btm">
			<div class="open_first_order">
				<a href="#">
					<b>Invoice# <?php echo $row->track_no; ?></b>
					<b>Receiver Phone no: <?php echo $row->rphone; ?><i class="fa fa-angle-down"></i></b>
					<b>Invoice Status: <?php echo $invoice_status; ?></b>
				</a>
			</div>
			<div class="down_box_order">
				<ul>
					<li><i class="fa fa-check"></i> <strong>Invoice#</strong> <?php echo $row->track_no; ?></li>
					<li><i class="fa fa-check"></i> <strong>Receiver City:</strong> <?php echo $row->receiver_address; ?></li>
					<li><i class="fa fa-check"></i> <strong>Receiver Phone:</strong> <?php echo $row->rphone; ?></li>
					<li><i class="fa fa-check"></i> <strong>Invoice Status:</strong> <?php echo $invoice_status; ?></li>
					<li><i class="fa fa-check"></i> <strong>Status:</strong> <?php echo $statusss; ?></li>
					<li><i class="fa fa-check"></i> <strong>Collection Amount:</strong> <?php echo $row->collection_amount; ?></li>
					<li><i class="fa fa-check"></i> <strong>Delivery Amount:</strong> <?php echo $row->price; ?></li>
					<li><i class="fa fa-check"></i> <strong>Total:</strong> <?php echo ((int)$row->collection_amount + (int)$row->price); ?></li>
					<li><a href="invoicehtml.php?id=<?php echo encrypt($row->id."-usUSMAN767###");?>" class="btn btn-info" target="-blank">View Invoice</a></li>
				</ul>
			</div>
		</li>
	<?php
		}
	}
	exit();
}
	
	if(isset($_SESSION['customers'])){
		include "includes/header.php";
	
	
	if(isset($_POST['attached'])){
		$target_dir = "invoices_att/";
		if($_FILES["invoice_attachment"]["name"]!=""){
			$target_file = $target_dir .uniqid(). basename($_FILES["invoice_attachment"]["name"]);
			$extension = pathinfo($target_file,PATHINFO_EXTENSION);
			if($extension!=='php') {
				move_uploaded_file($_FILES["invoice_attachment"]["tmp_name"],$target_file);
				$queryy=mysqli_query($con,"update orders set invoice_attachment='$target_file',invoice_status='pending' where id=".$_POST['id']) or die(mysqli_error($con));
				$msg= '<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> Alert!</h4>
					Success.You successfully upload your invioce attachment
				  </div>';
			}
		}
	}

?>
<section class="bg padding30">
  <div class="container-fluid dashboard">
   <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
      <!--sidebar come here!-->
	  <?php
	  $page_title = 'Invoices';
	$is_profile_page = true;
		include "includes/sidebar.php";
	  ?>
    </div>
    <style type="text/css">
	  	table th {
	  		color: #8f8f8f;
	  	}
	  	.table-bordered tr td{
	  		color: #000;
	  	}
	  	.white h4{
	  		color: #000;
	  	}
	  		section .dashboard .white {
		    background: #fff;
		    padding: 20px;
		    box-shadow: 0 0 3px #ccc;
		    width: 99%;
		    display: inline-block;
		}
		.btn-default {
		    min-width: 60px;
		}
		.sorting{width: 100%;}
		@media (max-width: 1199px){
			.container{
				width: 1000px;
			}
		}
		@media (max-width: 1024px){
			.container{
				width: 740px;
			}
			.white br {
				display: none;
			}
			.padding30 .dashboard {
		        margin-top: 20px !important;
		    }
		    .dashboard .white{
		      padding: 0 !important;
		    }
		    section .dashboard .white{
		    	box-shadow:none !important;
		    }

		}
		@media (max-width: 767px){
			.container{
				width: auto;
			}
			

		}
	  </style>
    <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
      <div class="white">
	  <?php
	  $query1=mysqli_query($con,"select * from orders where customer_id=$id order by id desc") or die(mysqli_error($con));
	
	  ?>
	  <h4 class="Order_list">Invoices</h4>
	  <?php
	  if(isset($msg))
		  echo $msg;
	  
	  ?>
	  <br>
	  <br>
        <table class="table table-hover table-bordered dataTable hide-on-tab">
			<thead>
				<tr>
					<th>Invoice#</th>
				  	<th>Receiver City</th>
					<th>Receiver Phone no.</th>
					<th>Invoice Status</th>
					<th>Delivery Status</th>
					<th>Collection Amount</th>
					<th>Delivery Amount</th>
					<th>Total</th>
					<th>View Invoices</th>
					<th>Admin Attachments</th>
					<!-- <th>Your Attachments</th> -->
				</tr>
			</thead>
			<tbody>
			 <?php 
			 while($fetch1=mysqli_fetch_array($query1)){
				 if(isset($fetch1['invoice_status'])&&$fetch1['invoice_status']==''){
					 $invoice_status='Not paid';
				 }
				 $statusss = isset($fetch['status']) ? $fetch['status'] : 'Pending';
				if($statusss == 'in process' || $statusss == 'accepted') {
					$statusss = 'On the Way';
				}
				 ?>
	
				<tr>
					<td><?php echo $fetch1['track_no']; ?></td>
					<td><?php echo $fetch1['receiver_address'];?></td>
					<td><?php echo $fetch1['rphone'];?></td>
					<td><?php echo isset($invoice_status)?$invoice_status:"";?></td>
					<td><?php echo $statusss;?></td>
					<td><?php echo $fetch1['collection_amount'];?></td>
					<td><?php echo $fetch1['price'];?></td>
					<td><?php echo $fetch1['price']+$fetch1['collection_amount'];?></td>
					<td><a href="invoicehtml.php?id=<?php echo encrypt($fetch1['id']."-usUSMAN767###");?>" class="btn btn-info" target="-blank">View Invoice</a></td>
					<td>
						<?php
						if($fetch1['admin_invoice_attachment']!=''){
				
						?>
						<a download href="<?php echo $fetch1['admin_invoice_attachment']; ?>" class="btn btn-success" >Download Attachment</a>
						<?php
						}
						
						?>
					</td>
					<!-- <td>
						<?php
						if($fetch1['invoice_attachment']==''){
				
						?>
						<form action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
							<div class="row">
								<input type="file" name="invoice_attachment" class="col-lg-8">
								<input type="submit" name="attached" value="Upload" class="btn col-lg-4 btn-sm btn-success">
							</div>
						</form>
						<?php
						}
						else{
							?>
						<a download href="<?php echo $fetch1['invoice_attachment']; ?>" class="btn btn-success" >Download Attachment</a>
						<?php
						}
						
						?>
					</td> -->
				</tr>
				<?php
			 }
				?>
			</tbody>
		  </table>



		  <div class="order_info-details">
			<ul id="results"></ul>
		</div>
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
	 (function($){	
	 $("body").on('click', ".open_first_order a", function(){
		    $(this).closest('li').find('.down_box_order').slideToggle();
	 });
	 
	if($('#results').length > 0) {
		 $("#results").loaddata({
		 	data_url: 'pending-invoices.php',
		 	end_record_text: ''
		 });
	}
	})(jQuery);
	 </script>
	  <?php include 'includes/profile_footer.php'; ?>
	  