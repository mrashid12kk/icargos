<?php
   session_start();
   include_once "includes/conn.php";
   $id = $_SESSION['customers'];

   $branch_id = $_SESSION['branch_id'];
   if (!isset($_SESSION['branch_id'])) {
   $branch_id = 1;
   }
   $courier_query=mysqli_query($con,"Select * from users where user_role_id = 4 ");

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
            $statusss = isset($row->status) ? $row->status : 'Pending';
           if($statusss == 'in process' || $statusss == 'accepted') {
               $statusss = 'On the Way';
           }
           ?>
<li class="bdr-btm">
   <div class="open_first_order">
      <a href="#">
      <b>Order# <?php echo $row->track_no; ?></b>
      <b>Pickup Date: <?php echo $row->order_date; ?> <i class="fa fa-angle-down"></i></b>
      <b>Status: <?php echo $statusss; ?></b>
      </a>
   </div>
   <div class="down_box_order">
      <ul>
         <li><i class="fa fa-check"></i> <strong>Tracking No#</strong> <?php echo $row->track_no; ?></li>
         <li><i class="fa fa-check"></i> <strong>Status:</strong> <?php echo $row->status; ?></li>
         <li><i class="fa fa-check"></i> <strong>Order Date</strong> <?php echo $row->order_date; ?></li>
         <li><i class="fa fa-check"></i> <strong>Collection Amount:</strong> <?php echo $row->collection_amount; ?></li>
         <li><i class="fa fa-check"></i> <strong>Price: </strong> <?php echo $row->price; ?></li>
         <li><i class="fa fa-check"></i> <strong>Total: </strong> <?php echo ((int)$row->collection_amount + (int)$row->price); ?></li>
      </ul>
   </div>
