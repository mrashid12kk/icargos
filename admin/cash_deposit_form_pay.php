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
}
$courier_code = '';
$date = '';
$delivery_sheet_no = '';
$ledger_query = '';
$branch_check = '';
if (isset($_SESSION['branch_id']) && $_SESSION['branch_id'] > 1) {
    $branch_check = " AND branch_id =" . $_SESSION['branch_id'];
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
            <div class="row">
                <div class="col-sm-12">
                    <?php if (isset($_GET['cash_id']) && $_GET['cash_id'] != '') {
                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cash_deposit_form_master WHERE id=" . $_GET['cash_id']));
                        $rider_name = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE user_name='" . $row['courier_code'] . "'"));
                    ?>
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-sm-2 padd_left">
                                    <div class="form-group">
                                        <label>Courier Code</label>
                                        <input type="text" class="form-control" required="true" name="courier_code" value="<?php echo isset($row['courier_code']) ? $row['courier_code'] : '';  ?>" required readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 padd_left">
                                        <div class="form-group">
                                            <label>Cash Deposit Date</label>
                                            <input type="text" name="date" class="form-control datetimepicker4" value="<?= date('Y-m-d', strtotime($row['date'])); ?>" required="true" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Report ID</label>
                                            <input type="text" name="reference_no" value="<?php echo $row['report_id']; ?>" class="form-control reference_no" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <?php
                                if (isset($_POST['cash_collect'])) {
                                    
                                    $trackNoArray = $_POST['order_id'];
                                    $track_nos = explode(',', $trackNoArray);
                                    foreach ($track_nos as $key => $value) {
                                        $order_id = $value;
                                        $sts_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT status from orders where track_no= '" . $value . "'"));
                                        $status = $sts_query['status'];
                                        if (isset($status) && $status != 'Delivered') {
                                            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error! </strong> Order ' . $value . ' is not delivered yet.</div>';
                                        } else {
                                            $sql = mysqli_query($con, "SELECT orders.rider_collection, orders.admin_collection, orders.collection_amount as collection_amount, orders.delivery_rider, orders.track_no, orders.assignment_no, orders.status as order_status,  users.Name as rider_name  FROM orders Join users on orders.delivery_rider = users.id WHERE orders.status = 'Delivered'  AND orders.track_no = '" . $order_id . "'");
                                            $order_Query = mysqli_fetch_assoc($sql);
                                            if ($order_Query['rider_collection'] == 1 && $order_Query['admin_collection'] == 0) {
                                                $rider_id = $order_Query['delivery_rider'];
                                                $rider_name = $order_Query['rider_name'];
                                                $collection_amount = $order_Query['collection_amount'];
                                                $riders_collections = $collection_amount;
                                                $all_cn_no = $order_id;
                                                $assignment_no = $order_Query['assignment_no'];
                                                $debitamount = 0;
                                                $rider_b = "SELECT * FROM rider_wallet_ballance WHERE rider_id=" . $rider_id;
                                                $rider_res = mysqli_query($con, $rider_b);
                                                $rider_prev_balance_q = mysqli_fetch_array($rider_res);
                                                $rider_prev_balance = $rider_prev_balance_q['balance'];
                                                $newBalance = $rider_prev_balance - $riders_collections;
                                                $check_q = "SELECT * FROM rider_wallet_ballance WHERE rider_id =" . $rider_id;
                                                $check_res = mysqli_query($con, $check_q);
                                                $check_rider_exists  = mysqli_fetch_array($check_res);
                                                $master_id = 0;
                                                if (isset($check_rider_exists['rider_id']) && !empty($check_rider_exists['rider_id'])) {
                                                    $cod_q = mysqli_query($con, "UPDATE  rider_wallet_ballance SET balance = " . $newBalance . ", update_date = '" . date('Y-m-d H:i:s') . "' WHERE rider_id =  " . $rider_id);
                                                    $master_id = $rider_prev_balance_q['id'];
                                                } else {
                                                    $cod_q = mysqli_query($con, "INSERT INTO `rider_wallet_ballance`(`rider_id`, `rider_name`, `balance`, `update_date`) VALUES (" . $rider_id . " , '" . $rider_name . "' , " . $newBalance . " , '" . date('Y-m-d H:i:s') . "'  )");
                                                    $master_id = mysqli_insert_id($con);
                                                }
                                                $log_q = mysqli_query($con, "INSERT INTO `rider_wallet_ballance_log`(`order_id`, `order_no`, `rider_id`, `rider_name`, `debit`, `credit`, `date`)VALUES (" . $master_id . " , '" . $all_cn_no . "'  , " . $rider_id . " , '" . $rider_name . "' , '$debitamount' , '" . $riders_collections . "' , '" . date('Y-m-d H:i:s') . "') ");
                                                $query = mysqli_query($con, "UPDATE orders set admin_collection = 1 where track_no = '" . $order_id . "'");
                                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong> Amount of ' . $value . ' is collected.</div>';
                                            }
                                            if (isset($order_Query['admin_collection']) && $order_Query['admin_collection'] == 1) {
                                                echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error!</strong> Order ' . $value . ' is already  collected.</div>';
                                            }
                                        }
                                    }
                                }
                                ?>
                                <table class="table table-striped table-bordered table_box" data-cod="0" id="cash_desposit_list">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" checked class="select_all_rows_for_cash"></th>
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
                                        $track_no_q = mysqli_query($con, "SELECT * FROM cash_deposit_form WHERE master_id=" . $_GET['cash_id']);
                                        foreach ($track_no_q as $key => $value) {
                                            $order = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM orders WHERE admin_collection=0 AND track_no='" . $value['track_no'] . "'"));
                                        ?>
                                            <tr>
                                                <td><input checked type="checkbox" class="cash_desposit_row" name="track_no[]" data-cod="<?php echo $order['collection_amount']; ?>"  data-trackno="<?php echo $order['track_no']; ?>"></td>
                                                <td><?php echo $order['track_no']; ?></td>
                                                <td><?php echo getConfig('currency'); ?> <?php echo $order['collection_amount']; ?></td>
                                                <td style="text-transform: capitalize;"><?php echo $order['status']; ?></td>
                                                <td style="text-transform: capitalize;"><?php echo $order['delivery_assignment_no']; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-3 padd_none" style="background-color: white;border-radius: 8px;width: 9%;padding: 7px;">
                                        <label>Total COD : <span class="total_cod"></span></label>
                                    </div>
                                </div>
                                <div class="col-sm-3 padd_none">
                                    <div class="form-group">
                                        <input type="button" name="submit" class="btn btn-info submit_btn_cod" value="<?php echo getlange('submit'); ?>" style="margin-top: 24px;">
                                    </div>
                                </div>
                        </form>
                        <form method="POST" id="bulk_submit_ver" action="#" target="_blank">
                            <input type="hidden" name="order_id" id="print_data">
                            <input type="hidden" name="cash_collect">
                        </form>
                </div>
            <?php } ?>
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
        <script type="text/javascript">
            calculatedepositecod();
            $('body').on('click', '.select_all_rows_for_cash', function(e) {
                var isChecked = $(this).prop('checked');
                $('#cash_desposit_list >tbody tr').each(function(i) {
                    $(this).find('.cash_desposit_row').prop('checked', isChecked);
                });
                calculatedepositecod();
            })



            $('body').on('click', '.cash_desposit_row', function() {
                calculatedepositecod();
            })

            function calculatedepositecod() {
                var total_cod = 0;
                var body = $('body');
                $('#cash_desposit_list > tbody  > tr').each(function() {
                    var checkbox = $(this).find('td:first-child .cash_desposit_row');
                    if (checkbox.prop("checked") == true) {
                        let cod = checkbox.attr("data-cod");
                        cod = (cod) ? cod : 0;
                        total_cod += parseFloat(cod);
                        total_cod = parseFloat(total_cod);

                    }
                });
                body.find('.total_cod').text(total_cod);
            }

            var mydata = [];
            $('body').on('click', '.submit_btn_cod', function(e) {
                e.preventDefault();
                $('#cash_desposit_list >tbody tr').each(function() {
                    var checkbox = $(this).find('td:first-child .cash_desposit_row');
                    if (checkbox.prop("checked") == true) {
                        var order_id = $(checkbox).data('trackno');
                        mydata.push(order_id);
                    }
                });
                var order_data = mydata.join(',');

                $('#print_data').val(order_data);
                $('#bulk_submit_ver').submit();
                location.reload();
            })
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