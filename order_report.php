<?php
session_start();
include_once "includes/conn.php";
$id = $_SESSION['customers'];
if(isset($_SESSION['customers'])){
 include "includes/header.php";
 $cities1 = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
 $cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
 $drivers = mysqli_query($con,"SELECT * FROM users WHERE type='driver' order by id desc ");
 $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");

 ?>
 <section class="bg padding30">
   <div class="container-fluid dashboard">
      <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
         <?php
         include "includes/sidebar.php";
         ?>
      </div>
      <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
         <div class="white" style="    margin-bottom: 25px;">
            <?php

            $active_origin = '';
            $active_destination = '';
            $paymentstatus = '';
            $active_courier = '';
            if(isset($_POST['submit']))
            {
              $active_status = '';
              $from = date('Y-m-d',strtotime($_POST['from']));
              $to = date('Y-m-d',strtotime($_POST['to']));
              $origin = $_POST['origin'];
              $destination = $_POST['destination'];
              $courier = $_POST['courier'];
              $payment = $_POST['payment'];
              $origin_check = '';
              $destination_check = '';
              $order_status = '';
              $active_order_status = '';
              if (isset($_POST['order_status'])) {
                $order_status = $_POST['order_status'];
                $active_order_status  = $order_status;
                $order_status = " AND status= '" . $order_status . "' ";
             }
             if(!empty($_POST['origin'])){
               $origin_check = " AND origin = '{$origin}' ";
               $active_origin = $origin;
            }
            if(!empty($_POST['payment'])){
               $paymentstatus = " AND payment_status = '{$payment}' ";
               $payment_status = $paymentstatus;
            }
            if(!empty($_POST['destination'])){
               $destination_check = " AND destination = '{$destination}' ";
               $active_destination = $destination;
            }
            if(!empty($_POST['courier'])){
               $destination_check = " AND assign_driver = '{$courier}' ";
               $active_courier = $courier;
            }

            if($active_status == '')
            {
               $stat_status = 'All';
            }else{
               $stat_status = $active_status;
            }
            if(!empty($_POST['status']))
            {
               $status = $_POST['status'];
               $status_check = " AND status = '".$status."' ";
               $active_status = $status;
            }
            $query1 = mysqli_query($con,"SELECT orders.*, services.service_type as order_type_name FROM orders  LEFT JOIN  services ON orders.order_type=services.id  WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND  status != 'cancelled' $origin_check $destination_check $status_check $order_status $active_customer_query $paymentstatus AND customer_id='".$_SESSION['customers']."' order by orders.id desc ");

         }else{
           $from = date('Y-m-d', strtotime('today - 30 days'));
           $to = date('Y-m-d');
           $query1 = mysqli_query($con,"SELECT orders.*, services.service_type as order_type_name FROM orders    LEFT JOIN  services ON orders.order_type=services.id   WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" .$to. "'  AND  status != 'cancelled'  $active_customer_query  AND customer_id='".$_SESSION['customers']."' order by orders.id desc ");



        }
        ?>
        <style type="text/css">
         table th {
            color: #8f8f8f;
         }
         .table-bordered tr td{
            color: #000;
         }
         table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before {
          left: 21px !important;
          top: 12px;
          height: 12px;
          width: 12px;
          text-indent: 2px;
          line-height: 14px;
       }
       section .dashboard .white {
         background: #fff;
         padding: 20px;
         width: 99%;
         display: inline-block;
      }
      .btn-default {
         min-width: 60px;
      }
      @media (max-width: 1250px){
         .container{
            width: 100%;
         }
         .submit_load {
            margin-top: 20px !important;
         }
      }
      @media (max-width: 1024px){
         .container{
            width: 100%;
         }
         .padding30 .dashboard {
            margin-top: 0 !important;
            padding: 0 12px 30px;
         }
         .dashboard .white{
            padding: 0 !important;
         }
         .white .col-sm-4 {
            width: 50%;
            float: left;
            margin-bottom: 11px;
            padding: 0;
         }
         section .dashboard .white{
            box-shadow:none !important;
         }
      }
      @media (max-width: 767px){
         .container{
            width: auto;
         }
         .white .col-sm-4 {
            width: 100%;
            float: none;
            margin-bottom: 11px;
            padding: 0;
         }
         section .dashboard .dashboard {
            padding: 3px 0 0;
         }
      }
      .print_invoice{
         color: #fff;
      }
      .print_invoice:hover,.print_invoice:focus{
         color: #fff !important;
      }
      .ready_for_pickup{
         color: #fff;
      }
      .ready_for_pickup:hover,.ready_for_pickup:focus{
         color: #fff !important;
      }
      .btn-danger,.btn-danger:hover{
         color: #fff !important;
      }
      .view_invoice{
         background-color: #4cade0 !important;
         border: none !important;
      }
      .btn-sm{
         padding: 0px 6px !important;
         font-size: 12px !important;
         line-height: 1.5 !important;
         border-radius: 3px !important;
         margin: 2px 0;
      }
      .buttons-print{
         display: none;
      }
      table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before{
       left: 30px;
    }


    @media(min-width: 1600px){
       .container{
         width: 1400px;
      }
      #checkboxes {
        margin: 5px 0 0 0px;
     }
  }
