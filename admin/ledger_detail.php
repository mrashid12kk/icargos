<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
	include "includes/header.php";

	if(isset($_POST['submit']) ){
		$paid = $_POST['paid'];

		$customer_id = $_GET['id'];
		mysqli_query($con,"INSERT INTO ledger(`paid`,`customer_id`,`ledger_type`)VALUES('".$paid."','".$customer_id."','Payment')");
	}
?>
<body data-ng-app>
 	
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	<button style="margin-bottom: 20px;" type="button" class="btn btn-info " data-toggle="modal" data-target="#payment_modal">Add payment</button><br>
            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
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
      		<?php $row = mysqli_query($con,"SELECT * FROM ledger WHERE customer_id = ".$_GET['id']." ");
      		$sr=1;
          $balance = 0;
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
        <!-- Warper Ends Here (working area) -->
        <div id="payment_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Payment</h4>
      </div>
       	<form method="POST" action="">
	      <div class="modal-body">
	       		<input type="number" class="form-control" placeholder="Enter Amount" name="paid">
	       	
	      </div>
      <div class="modal-footer">
        <button type="submit" name="submit" class="btn btn-info" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>