</li>
<?php
   }
   }
   exit();
   }
   $order_status = '';
   $active_order_status = '';
   $check_other = '';

   $query1 = mysqli_query($con,"SELECT * FROM orders WHERE (  status = 'New Booked ' || 'Ready for Pickup') AND customer_id =".$id."  order by id desc ");


   if(isset($_SESSION['customers'])){
   include "includes/header.php";


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
   $page_title = 'Generate load sheet';
   $is_profile_page = true;
   $status_q = mysqli_query($con,"SELECT * FROM order_status WHERE 1 ORDER BY sort_num");
   ?>
<section class="bg padding30">
   <div class="container-fluid dashboard">
      <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
         <?php
            include "includes/sidebar.php";
            ?>
      </div>
      <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
         <div class="white">
            <?php
               ?>
            <style type="text/css">
               table th {
               color: #8f8f8f;
               }
               .table-bordered tr td{
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
               @media (max-width: 1250px){
               .container{
               width: 100%;
               }
               .booking_btn{
               margin-left: -6px !important;
               }
               .submit_load{
               margin-top: 20px !important;
               }
               .form-control.from {
               border-right-color: #e7e8ec;
               }
               }
               @media (max-width: 1024px){
               .container{
               width: 100%;
               }
               .submit_load {
               margin-top: 0 !important;
               }
               .padding30 .dashboard {
               margin-top: 20px !important;
               }
               .padding30 .dashboard {
               margin-top: 0 !important;
               padding: 0 12px 30px;
               }
               .white .col-sm-4 {
               width: 50%;
               float: left;
               margin-bottom: 11px;
               padding: 0;
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
               .booked_packge{
               max-width: 100%;
               margin: 24px auto 13px;
               }
               .pacecourier_logo {
               float: left;
               width: 20%;
               margin-right: 22px;
               }
               /*.pacecourier_logo img {
               width: 204px;
               padding-top: 19px;
               }*/
               .booked_packges {
               float: right;
               width: 76%;
               }
               .booked_packges h4 {
               margin: 0;
               font-size: 20px;
               }
               .booked_packges ul{
               padding: 0;
               margin: 9px 0 0;
               }
               .booked_packges ul li {
               list-style: none;
               margin-bottom: 5px;
               display: inline-block;
               width: 44%;
               }
               .booking_sheet, .booking_sheet:hover,.booking_sheet:focus{
               color: #fff !important;
               }
               .buttons-print{
               display: none;
               }
            </style>
            <div id="same_form_layout">
               <h4 class="Order_list" style="color:#000;"><?php echo getLange('gererateloadsheet'); ?>  </h4>
               <form method="POST" action="pickup_status_assign.php" id="bulk_submit">
                  <div class="row">
                     <div class="col-sm-4 left_right_none" >
                        <div class="form-group">
                           <select class="form-control active_courier js-example-basic-single" name="active_courier" required="">
                              <option selected disabled> <?php echo getLange('select').' '.getLange('rider'); ?></option>
                              <?php while($row=mysqli_fetch_array($courier_query)){ ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <input type="hidden" name="order_ids" id="print_data" >
                     <div class="col-sm-1 left_right_none ">
                        <input style="color: #fff !important;" type="submit" name="submit" class="booking_sheet btn btn-info" value="<?php echo getLange('submit'); ?>">
                     </div>
                  </div>
               </form>
               <table class="table table-hover table-bordered dataTable hide-on-tab orders_tbl">
                  <thead>
                     <tr>
                        <th><input type="checkbox" name="" class="main_select"></th>
                        <th><?php echo getLange('date'); ?></th>
                        <th><?php echo getLange('trackingno'); ?> </th>
                        <th><?php echo getLange('pickupinfo'); ?> </th>
                        <th><?php echo getLange('deliveryinfo'); ?> </th>
                        <th><?php echo getLange('qty'); ?></th>
                        <th><?php echo getLange('pickupcity'); ?> </th>
                        <th><?php echo getLange('deliverycity'); ?> </th>
                        <th><?php echo getLange('weight'); ?></th>
                        <th><?php echo getLange('codamount'); ?> </th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        while($fetch1 = mysqli_fetch_array($query1)){
                          $totla_pieces += $fetch1['quantity'];
                          $total_weight += $fetch1['weight'];
                          $total_cod += $fetch1['collection_amount'];
                         ?>
                     <tr>
                        <td><input type="checkbox" name="" class="order_check" data-id="<?php echo $fetch1['id']; ?>"></td>
                        <td><?php echo date(DATE_FORMAT,strtotime($fetch1['order_date'])); ?></td>
                        <td><?php echo $fetch1['track_no']; ?></td>
                        <td>
                           <b><?php echo getLange('name'); ?>:</b><?php echo $fetch1['sname']; ?></br>
                           <b><?php echo getLange('company'); ?>:</b><?php echo $fetch1['sbname']; ?></br>
                           <b><?php echo getLange('phone'); ?>:</b><?php echo $fetch1['sphone']; ?></br>
                           <b><?php echo getLange('orderid'); ?>:</b><?php echo $fetch1['tracking_no']; ?></br>
                        </td>
                        <td>
                           <ul>
                              <li><b><?php echo getLange('name'); ?>:</b> <?php echo $fetch1['rname']; ?></li>
                              <li><b><?php echo getLange('phone'); ?>:</b> <?php echo $fetch1['rphone']; ?></li>
                           </ul>
                        </td>
                        <td><?php echo isset($fetch1['quantity']) ? $fetch1['quantity'] : '0'; ?> </td>
                        <td><?php echo isset($fetch1['origin']) ? $fetch1['origin'] : ''; ?></td>
                        <td><?php echo isset($fetch1['destination']) ? $fetch1['destination'] : ''; ?></td>
                        <td><?php echo isset($fetch1['weight']) ? $fetch1['weight'] : ''; ?> Kg</td>
                        <td>
                           Rs <?php echo number_format((float)$fetch1['collection_amount'],2); ?></li>
                           </ul>
                        </td>
                     </tr>
                     <?php  } ?>
                  </tbody>
               </table>
            </div>
            <!-- <form method="POST" id="bulk_submit" action="" target="_blank">
               <input type="hidden" name="print_data" id="print_data" >
               <input type="hidden" name="save_print">
               </form> -->
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
   $('.datepicker').datepicker({
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
   $('body').on('click','.booking_sheet',function(e){
      // e.preventDefault();
      var body = $('body');
      body.find('.orders_tbl > tbody  > tr').each(function() {
            var checkbox = $(this).find('td:first-child .order_check');
            if(checkbox.prop("checked") ==true){
                var order_id = $(checkbox).data('id');
                mydata.push(order_id);
            }
        });

      if (mydata.length === 0) {
         e.preventDefault();
          alert('Please select orders first')
      }
      else
      {
          var active_courier = body.find('.active_courier').val();
          if (active_courier===null)
          {
              e.preventDefault();
              alert('Please select rider first');
          }
          else
          {
              var order_data = JSON.stringify(mydata);
              body.find('#print_data').val(order_data);
              body.find('#bulk_submit').submit();
              // $("#bulk_submit").attr('target', '_blank');
          }
      }




   })
</script>
<?php include 'includes/footer.php'; ?>
<script>
   document.addEventListener('DOMContentLoaded', function(){
       $('title').text($('title').text()+' Booking Sheet')
   }, false);
</script>
<?php
   if(isset($_SESSION['print_url']) && !empty($_SESSION['print_url']))
     {
       echo "<script>window.open('".$_SESSION['print_url']."', '_blank')</script>";

       unset($_SESSION['print_url']);
     }
   ?>
