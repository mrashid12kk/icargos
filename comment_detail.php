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
      <style>
        .comments_table {
          border-collapse: collapse;
          width: 100%;
        }

        .comments_table td {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 10px;
        }

        .comments_table tr:nth-child(even) {
          background-color: #f5f5f5;
        }


        .form_box button {
          padding: 5px 24px;
          background: #416baf;
          color: #fff;
          border-radius: 3px;
          border: none;
          margin: 0 0 9px;
        }
      </style>
      <?php

      if(isset($_POST['reply']) && isset($_POST['track_code'])){
        $track_no = $_POST['track_code'];
        $order_query = mysqli_query($con,"SELECT * FROM orders WHERE track_no ='".$track_no."' ");
        $order_query_result = mysqli_fetch_array($order_query);
        $order_id = $order_query_result['id'];
        $subject = $_POST['subject'];
        $comment = $_POST['message'];
        $customer_id = $_SESSION['customers'];
        $date = date('Y-m-d H:i'); 
        mysqli_query($con,"INSERT INTO order_comments(`order_id`,`track_no`,`customer_id`,`subject`,`order_comment`,`customer_read`) VALUES('".$order_id."','".$track_no."','".$customer_id."','".$subject."','".$comment."',1) ");
        require_once "admin/includes/functions.php";
        //send email to admin
        $path = BASE_URL.'admin/order.php?id='.$order_id;
        $message['subject'] = 'Comment Received';
        $message['body'] = "<p>New Comment Added</p>";
        $message['body'] .= "<p><b>Subject:</b> $subject </p>";
        $message['body'] .= "<p><b>Message:</b> $comment </p>"; 
        $message['body'] .= "<p>Click below link to view Order.</p>";
        $message['body'] .= "<a href='$path'>$path</a>";
        $data = array();
        sendEmailToAdmin($data, $message);
        $message = 'Your Comment Updated Successfully';
        $class = 'success';
        $_SESSION['update_class'] = $class;
        $_SESSION['update_title'] = 'Sussess';
        $_SESSION['update_message'] = $message;
      }
      if (isset($_SESSION['update_message']) && !empty($_SESSION['update_message'])) {
        ?>
        <div class="alert alert-<?php echo $_SESSION['update_class'] ?> alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><?php echo $_SESSION['update_title'] ?>!</strong> <?php echo $_SESSION['update_message'] ?>.
        </div>
        <?php

        unset($_SESSION['update_class']);
        unset($_SESSION['update_message']);
        unset($_SESSION['update_title']);
      }
      $id = isset($_GET['comment_id']) ? $_GET['comment_id'] : '';
      mysqli_query($con, "UPDATE order_comments set customer_read = 1 where id = $id");
      $comment = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from order_comments WHERE id = $id"));
      ?>
      <div class="warper container-fluid">
        <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
        <div class="row">
          <div class="col-sm-6 sidegapp">
            <div class="panel panel-default">
              <div class="panel-heading">Order Comment Detail </div>
              <div class="panel-body">
                <table class="comments_table">
                  <tbody>
                    <tr>
                      <td>Tracking No </td>
                      <td><?php echo  $comment['track_no']; ?></td>
                    </tr>
                    <tr>
                      <td>Comment Date</td>
                      <td><?php echo $comment['created_on']; ?></td>
                    </tr>
                    <tr>
                      <td>Customer Name</td>
                      <td><?php //echo getBusinessName($comment['customer_id']); ?></td>
                    </tr>
                    <tr>
                      <td>Subject </td>
                      <td><?php echo $comment['subject']; ?></td>
                    </tr>
                    <tr>
                      <td>Comment By</td>
                      <td><?php echo $comment['comment_by']; ?></td>
                    </tr>
                    <tr>
                      <td>Order Comment</td>
                      <td><?php echo $comment['order_comment']; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-6 sidegapp">
            <div class="panel panel-default">
              <div class="panel-heading">Reply Commnets </div>
              <div class="panel-body">
                <form action="" method="POST">
                  <div class="row">
                    <input type="hidden" value="<?php echo $comment['track_no']; ?>" name="track_code">
                    <div class="col-sm-12 form_box">
                      <label for="">Subject</label>
                      <input type="text" name="subject" placeholder="Subject">
                    </div>
                    <div class="col-sm-12 form_box">
                      <label for="">Message</label>
                      <textarea placeholder="Message" name="message" required></textarea>
                    </div>
                    <div class="col-sm-12 form_box">
                      <button type="submit" name="reply">Reply</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
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