</style>
<?php
if(isset($_GET['message']) && !empty($_GET['message'])){
  echo $_GET['message'];
}
?>
<h4 class="Order_list" style="color:#000;"><?php echo getLange('orderreport'); ?></h4>
<?php
if(isset($_SESSION['fail_update']) && !empty($_SESSION['fail_update'])){
 $msg = $_SESSION['fail_update'];
 echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> '.$msg.'</div>';
 unset($_SESSION['fail_update']);
}
if(isset($_SESSION['up_message']) && !empty($_SESSION['up_message'])){
 $msg = $_SESSION['up_message'];
 echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successful!</strong> '.$msg.'</div>';
 unset($_SESSION['up_message']);
}
?>
<form method="POST" action="">
 <div class="row">
  <div class="col-sm-1 left_right_none upate_Btn" >
     <a href="#" style="    padding: 5px 1px !important;display: block;margin-top: 23px;" class="btn btn-info print_invoice btn-sm" ><?php echo getLange('printinvoice'); ?> </a>
     <a href="#" style="    padding: 5px 1px !important;display: block;margin-top: 23px;" class="btn btn-info print_small_invoice btn-sm" ><?php echo getLange('labelprint'); ?> </a>
  </div>
  <div class="col-sm-2 left_right_none">
    <div class="form-group">
      <label><?php echo getLange('status'); ?></label>
      <select class="form-control js-example-basic-single" name="order_status">
        <option selected disabled><?php echo getLange('select') . ' ' . getLange('status'); ?> </option>
        <?php
        $status_q = mysqli_query($con, "SELECT * FROM order_status WHERE 1 ORDER BY sort_num");
        while ($row = mysqli_fetch_array($status_q)) { ?>
          <option value="<?php echo $row['status']; ?>" <?php if ($active_order_status == $row['status']) {echo "selected";} ?>><?php echo $row['status']; ?></option>
       <?php } ?>
    </select>
 </div>
