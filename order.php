<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
  session_start();
  include_once "includes/conn.php";
$id = $_SESSION['customers'];
 require_once "includes/role_helper.php";
    if (!checkRolePermission(1 ,'view_only','')) {

        header("location:access_denied.php");
    }
 function getServiceType($id)
{
  global $con;
  $branchQ = mysqli_query($con, "SELECT * from services where id = $id");
  $res = mysqli_fetch_array($branchQ);
  return $res['service_code'];
} function getCustomer($customer_id)
  {
    $cust_detail = "";
    global $con;
    $sql= "SELECT * FROM customers   WHERE id  = '".$customer_id."'  ";
    $query_order_cus = mysqli_query($con,$sql);
    $cust_detail = mysqli_fetch_array($query_order_cus);
    return $cust_detail;
  }
 function decrypt($string) {
  $key="usmannnn";
    $result = '';
    $string = base64_decode($string);
    for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
    }
    return $result;
  }
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
            <li><i class="fa fa-check"></i> <strong>Order#</strong> <?php echo $row->track_no; ?></li>
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
$message = "";
//cancel order

$order_status = '';
$active_order_status = '';
$check_other = '';
$date_range ="";
$status_date_query = "";
$status_date_from = "";
$status_date_to = "";
$order_date_query = "";
if(isset($_GET['order_status'])){
    $order_status = $_GET['order_status'];
    $active_order_status  = $order_status;
    $order_status = " AND status= '".$order_status."' ";
  }

if((isset($_GET['status_date_from']) && !empty($_GET['status_date_from'])) && (isset($_GET['status_date_to']) && (!empty($_GET['status_date_to'])) )){
  $status_date_from = date('Y-m-d',strtotime($_GET['status_date_from']));
  $status_date_to = date('Y-m-d',strtotime($_GET['status_date_to']));

  $status_date_query = " AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '".$status_date_from."' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '".$status_date_to."' ";
  }
  if((isset($_GET['from']) && !empty($_GET['from'])) && (isset($_GET['to'])&& !empty($_GET['to'])) ){
  $from = date('Y-m-d',strtotime($_GET['from']));
  $to = date('Y-m-d',strtotime($_GET['to']));
  $order_date_query = " AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' ";
  }

if (isset($_GET['orderss_status']) && !empty($_GET['orderss_status'])) {
  $order_status = $_GET['orderss_status'];
  $active_order_status = $_GET['orderss_status'];
  $order_status = " AND status= '".$order_status."' ";
}

  $query1 = mysqli_query($con,"SELECT * FROM orders WHERE customer_id =".$id." $order_date_query  $order_status $status_date_query order by id desc ");



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
// $page_title = 'Request Details';
$is_profile_page = true;
$status_q = mysqli_query($con,"SELECT * FROM order_status WHERE 1 ORDER BY sort_num");

  $courier_query=mysqli_query($con,"Select * from users where type='driver'");
  $delivery_courier_query=mysqli_query($con,"Select * from users where type='driver'");
  $status_query=mysqli_query($con,"Select * from order_status where active='1'");
  $city_query=mysqli_query($con,"Select * from cities where 1");
  $city_querys=mysqli_query($con,"Select * from cities where 1");
  $branch_query=mysqli_query($con,"Select * from branches where 1");
  $currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
  $status_data_fr_dl = mysqli_fetch_array(mysqli_query($con,"Select * from order_status where sts_id=1  "));
?>

<style type="text/css">
	body,section .dashboard{
		    background:#ecedef;
	}
	.menu-bar {
	    background: #fff;
	}
