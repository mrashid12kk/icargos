<?php
  session_start();
  require 'includes/conn.php';
  
  $customer_balance = 0;

   if(isset($_GET['submit'])){
    $active_from = $_GET['from'];
    $active_to = $_GET['to'];
    $from = date('Y-m-d',strtotime($_GET['from']));
    $to = date('Y-m-d',strtotime($_GET['to']));
   $customer_q = mysqli_query($con,"SELECT DISTINCT customer_id FROM orders WHERE 1   ");

   
  } else{
      $from = date('Y-m-01');
      $to = date('Y-m-t');
      $active_from = $from;
      $active_to = $to;

      $customer_q = mysqli_query($con,"SELECT DISTINCT customer_id FROM orders WHERE 1   ");

  }
  if(true){

    if($customer_id) {
      $balance_query = mysqli_query($con, "SELECT (prev_balance + (total_payable - total_paid)) as total FROM customer_ledger_payments WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 1");
      $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
      $customer_balance = ($balance_query) ? $balance_query->total : 0;
    }

  include "includes/header.php";
    
  // $query         = mysqli_query($con,"SELECT * FROM customers WHERE id =".$customer_id." ");
  // $record        = mysqli_fetch_array($query);

  $customer_list = mysqli_query($con,"SELECT * FROM customers ");
  
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
          p{
            margin: 0px !important;
          }
        </style>
    <!-- Aside Ends-->
    
    <section class="content">
       
  <?php
  include "includes/header2.php";
  ?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
          
        <div class="page-header"></div>
           <form  method="GET" action="bulk_ledger_creation.php">
              <div class="row">
              
              <div class="col-md-2">
                <div class="form-group">
                  <label>Date From</label>
                  <input type="text" name="from" class="form-control datetimepicker4" value="<?php echo $active_from ?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Date To</label>
                  <input type="text" name="to" class="form-control datetimepicker4" value="<?php echo $active_to ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-info" value="Submit" style="margin-top: 24px;">
                </div>
              </div>
            </div><br>
           <!-- <a href="#" class="btn btn-success generate_payment" style="margin: 15px 0px;">Generate</a> -->
           </form>
           <form action="save_bulk_payment.php" method="POST" >
            
           <?php
           $total_delivery = 0;
           

            while($rec = mysqli_fetch_array($customer_q)){ 
              $total_gst = 0;
              $customer_id = $rec['customer_id'];

              $balance_query = mysqli_query($con, "SELECT (prev_balance + (total_payable - total_paid)) as total,SUM(total_paid) as paid FROM customer_ledger_payments WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 1");
              $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
              $customer_balance = ($balance_query) ? $balance_query->total : 0;
              $paid = ($balance_query) ? $balance_query->paid : 0;

              $customer_rec_q = mysqli_query($con,"SELECT fname,bname,email FROM customers WHERE id='".$customer_id."' ");
              $customer_rec = mysqli_fetch_array($customer_rec_q);
              $customer_name = $customer_rec['fname'];
              $customer_company = $customer_rec['bname'];
              $customer_email = $customer_rec['email'];
              //total orders
              mysqli_query($con,"SET SESSION group_concat_max_len = 1000000");
              $total_order_id_q = mysqli_query($con,"SELECT COUNT(id) as total, GROUP_CONCAT(id ) as total_orders_ids FROM orders WHERE (status ='delivered' || status='returned' ) AND payment_status='Pending' AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."' AND customer_id='".$customer_id."'  ");

              $total_order_id_q_rec = mysqli_fetch_array($total_order_id_q);
              $total_orders_records = $total_order_id_q_rec['total'];
              $total_orders_ids = $total_order_id_q_rec['total_orders_ids'];

              //total delivered
              $total_del_id_q = mysqli_query($con,"SELECT COUNT(id) as total, GROUP_CONCAT(id ) as total_deliver_ids FROM orders WHERE (status ='delivered'  ) AND payment_status='pending' AND customer_id='".$customer_id."' AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  ");

              $total_del_id_q_rec = mysqli_fetch_array($total_del_id_q);
              $total_del_records = $total_del_id_q_rec['total'];
              $total_del_ids = $total_del_id_q_rec['total_deliver_ids'];

              //total returned
              $total_ret_id_q = mysqli_query($con,"SELECT COUNT(id) as total, GROUP_CONCAT(id ) as total_deliver_ids FROM orders WHERE (status ='returned'  ) AND payment_status='pending' AND customer_id='".$customer_id."' AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."'  ");

              $total_ret_id_q_rec = mysqli_fetch_array($total_ret_id_q);
              $total_ret_records = $total_ret_id_q_rec['total'];
              $total_ret_ids = $total_ret_id_q_rec['total_deliver_ids'];


              $order_cal_q = mysqli_query($con,"SELECT SUM(price) as total_delivery, SUM(collection_amount) as collection_amount,SUM(CASE WHEN status = 'returned' THEN collection_amount ELSE 0 END) as total_returned FROM orders WHERE (status ='delivered' || status='returned' )  AND customer_id='".$customer_id."' AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$to."' AND payment_status='Pending' GROUP BY customer_id  ");

              //////////////flyer/////////////
              $total_flyer_amount = 0;
              $flyer_ids = array();
               $flyer_query = mysqli_query($con,"SELECT flayer_order_index.id as flyer_id,flayer_orders.total_price as total_price FROM flayer_order_index LEFT JOIN flayer_orders ON(flayer_order_index.id=flayer_orders.flayer_order_index) WHERE  flayer_order_index.customer=".$customer_id." AND flayer_order_index.payment_status = 'Pending' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'    ");
               $total_flyer = 0;
               $count_flyer = 0;
               if(mysqli_num_rows($flyer_query) > 0){
                while($fly_q = mysqli_fetch_array($flyer_query)){
                  $total_flyer_amount += $fly_q['total_price'];
                  $flyer_ids[] = $fly_q['flyer_id'];
                  $count_flyer = $count_flyer+1;
                }
               }
               $flyer_ids_res = implode(',',$flyer_ids);

              /////////////flyer/////////////
 
            while($order_rec = mysqli_fetch_array($order_cal_q)){
              $total_cod = $order_rec['collection_amount'];
              $total_del = $order_rec['total_delivery'];
              $total_ret = $order_rec['total_returned'];
              $total_gst = 0;
              $total_payable = $total_cod-$total_del-$total_ret-$total_flyer_amount-$total_gst;
              // $total_payable = round($total_payable - $paid);
              
            ?>
              <input type="hidden" name="payments[<?php echo $customer_id ?>][customer_balance]" value="<?php echo $customer_balance ?>">

              <input type="hidden" name="payments[<?php echo $customer_id ?>][count_flyer]" value="<?php echo $count_flyer ?>">

              <input type="hidden" name="payments[<?php echo $customer_id ?>][flyer_ids_res]" value="<?php echo $flyer_ids_res ?>">

            <input type="hidden" name="payments[<?php echo $customer_id ?>][total_orders_records]" value="<?php echo $total_orders_records ?>">

            <input type="hidden" name="payments[<?php echo $customer_id ?>][total_orders_ids]" value="<?php echo $total_orders_ids ?>">

            <input type="hidden" name="payments[<?php echo $customer_id ?>][total_del_records]" value="<?php echo $total_del_records ?>">
            <input type="hidden" name="payments[<?php echo $customer_id ?>][total_del_ids]" value="<?php echo $total_del_ids ?>">

            <input type="hidden" name="payments[<?php echo $customer_id ?>][total_ret_records]" value="<?php echo $total_ret_records ?>">
            <input type="hidden" name="payments[<?php echo $customer_id ?>][total_ret_ids]" value="<?php echo $total_ret_ids ?>">

            <input type="hidden" value="<?php echo $total_cod ?>" name="payments[<?php echo $customer_id ?>][total_cod]">
            <input type="hidden" value="<?php echo $total_del ?>" name="payments[<?php echo $customer_id ?>][total_delivery]">
            <input type="hidden" value="<?php echo $total_ret ?>" name="payments[<?php echo $customer_id ?>][total_return]">
            <input type="hidden" value="<?php echo $total_gst ?>" name="payments[<?php echo $customer_id ?>][total_gst]">
           
            <input type="hidden" value="<?php echo $total_flyer_amount ?>" name="payments[<?php echo $customer_id ?>][total_flyer]">
            <input type="hidden" value="" name="payments[<?php echo $customer_id ?>][count_total_flyer_checked]">
            <input type="hidden" value="" name="payments[<?php echo $customer_id ?>][count_total_return_checked]">
            <input type="hidden" value="" name="payments[<?php echo $customer_id ?>][count_total_del_checked]">

            

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th></th>
                  <th style="width: 200px;">Customer</th>
                  <th>Prev. Balance</th>
                  <th>Total COD</th>
                  <th>Total Delivery</th>
                  <th>Total Return</th>
                  <th>Total Flyer Sell</th>
                  <th>Total GST</th>
                  <th>Total Payable</th>
                  <th>Payment</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="checkbox" name="payments[<?php echo $customer_id ?>][is_checked]"></td>
                <td>
                  <p><b>Name:</b><?php echo $customer_name; ?></p>
                  <p><b>Company:</b><?php echo $customer_company; ?></p>
                </td>
               <td><b>Rs <?=number_format($customer_balance, 2);?></b></td>
               <td><b>Rs <span id=""><?php echo number_format($total_cod,2); ?></span></b></td>
               <td><b>Rs <span id=""><?php echo number_format($total_del,2); ?></span></b></td>
               <td><b>Rs <span id=""><?php echo number_format($total_ret,2); ?></span></b></td>
               <td><b>Rs <span id=""><?php echo $total_flyer_amount; ?></span></b></td>
               <td><b>Rs <span id=""><?php echo number_format($total_gst,2); ?></span></b></td>
               <td><b>Rs <span id="" class="toal_sum"><?php echo number_format($total_payable,2); ?></span></b></td>
               <td>
                <input type="hidden" name="payments[<?php echo $customer_id ?>][total_payable]" class="payable" value="<?php echo $total_payable; ?>">
                <input type="text"  name="payments[<?php echo $customer_id ?>][total_paid]" value="<?php echo $total_payable; ?>" class="form-control total_pay">
              </td>
              
              </tr>
              <tr style="display: none;" class="adjustment_reason">
                  <td colspan="6"><textarea  name="payments[<?php echo $customer_id ?>][adjustment_reason]" class="form-control " placeholder="Adjustment Reason"></textarea></td>
                </tr>
              </tbody>
            </table>
          <?php } } ?>

          <div class="row">
            <input type="submit" name="save_bulk_pay" value="Save" class="btn btn-success">
          </div>
          </form>
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
      $(function () {
          $('.datetimepicker4').datetimepicker({
            format: 'YYYY/MM/DD',
          });
      });
  </script>