</div>
<div class="col-sm-2 left_right_none">
  <div class="form-group">
    <label><?php echo getLange('selectpickupcity'); ?>  </label>
    <select class="form-control origin js-example-basic-single" name="origin">
     <option value="" <?php if($active_origin == ''){ echo "selected"; } ?> >All</option>

     <?php while($row = mysqli_fetch_array($cities1)){ ?>
        <option  <?php if($active_origin == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
     <?php } // if($row['city_name'] == 'Karachi'){ echo "selected"; }  ?>
  </select>
</div>
</div>
<div class="col-sm-2 left_right_none">
  <div class="form-group">

    <label><?php echo getLange('selectdeliverycity'); ?>  </label>
    <select class="form-control destination js-example-basic-single" name="destination">
     <option value="" <?php if($active_destination == ''){ echo "selected"; } ?>>All</option>
     <?php while($row = mysqli_fetch_array($cities2)){ ?>
        <option <?php if($active_destination == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
     <?php } ?>
  </select>
</div>
</div>
<div class="col-sm-2 left_right_none">
  <div class="form-group">
   <label><?php echo getLange('paymentstatus'); ?>  </label>
   <select class="form-control destination js-example-basic-single" name="payment">
    <option value="">All</option>
    <option value="Pending" <?php if(isset($payment) && $payment=='Pending'){ echo 'Selected';} ?>>Pending</option>
    <option value="Paid" <?php if(isset($payment) && $payment=='Paid'){ echo 'Selected';} ?>>Paid</option>
 </select>
</div>
</div>


<div class="col-sm-1 left_right_none">
  <div class="form-group">
   <label><?php echo getLange('from'); ?></label>
   <input type="text" value="<?php echo $from; ?>" class="form-control  datetimepicker4" name="from">
</div>
</div>
<div class="col-sm-1 left_right_none">
  <div class="form-group">
   <label><?php echo getLange('to'); ?></label>
   <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
</div>
</div>
<div class="col-sm-1 sidegapp-submit left_right_none">
  <input style="color: #fff !important;margin: 21px 0 0px;" type="submit"  name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>">
</div>
</div>
</form>
<table class="table table-hover table-bordered dataTable hide-on-tab orders_tbl" id="checkbox-items">
   <thead>
      <tr role="row">
         <th ><input type="checkbox" name="" class="main_select" ></th>
         <th><?php echo getLange('trackingno'); ?> </th>
         <th><?php echo getLange('servicetype'); ?>  </th>
         <th><?php echo getLange('status'); ?></th>
         <th><?php echo getLange('orderdate'); ?> </th>
         <th><?php echo getLange('pickupname'); ?> </th>
         <th><?php echo getLange('pickupcompany'); ?> </th>
         <th><?php echo getLange('pickupphone'); ?> </th>
         <th><?php echo getLange('pickupaddress'); ?> </th>
         <th><?php echo getLange('deliveryname'); ?> </th>
         <th><?php echo getLange('deliveryphone'); ?> </th>
         <th><?php echo getLange('deliveryaddress'); ?> </th>
         <th><?php echo getLange('orderid'); ?> </th>
         <th><?php echo getLange('orderstatus'); ?> </th>
         <th><?php echo getLange('pickupcity'); ?> </th>
         <th><?php echo getLange('deliverycity'); ?> </th>
         <th><?php echo getLange('parcelweight'); ?></th>
         <th ><?php echo getLange('salestax'); ?> </th>
         <th><?php echo getLange('deliveryfee'); ?> </th>
         <th><?php echo getLange('invoiceno'); ?> </th>
         <th><?php echo getLange('codamount'); ?> </th>
         <th><?php echo getLange('action'); ?></th>
      </tr>
   </thead>
   <tbody>
      <?php
      $totalweight=0;
      $totalcod=0;
      $totaltax=0;
      $totaldelivery=0;

      while($fetch1=mysqli_fetch_array($query1)){


       if (empty($fetch1['sbname']))
       {

        $company_name = mysqli_fetch_array(mysqli_query($con,"SELECT bname FROM customers WHERE id='".$fetch1['customer_id']."'  "));
        if (isset($company_name['bname']))
        {

         $fetch1['sbname'] = $company_name['bname'];
      }
   }


   $totalweight+=$fetch1['weight'];
   $totalcod+=$fetch1['collection_amount'];
   $totaltax+=$fetch1['pft_amount'];
   $totaldelivery+=$fetch1['price'];

   ?>
   <tr class="gradeA odd" role="row">

      <td style="width: 62px;"><input type="checkbox" name="" class="order_check" data-id="<?php echo $fetch1['id']; ?>" id="checkboxes">  <input type="hidden" name="" value="<?php echo $fetch1['id']; ?>"></td>
      <td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
      <td class="sorting_1"><?php echo $fetch1['order_type_name']; ?></td>
      <td class="sorting_1" style="text-transform: capitalize;"><?php echo getKeyWordCustomer($id,$fetch1['status']); ?></td>
      <td class="">
         <?php echo date('Y-m-d',strtotime($fetch1['order_date'])); ?>
      </td>
      <td class="">
         <?php echo $fetch1['sname']; ?>
      </td>
      <td class="">
         <?php echo $fetch1['sbname']; ?>
      </td>
      <td class="">
         <?php echo $fetch1['sphone']; ?>
      </td>
      <td class="">
            <?php 
        if(isset($fetch1['Pick_location']) && !empty($fetch1['Pick_location'])){
          echo $fetch1['Pick_location'];
         }else{
         echo $fetch1['sender_address'];
         } ?>
      </td>
      <td class="">
         <?php echo $fetch1['rname']; ?>
      </td>
      <td class="">
         <?php echo $fetch1['rphone']; ?>
      </td>
      <td class="">
         <?php echo $fetch1['receiver_address']; ?>
      </td>
   </td>
   <td class="">
      <?php echo $fetch1['product_id']; ?>
   </td>

   <td>
      <?php if ($fetch1['status'] == 'Delivered' or $fetch1['status'] == 'Returned to Shipper'): ?>
         <?php echo 'Closed'; ?>
         <?php else: ?>
            <?php echo 'Opened'; ?>
         <?php endif; ?>
      </td>
      <td>
         <?php echo $fetch1['origin']; ?>
      </td>
      <td>
         <?php echo $fetch1['destination']; ?>
      </td>
      <td>
         <?php echo $fetch1['weight']; ?>
      </td>
      <td>
         <?php echo number_format((float)$fetch1['pft_amount'],2); ?>
      </td>
      <td>
         <?php echo number_format((float)$fetch1['price'],2); ?>
      </td>
       <td>
         <?php echo $fetch1['payment_ledger_id']; ?>
         
      </td>
      <td>
         <?php echo number_format((float)$fetch1['collection_amount'],2); ?>
      </td>
      <td class=" action_btns" >
        <!--  <a href="order.php?id=<?php echo $fetch1['id']; ?>"> <i class="fa fa-eye" style="font-size: 14px;"></i></a> -->
        <a target="_blank" href="<?php echo BASE_URL ?>track-details.php?track_code=<?php echo $fetch1['track_no'] ?>"  > <i style="color: #000000;font-size: 15px;" class="fa fa-truck"></i></a>
        <?php 
        echo isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "<a target='_blank' href='invoicehtml_new.php?order_id=".$fetch1['id']." '> <i class='fa fa-print' style='font-size: 14px;'></i></a>" : "<a target='_blank' href='invoicehtml.php?order_id=".$fetch1['id']." '> <i class='fa fa-print' style='font-size: 14px;'></i></a>";
        echo isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "<a target='_blank' href='airway_bill.php?order_id=".$fetch1['id']." '> <i class='fa fa-plane' style='font-size: 14px;'></i></a>" : "";
        ?>
     </td>
  </tr>
  <?php
}
?>
</tbody>
<tfoot>
 <td></td>
 <td colspan="15">Total</td>
 <td><?php echo number_format((float)$totalweight,2); ?>Kg</td>
 <td><?php echo number_format((float)$totaltax,2); ?></td>
 <td><?php echo number_format((float)$totaldelivery,2); ?></td>
 <td><?php echo number_format((float)$totalcod,2); ?></td>
 <td></td>
</tfoot>
</table>
           <!--  <div class="order_report_cod">
              <label>Total Weight</label>
             <input type="text" name="" disabled value="<?php //echo number_format((float)$totalweight,2); ?>Kg">
              <label>Total COD</label>
             <input type="text" name="" disabled value="<?php //echo number_format((float)$totalcod,2); ?>">
          </div> -->
          <form method="GET" id="bulk_submit" action="<?php echo getConfig('print_template'); ?>" target="_blank">
            <input type="hidden" name="order_id" id="print_data" >
            <input type="hidden" name="save_print">
         </form>
         <form method="GET" id="small_bulk_submit" action="small_bulk_invoice.php" target="_blank">
            <input type="hidden" name="print_data" id="small_print_data" >
            <input type="hidden" name="save_print">
         </form>
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
   $('.datetimepicker4').datepicker({
    format: 'yyyy/mm/dd',
 });
   (function($){
      $("body").on('click', ".open_first_order a", function(){
       $(this).closest('li').find('.down_box_order').slideToggle();
    });

      if($('#results').length > 0) {
       $("#results").loaddata({
          data_url: 'orders.php',
          end_record_text: ''
       });
    }
 })(jQuery);
 $('body').on('click','.main_select',function(e){
   var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');
   if($('.main_select').prop("checked") == true){
    $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',true);
 }else{
    $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',false);
 }

 $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
})
 var mydata = [];
 $('body').on('click','.print_invoice',function(e){
   e.preventDefault();
   $('.orders_tbl > tbody  > tr').each(function() {
    var checkbox = $(this).find('td:first-child .order_check');
    console.log(checkbox);
    if(checkbox.prop("checked") ==true){
     var order_id = $(checkbox).data('id');
     mydata.push(order_id);
  }
});
   var order_data = mydata.join(',');

   $('#print_data').val(order_data);
   $('#bulk_submit').submit();
   location.reload();
});

 $('body').on('click','.print_small_invoice',function(e){
   e.preventDefault();
   $('.orders_tbl > tbody  > tr').each(function() {
    var checkbox = $(this).find('td:first-child .order_check');
    console.log(checkbox);
    if(checkbox.prop("checked") ==true){
     var order_id = $(checkbox).data('id');
     mydata.push(order_id);
  }
});
   var order_data = mydata.join(',');

   $('#small_print_data').val(order_data);
   $('#small_bulk_submit').submit();
   
   location.reload();
});




 $('body').on('click','.ready_for_pickup',function(e){

   e.preventDefault();
   $('.orders_tbl > tbody  > tr').each(function() {
    var checkbox = $(this).find('td:first-child .order_check');
    if(checkbox.prop("checked") ==true){
     var order_id = $(checkbox).data('id');
     mydata.push(order_id);
  }
});
   var order_data = mydata.join(',');

   $.ajax({
    url:"edit_ready_for_pickup.php",
    type: "post",
    dataType: 'json',
    data: {order_ids: order_data},
    success:function(result)
    {
     location.reload();
  }
});

});


</script>
<?php include 'includes/footer.php'; ?>
<script>
   document.addEventListener('DOMContentLoaded', function(){
    $('title').text($('title').text()+' Orders')
 }, false);
</script>
