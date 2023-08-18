<?php
	session_start();
	$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	require 'includes/conn.php';
	if(isset($_SESSION['customers'])){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission(6 ,'view_only','')) {

        header("location:access_denied.php");
    }
	include "includes/header.php";
    
	$customer_id = $_SESSION['customers'];
  $track_no=isset($_POST['track_no']) && $_POST['track_no']!='' ? $_POST['track_no'] : '';
?>
	<style>
        	.heading_track h2{
        		background-color: #286fad;
			    border-color: #286fad;
			    margin: 0;
			    color: #fff;
			    font-size: 14px;
			    padding: 10px 15px;
			    border-bottom: 1px solid transparent;
			    border-top-left-radius: 3px;
			    border-top-right-radius: 3px;
        	}
        	section .dashboard .white {
    background: #fff;
    padding: 0;
    box-shadow: 0 0 3px #ccc;
    width: 100%;
    display: table;
}
.padding_track {
    padding: 50px;
}
.padding_track input {
    width: 100%;
    float: left;
    height: 40px;
}
.padding_track label {
    font-size: 16px;
    padding-bottom: 4px;
    font-weight: 500;
    display: block;
}
.padding_track button {
    width: 18%;
    height: 40px;
    margin-left: 20px;
    font-size: 18px;
    font-weight: 500;
    border: unset;
    background: #c91717;
    border-radius: 5px;
    color: white;
}
.track-box{

}
.track-box ul{

}
.track-box ul li {
    background: whitesmoke;
    padding: 20px;
    margin: 10px 0;
        font-weight: 500;
    border-radius: 5px;
    font-size: 17px;
}
.track-box ul li i {
    color: #286fad;
}
.track-box ul li span {
    float: right;
}

.traking_results a {
    display: inline-block;
    width: 155px;
    margin: 10px 10px;
    border: 1px solid #3333;
    padding: 7px 10px;
    border-radius: 50px;
}
.traking_results a img {
    width: 100%;
    height: 32px;
    object-fit: contain;
}
        </style>

