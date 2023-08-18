<?php
	session_start();
	require 'includes/conn.php';
 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],14,'view_only',$comment =null)) {

        header("location:access_denied.php");
    }
	$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;

  $customer_balance = 0;

   if(isset($_GET['submit'])){
    $active_from = $_GET['from'];
    $active_to = $_GET['to'];
    $from = date('Y-m-d',strtotime($_GET['from']));
    $to = date('Y-m-d',strtotime($_GET['to']));
    $customer_id = $_GET['customer_id'];
    $pickup_driver_query = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  AND pickup_rider=".$customer_id." AND employee_payment_status = 'Pending'   order by id desc ");
    // echo "SELECT * FROM orders WHERE DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  AND pickup_rider=".$customer_id." AND employee_payment_status = 'Pending'   order by id desc ";
    // die();
    $delivery_driver_query = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  AND delivery_rider=".$customer_id." AND employee_payment_status = 'Pending' AND status='Delivered' order by id desc ");


  } else{
      $from = date('Y-m-01');
      $to = date('Y-m-t');
      $active_from = $from;
      $active_to = $to;
      $customer_id = $_GET['customer_id'];

      $pickup_driver_query = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  AND pickup_rider=".$customer_id." AND employee_payment_status = 'Pending'    order by id desc ");

      $delivery_driver_query = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  AND delivery_rider=".$customer_id."  AND employee_payment_status = 'Pending' AND status='Delivered'  order by id desc ");

  }
  if(true){

    if($customer_id) {
      $balance_query = mysqli_query($con, "SELECT ( (total_payable - total_paid)) as total FROM employee_ledger_payments WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 1");
      $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
      $customer_balance = ($balance_query) ? $balance_query->total : 0;

      $user_query = mysqli_query($con,"SELECT * FROM users WHERE id =".$customer_id." ");
      $user_record = mysqli_fetch_array($user_query);
    }

  include "includes/header.php";

	// $query         = mysqli_query($con,"SELECT * FROM customers WHERE id =".$customer_id." ");
	// $record        = mysqli_fetch_array($query);

  $customer_list = mysqli_query($con,"SELECT * FROM users WHERE type='driver' ");

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
          .ledger_list p{
            margin:0px !important;
          }
        </style>
    <!-- Aside Ends-->

    <section class="content">

	<?php
	include "includes/header2.php";
	?>

        <!-- Header Ends -->



        <div class="warper container-fluid">
        <div class="bulk_payment_box">
         <div class="row">
            <div class="col-sm-12">
              <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success">
                  <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']); 
                  ?>
                </div>
              <?php } ?>
              <?php if(isset($_SESSION['errors'])){ ?>
                <div class="alert alert-danger">
                  <?php
                    echo $_SESSION['errors'];
                    unset($_SESSION['errors']); 
                  ?>
                </div>
              <?php } ?>
            </div>
          </div>

				<div class="page-header"></div>
           <form  method="GET" action="employee_bulk_ledger_payment.php">
              <div class="row">
              <div class="col-md-2 padd_none">
                <div class="form-group">
                  <label><?php echo getLange('employee'); ?></label>
                  <select class="form-control flyer_selecter" required="true" name="customer_id">
                    <option disabled="">Select</option>
                    <?php while($row_customer = mysqli_fetch_array($customer_list))
                      {
                        ?>
                          <option <?php if($customer_id == $row_customer['id']){ echo "Selected"; } ?>  value="<?php echo $row_customer['id']; ?>" > <?php echo $row_customer['Name']." "; ?> </option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label><?php echo getLange('date'); ?> <?php echo getLange('from'); ?> </label>
                  <input type="text" name="from" class="form-control datetimepicker4" value="<?php echo $active_from ?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label><?php echo getLange('date'); ?> <?php echo getLange('to'); ?> </label>
                  <input type="text" name="to" class="form-control datetimepicker4" value="<?php echo $active_to ?>">
                </div>
              </div>
              <div class="col-md-3 ">
                <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>" style="margin-top: 24px;">
                </div>
              </div>
            </div><br>
           <!-- <a href="#" class="btn btn-success generate_payment" style="margin: 15px 0px;">Generate</a> -->
           </form>
           <form action="employee_submit_bulk_ledger_payment.php" method="POST" >
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <input type="hidden" name="total_payable">

            <div class="row">
              <div class="col-md-3 padd_none">
                <div class="form-group">
                  <label><?php echo getLange('date'); ?></label>
                  <input type="text" name="date" class="form-control datetimepicker4" value="<?=date('Y/m/d');?>" required="true">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo getLange('chequenotransactionid'); ?></label>
                  <input type="text" name="reference_no" class="form-control" required="true">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo getLange('employeebalnace'); ?> .</label>
                  <input type="text" readonly="true" name="prev_balance" class="form-control employee-balance" value="<?=$customer_balance;?>">
                </div>
              </div>
            </div>
           <br>
          <div class="panel panel-primary">
            <div class="panel-heading"><?php echo getLange('pickuporder'); ?> </div>
            <div class="panel-body">
               <table class="table table-striped table-bordered  " id="employee_ledger_list">
             <thead>
               <tr>
                 <th style="display: none;"><input  type="checkbox" class="select_all_orders"></th>
                 <th><?php echo getLange('srno'); ?></th>
                 <th><?php echo getLange('trackingno'); ?> </th>
                 <th><?php echo getLange('consignee'); ?></th>
                 <th><?php echo getLange('phone'); ?></th>
                 <th><?php echo getLange('origin'); ?></th>
                 <th><?php echo getLange('destination'); ?></th>
                 <th><?php echo getLange('pickupcommision'); ?> </th>
                 <th><?php echo getLange('status'); ?></th>
               </tr>
             </thead>
             <tbody>
              <?php
              $sr=1;
              $total_pickup_comm = 0;
               while($row = mysqli_fetch_array($pickup_driver_query)){
                $key_name = 'pickup_orders';
               $pickup_cmsn = isset($user_record['pickup_comm']) ? $user_record['pickup_comm'] : '0';
               $total_pickup_comm += $pickup_cmsn;
               ?>
               <tr>
                 <td style="display: none;"><input checked type="checkbox" class="orderid" data-status="<?php echo $row['status'] ?>" data-delivery="<?php echo $row['price'] ?>" data-cod="<?php echo $row['collection_amount'] ?>" value="<?php echo $row['id'] ?>" name="<?=$key_name;?>[<?=$row['id'];?>]">

                 </td>
                 <td><?php echo $sr; ?></td>
                 <td><?php echo $row['track_no']; ?></td>
                 <td><?php echo $row['rname']; ?></td>
                <td><?php echo $row['rphone']; ?></td>
                <td><?php echo $row['origin']; ?></td>
                <td><?php echo $row['destination']; ?></td>
                <td><?php echo getConfig('currency'); ?><?php echo $pickup_cmsn ; ?></td>
                <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
               </tr>
             <?php $sr++; } ?>
             </tbody>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <?php echo getLange('totalcommsion'); ?> <?php echo $total_pickup_comm; ?>
                    <input type="hidden" name="total_pickup_comm" value="<?php echo $total_pickup_comm; ?>" class="total_pickup_comm">
                  </td>
                 <td></td>
              </tr>
            </tfoot>
           </table>
            </div>
          </div>


          <div class="panel panel-primary">
            <div class="panel-heading"><?php echo getLange('deliveryorder'); ?> </div>
            <div class="panel-body">
               <table class="table table-striped table-bordered  " id="employee_ledger_list">
             <thead>
               <tr>
                 <th style="display: none;"><input  type="checkbox" class="select_all_orders"></th>
                 <th><?php echo getLange('srno'); ?></th>
                 <th><?php echo getLange('trackingno'); ?> </th>
                 <th><?php echo getLange('consignee'); ?></th>
                 <th><?php echo getLange('phone'); ?></th>
                 <th><?php echo getLange('origin'); ?></th>
                 <th><?php echo getLange('destination'); ?></th>
                 <th><?php echo getLange('deliverycommsion'); ?> </th>
                 <th><?php echo getLange('status'); ?></th>
               </tr>
             </thead>
             <tbody>
              <?php
              $sr=1;
               $total_delivery_comm = 0;
              while($row = mysqli_fetch_array($delivery_driver_query)){
                $key_name = 'delivery_orders';
                $delivery_cmsn = isset($user_record['delivery_comm']) ? $user_record['delivery_comm'] : '0' ;
                $total_delivery_comm += $delivery_cmsn;
               ?>
               <tr>
                 <td style="display: none;"><input checked type="checkbox" class="orderid" data-status="<?php echo $row['status'] ?>" data-delivery="<?php echo $row['price'] ?>" data-cod="<?php echo $row['collection_amount'] ?>" value="<?php echo $row['id'] ?>" name="<?=$key_name;?>[<?=$row['id'];?>]"></td>
                 <td><?php echo $sr; ?></td>
                 <td><?php echo $row['track_no']; ?></td>
                 <td><?php echo $row['rname']; ?></td>
                <td><?php echo $row['rphone']; ?></td>
                <td><?php echo $row['origin']; ?></td>
                <td><?php echo $row['destination']; ?></td>
                <td><?php echo getConfig('currency'); ?><?php echo $delivery_cmsn; ?></td>
                <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
               </tr>
             <?php $sr++; } ?>
             </tbody>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <?php echo getLange('totalcommsion'); ?> <?php echo $total_delivery_comm; ?>
                     <input type="hidden" name="total_delivery_comm" value="<?php echo $total_delivery_comm; ?>" class="total_delivery_comm">
                  </td>
                 <td></td>
              </tr>
            </tfoot>
           </table>
            </div>
          </div>






          	<table class="table table-bordered">
              <thead>
                <tr>
                  <th><?php echo getLange('prevbalance'); ?></th>
                  <th><?php echo getLange('totalpayable'); ?> </th>
                  <th><?php echo getLange('addition'); ?> </th>
                  <th><?php echo getLange('deduction'); ?> </th>
                  <th><?php echo getLange('payment'); ?></th>
                  <th><?php echo getLange('action'); ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
               <td><b><?php echo getConfig('currency'); ?><?=number_format($customer_balance, 2);?></b></td>
               <td><b><?php echo getConfig('currency'); ?><span id="emptotalPayable"></span></b></td>
               <td><input type="text" name="total_addition" value="0" class="form-control total_addition"></td>
               <td><input type="text" name="total_deduction" value="0" class="form-control total_deduction"></td>
               <td><input type="text" name="total_payment" class="form-control"></td>
               <td><input type="submit" name="submit" class="btn btn-success" value="<?php echo getLange('paynow'); ?>" /></td>
              </tr>
              </tbody>
            </table>
          </form>
        </div>
        </div>
        <!-- Warper Ends Here (working area) -->



        <!-- Modal -->


      <?php

	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>
	<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
      var global_payment_amount = $('body').find('[name="total_payment"]').val();
      global_payment_amount = (global_payment_amount && global_payment_amount > 0) ? global_payment_amount:0;
      $('body').on('keyup','.total_addition',function(event){
          event.preventDefault();
          calculate_deduct_addition();
      });
      $('body').on('keyup','.total_deduction',function(event){
          event.preventDefault();
          calculate_deduct_addition();
      });
      var calculate_deduct_addition = function()
      {
        var body = $('body');
        var total_payable_amount  = 0;
        var remaining_amount = 0;
        var addition_amount = body.find("[name='total_addition']").val();
        var deduction_amount = body.find("[name='total_deduction']").val();
        addition_amount = (addition_amount && addition_amount > 0) ? addition_amount:0;
        deduction_amount = (deduction_amount && deduction_amount > 0) ? deduction_amount:0;

        total_payable_amount = global_payment_amount;
        total_payable_amount = (total_payable_amount && total_payable_amount > 0) ? total_payable_amount:0;

        remaining_amount = total_payable_amount;
        remaining_amount = parseFloat(total_payable_amount) + parseFloat(addition_amount) - parseFloat(deduction_amount);
        body.find('[name="total_payment"]').val(remaining_amount);
      }
    },false);
      $(function () {
          $('.datetimepicker4').datetimepicker({
            format: 'YYYY/MM/DD',
          });
      });
  </script>
