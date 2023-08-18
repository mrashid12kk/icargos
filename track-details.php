<?php
session_start();
include_once "includes/conn.php";
include "includes/header.php";
global $msg;

if (isset($_POST['submit'])) {
    $track_no = mysqli_real_escape_string($con, $_POST['track_code']);
    $e_mail = mysqli_real_escape_string($con, $_POST['e_mail']);

    $query = mysqli_query($con, "select * from consignee_records WHERE track_no='$track_no'") or die(mysqli_error($con));
    if (mysqli_affected_rows($con) > 0) {
        echo $msg = "<div class='alert alert-danger  py-20 px-5 text-center' style='margin-left:370px; margin-right:370px;'><b>You have already registered! Plz login with given detail</b></div>";
    }
    $query = mysqli_query($con, "select * from consignee WHERE email='$e_mail'") or die(mysqli_error($con));
    if (mysqli_affected_rows($con) > 0) {
        echo $msg = "<div class='alert alert-danger  py-20 px-5 text-center' style='margin-left:370px; margin-right:370px;'><b>Your Email already exist!.</b></div>";
    } else {
        $e_mail = mysqli_real_escape_string($con, $_POST['e_mail']);
        $password = $_POST["password"];
        $hash = md5($password);
        $insert = mysqli_query($con, "INSERT INTO consignee(`email`,`password`) VALUES ('$e_mail','$hash')") or die(mysqli_error($con));

        $last_id = mysqli_insert_id($con);
        $_SESSION['consignee_id'] = $last_id;

        $insert = mysqli_query($con, "INSERT INTO consignee_records(`consignee_id`,`track_no`) VALUES ('$last_id','$track_no')") or die(mysqli_error($con));
        $last_id_record = mysqli_insert_id($con);
    }
    if ($last_id_record > 0) {
        echo "<script type='text/javascript'> document.location ='tracking_listing.php'; </script>";
    }
}
if (isset($_POST['login'])) {
    $id = $_SESSION['consignee_id'];
    $track_no = mysqli_real_escape_string($con, $_POST['track_code']);
    //print_r($track_no);
    //die;
    $query = mysqli_query($con, "select * from consignee_records WHERE track_no='$track_no' and consignee_id='$id'") or die(mysqli_error($con));
    if (mysqli_affected_rows($con) > 0) {
        echo "<script type='text/javascript'> document.location ='tracking_listing.php'; </script>";
    } else {
        $insert = mysqli_query($con, "INSERT INTO consignee_records(`consignee_id`,`track_no`) VALUES ('$id','$track_no')") or die(mysqli_error($con));
        echo "<script type='text/javascript'> document.location ='tracking_listing.php'; </script>";
    }
}
$msg = "";
if (isset($_POST['submit'])) {
    $track_no = mysqli_real_escape_string($con, $_POST['track_code']);
    $e_mail = mysqli_real_escape_string($con, $_POST['e_mail']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $new_password = md5($password);
    $query = mysqli_query($con, "select * from consignee where  email='$e_mail'");
    if (mysqli_affected_rows($con) > 0) {
        $row = mysqli_fetch_array($query);
        $hash = $row['password'];
        //print_r($new_password);
        //die();
        if (($new_password == $hash)) {
            $_SESSION['consignee_id'] = $row['id'];
            $id = $_SESSION['consignee_id'];
            $query = mysqli_query($con, "select * from consignee_records WHERE track_no='$track_no' and consignee_id='$id'") or die(mysqli_error($con));
            if (mysqli_affected_rows($con) > 0) {

                echo "<script type='text/javascript'> document.location ='tracking_listing.php'; </script>";
            } else {
                $insert = mysqli_query($con, "INSERT INTO `consignee_records`(consignee_id,track_no)VALUES('" . $_SESSION['consignee_id'] . "','$track_no')") or die(mysqli_error($con));
                echo "<script type='text/javascript'> document.location ='tracking_listing.php'; </script>";
            }
        } else {
            echo $msg = "<div class='py-20 px-5 text-center alert alert-danger' style='margin-left:370px; margin-right:370px;'><b>Wrong  Password</b></div>";
        }
    } else {
        echo $msg = "<div class='alert alert-danger  py-20 px-5 text-center' style='margin-left:370px; margin-right:370px;'><b>Wrong Email</b></div>";
    }
}
if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($con, $_POST['e_mail']);
    $user = mysqli_query($con, "SELECT * FROM consignee WHERE email = '" . $email . "'");
    $user = ($user) ? mysqli_fetch_object($user) : null;
    if ($user && isset($user->id)) {
        $key = base64_encode($user->id);
        $data['email'] = $email;
        $message['subject'] = 'Reset Password';
        $message['body'] = '<p>Please follow below link to reset your password:</p>';
        $message['body'] .= '<p><a href="' . BASE_URL . 'new_password.php?key=' . $key . '">Reset Password</a></p>';
        require_once 'admin/includes/functions.php';
        $flag = sendEmail($data, $message);
        if ($flag) {
            echo '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						Email has been sent!
					  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						Unable to send Email Please try later
					  </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						Email does not exist, Please enter an valid Email
					  </div>';
    }
}
?>
<style>
.order_sig_img img {
    height: 130px;
    width: 130px;
    object-fit: cover;
}

.sender_info h3 {
    background: #f5f5f5;
    color: #fefefe !important;
}

.btn-info {
    color: #fff;
    background-color: #0a68bb;
    border-color: #0a68bb;
}
.traking_results a {
    display: inline-block;
    width: 155px;
    margin: 10px 10px;
    border: 1px solid #3333;
    padding: 7px 10px;
    border-radius: 50px;
}
@media(max-width: 1250px) {
    .container {
        width: 100%;
    }
}

@media(max-width: 1024px) {
    .container {
        width: 100%;
    }
}

@media(max-width: 767px) {
    .container {
        width: auto;
    }
}

/*popup */
.main_popup_new button.close {
    position: absolute;
    right: 0;
    background: white;
    opacity: 100%;
    border-radius: 0 12px 0px 0px;
    padding: 0 5px;
}

.main_popup_new .modal-body img {
    width: 100%;
    object-fit: cover;
    height: 200px;
    border-radius: 12px;
}

.main_popup_new .modal-content {
    border-radius: 12px;
}

.main_popup_new .modal-body {
    position: relative;
    padding: 0;
}

.main_popup_new .modal-dialog {
    margin: 170px auto;
    width: 300px;
}
</style>
<?php echo $errors ?>
<div class="tracking_Bg">
    <h1> Tracking</h1>
</div>
<? php //if(isset($_GET['message']) && $_GET['message'] == 1){ 
?>
<!--<div class="row">
				<div class="" style="max-width: 746px;margin: 0 auto; margin-bottom: -34px;">
					<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Comment Added Successfully!</div>
				</div>
			</div>-->
<?php   //} 
?>
<?php
if (isset($_GET['track_code'])) {
    $code = $_GET['track_code'];
    $explode_orders = explode(',', $code);
    $implode = "'" . implode("', '", $explode_orders) . "'";

    $query = mysqli_query($con, "select * from orders where track_no IN($implode) AND status !='cancelled' ");
    $record = mysqli_num_rows($query);
    if (empty($record) || $record == 0) {
        $_SESSION['track_not_found'] = 'The tracking number you have provided is incorrect. Please enter a valid tracking number';
        echo "<script type='text/javascript'>window.location.href=''" . BASE_URL . "'tracking.php';</script>";
        exit;
    }
    while ($fetch = mysqli_fetch_array($query)) {
        $code = $fetch['track_no'];

        $query_log = mysqli_query($con, "SELECT  * FROM  order_logs where order_no='" . $code . "' order by id ");

        $curent_log = mysqli_query($con, "SELECT * FROM order_logs WHERE order_no='" . $code . "' ORDER BY id DESC LIMIT 1");
        $curent_log_data = mysqli_fetch_array($curent_log);
?>
<div class="tracking_wrap">
    <?php if (isset($fetch) && !empty($fetch)) { ?>
    <div class="container">
        <div class="panel panel-primary traking_results">
            <div class="panel-heading">
                <?php echo getLange('tracking'); ?> #<b
                    class=""><?php echo isset($fetch['track_no']) ? $fetch['track_no'] : ''; ?></b>
                <?php
                            if (isset($_SESSION['consignee_id'])) {
                            ?>
                <form method="POST" action="">
                    <button class="" name="login"><?php echo getLange('saveforfutureuse'); ?></button>
                    <input type="hidden" name="track_code"
                        value="<?php echo isset($fetch['track_no']) ? $fetch['track_no'] : ''; ?>">
                </form>
                <?php
                            } else {
                            ?>
                <button type="button" class="save_future"><?php echo getLange('saveforfutureuse'); ?><b
                        class="track"><?php echo $code; ?></b></button>
                <?php
                            }
                            ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php if (isset($fetch['order_signature']) &&  !empty($fetch['order_signature'])) : ?>
                    <div class="order_sig_img">
                        <img src="<?php echo BASE_URL . "admin/" . $fetch['order_signature']; ?>">
                    </div>
                    <?php
                                    $currentimage_moadl = $fetch['order_signature'];
                                endif ?>
                    <div class="traking_results table_shdow">
                    <div style="width: 100%;text-align: right;">
                                    <!-- <a href="#"><img src="assets/img/vendor/tcs.png" alt=""></a> -->
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
                        <h3><?php echo getLange('trackingresult'); ?></h3>
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
                                </p>
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
                                <th><?php echo getLange('Location'); ?></th>
                                <th><?php echo getLange('status'); ?></th>
                                <th><?php echo getLange('Status Remarks'); ?></th>
                            </tr>
                            <?php if (isset($query_log) && !empty($query_log)) {
                                            while ($fetch2 = mysqli_fetch_array($query_log)) {
                                                // var_dump($fetch2);
                                        ?>
                            <tr>
                                <td><?php echo date('d M Y h:i A', strtotime($fetch2['created_on'])); ?></td>
                                <td> <?php
                               
                                 if (isset($fetch2['country']) && empty($fetch2['city']))
                                  {
                                    echo $fetch2['country'];                               
                                       }
                                elseif( isset($fetch2['city']) && isset($fetch2['country']))
                                 {
                                     echo  $fetch2['country'].', '. $fetch2['city'] ;
                                 } 
                                else{

                                    echo $fetch2['location'];
                                }
                                 ?>
                                    
                                </td>
                                <!-- <?php echo substr($fetch2['order_status'], 9); ?> -->
                                <td>
                                    <?php echo $fetch2['order_status'];
                                                        if (substr($fetch2['order_status'], 0, 9) == 'Delivered') {
                                                            if (isset($fetch['order_signature']) && !empty($fetch['order_signature'])) {
                                                            if (file_exists($fetch['order_signature'])) {?>
                                    <a data-toggle="modal" data-target="#exampleModal"><i class="fa fa-eye"></i></a>
                                    <?php }
                                                        } }?>
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
                                <td><?php echo $fetch2['tracking_remarks']; ?></td>
                            </tr>
                            <?php }
                                        } ?>
                        </table>
                    </div>
                </div>
                <?php
                            $comment_query = mysqli_query($con, "SELECT customer_id FROM orders WHERE track_no='" . $code . "' ");
                            $query_record = mysqli_fetch_array($comment_query);
                            if (isset($_SESSION['customers'])) {
                                if ($_SESSION['customers'] == $query_record['customer_id']) {
                            ?>
                <div class="traking_results">
                    <div class="table-responsive">
                        <h3>Add Comment </h3>
                        <table class="table_info">
                            <form method="POST" action="add_comment.php">
                                <input type="hidden" name="track_code" value="<?php echo $code; ?>">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" name="subject" class="form-control" name="subject"
                                                placeholder="Enter Comment Subject" required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea class="form-control" name="message" placeholder="Enter Message..."
                                                required="true"></textarea>
                                        </div>
                                        <input type="submit" name="submit" class="btn btn-info btn_comment"
                                            style="color: #fff !important;" value="Add Comment">
                                    </td>
                                </tr>
                            </form>
                        </table>
                    </div>
                </div>
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
                <?php }
                            } ?>
            </div>
        </div>
    </div>
</div>

<!-- popup -->
<div class="overlay_future"></div>
<div class="overlay_save_box">
    <div class="main_contnts">
        <div class="close_btn">
            <i class="fa fa-close"></i>
        </div>
        <ul class="tabs">
            <li class="tab-link current" data-tab="tab-1"><?php echo getLange('register'); ?></li>
            <li class="tab-link" data-tab="tab-2"><?php echo getLange('login'); ?></li>
        </ul>
        <div id="tab-1" class="tab-content current">
            <form method="POST" action="">
                <div class="track_codee">
                </div>
                <div class="form_box">
                    <label><?php echo getLange('emailorphone'); ?></label>
                    <input type="text" name="e_mail" value="" autocomplete="off" required>
                    <input type="hidden" name="track_code"
                        value="<?php echo isset($fetch['track_no']) ? $fetch['track_no'] : ''; ?>">

                </div>
                <div class="form_box">
                    <label><?php echo getLange('password'); ?></label>
                    <input type="password" name="password" required>

                </div>
                <div class="form_box" style="text-align: center;">
                    <button type="submit" name="register"><?php echo getLange('register'); ?></button>
                </div>
            </form>
        </div>
        <div id="tab-2" class="tab-content">
            <form method="POST" action="">
                <div class="track_codee">

                </div>
                <div class="login_screen">
                    <div class="form_box">
                        <label><?php echo getLange('emailorphone'); ?></label>
                        <input type="text" name="e_mail" value="" autocomplete="off" required>
                        <input type="hidden" name="track_code"
                            value="<?php echo isset($fetch['track_no']) ? $fetch['track_no'] : ''; ?>">

                    </div>
                    <div class="form_box">
                        <label><?php echo getLange('password'); ?></label>
                        <input type="password" name="password" required>
                    </div>


                    <div class="form_box" style="text-align: center;">
                        <button type="submit" name="submit"><?php echo getLange('submit'); ?></button>
                    </div>
                </div>
            </form>
            <form method="POST" action="">
                <div class="form_box">
                    <p><a href="#" class="forget-pas"><?php echo getLange('forgetpass') ?></a></p>
                </div>
                <div class="forget_pasword">
                    <div class="form_box">
                        <label><?php echo getLange('email'); ?></label>
                        <input type="text" name="e_mail" value="">
                    </div>
                    <div class="form_box" style="text-align: center;">
                        <button type="send" name="email"><?php echo getLange('send'); ?></button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
        }
    } ?>
<script type="text/javascript">
$(document).ready(function() {
    $('ul.tabs li').click(function() {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    });
});

$(".save_future").click(function() {
    $(".overlay_future,.overlay_save_box").fadeIn();
});
$(".forget-pas").click(function() {
    $(".login_screen").hide();
});
$(".forget-pas").click(function() {
    $(".forget_pasword").show();
});
$(".close_btn,.overlay_future").click(function() {
    $(".overlay_future,.overlay_save_box").fadeOut();
});
$(document).on("click", ".save_future", function(e) {
    var track_code = $(this).find(".track").html();

    var html = '<input type="hidden" name="track_code" value="' + track_code + '">';
    //alert(html);
    $(".track_codee").empty("");
    $(".track_codee").append(html);
});
</script>
</body>

</html>
<?php
function getCustomerBus($customer_id)
{
    global $con;
    $sql = "select * from  customers where id='" . $customer_id . "'";
    $res = mysqli_query($con, $sql) or die(mysqli_error($con));
    $cusdata = mysqli_fetch_array($res);
    return $cusdata;
}
include "includes/footer.php";
// }
?>
<style>
    .traking_results {
        max-width: 800px;
    }
</style>