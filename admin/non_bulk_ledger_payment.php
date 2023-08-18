<?php
  session_start();
  require 'includes/conn.php';
   require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],16,'add_only',$comment =null)) {

        header("location:access_denied.php");
    }
  $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
  $from = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
  $to = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
  $customer_balance = 0;
  $orderfrom = date('Y-m-d', strtotime('today - 30 days'));
  $orderto = date('Y-m-d');
   if(isset($_GET['submit'])){
    // $active_from = $_GET['from'];
    // $active_to = $_GET['to'];

    if(isset($_GET['from']) && !empty($_GET['from'])){
         $orderfrom = date('Y-m-d',strtotime($_GET['from']));
    }else{
       $orderfrom = date('Y-m-d', strtotime('today - 30 days'));
    }
     if(isset($_GET['from']) && !empty($_GET['from'])){
         $orderto = date('Y-m-d',strtotime($_GET['to']));
    }else{
       $orderto = date('Y-m-d');
    }


    // echo '<pre>',print_r($_POST),'</pre>';exit();
    $customer_id = $_GET['customer_id'];
    $ledger_query = mysqli_query($con,"SELECT * FROM orders WHERE status!='Cancelled' and DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$orderfrom."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$orderto."'   and customer_id=".$customer_id."  AND payment_status = 'Pending' order by id desc ");
    $flyer_query = mysqli_query($con,"SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   AND customer=".$customer_id." AND payment_status = 'Pending'  order by id desc ");
  } else{
      $customer_id = $_GET['customer_id'];
      $ledger_query = mysqli_query($con,"SELECT * FROM orders WHERE status!='Cancelled' and customer_id=".$customer_id."   AND payment_status = 'Pending' order by id desc ");
      $flyer_query = mysqli_query($con,"SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'  AND customer =".$customer_id." AND payment_status = 'Pending'  order by id desc ");
  }
  if(true){
    if($customer_id) {
      $balance_query = mysqli_query($con, "SELECT (prev_balance + (total_payable - total_paid)) as total FROM non_customer_ledger_payments WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 1");
      $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
      $customer_balance = ($balance_query) ? number_format($balance_query->total,2) : 0;
    }
  include "includes/header.php";
  // $query         = mysqli_query($con,"SELECT * FROM customers WHERE id =".$customer_id." ");
  // $record        = mysqli_fetch_array($query);
  $customer_list = mysqli_query($con,"SELECT * FROM customers where status='1'");
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
          .ledgerLists p{
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
        <div class="bulk_payment_box">
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
          <div class="page-header customer_settle_period_lable">
         
        </div>
           <form  method="GET" action="non_bulk_ledger_payment.php">
              <div class="row">
              <div class="col-md-2 padd_none">
                <div class="form-group">
                  <label><?php echo getLange('customer'); ?></label>
                  <select class="form-control js-example-basic-single customer_settle_period" required="true" name="customer_id">
                    <option disabled="" selected="" value="">Select</option>
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
              <div class="col-md-2 ">
                <div class="form-group">
                  <label><?php echo getLange('from') ?></label>
                  <input class="form-control datetimepicker4 orderfrom" name="from" value="<?php echo $orderfrom; ?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label><?php echo getLange('to'); ?></label>
                  <input class="form-control datetimepicker4" name="to" value="<?php echo $orderto; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>" style="margin-top: 24px;">
                </div>
              </div>
            </div><br>
           <!-- <a href="#" class="btn btn-success generate_payment" style="margin: 15px 0px;">Generate</a> -->
           </form>
           <form action="non_submit_bulk_ledger_payment.php" method="POST" >
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <input type="hidden" name="total_cod">
            <input type="hidden" name="total_delivery">
            <input type="hidden" name="total_return_fee">
            <input type="hidden" name="total_charges">
            <input type="hidden" name="fuel_surcharge">
            <input type="hidden" name="net_amount">
            <input type="hidden" name="total_extra_charges">
            <input type="hidden" name="total_insuredpremium_charges">
            <input type="hidden" name="total_gst">
            <!-- <input type="hidden" name="total_cash_handling"> -->
            <input type="hidden" name="total_payable_price">
            <input type="hidden" name="total_flyer">
            <input type="hidden" name="count_total_flyer_checked">
            <input type="hidden" name="count_total_return_checked">
            <input type="hidden" name="count_total_del_checked">
            <div class="row">
              <div class="col-md-3 padd_none">
                <div class="form-group">
                  <label><?php echo getLange('date'); ?></label>
                  <input type="text" name="date" class="form-control datetimepicker4" value="<?=date('Y/m/d');?>" required="true">
                </div>
              </div>
              <div class="col-md-3">
                <?php
                $reference = strtoupper(substr(hash('sha256', mt_rand() . microtime()), 0, 8));
                 ?>
                <div class="form-group">
                  <label><?php echo getLange('transactionid'); ?></label>
                  <input type="text" name="reference_no" value="<?php echo $reference; ?>" class="form-control" required="true">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo getLange('customer').' '.getLange('balance'); ?>.</label>
                  <input type="text" readonly="true" name="prev_balance" class="form-control customer-balanced" value="<?=$customer_balance;?>">
                  <input type="hidden" class="customer_balance" value="<?php echo $customer_balance; ?>">
                </div>
              </div>
            </div>
           <br>
           <table class="table table-striped table-bordered" data-cod="1" id="ledgerLists">
             <thead>
               <tr>
                 <th ><input  type="checkbox" class="selectallorders"></th>
                 <th><?php echo getLange('trackingno'); ?></th>
                 <th><?php echo getLange('deliveryname'); ?></th>
                 <th><?php echo getLange('deliveryphone'); ?></th>
                 <th><?php echo getLange('deliverycity'); ?></th>
                 <th><?php echo getLange('weight'); ?></th>
                 <th><?php echo getLange('collectionamount'); ?></th>
                 <th><?php echo getLange('deliveycharges'); ?></th>
                 <th><?php echo getLange('specialcharges'); ?></th>
                 <th><?php echo getLange('extracharges'); ?></th>
                 <th><?php echo getLange('insurancepremium'); ?></th>
                 <th><?php echo getLange('totalcharges'); ?></th>
                 <th><?php echo getLange('fuelsurcharge'); ?></th>
                 <th><?php echo getLange('gst'); ?></th>
                 <th><?php echo getLange('netamount'); ?></th>
                 <th><?php echo getLange('status'); ?></th>
               </tr>
             </thead>
             <tbody>
              <?php while($row = mysqli_fetch_array($ledger_query)){
                $key_name = (strtolower($row['status']) == 'delivered') ? 'delivered' : 'returned';
               ?>
               <tr>
                 <td ><input checked type="checkbox" class="orderidS" data-totalNetAmount="<?php echo isset($row['net_amount']) ? $row['net_amount']:0; ?>" data-totalFuelSurcharge="<?php echo isset($row['fuel_surcharge']) ? $row['fuel_surcharge']:0; ?>" data-totalCharges="<?php echo isset($row['grand_total_charges']) ? $row['grand_total_charges']:0; ?>" data-gst_vat="<?php echo isset($row['pft_amount']) ? $row['pft_amount']:0; ?>" data-status="<?php echo $row['status'] ?>" data-delivery="<?php echo $row['price'] ?>" data-totanetamount="<?php echo $row['net_amount']; ?>" data-extracharge="<?php echo $row['extra_charges']; ?>" data-insuredpremium="<?php echo $row['insured_premium']; ?>" data-cod="<?php echo $row['collection_amount'] ?>" data-pft="<?php echo $row['pft_amount'] ?>" value="<?php echo $row['id'] ?>" name="<?=$key_name;?>[<?=$row['id'];?>]"></td>
                <td><?php echo $row['track_no']; ?></td>
                <td><?php echo $row['rname']; ?></td>
                <td><?php echo $row['rphone']; ?></td>
                <td><?php echo  $row['destination']; ?></td>
                <td><?php echo  $row['weight']; ?> KG</td>
                <td><?php echo $row['collection_amount']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['special_charges']; ?></td>
                <td><?php echo $row['extra_charges']; ?></td>
                <td><?php echo $row['insured_premium']; ?></td>
                <td><?php echo $row['grand_total_charges']; ?></td>
                <td><?php echo $row['fuel_surcharge']; ?></td>
                <td><?php echo $row['pft_amount']; ?></td>
                <td><?php echo $row['net_amount']; ?></td>
                <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
               </tr>
             <?php } ?>
             </tbody>
           </table>
           <table class="table table-striped table-bordered  " id="flyerList">
             <thead>
               <tr>
                 <th style="display: none;"><input type="checkbox" class="select_allflyersell"></th>
                 <th><?php echo getLange('invoiceno'); ?></th>
                 <th><?php echo getLange('date'); ?></th>
                 <th><?php echo getLange('description'); ?></th>
                 <th><?php echo getLange('grand_total_charges'); ?></th>
               </tr>
             </thead>
             <tbody>
              <?php while($row = mysqli_fetch_array($flyer_query)){
                $flayer_order_index = $row['id'];
                $flayer_order_query = mysqli_query($con,"SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=".$flayer_order_index." ");
                $total = getTotal($row['id']);
               ?>
                <tr>
                  <td style="width: 5%; display: none;"><input checked type="checkbox" class="orderidS"  data-flyer="<?php echo $total; ?>"  value="<?php echo $row['id'] ?>" name="flyer[<?=$row['id'];?>]"></td>
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
                  <th><?php echo getLange('prevbalance'); ?></th>
                  <th><?php echo getLange('totalcod'); ?></th>
                  <th><?php echo getLange('totalcharges'); ?></th>
                  <!-- <th>Total Delivery</th> -->
                  <th><?php echo getLange('fuelsurcharge'); ?></th>
                  <!-- <th><?php echo getLange('totalinsurancepremium'); ?></th> -->
                  <!-- <th>Total Return</th> -->
                  <!-- <th>Total Flyer Sell</th> -->
                  <th><?php echo getLange('total').' '.getLange('gst'); ?></th>
                  <!-- <th>Total Return Charges</th> -->
                  <!-- <th>Cash Handling</th> -->
                  <th><?php echo getLange('netamount'); ?> (<?php echo getLange('fee'); ?>)</th>
                  <th><?php echo getLange('totalpayable'); ?></th>
                  <!-- <th>Return Pay</th> -->
                  <th><?php echo getLange('payment'); ?></th>
                  <th><?php echo getLange('action'); ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
               <td><b><?=number_format($customer_balance, 2);?></b></td>
               <!-- <td><b> <span id="totalDelivery"></span></b></td> -->
               <td><b> <span id="totalCOD"></span></b></td>
               <td><b> <span id="totalCharges"></span></b></td>
               <td><b><span id="totalFuelSurcharge"></span></b></td>
               <!-- <td><b><span id="totalReturn"></span></b></td> -->
               <!-- <td><b><span id="totalextracharges"></span></b></td> -->
               <!-- <td><b><span id="totalinsuredpremium"></span></b></td> -->
               <!-- <td><b><span id="totalReturn"></span></b></td> -->
               <!-- <td><b><span id="totalFlyerSell"></span></b></td> -->
               <td><b><span id="totalGST"></span></b></td>
               <td><b><span id="totalNetAmount"></span></b></td>
               <!-- <td><b><span id="totalRETURNCHARGES"></span></b></td> -->
               <!-- <td><b><span id="totalChashhandling"></span></b></td> -->
               <td><b><span id="totalPayables"></span></b></td>
               <!-- <td><input type="text" readonly="true" class="form-control" name="total_return"></td> -->
               <td>
                <input type="text" name="total_payments" class="form-control">
               </td>
               <td><input type="submit" name="submit" class="btn btn-success" value="<?php echo getLange('createinvoice'); ?>" /></td>
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
      $(function () {
          $('.datetimepicker4').datetimepicker({
            format: 'YYYY/MM/DD',
          });
      });
        $('body').on('change', '.customer_settle_period', function() {
          var customer_id = $(this).val();
          $.ajax({
              type: 'POST',
                dataType: 'Json',
              data: {
                  customer_settle_period: 1,
                  customer_id_settle: customer_id
              },
              url: 'ajax.php',
              success: function(response) {
                  $('.orderfrom').val(response.from);
                    $('.customer_settle_period_lable').html(response.payment_within);
              }
          })
      })
  </script>
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
      var parseFloatFormatted = function(value) {
          if(value == undefined || value == ''){
              return value;
          }
          return parseFloat(value.toString().replaceAll(',', '').replaceAll(' ', ''));
      }
      $('body').on('click', '.select_allflyersell', function(e) {
        var isChecked = $(this).prop('checked');
        $('#flyer_list >tbody tr').each(function(i) {
          $(this).find('.orderidS').prop('checked', isChecked);
        });
        calculatebulklaedger();
      })
      $('body').on('click', '.selectallorders', function(e) {
        var isChecked = $(this).prop('checked');
        $('#ledgerLists >tbody tr').each(function(i) {
          $(this).find('.orderidS').prop('checked', isChecked);
        });
        calculatebulklaedger();
      })
      $('body').on('click','.orderidS',function(){
        calculatebulklaedger();
      })
      function calculatebulklaedger(){
        var bodyParent = $('body');
        var orderidSs = [];
        var total_cod = 0;
        var total_delivery_charges = 0;
        var total_charges = 0;
        var total_fuelsurcharges = 0;
        var total_net_amount = 0;
        var total_extra_charges = 0;
        var total_insuredpremium_charges = 0;
        var total_return = 0;
        var total_return_count = 0;
        var total_flyer  = 0;
        var count_checked_flyer  = 0;
        var count_checked_del  = 0;
        var count_checked_return  = 0;
        var total_cash_handling = 0;
        var total_pft = 0;
        var total_gst = 0;
        bodyParent.find('#ledgerLists > tbody  > tr').each(function() {
          var checkbox = $(this).find('td:first-child .orderidS');
          if(checkbox.prop("checked") == true){
            let cod = checkbox.attr("data-cod");
            cod = (cod) ? Number(cod) : 0;
            let delivery = checkbox.attr("data-delivery");
            delivery = (delivery) ? Number(delivery) : 0;
            let totanetamount = checkbox.data("totanetamount");
            totanetamount = (totanetamount) ? totanetamount : 0;
            let totalcharges = checkbox.data("totalcharges");
            totalcharges = (totalcharges) ? totalcharges : 0;
            let totalfuelsurcharges = checkbox.data("totalfuelsurcharge");
            totalfuelsurcharges = (totalfuelsurcharges) ? totalfuelsurcharges : 0;
            let extracharge = checkbox.attr("data-extracharge");
            extracharge = (extracharge) ? Number(extracharge) : 0;
            let insuredpremium = checkbox.attr("data-insuredpremium");
            insuredpremium = (insuredpremium && insuredpremium > 0) ? insuredpremium : 0;
            let gst_vat = checkbox.data("gst_vat");
            // alert(gst_vat);
            // if(gst_vat > 0)
            // {
            //   gst_vat = gst_vat.replace(',','');
            // }
            gst_vat = (gst_vat) ? gst_vat : 0;
            total_gst+=parseFloat(gst_vat);
            total_cod+= parseFloat(cod);
            total_delivery_charges+=parseFloat(delivery);
            total_net_amount+=parseFloat(totanetamount);
            total_extra_charges+=parseFloat(extracharge);
            total_insuredpremium_charges+=parseFloat(insuredpremium);
            total_charges+=parseFloat(totalcharges);
            total_fuelsurcharges+=parseFloat(totalfuelsurcharges);
            if(checkbox.attr("data-status") == "Delivered"){
              count_checked_del = count_checked_del+1;
              var pft = checkbox.attr("data-pft");
              pft = (pft) ? Number(pft) : 0;
              total_pft += pft;
            } else if(checkbox.attr('data-status') == 'Returned to Shipper') {
              count_checked_del = count_checked_del+1;
              var pft = checkbox.attr("data-pft");
              pft = (pft) ? Number(pft) : 0;
              total_pft += pft;
              total_return += cod;
              total_return_count +=1;
              count_checked_return = count_checked_return+1;
            }
            else{
              count_checked_del = count_checked_del+1;
              var pft = checkbox.attr("data-pft");
              pft = (pft) ? Number(pft) : 0;
              total_pft += pft;
            }
          }
        });
        bodyParent.find('#flyerList > tbody  > tr').each(function() {
          var checkbox = $(this).find('td:first-child .orderidS');
          if(checkbox.prop("checked") ==true){
            let flyer = checkbox.attr("data-flyer");
            flyer = (flyer) ? Number(flyer) : 0;
            total_flyer +=flyer;
            count_checked_flyer = count_checked_flyer+1;
          }
        });
        var cash_handling_fee = bodyParent.find('#cash_handling_fee_setting').val();
        cash_handling_fee = (cash_handling_fee) ? Number(cash_handling_fee) : 0;
        var total_gst_per = $('body').find('#total_gst').val();
        // total_gst = (total_pft) ? Number(total_pft) : 0;
        // var total_cash = total_cod - total_return;
        // var total_cash_handling = (total_cash/100)*cash_handling_fee;
        // var return_fee = $('#return_fee_setting').val();
        // return_fee = (return_fee) ? Number(return_fee) : 0;
        // var total_return_fee = total_return_count*return_fee;
        // var total_gst = 0;
        // total_gst = parseFloat((total_delivery_charges)/100 * total_gst_per);
        // var cod=bodyParent.find('#ledgerLists').attr("data-cod");
        // console.log(cod);
        var total_payable = total_net_amount;
        var balance = bodyParent.find('.customer_balance').val();
        balance = ((balance) && balance > 0) ? balance : 0;
        balance = parseFloatFormatted(balance);
        total_payable = parseFloat(total_payable) + parseFloat(balance);
        total_payable = parseFloat(total_payable - total_cod).toFixed(2);
        total_charges = parseFloat(total_charges).toFixed(2);
        total_fuelsurcharges = parseFloat(total_fuelsurcharges).toFixed(2);
        total_insuredpremium_charges = parseFloat(total_insuredpremium_charges).toFixed(2);
        total_extra_charges = parseFloat(total_extra_charges).toFixed(2);
        total_gst = parseFloat(total_gst).toFixed(2);
        total_delivery_charges = parseFloat(total_delivery_charges).toFixed(2);
        total_cod = parseFloat(total_cod).toFixed(2);
        total_net_amount = parseFloat(total_net_amount).toFixed(2);
        bodyParent.find('#totalCOD').text(total_cod);
        bodyParent.find('#totalDelivery').text(total_delivery_charges);
        // alert(total_charges);
        bodyParent.find('#totalCharges').text(total_charges);
        bodyParent.find('[name="total_charges"]').val(total_charges);
        bodyParent.find('#totalFuelSurcharge').text(total_fuelsurcharges);
        bodyParent.find('[name="fuel_surcharge"]').val(total_fuelsurcharges);


        bodyParent.find('#totalextracharges').text(total_extra_charges);
        bodyParent.find('#totalinsuredpremium').text(total_insuredpremium_charges);
        bodyParent.find('#totalGST').text(total_gst);
        bodyParent.find('#totalPayables').text(total_payable);
        bodyParent.find('[name="total_cod"]').val(total_cod);
        bodyParent.find('[name="total_delivery"]').val(total_delivery_charges);
        bodyParent.find('[name="net_amount"]').val(total_net_amount);
        bodyParent.find('#totalNetAmount').text(total_net_amount);
        bodyParent.find('[name="total_extra_charges"]').val(total_extra_charges);
        bodyParent.find('[name="total_insuredpremium_charges"]').val(total_insuredpremium_charges);
        bodyParent.find('[name="total_gst"]').val(total_gst);
        bodyParent.find('[name="total_payable_price"]').val(total_payable);
        bodyParent.find('[name="total_payments"]').val(total_payable);
        bodyParent.find('[name="count_total_del_checked"]').val(count_checked_del);
        bodyParent.find('[name="count_total_flyer_checked"]').val(count_checked_flyer);
        bodyParent.find('[name="count_total_return_checked"]').val(count_checked_return);
      }
      if($('body').find('#ledgerLists').length > 0) {
        calculatebulklaedger();
        calculateemployeeledger();
      }
      function calculateemployeeledger(){
        var orderidSs = [];
        var total_cod = 0;
        var total_pickup_comm = 0;
        var total_delivery_comm = 0;
        total_pickup_comm = Number($('.total_pickup_comm').val());
        total_delivery_comm = Number($('.total_delivery_comm').val());
        var total_payable = Number(total_pickup_comm+total_delivery_comm);
        var balance = parseFloat($('.employee-balance').val());
        balance = ((balance) && balance > 0) ? balance : 0;
        if(balance > 0)
        {
          balance = balance.replace(',', '');
        }
        total_payable = total_payable + balance;
        total_payable = (total_payable) ? Number(total_payable) : 0;
        $('#emptotalPayable').text(total_payable.toFixed(2));
        $('[name="total_payable"]').val(total_payable);
        $('[name="total_payment"]').val((total_payable >= 0) ? total_payable : 0);
      }
    },false);
  </script>
  <?php if (isset($_GET['customer_id']) && $_GET['customer_id']!='') {?>
    <script type="text/javascript">
        var customer_id = $('.customer_settle_period').val();
        $.ajax({
            type: 'POST',
            dataType: 'Json',
            data: {
                customer_settle_period: 1,
                customer_id_settle: customer_id
            },
            url: 'ajax.php',
            success: function(response) {
                // $('.orderfrom').val('');
                $('.customer_settle_period_lable').html(response.payment_within);
            }
        })
  </script>
  <?php } ?>
