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
 <style type="text/css">
   .action_btns {
    text-align: left;
  }

  .action_btns ul {
    padding: 0;
    margin: 0;
    list-style: none;
    text-align: revert;
    }.action_btns ul li a {
      background: #bbbaba !important;
      color: #000;
      padding: 8px 19px;
      display: inline-block;
      border-radius: 3px;
      margin: 0;
      font-size: 13px !important;
      font-weight: 500;
    }
    .action_btns .active, .action_btns ul li a:hover, .action_btns ul li a:focus {
      background: #e41c2a !important;
      color: #fff;
      text-decoration: none;
      }.action_btns {
        padding: 0;
      }
    </style>
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
        $id=$_SESSION['customer_id'];
        $searchQuery = '';
        $read = '';
        $active = 'active';
        if (isset($_GET['read']) && $_GET['read']=='all') {
          $read='';
          $active = '';
        }
        elseif (isset($_GET['read']) && $_GET['read']=='read') {
          $read='AND customer_read=1';
          $active = '';
        }
        elseif (isset($_GET['read']) && $_GET['read']=='unread') {
         $read='AND customer_read=0';
         $active = '';
       }
       if(isset($_POST['submit']))
       {
        $from = date('Y-m-d',strtotime($_POST['from']));
        $to = date('Y-m-d',strtotime($_POST['to']));
        if ($from != '' && $to != '') {
          $from = date('Y-m-d', strtotime($_POST['from']));

          $to = date('Y-m-d', strtotime($_POST['to']));

          $searchQuery .= " and DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "' ";
        }

        $query1 = mysqli_query($con, "SELECT order_comments.*,customers.bname From order_comments inner join customers on order_comments.customer_id=customers.id WHERE 1 AND customer_id='".$_SESSION['customers']."' " . $searchQuery . " ".$read." order by id desc");
      //read
        $empQueryd = "SELECT count(*) as readdata from order_comments where customer_read=1  AND customer_id='".$_SESSION['customers']."' ".$searchQuery." ";
          // echo $empQueryd;die;
        $empRecordsd = mysqli_query($con, $empQueryd);
        while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
          $read = $fetch1['readdata'];
        }
      ///unread
        $empQueryo = "SELECT count(*) as unread from order_comments where customer_read=0  AND customer_id='".$_SESSION['customers']."' ".$searchQuery."";

        $empRecordso = mysqli_query($con, $empQueryo);
        while ($fetch1 = mysqli_fetch_assoc($empRecordso)) {
          $unread = $fetch1['unread'];
        }
      //all
        $empQueryr = "SELECT count(*) as data from order_comments WHERE 1  AND customer_id='".$_SESSION['customers']."' ".$searchQuery ."";
          // echo $empQueryr;
          // die;
        $empRecordsr = mysqli_query($con, $empQueryr);
        while ($fetch1 = mysqli_fetch_assoc($empRecordsr)) {
          $all = $fetch1['data'];
        }
      }else{

        $from = date('Y-m-d', strtotime('today - 30 days'));
        $to = date('Y-m-d');
        $searchQuery .= " and DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "' ";
        $query1 = mysqli_query($con, "SELECT order_comments.*,customers.bname From order_comments inner join customers on order_comments.customer_id=customers.id WHERE 1 AND customer_id='".$_SESSION['customers']."' " . $searchQuery . " ".$read." order by id desc");
        //read
        $empQueryd = "SELECT count(*) as readdata from order_comments where customer_read=1  AND customer_id='".$_SESSION['customers']."' AND customer_id='".$_SESSION['customers']."' " . $searchQuery . "";
        $empRecordsd = mysqli_query($con, $empQueryd);
        while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
          $read = $fetch1['readdata'];
        }
        //unread
        $empQueryo = "SELECT count(*) as unread from order_comments where customer_read=0  AND customer_id='".$_SESSION['customers']."' AND customer_id='".$_SESSION['customers']."' " . $searchQuery . "";
        $empRecordso = mysqli_query($con, $empQueryo);
        while ($fetch1 = mysqli_fetch_assoc($empRecordso)) {
          $unread = $fetch1['unread'];
        }
        //all
        $empQueryr = "SELECT count(*) as data from order_comments WHERE 1 AND customer_id='".$_SESSION['customers']."' " . $searchQuery . " ".$searchQuery ."";
        $empRecordsr = mysqli_query($con, $empQueryr);
        while ($fetch1 = mysqli_fetch_assoc($empRecordsr)) {
          $all = $fetch1['data'];
        }

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
 <form method="POST" action="">
  <div class="row">
    <div class="col-sm-2 left_right_none">
      <div class="form-group">
        <label><?php echo getLange('from'); ?></label>
        <input type="text" value="<?php echo $from; ?>" class="form-control  datetimepicker4" name="from">
      </div>
    </div>
    <div class="col-sm-2 left_right_none">
      <div class="form-group">
        <label><?php echo getLange('to'); ?></label>
        <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
      </div>
    </div>
    <div class="col-sm-1 sidegapp-submit left_right_none">
      <input style="color: #fff !important;margin: 21px 0 0px;" type="submit"  name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>">
    </div>
    <div class="col-sm-4 action_btns">
      <ul>
        <li><a href="<?php echo BASE_URL; ?>comments_report.php?read=all" class="readhref <?php echo isset($_GET['read']) && $_GET['read']=='all' ? 'active' : ''; ?> <?php echo $active; ?>" data-read="">All (<span><?php echo $all; ?></span>)</a>
        </li>
        <li> <a href="<?php echo BASE_URL; ?>comments_report.php?read=read" class="readhref <?php echo isset($_GET['read']) && $_GET['read']=='read' ? 'active' : ''; ?>" data-read="1">Read (<span><?php echo $read; ?></span>)</a></li>
        <li> <a href="<?php echo BASE_URL; ?>comments_report.php?read=unread" class="readhref <?php echo isset($_GET['read']) && $_GET['read']=='unread' ? 'active' : ''; ?>" data-read="0">UnRead (<span><?php echo $unread; ?></span>)</a>
        </li>
      </ul>
    </div>
  </div>
