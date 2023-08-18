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


        <div class="warper container-fluid">

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->

            <?php

      include "pages/orders/ajax_order.php";

      ?>


        </div>
        <!-- Warper Ends Here (working area) -->


      <?php

  include "includes/footer.php";
  }
  else{
    header("location:index.php");
  }
  ?>
  <script type="text/javascript">
    $( "#submit_order_bulk" ).click(function() {
  $( "#submit_order" ).click();
});
  </script>
  <script type="text/javascript">
            $(function () {
                $('.datetimepicker4').datetimepicker({
                  format: 'YYYY/MM/DD',
                });
            });
        </script>
        <script type="text/javascript">
          $('body').on('click','.main_select',function(e){
    var check = $('#basic-datatable').find('tbody > tr > td:first-child .order_check');
    if($('.main_select').prop("checked") == true){
      $('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked',true);
    }else{
      $('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked',false);
    }

    $('#basic-datatable').find('tbody > tr > td:first-child .order_check').val();
  })
          var mydata = [];
  $('body').on('click','.update_status',function(e){
    e.preventDefault();
    $('#basic-datatable > tbody  > tr').each(function() {
      var checkbox = $(this).find('td:first-child .order_check');
      if(checkbox.prop("checked") ==true){
        var order_id = $(checkbox).data('id');
        mydata.push(order_id);
      }
    });
    var order_data = JSON.stringify(mydata);
        $('#print_data').val(order_data);
        $('#bulk_submit').submit();
  })
        </script>
                <script type="text/javascript">
 $(window).on('scroll', function()
     { var scrollTop = $(window).scrollTop();
     if(scrollTop > 300) {
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


 // banner cargo
$(window).on('scroll', function()
     { var scrollTop = $(window).scrollTop();
     if(scrollTop > 320) {
       $('.cargo_banner').css('position', 'fixed');
       $('.cargo_banner').css('z-index', '999');
       $('.cargo_banner').css('top', '10px');
       $('.cargo_banner').css('right', '16px');
       $('.cargo_banner').css('margin-top', '10px');
     }
     else {
      $('.cargo_banner').css('position', 'absolute');
       $('.cargo_banner').css('margin-top', '-6px');
       $('.cargo_banner').css('top', 'auto');
       $('.cargo_banner').css('right', '16px');
     }
   })

$(document).on("click",".view_detail_show",function(){
  var id=$(this).attr('data-id');
  $("#"+id).toggle();
 })
$('body').on('click','.close_details',function(e){
    e.preventDefault();
  $("#view_box_detail").addClass('hidden');
 })


  $('body').on('click','.view_detail',function(e){
    e.preventDefault();
    var id=$(this).attr('data-id');
          $.ajax({
          type:'POST',
          data:{id:id,track_id:1},
          url:'ajax.php',
          success:function(response){
          $('.cargo_banner').addClass('hidden');
          $('#view_box_detail').removeClass('hidden');
          $('.order_info_box').html('');
          $('.order_info_box').html(response);
          }
          });
     })

  $('body').on('click','.live_tracking',function(e){
    e.preventDefault();
    var track=$(this).attr('data-track');
      $.ajax({
      type:'POST',
      data:{track:track,order_log_detail:1},
      url:'ajax.php',
      success:function(response){
      $('.cargo_banner').addClass('hidden');
      $('#view_box_detail').removeClass('hidden');
      $('.fix_wrapper_h').html('');
      $('.fix_wrapper_h').html(response);
      }
      });
     })
  $('body').on('click','.relaod',function(e){
    location.reload()
     })
</script>

</script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {

}, false);
</script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
 $(document).ready(function(){

     var tracking_no = $('#tracking_no').val();
          var customer_name = $('#customer_name').val();
          var customer_phone = $('#customer_phone').val();
          var date_type = $('#date_type').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var active_customer = $('#active_customer').val();
          var pickup_rider = $('#pickup_rider').val();
          var delivery_rider = $('#delivery_rider').val();
          var order_status = $('#order_status').val();
          var order_city = $('#order_city').val();
          var origin_city = $('#origin_city').val();
          var tracking_no_bulk = $('#tracking_no_bulk').val();
          var delayed = $('#delayed').val();
   var data= {
          tracking_no:tracking_no,
          customer_name:customer_name,
          customer_phone:customer_phone,
          date_type:date_type,
          date_from:date_from,
          date_to:date_to,
          active_customer:active_customer,
          pickup_rider:pickup_rider,
          delivery_rider:delivery_rider,
          order_status:order_status,
          order_city:order_city,
          origin_city:origin_city,
          tracking_no_bulk:tracking_no_bulk,
          delayed:delayed,
          vieworder:1,
      };
      $.ajax({
      type:'POST',
      data:data,
       dataType:'json',
      url:'ajax_delivery.php',
      success:function(response){
      $('.openCount').html('');
      $('.openCount').html(response.open);
        $('.deliverCount').html('');
      $('.deliverCount').html(response.delivery);
        $('.returnedCount').html('');
      $('.returnedCount').html(response.returned);
      }
      });
     })
 $('#tracking_no').on('keyup', function(){
  $("#customer_name").prop("readonly", false);
    $("#customer_phone").prop("readonly", false);
    $("#date_type").prop("disabled", false);
    $("#pickup_rider").prop("disabled", false);
    $("#active_customer").prop("disabled", false);
    $("#delivery_rider").prop("disabled", false);
    $("#order_status").prop("disabled", false);
    $("#origin_city").prop("disabled", false);
    $("#order_city").prop("disabled", false);
    $("#delayed").prop("disabled", false);
    $("#date_from").prop("readonly", false);
    $("#date_to").prop("readonly", false);
    $("#tracking_no_bulk").prop("readonly", false);
    $("#submit_order_bulk").prop("disabled", false);
  var val = $(this).val();
  if(val){
    $("#customer_name").prop("readonly", true);
    $("#customer_phone").prop("readonly", true);
    $("#date_type").prop("disabled", true);
    $("#pickup_rider").prop("disabled", true);
    $("#active_customer").prop("disabled", true);
    $("#delivery_rider").prop("disabled", true);
    $("#order_status").prop("disabled", true);
    $("#origin_city").prop("disabled", true);
    $("#order_city").prop("disabled", true);
    $("#delayed").prop("disabled", true);
    $("#date_from").prop("readonly", true);
    $("#date_to").prop("readonly", true);
    $("#tracking_no_bulk").prop("readonly", true);
    $("#submit_order_bulk").prop("disabled", true);
  }
 });
 $('#tracking_no_bulk').on('keyup',function(){
  $("#submit_order").prop("disabled", false);
   var val = $(this).val();
  if(val){
  $("#submit_order").prop("disabled", true);
}
 });
  $('#submit_order').click(function(e){
    // alert();
    e.preventDefault();
     var tracking_no = $('#tracking_no').val();
          var customer_name = $('#customer_name').val();
          var customer_phone = $('#customer_phone').val();
          var date_type = $('#date_type').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var active_customer = $('#active_customer').val();
          var pickup_rider = $('#pickup_rider').val();
          var delivery_rider = $('#delivery_rider').val();
          var order_status = $('#order_status').val();
          var order_city = $('#order_city').val();
          var tracking_no_bulk = $('#tracking_no_bulk').val();
          var origin_city = $('#origin_city').val();
          var delayed = $('#delayed').val();
   var data= {
          tracking_no:tracking_no,
          customer_name:customer_name,
          customer_phone:customer_phone,
          date_type:date_type,
          date_from:date_from,
          date_to:date_to,
          active_customer:active_customer,
          pickup_rider:pickup_rider,
          delivery_rider:delivery_rider,
          order_status:order_status,
          order_city:order_city,
          origin_city:origin_city,
          tracking_no_bulk:tracking_no_bulk,
          delayed:delayed,
          vieworder:1,
      };
      //alert(order_status);
      $.ajax({
      type:'POST',
      data:data,
       dataType:'json',
      url:'ajax_delivery.php',
       success:function(response){
      $('.openCount').html('');
      $('.openCount').html(response.open);
        $('.deliverCount').html('');
      $('.deliverCount').html(response.delivery);
        $('.returnedCount').html('');
      $('.returnedCount').html(response.returned);
      }
      });
     })
}, false);
</script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var dataTable = $('#unique_order_datatable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    // 'scrollCollapse': true,
        // 'ordering': false,
        // pageLength: 5,
        'responsive': true,
        'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       // dom: '<"html5buttons"B>lTfgitp',
         'buttons': [
                  // {extend: 'copy'},
                  // {extend: 'csv'},
                  // {extend: 'excel', title: 'ExampleFile'},
                  // {extend: 'pdf', title: 'ExampleFile'},
                  // {extend: 'print',

                  //  customize: function (win){
                  //    $(win.document.body)
                  //       .css( 'font-size', '10pt' )
                  //       .prepend(
                  //           '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                  //       );
                  //         $(win.document.body).addClass('white-bg');
                  //         $(win.document.body).css('font-size', '10px');
                  //         $(win.document.body).find('table')
                  //                 .addClass('compact')
                  //                 .css('font-size', 'inherit');
                  // }
                  // }
              ],
    //'searching': false, // Remove default Search Control
    'ajax': {

       'url':'ajax_view_order.php',
       'data': function(data){
          // Read values
          var tracking_no = $('#tracking_no').val();
          // alert(tracking_no);
          var customer_name = $('#customer_name').val();
          var customer_phone = $('#customer_phone').val();
          var date_type = $('#date_type').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var active_customer = $('#active_customer').val();
          var pickup_rider = $('#pickup_rider').val();
          var delivery_rider = $('#delivery_rider').val();
          var order_status = $('#order_status').val();
          var order_city = $('#order_city').val();
          var origin_city = $('#origin_city').val();
          var status_check = $('.active_orders').attr('data-status');
          var delayed = $('#delayed').val();
          var tracking_no_bulk = $('#tracking_no_bulk').val();
          data.tracking_no = tracking_no;
          data.customer_name = customer_name;
          data.customer_phone = customer_phone;
          data.date_type = date_type;
          data.date_from = date_from;
          data.date_to = date_to;
          data.active_customer = active_customer;
          data.pickup_rider = pickup_rider;
          data.delivery_rider = delivery_rider;
          data.order_status = order_status;
          data.order_city = order_city;
          data.origin_city = origin_city;
          data.type = status_check;
          data.delayed = delayed;
          data.tracking_no_bulk = tracking_no_bulk;
       }
    },
    'columns': [
       { data: 'id' },
       { data: 'srno' },
       { data: 'pickup_deatil' },
       { data: 'order_detail' },
       { data: 'tracK_image' },
       { data: 'delivery_detail' },
       { data: 'orgin_destination' },
       { data: 'action' },
       { data: 'payment' },
    ]
  });

  $('#submit_order').click(function(e){
    e.preventDefault();
    dataTable.draw();
  });
   $('body').on('click','.orders_items',function(e){
    e.preventDefault();
   var status=$(this).attr('data-status');
    $('.orders_items').removeClass('active_orders');
    $(this).addClass('active_orders');
     dataTable.draw();
     });


    $('#tracking_no').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            $('#submit_order').click();
            return false;
        }
    });


}, false);
</script>