</style>
<section class="bg padding30" >
  <div class="container-fluid dashboard">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="BACKGROUND-COLOR: white;margin-left:0;">
    <?php
    include "includes/sidebar.php";
    ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 padd_none_all" id="customer_order" style="BACKGROUND-COLOR: #fefefe;">
      <div class="warper container-fluid padd_none">
             <div class="myTextMessage"></div>
             <div class="filter_box_view">
                         <form method="POST" action="">
                            <div class="row" >
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('trackingno'); ?> </label>
                                        <input type="text" placeholder="<?php echo getLange('trackingno'); ?>" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no" id="tracking_no">
                                        <div class="field_svg">
                                          <svg viewBox="0 0 24 24"><path d="M11.5 7a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm-4.7 4.357l4.7 7.73l4.7-7.73a5.5 5.5 0 1 0-9.4 0zm10.254.52L11.5 22.012l-5.554-9.135a6.5 6.5 0 1 1 11.11 0h-.002z" fill="#626262"/></svg>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label>Date Type </label>
                                        <select class="form-control" name="date_type" id="date_type">
                                            <option value="order_date">Order Date</option>
                                            <option value="action_date">Status Date</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('from'); ?></label>
                                        <input type="text" value="<?php echo $from; ?>" autocomplete="off" class="form-control datepicker" name="from" id="date_from">
                                    </div>
                                    <div class="field_svg">
                                        <svg  viewBox="0 0 24 24"><path d="M7 2h1a1 1 0 0 1 1 1v1h5V3a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3V3a1 1 0 0 1 1-1zm8 2h1V3h-1v1zM8 4V3H7v1h1zM6 5a2 2 0 0 0-2 2v1h15V7a2 2 0 0 0-2-2H6zM4 18a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V9H4v9zm8-5h5v5h-5v-5zm1 1v3h3v-3h-3z" fill="#626262"/></svg>
                                      </div>

                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('to'); ?></label>
                                        <input type="text" value="<?php echo $to; ?>" autocomplete="off" class="form-control datepicker" name="to"  id="date_to" >
                                    </div>
                                    <div class="field_svg">
                                        <svg  viewBox="0 0 24 24"><path d="M7 2h1a1 1 0 0 1 1 1v1h5V3a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3V3a1 1 0 0 1 1-1zm8 2h1V3h-1v1zM8 4V3H7v1h1zM6 5a2 2 0 0 0-2 2v1h15V7a2 2 0 0 0-2-2H6zM4 18a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V9H4v9zm8-5h5v5h-5v-5zm1 1v3h3v-3h-3z" fill="#626262"/></svg>
                                      </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('orderstatus'); ?> </label>
                                        <select class="form-control courier_list js-example-basic-single" id="order_status" name="order_status"  >
                                            <option selected value=""><?php echo getLange('select').' '.getLange('status'); ?></option>
                                            <?php while($row=mysqli_fetch_array($status_query)){ ?>
                                            <option <?php if($row['status'] == $active_order_status ){ echo "selected"; } ?> value="<?php echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('origin'); ?></label>
                                        <select class="form-control courier_list js-example-basic-single" name="origin_city"  id="origin_city">
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
                                        <select class="form-control courier_list js-example-basic-single" name="order_city"  id="order_city">
                                            <option value="" selected><?php echo getLange('all').' '.getLange('city'); ?></option>
                                            <?php while($row=mysqli_fetch_array($city_querys)){ ?>
                                            <option  <?php if($row['city_name'] == $active_order_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="customer_id" value="<?php echo $_SESSION['customers']; ?>">
                                <div class="col-sm-2 sidegapp-submit " style="margin: 0;">
                                    <input type="button"  id="submit_order" name="submit" class="btn btn-success search_filter" value="<?php echo getLange('search'); ?>">
                                </div>
                            </div>

                        </form>
                     </div>
             <div class="orders_btns">
                 <ul>
                     <li>
                         <div class="orders_items active_orders" id="rtl_active_orders" data-status="open"  style='border-radius:  35px 0px 0px 35px;'>
                             <b><?php echo getLange('openorders'); ?></b>
                             <p ><?php echo getLange('donetotal') ?> <span class="openCount"></span></p>
                         </div>
                     </li>
                     <li class="delivered_status_active">
                         <div id="second_tab" class="orders_items" data-status="Delivered">
                             <b><?php echo getLange('delivered'); ?></b>
                             <p><?php echo getLange('total'); ?><span class="deliverCount"></span></p>
                         </div>
                     </li>
                     <li>
                         <div id="third_tab" class="active_return_orders orders_items" data-status="Returned" style='border-radius:0 35px 35px 0;'>
                             <b><?php echo getLange('returned'); ?></b>
                             <p><?php echo getLange('donetotal') ?><span class="returnedCount"></span></p>
                         </div>
                     </li>
                 </ul>
             </div>
                <!-- <div class="page-header"><h1>Order List </h1></div> -->
                <div class="manifest_box_">
                    <div class="row">
                        <div class="col-sm-9 order_listing_views table-responsive">
                        	<!-- <table class="order_list_view table table-hover table-bordered  hide-on-tab orders_tbl"  id="unique_order_datatable" > -->
                          <table class="order_list_view" id="unique_order_datatable" >
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
                  <th></th>
                </thead>

                            </table>
              </div>
              <div class="cargo_banner"></div>
                          <div class="col-sm-3 order_info_box hidden" id="view_box_detail">

                                  <div class="fix_wrapper_h" id="fix_wrapper_h">
                                  </div>
                          </div>

                      </div>

                </div>
            </div>
    </div>
  </div>
</section>
</div>

<?php include 'includes/footer.php'; ?>
  <?php

  }
  else{
    header("location:index.php");

  }
  ?>
   <script type="text/javascript">
	 	$('.datepicker').datepicker({
	 		format: 'yyyy/mm/dd',
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
          var pickup_rider = $('#pickup_rider').val();
          var delivery_rider = $('#delivery_rider').val();
          var order_status = $('#order_status').val();
          var order_city = $('#order_city').val();
          var origin_city = $('#origin_city').val();
          var customer_id = $('#customer_id').val();
   var data= {
          tracking_no:tracking_no,
          customer_name:customer_name,
          customer_phone:customer_phone,
          date_type:date_type,
          date_from:date_from,
          date_to:date_to,
          pickup_rider:pickup_rider,
          delivery_rider:delivery_rider,
          order_status:order_status,
          order_city:order_city,
          origin_city:origin_city,
          customer_id:customer_id,
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
  $('#submit_order').click(function(e){
    e.preventDefault();
     var tracking_no = $('#tracking_no').val();
          var date_type = $('#date_type').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var order_status = $('#order_status').val();
          var order_city = $('#order_city').val();
          var origin_city = $('#origin_city').val();
          var customer_id = $('#customer_id').val();

   var data= {
          tracking_no:tracking_no,
          date_type:date_type,
          date_from:date_from,
          date_to:date_to,
          order_status:order_status,
          order_city:order_city,
          origin_city:origin_city,
          customer_id:customer_id,
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
          var date_type = $('#date_type').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var order_status = $('#order_status').val();
          var order_city = $('#order_city').val();
          var origin_city = $('#origin_city').val();
          var status_check = $('.active_orders').attr('data-status');
          data.tracking_no = tracking_no;
          data.date_type = date_type;
          data.date_from = date_from;
          data.date_to = date_to;
          data.order_status = order_status;
          data.order_city = order_city;
          data.origin_city = origin_city;
          data.type = status_check;
          
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
     })
}, false);
</script>
