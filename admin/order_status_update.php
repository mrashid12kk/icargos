<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
         require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],40,'view_only',$comment =null)) {
        header("location:access_denied.php");
    }
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
        <div class="warper container-fluid">
            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            <?php
            include "pages/orders/order_status_update.php";
            ?>
        </div>
        <!-- Warper Ends Here (working area) -->
      <?php include "includes/footer.php";
    }else{
        header("location:index.php");
    }
    ?>
<script type="text/javascript">
    $(document).ready(function(){

    //     // alert();
    //       $.ajax({
    //     url: 'ajax_new_country.php',
    //     type: "Post",
    //     async: true,
    //     data: { 
    //         name:$('#country').val()
    //     },
    //     success: function (data) {
    //        // alert(data);
    //        $('#city').html(data);

    //     },
    //     error: function (xhr, exception) {
          
    //     }
    // }); 
     $('#country').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
          
           
        }
    }); 
    });
});
$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});
$('body').on('change','.country_selection',function(){
        let country = $(this).val();
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {getCountCity:1,country:country},
            success: function (data) {
                console.log(data.htmlRes);
                $(".select_dynmic_city").html(data.htmlRes);
            },
            complete: function(){
                // var delivery_rate = $('.total_amount_hidden').val();
                // $('.total_amount').val(delivery_rate.toFixed(2));
            }
        });
    });
// Update status of the orders table:

$(document).on("click", ".edit_orders_sts", function () {

    var payment = $(this).parents().find('.order_payment_status').html();
    if (payment=='Paid') {
        $('body').find('.order_condition').show();
    }else{
        $(this).removeClass('fa-edit');
        $(this).removeClass('edit_orders_sts');
        $(this).addClass('fa-check');
        $(this).addClass('update_orders_sts');
        $(document).find('.prev_main_status').hide();
        $(document).find('.main_status_update').show();
    }
 });

$(document).on("click", ".update_orders_sts", function () {
    var thisbtn = $(this);
    $('body').find('.cn_table').hide();
    $('body').find('.cn_loader').show();
    var status = $('body').find('.sts_main_table').val();
    var track_no = thisbtn.attr('data-track_no');
   $.ajax({
        url: 'order_status_update_ajax.php',
        type: 'POST',
        data:{update_main_table:1,status:status,track_no:track_no},
        success: function (response) {
            if (response == 'updated') {
                $.ajax({
                    url: 'order_status_update_ajax.php',
                    type: 'POST',
                    data:{enter_cn:track_no},
                    success: function (response) {
                        $('body').find('.cn_table').show();
                        $('body').find('.cn_loader').hide();
                        $('body').find(".response_table_body").html(response);
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Status updated successfuly.',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                });

            }
         }
    });
 });


// update log table status

 $(document).on("click", ".update_to_log_btn", function () {
    var thisbtn = $(this);
    $('body').find('.cn_table').hide();
    $('body').find('.cn_loader').show();
    var status = $('body').find('.update_order_status_log').val();
    var date = $('body').find('.update_order_log_date').val();
    var order_no = $('body').find('.all_cn_no').val();
    var time = $('body').find('.update_order_log_time').val();
    var log_id = $('body').find('.order_log_update_id').val();
    var country = $('body').find('.country').val();
    var city = $('body').find('#city').val();
    var tracking_remarks  = $('body').find('.tracking_remarks ').val();
    // alert(country);
    $.ajax({
        url: 'order_status_update_ajax.php',
        type: 'POST',
        data:{update_log:1,status:status,date:date,time:time,order_no:order_no,log_id:log_id,country:country,city:city,tracking_remarks :tracking_remarks },
        cache:false,
        success: function (response) {
           if (response=="updated") {
                $(".overlay_popup_fixed,.overly_popup").fadeOut();
                $.ajax({
                    url: 'order_status_update_ajax.php',
                    type: 'POST',
                    data:{enter_cn:order_no,length:length},
                    success: function (response) {
                        $('body').find('.cn_table').show();
                        $('body').find('.cn_loader').hide();
                        $('body').find(".response_table_body").html(response);
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Status updated successfuly.',
                            showConfirmButton: false,
                            timer: 2500
                        });
                     }
                });
           }
        }
    });
 });

 //  Add a new status to the order_logs table
$(document).on("click", ".add_status_log", function () {
    $(".overlay_popup_fixed,.overly_popup").fadeIn();
 });
 $(document).on("click", ".add_to_log_btn", function () {
    $('body').find('.cn_table').hide();
    $('body').find('.cn_loader').show();
    var thisbtn = $(this);
    var status = $('body').find('.add_order_status_log').val();
    var country_selection = $('body').find('.country').val();
    var select_dynmic_city = $('body').find('.select_dynmic_city').val();
    // var created_on_date = $('body').find('.created_on_date').val();
    var tracking_remarks = $('body').find('.tracking_remarks').val();
    var date = $('body').find('.add_order_log_date').val();
    var order_no = $('body').find('.all_cn_no').val();
    var time = $('body').find('.add_order_log_time').val();
    $.ajax({
        url: 'order_status_update_ajax.php',
        type: 'POST',
        data:{add_log:1,status:status,date:date,time:time,order_no:order_no,tracking_remarks:tracking_remarks,city:select_dynmic_city,country:country_selection},
        cache:false,
        success: function (response) {
           if (response=="Added") {
                $(".overlay_popup_fixed,.overly_popup").fadeOut();
                $.ajax({
                    url: 'order_status_update_ajax.php',
                    type: 'POST',
                    data:{enter_cn:order_no,length:length},
                    success: function (response) {
                        $('body').find(".response_table_body").html(response);
                        $('body').find('.cn_table').show();
                        $('body').find('.cn_loader').hide();
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Status added successfuly.',
                            showConfirmButton: false,
                            timer: 2500
                        });
                     }
                });
           }
        }
    });
 });

 // When click on edit icon in history table
