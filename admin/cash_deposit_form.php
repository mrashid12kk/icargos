<?php
session_start();
require 'includes/conn.php';
require_once "includes/role_helper.php";
// if (!checkRolePermission($_SESSION['user_role_id'], 68, 'add_only', $comment = null)) {
//     header("location:access_denied.php");
// }

$msg = '';

if (isset($_POST['submit'])) {
    $date = date('Y-m-d H:i:s');
    $post_date = $_POST['date'] . " " . date('H:i:s');
    $total = 0;
    $user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 100;
    $branch_id = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : 1;

    $last_id_q = mysqli_query($con, "SELECT max(id) as id from cash_deposit_form_master");
    $lastIdRes = mysqli_fetch_assoc($last_id_q);
    $lastId = isset($lastIdRes['id']) ? $lastIdRes['id'] : 0;
    $nextId = $lastId + 1;
    $reference = 'CDF-0' . $nextId;
    $master_query = "INSERT INTO `cash_deposit_form_master`(`branch_id`,`courier_code`, `date`, `deposite_date`, `report_id`, `total_cod`, `created_by`, `created_at`) VALUES ('" . $branch_id . "','" . $_POST['courier_code'] . "','" . $post_date . "','" . $date . "','" . $reference . "','" . $total . "','" . $user_id . "','" . $date . "')";
    $queryMaster = mysqli_query($con, $master_query);
    $master_id = mysqli_insert_id($con);
    if ($master_id > 0) {
        $new_cdf_no = 'CDF-0' . $master_id;
        mysqli_query($con, "UPDATE cash_deposit_form_master set report_id = '$new_cdf_no' WHERE id = $master_id");
        foreach ($_POST['cash_deposite_track_no'] as $key => $track_no) {
            mysqli_query($con, "INSERT INTO `cash_deposit_form`(`master_id`, `report_id`, `track_no`, `created_on`) VALUES ('" . $master_id . "','" . $new_cdf_no . "','" . $track_no . "','" . $date . "')");
        }
    }

    if ($master_id > 0) {
        $_SESSION['insert_id'] = $master_id;
        // $msg='<div class="alert alert-success">Cash Deposit Successfully</div>';
    } else {
        // $msg='<div class="alert alert-danger">Cash Deposit Unsuccessfully</div>';
    }
}
$courier_code = '';
$date = '';
$delivery_sheet_no = '';
$ledger_query = '';
$branch_check = '';
if(isset($_SESSION['branch_id']) && $_SESSION['branch_id'] > 1){
    $branch_check = " AND branch_id =".$_SESSION['branch_id'] ;
}
if (isset($_POST['filter'])) {
    $rider_id = '';
    $courier_code = $_POST['courier_code'];
    // echo "SELECT id FROM users WHERE  user_name='" . $_POST['courier_code'] . "' $branch_check";
    if (isset($_POST['courier_code']) && $_POST['courier_code'] != '') {
        $rider_id_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM users WHERE  user_name='" . $_POST['courier_code'] . "' $branch_check"));
        $rider_id = isset($rider_id_q['id']) && $rider_id_q['id'] != '' ? 'AND delivery_rider=' . $rider_id_q['id'] : '';
    }
    if ($rider_id && !empty($rider_id) && $rider_id != '') {
        $from = date('Y-m-d', strtotime($_POST['from']));
        $to = date('Y-m-d', strtotime($_POST['to']));
        $delivery_sheet_no = $_POST['delivery_sheet_no'];
        // $main_Sql = "SELECT * FROM orders WHERE  (status ='delivered')   $rider_id AND track_no  NOT IN (SELECT track_no FROM cash_deposit_form)  order by id desc ";
        $main_Sql = "SELECT * FROM orders WHERE  (status ='delivered') AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '" . $to . "'  $rider_id  AND track_no  NOT IN (SELECT track_no FROM cash_deposit_form)  order by id desc ";
    } else {
        $msg = '<div class="alert alert-danger">Invalid Rider Code</div>';
    }

    $ledger_query = mysqli_query($con, $main_Sql);
}
if (true) {
    include "includes/header.php";
    $customer_list = mysqli_query($con, "SELECT * FROM customers where status='1'");
?>

<body data-ng-app>


    <?php

        include "includes/sidebar.php";

        ?>
    <style type="text/css">
    .city_to option.hide {
        /*display: none;*/
    }

    .form-group {
        margin-bottom: 0px !important;
    }

    .ledger_list p {
        margin: 0px !important;
    }
    </style>
    <!-- Aside Ends-->

    <section class="content">

        <?php
            include "includes/header2.php";
            ?>
        <?php
            echo $msg;
            if (isset($ledger_query) && empty($ledger_query)) {
                echo '<div class="alert alert-success">Please Filter The Data First</div>';
            }
            ?>
        <div class="row">
            <div class="col-sm-12">
                <form method="POST" action="cash_deposit_form.php">
                    <div class="row">
                        <div class="col-sm-2 padd_left">
                            <div class="form-group">
                                <label>Courier Code</label>
                                <input type="text" class="form-control" required="true" name="courier_code"
                                    value="<?php echo isset($courier_code) ? $courier_code : '';  ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-2 ">
                            <div class="form-group">
                                <label>From</label>
                                <input class="form-control datetimepicker4" name="from" readonly required
                                    value="<?php echo isset($from) && $from != '' ? $from : date('Y-m-d', strtotime('today - 7 days')); ?>">
                            </div>
                        </div>
                        <div class="col-sm-2 ">
                            <div class="form-group">
                                <label>To</label>
                                <input class="form-control datetimepicker4" name="to" readonly required
                                    value="<?php echo isset($to) && $to != '' ? $to :  date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <!-- <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Delivery Sheet No</label>
                                    <input class="form-control" name="delivery_sheet_no" required value="<?php echo isset($delivery_sheet_no) ? $delivery_sheet_no : ''; ?>">
                                </div>
                            </div> -->
                        <div class="col-sm-3 padd_none">
                            <div class="form-group">
                                <input type="submit" name="filter" class="btn btn-info"
                                    value="<?php echo getlange('submit'); ?>" style="margin-top: 24px;">
                            </div>
                        </div>
                    </div><br>
                    <!-- <a href="#" class="btn btn-success generate_payment" style="margin: 15px 0px;">Generate</a> -->
                </form>
                <form action="" method="POST">
                    <input type="hidden" name="date"
                        value="<?php echo isset($date) && $date ? $date : date('Y-m-d'); ?>">
                    <input type="hidden" name="courier_code"
                        value="<?php echo isset($courier_code) ? $courier_code : '';  ?>">
                    <div class="row">
                        <div class="col-sm-3 padd_left">
                            <div class="form-group">
                                <label>Cash Deposit Date</label>
                                <input type="text" name="date" class="form-control datetimepicker4"
                                    value="<?= date('Y-m-d'); ?>" required="true">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <?php
                                $reference = '';
                                if (isset($ledger_query) && !empty($ledger_query)) {
                                    if (mysqli_num_rows($ledger_query)) {
                                        $last_id_q = mysqli_query($con, "SELECT max(id) as id from cash_deposit_form_master");
                                        $lastIdRes = mysqli_fetch_assoc($last_id_q);
                                        $lastId = isset($lastIdRes['id']) ? $lastIdRes['id'] : 0;
                                        $nextId = $lastId + 1;
                                        $reference = 'CDF-0' . $nextId;
                                    }
                                }

                                // $reference = strtoupper(substr(hash('sha256', mt_rand() . microtime()), 0, 8));
                                ?>
                            <div class="form-group">
                                <label>Report ID</label>
                                <input type="text" name="reference_no" value="<?php echo $reference; ?>"
                                    class="form-control reference_no" required readonly>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="table table-striped table-bordered table_box" data-cod="0" id="ledger_list">
                        <thead>
                            <tr>
                                <!-- <th><input type="checkbox" class="select_all_orders"></th> -->
                                <th><?php echo getLange('srno'); ?></th>
                                <th><?php echo getLange('trackingno'); ?></th>
                                <th><?php echo getLange('collectionamount'); ?></th>
                                <th><?php echo getLange('status'); ?></th>
                                <th>Delivery Sheet No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $srno = 1;
                                $total_cod = 0;
                                while ($row = mysqli_fetch_array($ledger_query)) {
                                    $total_cod += $row['collection_amount'];
                                ?>
                            <tr>
                                <!-- <td><input checked type="checkbox" class="orderid"></td> -->
                                <td><?php echo $srno++; ?></td>
                                <td style="color: <?php echo $color; ?>"><input type="hidden"
                                        name="cash_deposite_track_no[]"
                                        value="<?php echo $row['track_no']; ?>"><?php echo $row['track_no']; ?></td>
                                <td><?php echo getConfig('currency'); ?> <?php echo $row['collection_amount']; ?></td>
                                <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
                                <td style="text-transform: capitalize;"><?php echo $row['delivery_assignment_no']; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-3 padd_none"
                            style="background-color: white;border-radius: 8px;width: 9%;padding: 7px;">
                            <input type="hidden" name="" value="<?php echo $total_cod; ?>">
                            <label>Total COD : <?php echo $total_cod; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-3 padd_none">
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-info submit_btn_cod"
                                value="<?php echo getlange('submit'); ?>" style="margin-top: 24px;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
        </div>
        <!-- Warper Ends Here (working area) -->

        <!-- Modal -->

        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
        $(function() {
            $('.datetimepicker4').datetimepicker({
                format: 'YYYY/MM/DD',
            });
        });
        var reference_no = $('.reference_no').val();
        $('.submit_btn_cod').prop("disabled", true);
        if (reference_no != '') {
            $('.submit_btn_cod').prop("disabled", false);
        }
        </script>
        <?php if (isset($_SESSION['insert_id']) && $_SESSION['insert_id'] != '') { ?>
        <script type="text/javascript">
        var id = <?php echo $_SESSION['insert_id']; ?>;
        window.open('cash_deposit_sheet.php?cash_id=' + id + '&print=1&frontdesk=1', 'mywindow',
            'width:600,height:400 status=1');
        </script>
        <?php
            unset($_SESSION['insert_id']);
        } ?>