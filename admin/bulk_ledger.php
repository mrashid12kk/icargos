<?php
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) && isset($_GET['customer_id'])){
	include "includes/header.php";
	$customer_id = $_GET['customer_id'];
		
	$query = mysqli_query($con,"SELECT * FROM customers WHERE id =".$customer_id." ");
	$record = mysqli_fetch_array($query);
	
	
?>
<body data-ng-app>
 	
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
   <style type="text/css">
          .city_to option.hide {
            /*display: none;*/
          }
          .form-group{
          	margin-bottom: 0px !important;
          }
        </style>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1><?php echo getLange('customer_detail'); ?></h1></div>
            <table class="table table-bordered">
              <tr>
                <th><?php echo getLange('customer_code') ?>:</th>
                <td><?php echo $record['client_code']; ?></td>
                <th><?php echo getLange('customername'); ?>:</th>
                <td><?php echo $record['fname']; ?></td>
              </tr>
              <tr>
                <th><?php echo getLange('customer_email') ?>:</th>
                <td><?php echo $record['email']; ?></td>
                <th><?php echo getLange('customer_phone'); ?>:</th>
                <td><?php echo $record['mobile_no']; ?></td>
              </tr>
            	<tr>
                <th><?php echo getLange('customer_address'); ?>:</th>
                <td><?php echo $record['address']; ?></td>
                <th><?php echo getLange('customer_city'); ?>:</th>
                <td><?php echo $record['city']; ?></td>
              </tr>
              <tr>
                <th><?php echo getLange('customer_bank'); ?>:</th>
                <td><?php echo $record['bank_name']; ?></td>
                <th><?php echo getLange('accountno'); ?>:</th>
                <td><?php echo $record['bank_ac_no']; ?></td>
              </tr>
              <tr>
                <th><?php echo getLange('cnic_copy'); ?>:</th>
                <td><a download href="<?php echo $url ?>/<?php echo $record['cnic_copy'] ?>"><?php echo getLange('view_cnic'); ?></a></td>
                <th></th>
                <td></td>
              </tr>
            </table>
				<div class="page-header"><a href="bulk_ledger_payment.php?customer_id=<?php echo $customer_id; ?>" class="btn btn-info"><?php echo getLange('addpayment'); ?></a> <h3><?php echo getLange('customer_ledger_pricing'); ?></h3></div>
            
            	<table class="table table-bordered">
               <thead>
                 <tr>
                   <th><?php echo getLange('srno'); ?></th>
                   <th><?php echo getLange('paymentid'); ?></th>
                   <th><?php echo getLange('date'); ?></th>
                   <th><?php echo getLange('totalshipment'); ?></th>
                   <th><?php echo getLange('delivered_shipments'); ?></th>
                   <th><?php echo getLange('returned_shipments'); ?></th>
                   <th><?php echo getLange('totalamount'); ?></th>
                   <th>Total Charges</th>
                   <th>GST</th>
                   <th>Total Payable</th>
                 </tr>

               </thead> 
               <tbody>
                 <tr>
                   <td>001</td>
                   <td>001234</td>
                   <td>12 july 2019</td>
                   <td>10</td>
                   <td>8</td>
                   <td>2</td>
                   <td>1000</td>
                   <td>700</td>
                   <td>200</td>
                   <td>800</td>
                 </tr>
                   <tr>
                   <td>001</td>
                   <td>001234</td>
                   <td>12 july 2019</td>
                   <td>10</td>
                   <td>8</td>
                   <td>2</td>
                   <td>1000</td>
                   <td>700</td>
                   <td>200</td>
                   <td>800</td>
                 </tr>
                   <tr>
                   <td>001</td>
                   <td>001234</td>
                   <td>12 july 2019</td>
                   <td>10</td>
                   <td>8</td>
                   <td>2</td>
                   <td>1000</td>
                   <td>700</td>
                   <td>200</td>
                   <td>800</td>
                 </tr>
                   <tr>
                   <td>001</td>
                   <td>001234</td>
                   <td>12 july 2019</td>
                   <td>10</td>
                   <td>8</td>
                   <td>2</td>
                   <td>1000</td>
                   <td>700</td>
                   <td>200</td>
                   <td>800</td>
                 </tr>
                   <tr>
                   <td>001</td>
                   <td>001234</td>
                   <td>12 july 2019</td>
                   <td>10</td>
                   <td>8</td>
                   <td>2</td>
                   <td>1000</td>
                   <td>700</td>
                   <td>200</td>
                   <td>800</td>
                 </tr>
               </tbody>
              </table>
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>
	 