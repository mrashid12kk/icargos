<?php
	session_start();
	include_once "includes/conn.php";
	if(isset($_SESSION['customers'])){


	include "includes/header.php";
	function get_service_type($id)
	{
		global $con;
		$branchQ = mysqli_query($con, "SELECT * from services where id = $id");
		$res = mysqli_fetch_array($branchQ);
		return $res['service_type'];
	}
	//$page_title = 'Change Password';
	$is_profile_page = true;
	if(isset($_GET['id']) && !empty($_GET['id'])) {
			$id =$_GET['id'];
			$orderID =$_GET['id'];
			$query = mysqli_query($con, "SELECT * FROM orders WHERE id = '".$id."'");
			$data = mysqli_fetch_array($query);
			$customer_data=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customers WHERE id = '".$data['customer_id']."'"))
?>




<section class="bg padding30">
  <div class="container-fluid dashboard">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
      <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 password">
      <table class="table table-bordered business_tbl" id="order_list_page">
   		<form action="" method="POST" enctype=""></form>
		   <tbody>
		      <tr>
		         <td><b>Order View-</b> 2010961591</td>
		         <td><b>Status:</b> <?php echo $data['status']; ?></td>
		         <td><b>BDM:</b><?php echo $customer_data['bname']; ?></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b>Hub:</b> Hyderabad</td>
		         <td><b>Reference # :</b> Mustafa Traders</td>
		         <td><b>Created Date:</b> <?php echo date('Y-m-d',strtotime($data['order_date'])).'  '.date('h:i:s',strtotime($data['order_time'])); ?></td>
		         <td><a target="_blank" href="<?php echo getConfig('print_template'); ?>?order_id=<?php echo $data['id']; ?>&print=1&booking=1" class="btn btn-info btn-sm view_invoice"><?php echo getLange('viewinvoice'); ?> </a></td>
		      </tr>
		      <tr>
		         <td><b class="font-18">Business Name:</b> 247 Express</td>
		         <td></td>
		         <td></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b class="font-18">Pickup Address</b></td>
		         <td></td>
		         <td><b class="font-18">Delivery Address</b></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b>Name</b></td>
		         <td><?php echo $data['sname']; ?></td>
		         <td><b>Name</b></td>
		         <td><?php echo $data['rname']; ?></td>
		      </tr>
		      <tr>
		         <td><b>Address</b></td>
		         <td><?php echo $data['sender_address']; ?></td>
		         <td><b>Address</b></td>
		         <td><?php echo $data['receiver_address']; ?></td>
		      </tr>
		      <tr>
		         <td><b>City</b></td>
		         <td><?php echo $data['origin']; ?></td>
		         <td><b>City</b></td>
		         <td><?php echo $data['destination']; ?></td>
		      </tr>
		      <tr>
		         <td><b>Email</b></td>
		         <td><?php echo $data['semail']; ?></td>
		         <td><b>Email</b></td>
		         <td><?php echo $data['remail']; ?></td>
		      </tr>
		      <tr>
		         <td><b>Phone</b></td>
		         <td><?php echo $data['sphone']; ?></td>
		         <td><b>Phone</b></td>
		         <td><?php echo $data['rphone']; ?></td>
		      </tr>
		      <tr>
		         <td><b class="font-18">Order Details</b></td>
		         <td></td>
		         <td></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b>Service Type</b></td>
		         <td><?php echo get_service_type($data['order_type']); ?></td>
		         <td><b>No.of Pieces</b></td>
		         <td><?php echo $data['quantity']; ?></td>
		      </tr>
		      <tr>
		         <td><b>Amount (COD)</b></td>
		         <td><?php echo $data['collection_amount']; ?></td>
		         <td><b>Weight(Kg)</b></td>
		         <td><?php echo $data['weight']; ?></td>
		      </tr>
		      <tr>
		         <td><b>Declared Value</b></td>
		         <td>2200</td>
		         <td><b>Length(In)</b></td>
		         <td>0.00</td>
		      </tr>
		      <tr>
		         <td><b>Item Detail</b></td>
		         <td><?php echo $data['product_desc']; ?></td>
		         <td><b>Height(In)</b></td>
		         <td>0.00</td>
		      </tr>
		      <tr>
		         <td><b>Fragile</b></td>
		         <td>NO</td>
		         <td><b>Width(In)</b></td>
		         <td>0.00</td>
		      </tr>
		      <tr>
		         <td><b>Special Instructions</b></td>
		         <td><?php echo $data['special_instruction']; ?></td>
		         <td><b>Delivery Charges (Incl. Tax)</b></td>
		         <td><?php echo $data['grand_total_charges']; ?></td>
		      </tr>

		      <tr>
		         <td><b>Status</b></td>
		         <td><input type="text" class="form-control" name="" placeholder="Please enter comments..."></td>
		         <td></td>
		         <td></td>
		      </tr>

		      <tr>
		         <td><b class="font-18">Logs</b></td>
		         <td></td>
		         <td></td>
		         <td></td>
		      </tr>
		      <?php

		      $querylog=mysqli_query($con,"SELECT * from order_logs where order_no='".$data['track_no']."'");
		      while ($row=mysqli_fetch_array($querylog)) {
		       ?>
		      <tr>
		         <td colspan="2"><b>Status changed to <?php echo $row['order_status'] ?></b></td>
		         <td  colspan="2"><?php echo date('Y-m-d',strtotime($row['created_on'])).'  '.date('h:i:s',strtotime($row['created_on'])); ?></td>
		      </tr>
		      <?php } ?>




		   </tbody>
</table>
  </div>
</section>
<?php } ?>

<?php
// include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
?>
	  <?php include 'includes/footer.php'; ?>