<section class="bg padding30">
  <div class="container-fluid dashboard">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
	  <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8" >
      <div class="white heading_track" style="padding-top: 0;" >
      <h2>Track</h2>

       <div class="row">
       	<div class="col-lg-12 padding_track">
       		<form method="POST" action="">
             <div class="form-group">
              <label >Enter Tracking Number</label>
              <input type="text" class="form-control" placeholder="12234345433434" name="track_no" required="true" value="<?php echo isset($track_no) && $track_no!='' ? $track_no : ''; ?>">
              <button>Track</button>
            </div> 
          </form>
          <?php
          function getCustomerBus($customer_id)
          {
              global $con;
              $sql = "select * from  customers where id='" . $customer_id . "'";
              $res = mysqli_query($con, $sql) or die(mysqli_error($con));
              $cusdata = mysqli_fetch_array($res);
              return $cusdata;
          }
          if (isset($_POST['track_no'])) {
              $code = $_POST['track_no'];
              $query = mysqli_query($con, "SELECT * FROM orders WHERE track_no='".$code."' AND status !='cancelled' AND customer_id='".$customer_id."'");
              $record = mysqli_num_rows($query);
              if (empty($record) || $record == 0) {
                  echo "<div class='alert alert-danger'>The tracking number you have provided is incorrect. Please enter a valid tracking number</div>";
              }
              while ($fetch = mysqli_fetch_array($query)) {
                  $code = $fetch['track_no'];

                  $query_log = mysqli_query($con, "SELECT  * FROM  order_logs where order_no='" . $code . "' order by id");
                  $curent_log_data = mysqli_fetch_array($curent_log);
          ?>
          <div class="panel panel-primary traking_results">
               <div class="panel-heading">
                  Tracking #<b class=""><?php echo $track_no; ?></b>
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="traking_results table_shdow">
                     <div style="width: 100%;text-align: right;">
                            <?php if (isset($fetch['vendor_id']) && !empty($fetch['vendor_id'])) {
                                $vendor_url=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM vendors WHERE id=".$fetch['vendor_id']));
                                if (isset($vendor_url['vendor_url']) && !empty($vendor_url['vendor_url'])) {
                                    ?>
                                    <a href="<?php echo isset($vendor_url['vendor_url']) ? $vendor_url['vendor_url'].$fetch['vendor_track_no'] : ''; ?>" target="_blank"><?php echo isset($fetch['vendor_track_no']) ? $fetch['vendor_track_no'] : ''; ?></a>
                                <?php }
                                else{
                                   ?>
                                   <a href="#"><?php echo isset($fetch['vendor_track_no']) ? $fetch['vendor_track_no'] : ''; ?></a>
                                   <?php

                               }
                           } ?>
                       </div>
                        <h3>Tracking Results</h3>
                     </div>
                  </div>
                  <div class="traking_results">
                     <div class="row inner_shadow_info">
                        <div class="col-sm-6">
                           <!-- <h3></h3> -->
                            <div class="sender_info">
                                <h3><?php echo getLange('shipperinformation'); ?>:</h3>
                                <?php
                                  $data_cus = getCustomerBus($fetch['customer_id']);
                                ?>
                                <p><b><?php echo getLange('shipper'); ?>:</b>
                                    <?php echo isset($fetch['sname']) ? $fetch['sname'] : ''; ?></p>
                                <p><b><?php echo getLange('origin'); ?>:</b>
                                    <?php echo isset($fetch['origin']) ? $fetch['origin'] : ''; ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sender_info">
                                <h3><?php echo getLange('consigneeinformation'); ?></h3>
                                <p><b><?php echo getLange('name') ?>:</b>
                                    <?php echo isset($fetch['rname']) ? $fetch['rname'] : ''; ?></p>
                                <p><b><?php echo getLange('destination'); ?>:</b>
                                    <?php echo isset($fetch['destination']) ? $fetch['destination'] : ''; ?></p>
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="traking_results table_shdow">
                     <div class="table-responsive">
                        <h3><?php echo getLange('trackinghistory') ?> </h3>
                        <table class="table_info">
                            <tr>
                                <th><?php echo getLange('date'); ?></th>
                                <th><?php echo getLange('status'); ?></th>
                            </tr>
                            <?php if (isset($query_log) && !empty($query_log)) {
                                            while ($fetch2 = mysqli_fetch_array($query_log)) {
                                        ?>
                            <tr>
                                <td><?php echo date('d M Y h:i A', strtotime($fetch2['created_on'])); ?></td>
                                <!-- <?php echo substr($fetch2['order_status'], 9); ?> -->
                                <td>
                                    <?php echo $fetch2['order_status'];
                                                        if (substr($fetch2['order_status'], 0, 9) == 'Delivered') {
                                                            if (isset($fetch['order_signature']) && !empty($fetch['order_signature'])) { ?>
                                    <a data-toggle="modal" data-target="#exampleModal"><i class="fa fa-eye"></i></a>
                                    <?php }
                                                        } ?>
                                    <!-- modal -->
                                    <!-- Modal -->
                                    <div class="main_popup_new">
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <img src="<?php echo BASE_URL . "admin/images/order_signature/" . $currentimage_moadl; ?>"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end modal -->
                                </td>
                            </tr>
                            <?php }
                                        } ?>
                        </table>
                    </div>

                    <!-- Add comemnt Section starts -->
                    <div class="traking_results">
                        <div class="table-responsive">
                            <h3>Add Comment </h3>
                            <table class="table_info">
                                <form method="POST" action="">
                                    <input type="hidden" name="track_code" value="<?php echo $code; ?>" id="track_no">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label>Subject</label>
                                                <input type="text" name="subject" class="form-control subject" name="subject"
                                                    placeholder="Enter Comment Subject" required="true">
                                            </div>
                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea class="form-control message" name="message" placeholder="Enter Message..."
                                                    required="true"></textarea>
                                            </div>
                                            <input type="submit" name="submit" class="btn btn-primary btn_comment"
                                                style="color: #fff !important;" value="Add Comment" id="btn_comment">
                                        </td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                    </div>

                    <!-- Add comemnt Section ends -->

                    <!-- comments history Section starts -->

                    <div class="traking_results table_shdow">
                        <div class="table-responsive">
                            <h3>Comments History </h3>
                            <table class="table_info">
                                <tr>
                                    <th>Send By</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                                <?php
                                    $comment_query2 = mysqli_query($con, "SELECT * FROM order_comments WHERE track_no='" . $code . "' ORDER BY id ");

                                    while ($comm = mysqli_fetch_array($comment_query2)) {
                                ?>
                                <tr>
                                    <td><?php echo $comm['comment_by']; ?></td>
                                    <td><?php echo $comm['order_comment']; ?></td>
                                    <td><?php echo date('d M Y h:i A', strtotime($comm['created_on'])); ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>

                    <!-- comments history Section ends -->


                  </div>
               </div>
            </div>
		    <?php }
      } ?>
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

	 <?php
	 include 'includes/footer.php';
	 
	 ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
	$('title').text($('title').text()+' Ledger Payments')
}, false);
</script>


<script type="text/javascript">
  $('#btn_comment').on('click',function(e){
    e.preventDefault();
    var track_no = $('#track_no').val();
    var subject = $('.subject').val();
    var message = $('.message').val();
     $.ajax({
        url: 'add_comments.php',
        dataType: "json",
        type: "Post",
        async: true,
        data: {track_code:track_no,submit:1,message:message,subject:subject },
        success: function (data) {
           console.log(data);
           if(data.msg == 'success'){
            location.reload();
           }
        },
    });
  })
  
</script>