$(document).on("click", ".edit_order_status", function () {
    var thisbtn = $(this);
    var order_no = $(this).attr('data-order_no');
    var log_id = $(this).attr('data-log_id');
    var status = $(this).attr('data-status');
    var country = $(this).attr('country');
    var city = $(this).attr('city');
    var tracking_remarks = $(this).attr('tracking_remarks');
    var time = $(this).attr('data-time');
    var date = $(this).attr('data-date');
    $.ajax({
        url: 'order_status_update_ajax.php',
        type: 'POST',
        data:{update_popup:1,status:status,date:date,time:time,order_no:order_no,log_id:log_id},
        cache:false,
        success: function (response) {
            $("#order_status_update_log").html(response);
            $(".overlay_popup_fixed,.overly_popup").fadeIn();
        }
    });
});



     $(".close_btn").click(function(){
        $(".overlay_popup_fixed,.overly_popup").fadeOut();

    });
     $(".overlay_popup_fixed").click(function(){
        $(".overlay_popup_fixed,.overly_popup").fadeOut();

    });

 // Delete Record From order_logs Table

    $(document).on('click','.delete_status_log',function(){
        $(this).closest('tr').remove();
        var a = $(this).attr('data-log_id');
        $.ajax({
            url: 'order_status_update_ajax.php',
            type: 'POST',
            data:{del_log_id:a},
            cache:false,
            success: function (response) {
                $('body').find('.cn_table').show();
                $('body').find('.cn_loader').hide();
               Swal.fire({
                 position: 'bottom-end',
                 icon: 'success',
                 title: 'Status deleted successfuly.',
                 showConfirmButton: false,
                 timer: 2500
               });
            }
        });
    });

// Appending next Row from here....

    function appendNextRow()
    {
        $('body').find('.cn_table').hide();
        $('body').find('.cn_loader').show();
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
            $('body').find('.cn_table').show();
            $('body').find('.cn_loader').hide();
            return false;
          }
        $.ajax({
            url: 'order_status_update_ajax.php',
            type: 'POST',
            data:{enter_cn:a,length:length},
            success: function (response) {
                $('body').find('.cn_table').show();
                $('body').find('.cn_loader').hide();
                if (response == '') {
                    alert('No record found');
                }else{
                    $('body').find(".cn_table").show();
                    $('body').find(".response_table_body").html(response);
                    $('body').find('.enter_cn').val('');
                    $('body').find('.enter_cn').focus();
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
</script>