</form>
<table class="table table-hover table-bordered dataTable hide-on-tab orders_tbl" id="checkbox-items">
 <thead>
  <tr >
    <th>#</th>
    <th><?php echo getLange('trackingno'); ?> </th>
    <th><?php echo getLange('orderdate'); ?> .</th>
    <th><?php echo getLange('customername'); ?> </th>
    <th ><?php echo getLange('subject'); ?></th>
    <th ><?php echo getLange('ordercomment'); ?> </th>
    <th ><?php echo getLange('commentby'); ?> </th>
    <th ><?php echo getLange('status'); ?></th>
    <th ><?php echo getLange('action'); ?></th>
  </tr>
</thead>
<tbody>
  <?php
  $srno=1;
  while($fetch1=mysqli_fetch_array($query1)){
    if ($fetch1['customer_read'] == 0) {
      $me = '<span class="label label-default">Unread</span>';
    } else if ($fetch1['customer_read'] == 1) {
      $me = '<span class="label label-default">Read</span>';
    }
    ?>
    <tr class="gradeA odd" role="row">
      <td style="width: 62px;"><?php echo $srno++; ?></td>
      <td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
      <td class="sorting_1"><?php echo $fetch1['created_on']; ?></td>
      <td class="sorting_1"><?php echo $fetch1['bname']; ?></td>
      <td class="sorting_1"><?php echo $fetch1['subject']; ?></td>
      <td class="sorting_1"><?php echo $fetch1['order_comment']; ?></td>
      <td class="sorting_1"><?php echo $fetch1['comment_by']; ?></td>
      <td class="sorting_1"><?php echo $me; ?></td>
      <td class=" action_btns" ><?php echo "<a style='font-size:19px' href='comment_detail.php?comment_id=" . $fetch1['id'] . "'><i class='fa fa-eye' ></i></a>"; ?>
    </td>
  </tr>
  <?php
}
?>
</tbody>

</table>

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
