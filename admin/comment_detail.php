<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {

    include "includes/header.php";
?>

<body data-ng-app>
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
        if (isset($_POST['reply'])) {
            $id = $_GET['comment_id'];
            $date = date('Y-m-d H:i:s');
            $user_name = getusernameById($_SESSION['users_id']);
            $comment = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from order_comments WHERE id = $id"));
            $order_id = isset($comment['order_id']) ? $comment['order_id'] : '';
            $track_no = isset($comment['track_no']) ? $comment['track_no'] : '';
            $customer_id = isset($comment['customer_id']) ? $comment['customer_id'] : '';
            $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
            $order_comment = isset($_POST['order_comment']) ? $_POST['order_comment'] : '';
            $sql = "INSERT INTO `order_comments`(`order_id`, `track_no`, `customer_id`, `subject`, `order_comment`, `comment_by`, `created_on`,`is_read`) VALUES ('" . $order_id . "','" . $track_no . "','" . $customer_id . "','" . $subject . "','" . $order_comment . "','" . $user_name . "','" . $date . "',1)";
            mysqli_query($con, $sql);
            $message = 'Your Profile Updated Successfully';
            $class = 'success';
            $_SESSION['update_class'] = $class;
            $_SESSION['update_title'] = 'Sussess';
            $_SESSION['update_message'] = $message;
        }
        include "includes/sidebar.php";
        $id = isset($_GET['comment_id']) ? $_GET['comment_id'] : '';
        mysqli_query($con, "UPDATE order_comments set is_read = 1 where id = $id");
        $comment = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from order_comments WHERE id = $id"));

        ?>
    <!-- Aside Ends-->
    <section class="content">
        <?php
            include "includes/header2.php";
            ?>
        <!-- Header Ends -->
        <?php

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
            ?>
        <div class="warper container-fluid">
            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            <div class="row">
                <div class="col-sm-6">
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
                                        <td><?php echo getBusinessName($comment['customer_id']); ?></td>
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
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Reply Commnets </div>
                        <div class="panel-body">
                            <form action="#" method="POST">
                                <div class="row">
                                    <div class="col-sm-12 form_box">
                                        <label for="">Subject</label>
                                        <input type="text" name="subject" placeholder="Subject">
                                    </div>
                                    <div class="col-sm-12 form_box">
                                        <label for="">Message</label>
                                        <textarea placeholder="Message" name="order_comment" required></textarea>
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
        <!-- Warper Ends Here (working area) -->
        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>