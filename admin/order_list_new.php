<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
    include "includes/header.php";
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
        <div class="warper container-fluid padd_none">
             <div class="myTextMessage"></div>
             <div class="filter_box_view">
                                 <form method="POST" action="">
                                    <div class="row" >
                                        <div class="col-sm-2 left_right_none">
                                            <div class="form-group">
                                                <label><?php echo getLange('trackingno'); ?> </label>
                                                <input type="text" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no">
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none">
                                            <div class="form-group">
                                                <label><?php echo getLange('pickupname'); ?> </label>
                                                <input type="text" value="<?php echo $active_customer_name; ?>" class="form-control " name="customer_name">
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none">
                                            <div class="form-group">
                                                <label><?php echo getLange('pickupphone'); ?></label>
                                                <input type="text" value="<?php echo $active_customer_phone; ?>" class="form-control " name="customer_phone">
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none">
                                            <div class="form-group">
                                                <label><?php echo getLange('pickupphone'); ?> </label>
                                                <input type="text" value="<?php echo $active_customer_email; ?>" class="form-control " name="customer_email">
                                            </div>
                                        </div>
                                        <div class="col-sm-1 left_right_none">
                                            <div class="form-group">
                                                <label><?php echo getLange('from'); ?></label>
                                                <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
                                            </div>
                                        </div>
                                        <div class="col-sm-1 left_right_none">
                                            <div class="form-group">
                                                <label><?php echo getLange('to'); ?></label>
                                                <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none" >
                                            <div class="form-group">
                                                <label><?php echo getLange('customer'); ?></label>
                                                <select class="form-control active_customer_detail js-example-basic-single" name="active_customer">
                                                    <option selected value=""><?php echo getLange('all').' '.getLange('customer'); ?></option>
                                                    <?php foreach($customers as $customer){ ?>
                                                    <option  <?php if($customer['id'] == $active_customer_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none" >
                                            <div class="form-group">
                                                <label><?php echo getLange('pickuprider'); ?> </label>
                                                <select class="form-control courier_list js-example-basic-single" name="pickup_rider">
                                                    <option selected value=""><?php echo getLange('select').' '.getLange('rider'); ?></option>
                                                    <?php while($row=mysqli_fetch_array($courier_query)){ ?>
                                                    <option <?php if($row['id'] == $pickup_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none" >
                                            <div class="form-group">
                                                <label><?php echo getLange('deliveryrider'); ?> </label>
                                                <select class="form-control courier_list js-example-basic-single" name="delivery_rider">
                                                    <option selected value=""><?php echo getLange('select').' '.getLange('rider'); ?></option>
                                                    <?php while($row=mysqli_fetch_array($delivery_courier_query)){ ?>
                                                    <option <?php if($row['id'] == $delivery_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none" >
                                            <div class="form-group">
                                                <label><?php echo getLange('orderstatus'); ?> </label>
                                                <select class="form-control courier_list js-example-basic-single" name="order_status">
                                                    <option selected value=""><?php echo getLange('select').' '.getLange('status'); ?></option>
                                                    <?php while($row=mysqli_fetch_array($status_query)){ ?>
                                                    <option <?php //if($row['status'] == $active_order_status ){ echo "selected"; } ?> value="<?php //echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none" >
                                            <div class="form-group">
                                                <label><?php echo getLange('origin'); ?></label>
                                                <select class="form-control courier_list js-example-basic-single" name="origin_city">
                                                    <option value="" selected><?php echo getLange('all').' '.getLange('city'); ?></option>
                                                    <?php while($row=mysqli_fetch_array($city_query)){ ?>
                                                    <option  <?php if($row['city_name'] == $active_origin_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 left_right_none" >
                                            <div class="form-group">
                                                <label><?php echo getLange('destination'); ?></label>
                                                <select class="form-control courier_list js-example-basic-single" name="order_city">
                                                    <option value="" selected><?php echo getLange('all').' '.getLange('city'); ?></option>
                                                    <?php while($row=mysqli_fetch_array($city_querys)){ ?>
                                                    <option  <?php if($row['city_name'] == $active_order_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 sidegapp-submit " style="margin: 0;">
                                            <input type="submit"  name="submit" class="btn btn-success search_filter" value="<?php echo getLange('search'); ?>">
                                        </div>
                                    </div>
                                    
                                </form>
                             </div>
             <div class="orders_btns">
                 <ul>
                     <li>
                         <div class="orders_items active_orders">
                             <b>Open Orders</b>
                             <p>Done / Total <span>25,000</span></p>
                         </div>
                     </li>
                     <li class="delivered_status_active">
                         <div class="orders_items">
                             <b>Delivered</b>
                             <p>Total <span>50,898</span></p>
                         </div>
                     </li>
                     <li>
                         <div class="orders_items">
                             <b>Returned</b>
                             <p>Done / Total <span>1200</span></p>
                         </div>
                     </li>
                 </ul>
             </div>
                <!-- <div class="page-header"><h1>Order List </h1></div> -->
                <div class="manifest_box_">
                    <div class="row">
                        <div class="col-sm-9 order_listing_views">
                            <table class="order_list_view   dataTable_with_sorting no-footer" id="basic-datatable" >
                            <!-- <table class="" > -->
                                <thead style="display: none">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </thead>
                                      <tbody>
                                        <?php while($fetch1=mysqli_fetch_array($query1)){
                                        $iddd=encrypt($fetch1['id']."-usUSMAN767###"); 
                                          $customer_id = isset($fetch1['customer_id']) ? $fetch1['customer_id']:'';
                                        $customerData = getCustomer($customer_id);
                                        ?>
                                          <tr>

                                            <td class="all_brands">
                                            <div class="brand_logo">
                                                <img src="<?php echo BASE_URL ?><?php echo $customerData['image']; ?>">
                                            </div>
                                        </td>
                                        <td class="brand_info">
                                            <div class="notes_details">
                                                <h3><?php echo $customerData['bname']; ?></h3>
                                                <span><?php echo $fetch1['sname']; ?></span>
                                                <h5><?php echo $fetch1['sphone']; ?></h5>
                                                <h6>View More Details <i class="fa fa-angle-down"></i></h6>
                                            </div>
                                        </td>
                                        <td class="date_bx">
                                            <div class="listing_boxes">
                                                <ul>
                                                    <li>
                                                        <h5><?php echo $fetch1['track_no']; ?></h5>
                                                        <h4><?php echo date(DATE_FORMAT,strtotime($fetch1['order_date'])); ?><?php echo date('h:i A',strtotime($fetch1['order_time'])); ?></h4>
                                                        <b><i class="fa fa-lightbulb-o"></i> COD - <i class="fa fa-balance-scale"></i> <?php echo $fetch1['weight']; ?> Kg</b>
                                                        <span><i class="fa fa-credit-card"></i> <?php echo number_format((float)$fetch1['price'],2); ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="cod_box">
                                            <div class="cod_imgbox">
                                                <svg  viewBox="0 0 24 24"><path d="M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z" fill="#626262"/></svg>
                                            </div>
                                        </td>
                                        <td class="client_info">
                                            <div class="listing_boxes">
                                                <ul>
                                                    <li>
                                                        <h5><?php echo $fetch1['rname']; ?></h5>
                                                        <h4><?php echo $fetch1['rphone']; ?></h4>
                                                        <h6><?php echo $fetch1['receiver_address']; ?></h6>
                                                        <b>COD Amount: <?php echo number_format((float)$fetch1['collection_amount'],2); ?></b>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="from_to">
                                            <div class="divider_location">
                                                <b class="from_info"><?php echo $fetch1['origin']; ?></b>
                                                <b class="to_info"><?php echo $fetch1['destination']; ?></b>
                                                <span class="middle_area"></span>
                                            </div>
                                        </td>
                                        <td class="percel_box">
                                            <div class="checkin_box">
                                               <button class="view_detail" data-id="<?php echo $fetch1['id']; ?>">View Details</button>
                                                <button  class="live_tracking" data-track="<?php echo $fetch1['track_no']; ?>">Live Tracking</button>
                                                <ul>
                                                    <!-- <li class="close_card"><i class="fa fa-money"></i> To be paid</li> -->
                                                    <li><i class="fa fa-check" ></i> <?php echo $fetch1['status']; ?></li>
                                                </ul>
                                            </div>
                                        </td>
                                         <td>
                                            <?php if(isset($fetch1['payment_status']) && $fetch1['payment_status']=='Paid') {?>
                                            <div class="price-box">
                                          <div class="ribbon">
                                             <span>Paid</span>
                                          </div>  
                                        </div>
                                        <?php } ?></td>
                                        
                                      </tr>
                                <?php } ?>
                                      </tbody>
                                    </table>
                            </div>

                            <div class="col-sm-3 order_info_box hidden" id="view_box_detail">
                                        <div class="fix_wrapper_h" id="fix_wrapper_h">
                                        </div>
                            </div>
                       
                        </div>

                </div>
            </div>
        <!-- Warper Ends Here (working area) -->
      <?php include "includes/footer.php";
    }else{
        header("location:index.php");
    }
    ?>
<script type="text/javascript">
$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});
 // $(document).on("click", "#rider_credit_btn", function () {
 //    });
     $(".close_btn,.overlay_popup_fixed").click(function(){
        $(".overlay_popup_fixed,.overly_popup").fadeOut();
    });
 $(document).on("change", ".this_status", function () {
  var length = $('body').find('.response_table_body').find('tr').length;
    if (length < 1) {
      alert('Please select track no first')
    }else{
      var a = $(this).val();
       if(a=== 'Picked up' )
       {
          $(".active_courier_div").removeClass("display_none");
       }
       if (a=== 'Delivered') {
        var mydata = [];
         $('.response_table_body  > tr').each(function() {
            // var checkbox = $(this).find('td:first-child .order_check');
             var order_id = $(this).find('.all_cn_no').val();
             mydata.push(order_id);
         });
          $.ajax({
              url: 'bulk_status_update_ajax.php',
              type: 'POST',
              data:{delivered_status:'Delivered',some_cn_no:mydata},
              cache:false,
              success: function (response) {
                 $("#rider_balance_report").html(response);
                 $(".overlay_popup_fixed,.overly_popup").fadeIn();
              }
          });
       }
    }
    });
 function totalPieces()
    {
        let totalpcs = 0;
        let nextTotal = $('body').find('.response_table_body').find('tr').find('td').eq(6).html();
        $('body').find('.hidden_qunatity_value').each(function(index,value)
        {
            totalpcs +=parseFloat($(this).val());
        });
        $('body').find('.total_pieces').val(totalpcs);
    }
     function totalWeight()
    {
        let totalweight = 0;
        let nextTotal = $('body').find('.response_table_body').find('tr').find('td').eq(6).html();
        $('body').find('.hidden_weight').each(function(index,value)
        {
            totalweight +=parseFloat($(this).val());
        });
        $('body').find('.total_weight').val(totalweight);
    }
    $(document).on('click','.delete_row',function(){
        var data_wt=$(this).attr('data-wt');
        var data_qt=$(this).attr('data-qt');
        $(this).closest ('tr').remove ();
        var total_wt=$('.total_weight').val();
        var total_pc=$('.total_pieces').val();
        total_pcs=total_pc-data_qt;
        total_wts=total_wt-data_wt;
        $('.total_weight').val("");
        $('.total_weight').val(total_wts);
        $('.total_pieces').val("");
        $('.total_pieces').val(total_pcs);
    })
    $(document).on("click", ".mode_type_name", function () {
     var a = $('.mode_type_name:checked').val();
     $.ajax({
            url: 'bulk_status_update_ajax.php',
            type: 'POST',
            data:{mode_id:a},
            cache:false,
            success: function (response) {
               $(".transport_company").html(response);
            }
        });
    });
    $(document).on("click", "#weight_bulk_update", function () {
     var value= $('#weight_bulk_update_val').val();
     var mydata = [];
     $('.response_table_body  > tr').each(function() {
        // var checkbox = $(this).find('td:first-child .order_check');
         var order_id = $(this).find('.all_cn_no').val();
         mydata.push(order_id);
     });
     $.ajax({
            url: 'bulk_status_update_ajax.php',
            type: 'POST',
            data:{bulk_edit:value,bulk_value:mydata},
            cache:false,
            success: function (response) {
               $(".single_weight").html(response);
               Swal.fire({
                 position: 'bottom-end',
                 icon: 'success',
                 title: 'Updated Successfully...',
                 showConfirmButton: false,
                 timer: 2500
               })
            }
        });
    });
    function appendNextRow()
    {
        var length = $('body').find('.response_table_body').find('tr').length;
        var a = $('body').find('.enter_cn').val();
        var tbody = $('body').find('.response_table_body').find('tr');
            var existing_array=[];
            tbody.each(function(index){
                existing_array.push($(this).find('.all_cn_no').val());
            });
            var flag =false;
            tbody.each(function(index){
              if($.inArray(a, existing_array) !== -1)
              {
                flag = true;
                return false;
              }
              else
              {
                existing_array.push($(this).find('.all_cn_no').val());
              }
            });
          if(flag)
          {
            flag = false;
            alert('Track Number already exists.');
            return false;
          }
        $.ajax({
            url: 'bulk_status_update_ajax.php',
            type: 'POST',
            data:{enter_cn:a,length:length},
            success: function (response) {
                if (response == '') {
                    alert('No record found');
                }else{
                    $('body').find(".response_table_body").append(response);
                    $('body').find('.enter_cn').val('');
                    $('body').find('.enter_cn').focus();
                    totalPieces();
                    totalWeight();
                }
             }
        });
    }
    $('body').on('keydown','.enter_cn_no',function(event){
        if(event.keyCode == 13)
        {
            appendNextRow();
           event.preventDefault();
        }
    });
    $(document).on("click", ".append_cn_nos", function (event) {
        event.preventDefault();
        appendNextRow();
    });
    $(document).ready(function () {
     $.ajax({
            url: 'bulk_status_update_ajax.php',
            type: 'POST',
            data:{receiver_person:1},
            cache:false,
            success: function (response) {
               $(".receiver_person").html(response);
            }
        });
    });
    $(document).on("change", ".destination", function () {
     var a = $(this).val();
     $.ajax({
            url: 'bulk_status_update_ajax.php',
            type: 'POST',
            data:{city_value:a},
            cache:false,
            success: function (response) {
               $(".area").html(response);
            }
        });
    });
    function filterData(value)
    {
        var allowed_statuses = $('.allowed_statuses').val();
         var origin = $('.origin').val();
         var destination = $('.destination').val();
         var allowed_statuses = $('.allowed_statuses').val();
         var receivingbranch = $('.receiving_branch').val();
         var custom_field = $(value).parent().parent().find('.custom_field').val();
         var field_name = $(value).attr("data-name");
         $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data:{custom_field:custom_field, field_name:field_name, origin:origin, destination:destination,pick_update_cn:1,allowed_statuses:allowed_statuses,receivingbranch:receivingbranch},
                cache:false,
                success: function (response) {
                    $(".inner_contents").html(response);
                    var totalpcs = $('body').find(".pieces").val();
                    var weight = $('body').find(".new_weight").val();
                    $(".total_pieces").val(totalpcs);
                    $(".total_weight").val(weight);
                }
            });
    }
    $(document).on("click", ".filter_right", function () {
        filterData(this);
    });
    $(document).on("click", ".pick_cn_number", function () {
        var custom_field = $(this).parent().parent().find('.custom_field').val();
        if (custom_field ==='') {
            Swal.fire({
                 position: 'bottom-end',
                 icon: 'warning',
                 title: 'Please enter a value to continue.',
                 showConfirmButton: false,
                 timer: 2500
               })
        }else{
            filterData(this);
        }
    });
    $(document).on("click", "#update_statuses", function (e) {
        var length = $('body').find('.response_table_body').find('tr').length;
        if (length < 1) {
            alert('Please select track no to change status');
        }else{
         var status = $(".status").val();
         if (status === '') {
            alert('Please select a status first.');
            // Swal.fire({
            //      position: 'bottom-end',
            //      icon: 'warning',
            //      title: 'Please select a status first.',
            //      showConfirmButton: false,
            //      timer: 2500
            //    })
         }else{
            $('form').submit();
         }
        }
    });
    $(document).on("click", ".edit_row", function (e) {
        var single_weight = $(this).parent().parent().find('.single_weight').text();
        $(this).parent().parent().find(".single_weight").html("<input type='text' class='single_weight ' style='width:45px' value="+single_weight+" /><input type='hidden' style='width:45px' class='hidden_weight' value="+single_weight+" /><i class='fa fa-check'></i>");
    });
    $(document).on("click", ".fa-check", function (e) {
        var thisone = $(this).parent().find('.single_weight');
        var thatone = $(this).parent().parent().find('.single_weight');
        var single_weight = thisone.val();
        var single_weighthtml = $(this).parent().find('.single_weight');
        var track_no = $(this).parent().parent().find('.all_cn_no').val();
        if (single_weight ==='' || single_weight < 0.1) {
            Swal.fire({
                 position: 'bottom-end',
                 icon: 'warning',
                 title: 'Please enter a valid number.',
                 showConfirmButton: false,
                 timer: 2500
               })
        }else{
            $.ajax({
                type: "POST",
                url: 'bulk_status_update_ajax.php',
                dataType: "json",
                data: {single_weight:single_weight,track_no:track_no},
                success: function(msg){
                    thatone.html(msg+"<input type='hidden' class='hidden_weight' value="+msg+" />");
                    $(this).addClass('display_none');
                }
            });
        }
    });
function updatecredit()
 {
    var riders_names = [];
     var riders_ids = [];
     var riders_collections = [];
     var all_assignment_nos = [];
     var mydata = [];
     $('.response_table_body  > tr').each(function() {
         var order_id = $(this).find('.all_cn_no').val();
         mydata.push(order_id);
     });
         $('#collection_table  > tr').each(function() {
             var rider_n = $(this).find('.riders_names').val();
             riders_names.push(rider_n);
         });
         $('#collection_table  > tr').each(function() {
             var rider_id = $(this).find('.riders_ids').val();
             riders_ids.push(rider_id);
         });
         $('#collection_table  > tr').each(function() {
             var rider_collectioin = $(this).find('.riders_collections').val();
             riders_collections.push(rider_collectioin);
         });
         $('#collection_table  > tr').each(function() {
             var assignment_no = $(this).find('.assignment_no').val();
             all_assignment_nos.push(assignment_no);
         });
          $.ajax({
              url: 'bulk_status_update_ajax.php',
              type: 'POST',
              data:{riders_ids:riders_ids,all_assignment_nos:all_assignment_nos, riders_names:riders_names, riders_collections:riders_collections, all_cn_no:mydata, update_credit:1},
              cache:false,
              success: function (response) {
              }
          });
 }
</script>


<script type="text/javascript">
$(window).on('scroll', function() 
     { var scrollTop = $(window).scrollTop(); 
     if(scrollTop > 280) { 
       $('.order_info_box').css('position', 'fixed'); 
       $('.order_info_box').css('z-index', '999'); 
       $('.order_info_box').css('top', '10px');  
       $('.order_info_box').css('right', '16px'); 
       $('.order_info_box').css('margin-top', '10px'); 
       $('.order_info_box').css('height', '96vh'); 
     } 
     else { 
      $('.order_info_box').css('position', 'absolute');
       $('.order_info_box').css('top', 'auto');  
     } 
   })

</script>
