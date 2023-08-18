<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
  session_start();
  include_once "includes/conn.php";
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
  if(isset($_SESSION['customers'])){
    include "includes/header.php";
$page_title = 'Dashboard';
$is_profile_page = true;
$from_date = date('Y-m-d', strtotime('-30 days'));
$to_date = date('Y-m-d');
if(isset($_POST['submit'])){
    $from_date = date('Y-m-d',strtotime($_POST['from']));
    $to_date = date('Y-m-d',strtotime($_POST['to']));
  }
?>
<style>
  a{
      text-decoration: none !important;
  }

  .pull-right{
    font-size: 30px;
  }
  .{
    font-size: 27px;
  }
  .whitee{
        font-size: 16px;
  }
  .menu-bar {
    padding: 5px 0px 24px 0px;
}

@media(max-width: 767px){
  .container{
    width: auto;
  }

}
 


 
.main_fix_tree {
    background: linear-gradient( 
45deg
 , #234277, #254985);
    padding: 20px;
    border-radius: 10px;
    background-repeat: no-repeat;
    margin-bottom: 30px;
    box-shadow: 3px 5px 11px 0 #0000002e;
}
.new_color_last:after{
  content: unset !important;
}
.new_color:after {
    right: unset;
    left: 23px;
    background: #7700f8 !important;
}
.main_fix_tree:after {
    position: absolute;
    content: "";
    background-color: #a12fae;
    height: 83px;
    width: 12px;
    right: 23px;
    z-index: 0;
    border-radius: 22px;
}
.main_fix_tree:before {
    position: absolute;
    content: "";
    background-image: url(https://codportal.icargos.com/V6/admin/img/overlay.svg);
    right: 0;
    left: 0;
    bottom: 0;
    background-repeat: no-repeat;
    width: 100%;
    top: 0;
    background-position: 9px 60px;
    background-size: cover;
    z-index: 999999;
}
.main_fix_tree h3 {
    color: #fff;
    font-size: 17px;
    text-align: center;
    margin: 0;
}
.main_fix_tree b {
    font-size: 25px;
    font-weight: 500;
    text-align: center;
    color: #fff;
    display: block;
}
.box_wrapper_fix{
  padding: 0px 0 30px;
}
.final_first_c {
  padding-top: 20px;
}
.pr-0{
  padding-right: 0;
} 
.pl-0{
  padding-left: 0;
}
.left_center:after {
    right: 270px;
    background-color: #5f4ea8;
}
.right_center:after {
    right: unset;
    left: 270px;
    background-color:#6001c8 !important;
}
.padd_left_p{
	padding-left: 30px;
}
.last_hide_fix_v{

}
.first_box_hide_v:after {
    right: 0;
    left: 0;
    margin: 0 auto;
}
.last_hide_fix_v:after{
  content: unset !important;
}
.date_tosearch input{
      border: none;
    background: #c91717;
    color: #fff !important;
}
.form-control, .input-group-addon, .bootstrap-select .btn {
    border-color: #c4c4c4;
}
</style>
<section class="bg padding30">
  <div class="container-fluid dashboard">
    <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
       <!--sidebar come here!-->
    <?php
    include "includes/sidebar.php";
    ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
      
      
       
      <div class="white">
         
      <form method="POST" action="">
        <div class="row">
          <div class="col-sm-2 left_right_none from_to">
              <div class="form-group">
                  <label><?php echo getLange('from'); ?></label>
                  <input type="text" value="<?php echo $from_date; ?>" class="form-control datepicker" name="from">
              </div>
          </div>
          <div class="col-sm-2 left_right_none from_to">
              <div class="form-group">
                  <label><?php echo getLange('to'); ?></label>
                  <input type="text" value="<?php echo $to_date; ?>" class="form-control datepicker" name="to">
              </div>
          </div>
           <div class="col-sm-1 sidegapp-submit search_dashboard_Btn date_tosearch" style="    margin-top: 21px;">
            <input type="submit"  name="submit" class="btn btn-success" value="<?php echo getLange('search'); ?>">
        </div>
        </div>
      </form>


    <?php
   $totalcod=0;
    $id=$_SESSION['customers'];
    $querycod= mysqli_fetch_array(mysqli_query($con,"SELECT SUM(collection_amount) AS collection_amount FROM orders WHERE customer_id='".$id."' AND status='Delivered' AND payment_status='Pending' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."'"));
  //      while($singleer = mysqli_fetch_array($querycod)){
  // }
  $totalcod .=$querycod['collection_amount'];
  $totalorder=0;
    $id=$_SESSION['customers'];

    $querytotal= mysqli_query($con,"SELECT count(id) as customerorderid from orders where customer_id='".$id."'  and status!='Cancelled' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."'");
       while($singleerr = mysqli_fetch_array($querytotal)){

    $totalorder=$singleerr['customerorderid'];
  }
   $main_query = mysqli_query($con,"SELECT * FROM order_status WHERE 1  ORDER BY order_status.sort_num ");


  $main_querys = mysqli_query($con,"SELECT * FROM order_status WHERE 1  ORDER BY order_status.sort_num ");



 /*  $query = mysqli_query($con, "SELECT * FROM orders WHERE customer_id = $id");
  while($row = mysqli_fetch_array($query)) {
      $pendingPayments += ($row['collection_amount'] - $row['payment_amount']);
      $collectedPayments += $row['payment_amount'];
  }*/
   // echo $collectedPayments;
    ?>
      <?php

    while($single = mysqli_fetch_array($main_query)){
       $status = $single['status'];
      $countorderquery = mysqli_query($con,"SELECT count(id) as total_count FROM orders WHERE status='".$status."' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."' AND customer_id=".$id);
        $total_res = mysqli_fetch_array($countorderquery);
        $total_orders = $total_res['total_count'];
        $color_code = $single['color_code'];
      ?>
        <div class="col-sm-2 profile-page-title">
          <div style="background: <?php echo $color_code; ?>" class="mini-stat clearfix bx-shadow bg-info dash-item"> <span class="mini-stat-icon"><i class="fa fa-cube fa-6"></i></span>
            <div class="mini-stat-info text-right"><a class="whitee" href="orders.php?orderss_status=<?php echo $single['status']; ?>"> <span class=""><?php echo $total_orders; ?></span> <?php echo getKeyWordCustomer($id,$single['status']); ?> </a></div>

          </div>
        </div>
       <?php } ?>

        <div class="col-sm-2 profile-page-title">
          <div class="mini-stat clearfix bx-shadow bg-info6 dash-item" style="background:#7fa9f6;"> <span class="mini-stat-icon"><i class="fa fa-cube fa-6"></i></span>
            <div class="mini-stat-info text-right"> <a class="whitee"  href="tracking.php" > <span class=" whitee"></span></a>  <?php echo getLange('trackorder'); ?></div>

          </div>
        </div>
        <div class="col-sm-2 profile-page-title">
          <div class="mini-stat clearfix bx-shadow bg-info7 dash-item" style="background: #83a1d3;"> <span class="mini-stat-icon"><i class="fa fa-cube fa-6"></i></span>
            <div class="mini-stat-info text-right"> <a class="whitee"  href="orders.php" > <span class=" whitee"><?php echo $totalorder; ?></span> </a> <?php echo getLange('vieworder') ?></div>

          </div>
        </div>



        <div class="col-sm-2 profile-page-title">
          <div style="background: #a2bcec;" class="mini-stat clearfix bx-shadow bg-info dash-item"> <span class="mini-stat-icon"><i class="fa fa-cube fa-6"></i></span>
            <div class="mini-stat-info text-right"><a class="whitee" href="orders.php"> <span class=""><?php echo $totalcod; ?></span> <?php echo getLange('pending').' '.getLange('codamount'); ?></a></div>

          </div>
        </div>


    <?php 
     $totalcod_open =0;
    $totalgrandtotal_open =0;
    $totalpayable_open =0;
    $totalcod_delivered =0;
    $totalgrandtotal_delivered =0;
    $totalgrandtotal_returned =0;
    $totalpayable_closed_order =0;
    $totalnet_payable =0;
    $totalcollection_amount_open=0;
   $totalgrand_total_charges_open=0;
   $total_payable_open_order=0;
   $total_payable_closed_order=0;
   $net_payables=0;
   $totalcollection_amount_deliverd=0;
   $totalgrand_total_charges_deliverd=0;
   $totalgrand_total_charges_returned=0;
   $query1 = mysqli_query($con,"SELECT SUM(collection_amount) as collection_amount,SUM(net_amount) as grand_total_charges FROM orders WHERE 1  AND status!='Delivered' AND status!='cancelled' AND status!='Returned to Shipper' AND status!='New Booked' AND status!='Pick up in progress' AND payment_status='Pending'  AND customer_id='".$_SESSION['customers']."' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."' ");
  
  while($fetch1=mysqli_fetch_array($query1)){
    
    if($fetch1['collection_amount']!='' || $fetch1['grand_total_charges']!=''){

    $totalcollection_amount_open =$fetch1['collection_amount'];

    $totalgrand_total_charges_open =$fetch1['grand_total_charges'];
     }
  }
 
  $query2 = mysqli_query($con,"SELECT SUM(collection_amount) as collection_amount,SUM(net_amount) as grand_total_charges FROM orders WHERE 1 AND status='Delivered' AND payment_status='Pending'  AND customer_id='".$_SESSION['customers']."' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."' ");
   
  while($fetch2=mysqli_fetch_array($query2)){

    if($fetch2['collection_amount']!='' || $fetch2['grand_total_charges']!=''){
    $totalcollection_amount_deliverd =$fetch2['collection_amount'];

    $totalgrand_total_charges_deliverd =$fetch2['grand_total_charges'];
     }
  }
  
  $query3 = mysqli_query($con,"SELECT SUM(net_amount) as grand_total_charges FROM orders WHERE 1 AND status='Returned to Shipper' AND payment_status='Pending'  AND customer_id='".$_SESSION['customers']."' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."' ");
   
  while($fetch3=mysqli_fetch_array($query3)){

    if($fetch3['grand_total_charges']!=''){

    $totalgrand_total_charges_returned =$fetch3['grand_total_charges'];
     }
  }
  if (isset($_SESSION['customer_type']) && $_SESSION['customer_type']==0) {
    $total_payable_open_order=$totalcollection_amount_open - $totalgrand_total_charges_open;
    $total_payable_closed_order=$totalcollection_amount_deliverd - $totalgrand_total_charges_deliverd - $totalgrand_total_charges_returned;
    $net_payables=$total_payable_open_order  + $total_payable_closed_order;
    // total
    $totalcod_open =$totalcollection_amount_open;
    $totalgrandtotal_open =$totalgrand_total_charges_open;
    $totalpayable_open =$total_payable_open_order;
    $totalcod_delivered =$totalcollection_amount_deliverd;
    $totalgrandtotal_delivered =$totalgrand_total_charges_deliverd;
    $totalgrandtotal_returned =$totalgrand_total_charges_returned;
    $totalpayable_closed_order =$total_payable_closed_order;
    $totalnet_payable =$net_payables;
  }
  else{
    $total_payable_open_order= $totalgrand_total_charges_open - $totalcollection_amount_open;
    $total_payable_closed_order=$totalcollection_amount_deliverd - $totalgrand_total_charges_deliverd - $totalgrand_total_charges_returned;
    $net_payables=$total_payable_open_order  + $total_payable_closed_order;
    // total
    $totalcod_open =$totalcollection_amount_open;
    $totalgrandtotal_open =$totalgrand_total_charges_open;
    $totalpayable_open =$total_payable_open_order;
    $totalcod_delivered =$totalcollection_amount_deliverd;
    $totalgrandtotal_delivered =$totalgrand_total_charges_deliverd;
    $totalgrandtotal_returned =$totalgrand_total_charges_returned;
    $totalpayable_closed_order =$total_payable_closed_order;
    $totalnet_payable =$net_payables;
  }

     ?>

<div class="row">
  <div class="col-sm-12 order_chart">


    <?php 
  if (isset($_SESSION['customer_type']) && $_SESSION['customer_type']==0) { ?>
         <div class="panel panel-default">
            <div class="panel-heading"><?php echo  isset($_SESSION['customer_type']) && $_SESSION['customer_type']==1 ? ' Invoice Payable'  : getLange('cod_receivable');?> </div>
            <div class="panel-body">

              <div class="row charts padd_left_p" style="background:#fff;border: 1px solid #ccccccab;padding-bottom: 12px;">
                    <div class="row">
           <div class="col-lg-6 final_first_c pl-0">
             <div class="main_fix_tree bottom_left left_center">
               <div class="row">
               <div class="col-lg-6 box_fix_tree">
                 <h3><?php echo getLange('open_orders_cod'); ?></h3>
                 <b><?php echo number_format((float)$totalcollection_amount_open , 2); ?></b>
               </div>
               <div class="col-lg-6 box_fix_tree">
                 <h3><?php echo getLange('open_orders_charges'); ?></h3>
                 <b><?php echo number_format((float)$totalgrand_total_charges_open , 2); ?></b>
               </div>
             </div>
             </div>
             <div class="row">
               <div class="col-lg-12 last_total main_fix_tree">
                  <h3><?php echo getLange('open_order_total_receivable'); ?></h3>
                 <b><?php echo number_format((float)$total_payable_open_order , 2); ?></b>
               </div>
             </div>
           </div>


           <div class="col-lg-6 final_first_c pr-0">
             <div class="main_fix_tree bottom_left new_color right_center" style="background:linear-gradient(45deg, #7a00ff, #3f0282);">
               <div class="row">
               <div class="col-lg-4 box_fix_tree ">
                 <h3><?php echo getLange('delivered_cod'); ?></h3>
                 <b><?php echo number_format((float)$totalcollection_amount_deliverd , 2); ?></b>
               </div>
               <div class="col-lg-4 box_fix_tree">
                 <h3><?php echo getLange('delivered_charges'); ?></h3>
                 <b><?php echo number_format((float)$totalgrand_total_charges_deliverd , 2); ?></b>
               </div>
               <div class="col-lg-4 box_fix_tree">
                 <h3><?php echo getLange('returned_charges'); ?></h3>
                 <b><?php echo number_format((float)$totalgrand_total_charges_returned , 2); ?></b>
               </div>
             </div>
             </div>
             <div class="row">
               <div class="col-lg-12 last_total main_fix_tree new_color" style="background:linear-gradient(45deg, #7a00ff, #3f0282);">
                  <h3><?php echo getLange('closed_order_total_receivable'); ?></h3>
                 <b><?php echo number_format((float)$total_payable_closed_order , 2); ?></b>
               </div>
             </div>
           </div>
           <div class="col-lg-3">
                 
               </div>
           <div class="col-lg-6 final_first_c ">
             <div class="main_fix_tree bottom_left new_color_last" style="background:linear-gradient(310deg, #3456fb, #a12eae);">
               <div class="row">
               
               <div class="col-lg-12 box_fix_tree">
                 <h3><?php echo getLange('net_receivable'); ?></h3>
                 <b><?php echo number_format((float)$net_payables , 2); ?></b>
               </div>
            
             </div>
             </div>
           </div>
           <div class="col-lg-3">
                 
               </div>
         </div>
                    <!-- <div class="col-sm-6">
                        <h3>Payments</h3>
                        <input type="hidden" name="pending" value="<?php echo $pendingPayments.'_#4286f4'; ?>" />
                        <input type="hidden" name="completed" value="<?php echo $collectedPayments.'_#e28118'; ?>" />
                        <canvas id="paymentChart"></canvas>
                    </div> -->
                </div>
            </div>
          </div>
         <?php } else{
          ?>
          <div class="panel panel-default">
            <div class="panel-heading"><?php echo  isset($_SESSION['customer_type']) && $_SESSION['customer_type']==1 ? ' Invoice Payable'  : getLange('cod_receivable');?> </div>
            <div class="panel-body">

              <div class="row charts padd_left_p" style="background:#fff;border: 1px solid #ccccccab;padding-bottom: 12px;">
                    <div class="row">
           <div class="col-lg-12 final_first_c pl-0">
             <div class="main_fix_tree bottom_left left_center first_box_hide_v">
               <div class="row">
               <div class="col-lg-6 box_fix_tree">
                 <h3><?php echo getLange('open_orders_cod'); ?></h3>
                 <b><?php echo number_format((float)$totalcollection_amount_open , 2); ?></b>
               </div>
               <div class="col-lg-6 box_fix_tree">
                 <h3><?php echo getLange('open_orders_charges'); ?></h3>
                 <b><?php echo number_format((float)$totalgrand_total_charges_open , 2); ?></b>
               </div>
             </div>
             </div>
             <div class="row">
              <div class="col-lg-3">
                
              </div>
               <div class="col-lg-6 last_total main_fix_tree last_hide_fix_v">
                  <h3><?php echo getLange('open_order_total_receivable'); ?></h3>
                 <b><?php echo number_format((float)$total_payable_open_order , 2); ?></b>
               </div>
               <div class="col-lg-3">
                
              </div>
             </div>
           </div>


           
            
           
            
         </div>
                    
                </div>
            </div>
          </div>
        <?php } ?>
          <div class="panel panel-default">
            <div class="panel-heading"><?php echo getLange('orderstatistic'); ?> </div>
            <div class="panel-body">

              <div class="row charts" style="background:#fff;border: 1px solid #ccccccab;padding-bottom: 12px;">
                    <div class="col-sm-12">

                      <?php

                      while($single2 = mysqli_fetch_array($main_querys))
                      {
                        $countorderquery = mysqli_query($con,"SELECT count(id) as total_count FROM orders WHERE status='".$single2['status']."' AND customer_id='".$id."' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '".$to_date."' ");
                        $total_res = mysqli_fetch_array($countorderquery);

                        $total_orders = $total_res['total_count'];


                      $can = $total_orders.'_'.$single2['color_code'];

                        ?>
                        <input type="hidden" name="<?php echo getKeyWordCustomer($id,$single2['status']) ?>" value="<?php echo $can; ?>" />
                    <?php } ?>

                        <canvas id="orderChart"></canvas>
                    </div>
                    <!-- <div class="col-sm-6">
                        <h3>Payments</h3>
                        <input type="hidden" name="pending" value="<?php echo $pendingPayments.'_#4286f4'; ?>" />
                        <input type="hidden" name="completed" value="<?php echo $collectedPayments.'_#e28118'; ?>" />
                        <canvas id="paymentChart"></canvas>
                    </div> -->
                </div>
            </div>
          </div>
        </div>
</div>


      </div>

    </div>

  </div>
  <style>
   .whitee{
    color:white !important;
  }
  .whitee:hover{
    color:white !important;
  }
  </style>
</section>
</div>
  <?php
  }
  else{
    header("location:index.php");

  }
  ?>
  <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap -->
  <script>
document.addEventListener('DOMContentLoaded', function(){

  $('title').text($('title').text()+' Profile')
}, false);
</script>
<script  type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $('.datepicker').datepicker({
      format: 'yyyy/mm/dd',
    });
  charts();

  function charts()
  {
    // alert("hello");
    var orderElement = $('#orderChart');

      var paymentElement = $('#paymentChart');
      var labels = [];
      var data = [];
      var colors = [];
      var orders = orderElement.parent().find('input[type="hidden"]');

      orders.each(function(index) {
        labels[index] = $(this).attr('name');
        data[index] = $(this).val().split('_')[0];
        colors[index] = $(this).val().split('_')[1];
      });
      var orderData = {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: colors,
          hoverBackgroundColor: colors
        }]
      };
      var order = new Chart(orderElement,{
        type: 'pie',
        data: orderData
      });
      var paymentlabels = [];
      var paymentDataSet = [];
      var paymentColors = [];
      var payments = paymentElement.parent().find('input[type="hidden"]');
      payments.each(function(index) {
        paymentlabels[index] = $(this).attr('name');
        paymentDataSet[index] = $(this).val().split('_')[0];
        paymentColors[index] = $(this).val().split('_')[1];
      });
      var paymentData = {
        labels: paymentlabels,
        datasets: [{
          data: paymentDataSet,
          backgroundColor: paymentColors,
          hoverBackgroundColor: paymentColors
        }]
      };
      var payment = new Chart(paymentElement,{
        type: 'pie',
        data: paymentData
      });
  }
}, false);
</script>
