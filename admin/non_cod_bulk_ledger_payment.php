<?php
  session_start();
  require 'includes/conn.php';
  
  $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
  
  $customer_balance = 0;

   if(isset($_GET['submit'])){
    // $active_from = $_GET['from'];
    // $active_to = $_GET['to'];
    // $from = date('Y-m-d',strtotime($_GET['from']));
    // $to = date('Y-m-d',strtotime($_GET['to']));
    $customer_id = $_GET['customer_id'];
    $ledger_query = mysqli_query($con,"SELECT * FROM orders WHERE  (status ='delivered' || status='Returned to Shipper' ) AND customer_id=".$customer_id."  AND payment_status = 'Pending' order by id desc ");

    $flyer_query = mysqli_query($con,"SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   AND customer=".$customer_id." AND payment_status = 'Pending'  order by id desc ");
  } else{
      // $from = date('Y-m-01');
      // $to = date('Y-m-t');
      // $active_from = $from;
      // $active_to = $to;
      $customer_id = $_GET['customer_id'];

      $ledger_query = mysqli_query($con,"SELECT * FROM orders WHERE  (status ='delivered' || status='Returned to Shipper' ) AND customer_id=".$customer_id."   AND payment_status = 'Pending' order by id desc ");

      $flyer_query = mysqli_query($con,"SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'  AND customer =".$customer_id." AND payment_status = 'Pending'  order by id desc ");
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

  $customer_list = mysqli_query($con,"SELECT * FROM customers WHERE is_non_cod=1 ");
  
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
        <?php
        $return_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='return_fee'  ");
  $total_return_fee = mysqli_fetch_array($return_query); 
  $cash_handling_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='cash_handling'  ");
  $cash_handling_query_fee = mysqli_fetch_array($cash_handling_query); 
  $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
  $total_gst = mysqli_fetch_array($gst_query);
   ?>
   <input type="hidden" name="" id="return_fee_setting" value="<?php echo $total_return_fee['value']; ?>">
 <input type="hidden" name="" id="cash_handling_fee_setting" value="<?php echo $cash_handling_query_fee['value']; ?>">
 <input type="hidden" name="" id="total_gst" value="<?php echo $total_gst['value']; ?>">
        
        <div class="warper container-fluid">
          
            <!-- <div class="page-header"><h1>Customer Detail</h1></div>
            <table class="table table-bordered">
              <tr>
                <th>Customer Code:</th>
                <td><?php echo $record['client_code']; ?></td>
                <th>Customer Name:</th>
                <td><?php echo $record['fname']; ?></td>
              </tr>
              <tr>
                <th>Customer Email:</th>
                <td><?php echo $record['email']; ?></td>
                <th>Customer Phone:</th>
                <td><?php echo $record['mobile_no']; ?></td>
              </tr>
              <tr>
                <th>Customer Address:</th>
                <td><?php echo $record['address']; ?></td>
                <th>Customer City:</th>
                <td><?php echo $record['city']; ?></td>
              </tr>
              <tr>
                <th>Customer Bank:</th>
                <td><?php echo $record['bank_name']; ?></td>
                <th>Account Number:</th>
                <td><?php echo $record['bank_ac_no']; ?></td>
              </tr>
              <tr>
                <th>CNIC Copy:</th>
                <td><a download href="<?php echo $url ?>/<?php echo $record['cnic_copy'] ?>">View CNIC</a></td>
                <th></th>
                <td></td>
              </tr>
            </table> -->
            <!-- <hr></hr> -->
         
        <div class="page-header"></div>
           <form  method="GET" action="non_cod_bulk_ledger_payment.php">
              <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label>Customer</label>
                  <select class="form-control flyer_selecter" required="true" name="customer_id">
                    <option disabled="">Select</option>
                    <?php while($row_customer = mysqli_fetch_array($customer_list))
                      {  
                        ?>
                          <option <?php if($customer_id == $row_customer['id']){ echo "Selected"; } ?>  value="<?php echo $row_customer['id']; ?>" > <?php echo $row_customer['fname']." (".$row_customer['bname'].")"; ?> </option>
                        <?php 
                      }
                    ?>
                  </select>
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
           <form action="submit_bulk_ledger_payment.php" method="POST" >
            <input type="hidden" name="is_non_cod">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <input type="hidden" name="total_cod">
            <input type="hidden" name="total_delivery">
            
            <input type="hidden" name="total_return_fee">
            <input type="hidden" name="total_gst">
            <input type="hidden" name="total_cash_handling">
            <input type="hidden" name="total_payable_price">
            <input type="hidden" name="total_flyer">
            <input type="hidden" name="count_total_flyer_checked">
            <input type="hidden" name="count_total_return_checked">
            <input type="hidden" name="count_total_del_checked">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Date</label>
                  <input type="text" name="date" class="form-control datetimepicker4" value="<?=date('Y/m/d');?>" required="true">
                </div>
              </div>
              <div class="col-md-3">
                <?php
                $reference = strtoupper(substr(hash('sha256', mt_rand() . microtime()), 0, 8));
                 ?>
                <div class="form-group">
                  <label>Transaction ID</label>
                  <input type="text" name="reference_no" value="<?php echo $reference; ?>" class="form-control" required="true">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Customer Balance.</label>
                  <input type="text" readonly="true" name="prev_balance" class="form-control customer-balanced" value="<?=$customer_balance;?>">
                  <input type="hidden" class="customer_balance" value="<?php echo $customer_balance; ?>">
                </div>
              </div>
            </div>
           <br>
           <table class="table table-striped table-bordered  " id="ledger_list">
             <thead>
               <tr>
                 <th ><input  type="checkbox" class="select_all_orders"></th>
                 <th>Tracking No</th>
                 <th>Delivery Name</th>
                 <th>Delivery Phone</th>
                 <th>Delivery City</th>
                 <th>Weight</th>
                 <th>Collection Amount</th>
                 <th>Delivery Charges</th>
                 <th>Status</th>
               </tr>
             </thead>
             <tbody>
              <?php while($row = mysqli_fetch_array($ledger_query)){
                $key_name = (strtolower($row['status']) == 'delivered') ? 'delivered' : 'returned';
               ?>
               <tr> 
                 <td ><input checked type="checkbox" class="orderid" data-status="<?php echo $row['status'] ?>" data-delivery="<?php echo $row['price'] ?>" data-cod="<?php echo $row['collection_amount'] ?>" data-pft="<?php echo $row['pft_amount'] ?>" value="<?php echo $row['id'] ?>" name="<?=$key_name;?>[<?=$row['id'];?>]"></td>
                 <td><?php echo $row['track_no']; ?></td>
                 <td><?php echo $row['rname']; ?></td>
                <td><?php echo $row['rphone']; ?></td>
                <td><?php echo  $row['destination']; ?></td>
                <td><?php echo  $row['weight']; ?> KG</td>
                <td>Rs <?php echo $row['collection_amount']; ?></td>
                <td>Rs <?php echo $row['price']; ?></td>
                <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
               </tr>
             <?php } ?>
             </tbody>
           
           </table>


           <table class="table table-striped table-bordered  " id="flyer_list">
             <thead>
               <tr>
                 <th style="display: none;"><input type="checkbox" class="select_all_flyer_sell"></th>
                 <th>Invoice No</th>
                 <th>Date</th>
                 <th>Description</th>
                 <th>Total Amount</th> 
               </tr>
             </thead>
             <tbody>
              <?php while($row = mysqli_fetch_array($flyer_query)){
                $flayer_order_index = $row['id'];
                $flayer_order_query = mysqli_query($con,"SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=".$flayer_order_index." ");

                $total = getTotal($row['id']);
               ?>
                <tr>
                  <td style="width: 5%; display: none;"><input checked type="checkbox" class="orderid"  data-flyer="<?php echo $total; ?>"  value="<?php echo $row['id'] ?>" name="flyer[<?=$row['id'];?>]"></td>
                  <td><?php echo sprintf("%04d",$row['id']); ?></td>
                  <td><?php echo $row['order_date']; ?></td>
                  <td>
                    <?php 
                      while($rec2 = mysqli_fetch_array($flayer_order_query)){
                        ?>
                      <p><b>Flayer: </b><?php echo $rec2['flayer_name']; ?>, <b>Qty: </b><?php echo $rec2['qty']; ?></p>
                      <?php } ?>
                     
                   </td>
                  <td><?php echo $total; ?></td> 
                </tr>
             <?php } ?>
             </tbody>
           
           </table>




            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Prev. Balance</th>
                  <th>Total COD</th>
                  <th>Total Delivery</th>
                  <th>Total Return</th>
                  <th>Total Flyer Sell</th>
                  <th>Total GST(<?php echo $total_gst['value']; ?>%)</th>
                  <th>Total Return Charges</th>
                  <th>Cash Handling</th>
                  <th>Total Payable</th>
                  <th>Return Pay</th>
                  <th>Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
               <td><b>Rs <?=number_format($customer_balance, 2);?></b></td>
               <td><b>Rs <span id="totalCOD"></span></b></td>
               <td><b>Rs <span id="totalDelivery"></span></b></td>
               <td><b>Rs <span id="totalReturn"></span></b></td>
               <td><b>Rs <span id="totalFlyerSell"></span></b></td>
               <td><b>Rs <span id="totalGST"></span></b></td>
               <td><b>Rs <span id="totalRETURNCHARGES"></span></b></td>
               <td><b>Rs <span id="totalChashhandling"></span></b></td>
               <td><b>Rs <span id="totalPayables"></span></b></td>
               <td><input type="text" readonly="true" class="form-control" name="total_return"></td>
               <td>
                <input type="text" name="total_payments" class="form-control" readonly="true">
               </td>
               
               <td><input type="submit" name="submit" class="btn btn-success" value="Pay Now" /></td>
              </tr>
              </tbody>
            </table>
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