<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// session_start();
require 'includes/conn.php';
require_once "includes/role_helper.php";
if (!checkRolePermission($_SESSION['user_role_id'], 35, 'add_only', $comment = null)) {
    header("location:access_denied.php");
}
// var_dump($_GET);
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
$customer_balance = 0;
$orderfrom = date('Y-m-d', strtotime('today - 30 days'));
$orderto = date('Y-m-d');
if (isset($_GET['submit'])) {
    if (isset($_GET['from']) && !empty($_GET['from'])) {
        $orderfrom = date('Y-m-d', strtotime($_GET['from']));
    } else {
        $orderfrom = date('Y-m-d', strtotime('today - 30 days'));
    }
    if (isset($_GET['from']) && !empty($_GET['from'])) {
        $orderto = date('Y-m-d', strtotime($_GET['to']));
    } else {
        $orderto = date('Y-m-d');
    }
    $customer_id = $_GET['customer_id'];
    $date_type=$_GET['date_type'];
    // $sql1 = "SELECT * FROM flayer_order_index WHERE payment_status = 'pending'";
    // var_dump($sql1);
    // $q = mysqli_query($con, $sql1);
    // var_dump($q);

    // die();
    $ledger_query = mysqli_query($con, "SELECT * FROM orders WHERE  (status ='delivered' || status='Returned to Shipper' ) AND customer_id=" . $customer_id . " AND DATE_FORMAT(`".$_GET['date_type']."`, '%Y-%m-%d') >= '" . $orderfrom . "' AND  DATE_FORMAT(`".$_GET['date_type']."`, '%Y-%m-%d') <= '" . $orderto . "'  AND payment_status = 'Pending' order by id desc ");
    // echo  $ledger_query;
    $sql = "SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $orderfrom . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $orderto . "'  AND customer=" . $customer_id . " AND payment_status = 'Pending'  order by id desc ";
    $flyer_query = mysqli_query($con, $sql);
        // var_dump($sql);
        // action_date
} else {
    $customer_id = $_GET['customer_id'];
    $date_type=$_GET['date_type'];
    $ledger_query = mysqli_query($con, "SELECT * FROM orders WHERE  (status ='delivered' || status='Returned to Shipper' ) AND customer_id=" . $customer_id . " AND DATE_FORMAT(`".$_GET['date_type']."`, '%Y-%m-%d') >= '" . $orderfrom . "' AND  DATE_FORMAT(`".$_GET['date_type']."`, '%Y-%m-%d') <= '" . $orderto . "'  AND payment_status = 'Pending' order by id desc ");
    var_dump( $ledger_query);
    $flyer_query = mysqli_query($con, "SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $orderfrom . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $orderto . "'  AND customer =" . $customer_id . " AND payment_status = 'Pending'  order by id desc ");

}
if (true) {
    if ($customer_id) {
        $balance_query = mysqli_query($con, "SELECT (prev_balance + (total_payable - total_paid)) as total FROM customer_ledger_payments WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 1");
        $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
        $customer_balance = ($balance_query) ? $balance_query->total : 0;
    }
    include "includes/header.php";
    $customer_list = mysqli_query($con, "SELECT * FROM customers where status='1'");

    function getTotal($flayer_id)
    {
        $sql_t = "Select * from flayer_orders WHERE flayer_order_index = " . $flayer_id;
        global $con;
        $query11 = mysqli_query($con, $sql_t);
        $total = 0;
        while ($fetch12 = mysqli_fetch_array($query11)) {
            $total += $fetch12['total_price'];
        }
        return $total;
    }
    function getCustomerDetail($customer_id = null)
    {
        global $con;
        if ($customer_id != null) {
            $total = 0;
            $customer_t = "SELECT * FROM customers WHERE id = " . $customer_id;
            $query11_customer = mysqli_query($con, $customer_t);
            $fetch12_customer = mysqli_fetch_array($query11_customer);
            return $fetch12_customer;
        }
    }
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

            <!-- Header Ends -->
            <?php
            $return_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='return_fee'  ");
            $total_return_fee = mysqli_fetch_array($return_query);
            $cash_handling_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='cash_handling'  ");
            $cash_handling_query_fee = mysqli_fetch_array($cash_handling_query);

            $gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
            $total_gst = mysqli_fetch_array($gst_query);
            $customerDetail = getCustomerDetail($customer_id);
            $is_return_fee_per_parcel = isset($customerDetail['is_return_fee_per_parcel']) ? $customerDetail['is_return_fee_per_parcel'] : '';
            $personlize_fee = 0;
            $personlize_fee = isset($customerDetail['return_fee_per_parcel']) ? $customerDetail['return_fee_per_parcel'] : '';
            ?>
            <?php if (isset($is_return_fee_per_parcel) && $is_return_fee_per_parcel == 1) { ?>
                <input type="hidden" name="" id="return_fee_setting"
                value="<?php echo isset($personlize_fee) ? $personlize_fee : 0; ?>">
            <?php } else { ?>
                <input type="hidden" name="" id="return_fee_setting" value="<?php echo $total_return_fee['value']; ?>">
            <?php } ?>
            <input type="hidden" name="" id="cash_handling_fee_setting" value="<?php echo getConfig('cash_handling'); ?>">
            <input type="hidden" name="" id="total_gst" value="<?php echo $total_gst['value']; ?>">

            <div class="warper container-fluid ">
                <div class="bulk_payment_box">

                <!-- <div class="page-header"><h1>Customer Detail</h1></div>
            <table class="table table-bordered">
              <tr>
                <th>Customer Code:</th>
                <td><?php echo $record['client_code']; ?></td>
                <th>Customer Name:</th>
                <td><?php echo $record['fname']; ?></td>
              </tr>
              <tr>
                <th>Customer Email:</th>
                <td><?php echo $record['email']; ?></td>
                <th>Customer Phone:</th>
                <td><?php echo $record['mobile_no']; ?></td>
              </tr>
              <tr>
                <th>Customer Address:</th>
                <td><?php echo $record['address']; ?></td>
                <th>Customer City:</th>
                <td><?php echo $record['city']; ?></td>
              </tr>
              <tr>
                <th>Customer Bank:</th>
                <td><?php echo $record['bank_name']; ?></td>
                <th>Account Number:</th>
                <td><?php echo $record['bank_ac_no']; ?></td>
              </tr>
              <tr>
                <th>CNIC Copy:</th>
                <td><a download href="<?php echo $url ?>/<?php echo $record['cnic_copy'] ?>">View CNIC</a></td>
                <th></th>
                <td></td>
              </tr>
          </table> -->
          <!-- <hr></hr> -->

          <div class="page-header customer_settle_period_lable">

          </div>
          <form method="GET" action="bulk_ledger_payment.php">
            <div class="row">
                <div class="col-md-2 padd_left">
                    <div class="form-group">
                        <label><?php echo getLange('customer'); ?></label>
                        <select class="form-control js-example-basic-single customer_settle_period"
                        required="true" name="customer_id">
                        <option value="" disabled="" selected="">Select</option>
                        <?php while ($row_customer = mysqli_fetch_array($customer_list)) {
                            ?>
                            <option <?php if ($customer_id == $row_customer['id']) {
                                echo "selected";
                            } ?> value="<?php echo $row_customer['id']; ?>">
                            <?php echo $row_customer['fname'] . " (" . $row_customer['bname'] . ")"; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-2 left_right_none">
            <div class="form-group">
                <label>Date Type </label>
                <select class="form-control" name="date_type" id="date_type">
                    <option value="action_date" <?php echo isset($date_type) && $date_type=='action_date' ? 'selected' : ''; ?>>Status Date</option>
                    <option value="order_date" <?php echo isset($date_type) && $date_type=='order_date' ? 'selected' : ''; ?>>Order Date</option>
                </select>
            </div>
        </div>
        <div class="col-md-2 ">
            <div class="form-group">
                <label><?php echo getLange('from') ?></label>
                <input class="form-control datetimepicker4 orderfrom" name="from"
                value="<?php echo $orderfrom; ?>">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo getLange('to'); ?></label>
                <input class="form-control datetimepicker4" name="to" value="<?php echo $orderto; ?>">
            </div>
        </div>
        <div class="col-md-3 padd_none">
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-info"
                value="<?php echo getlange('submit'); ?>" style="margin-top: 24px;">
            </div>
        </div>
    </div><br>
    <!-- <a href="#" class="btn btn-success generate_payment" style="margin: 15px 0px;">Generate</a> -->
</form>
<form action="submit_bulk_ledger_payment.php" method="POST">
    <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
    <input type="hidden" name="total_cod">
    <input type="hidden" name="total_delivery">
    <input type="hidden" name="fuel_surcharge">

    <input type="hidden" name="total_return_fee">
    <input type="hidden" name="total_charges">
    <input type="hidden" name="total_return">
    <input type="hidden" name="total_gst">
    <input type="hidden" name="total_cash_handling">
    <input type="hidden" name="total_payable_price">
    <input type="hidden" name="total_flyer">
    <input type="hidden" name="count_total_flyer_checked">
    <input type="hidden" name="count_total_return_checked">
    <input type="hidden" name="count_total_del_checked">
    <input type="hidden" name="chash_handling">
    <input type="hidden" name="net_amount">
    <div class="row">
        <div class="col-md-3 padd_left">
            <div class="form-group">
                <label><?php echo getLange('date'); ?></label>
                <input type="text" name="date" class="form-control datetimepicker4"
                value="<?= date('Y/m/d'); ?>" required="true">
            </div>
        </div>
        <div class="col-md-3">
            <?php
            $last_id_q = mysqli_query($con, "SELECT max(id) as id from customer_ledger_payments");
            $lastIdRes = mysqli_fetch_assoc($last_id_q);
            $lastId = isset($lastIdRes['id']) ? $lastIdRes['id'] : 0;
            $nextId = $lastId + 1;
            $reference = 'SI-' . $nextId;
                                // $reference = strtoupper(substr(hash('sha256', mt_rand() . microtime()), 0, 8));
            ?>
            <div class="form-group">
                <label><?php echo getLange('invoiceno'); ?></label>
                <input type="text" name="reference_no" value="<?php echo $reference; ?>"
                class="form-control" required="true" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo getLange('customer') . ' ' . getLange('balance'); ?>.</label>
                <input type="text" readonly="true" name="prev_balance"
                class="form-control customer-balanced" value="<?= number_format($customer_balance, getConfig('number_format')); ?>">
                <input type="hidden" class="customer_balance" value="<?php echo $customer_balance; ?>">
            </div>
        </div>
    </div>
    <br>
    <table class="table table-striped table-bordered " data-cod="0" id="ledger_list">
        <thead>
            <tr>
                <th><input type="checkbox" class="select_all_orders"></th>
                <th><?php echo getLange('trackingno'); ?></th>
                <th><?php echo getLange('deliveryname'); ?></th>
                <th><?php echo getLange('deliveryphone'); ?></th>
                <th><?php echo getLange('deliverycity'); ?></th>
                <th><?php echo getLange('weight'); ?></th>
                <th><?php echo getLange('collectionamount'); ?></th>
                <th><?php echo getLange('deliveycharges'); ?></th>
                <th><?php echo getLange('specialcharges'); ?></th>
                <th><?php echo getLange('extracharges'); ?></th>
                <th><?php echo getLange('insurancepremium'); ?></th>
                <th><?php echo getLange('totalcharges'); ?></th>
                <th><?php echo getLange('fuelsurcharge'); ?></th>
                <th><?php echo getLange('gst'); ?></th>
                <th><?php echo getLange('netamount'); ?></th>
                <th><?php echo getLange('status'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($ledger_query)) {
                $color = '#000000';
                if ($row['rider_collection'] == 1 and $row['admin_collection'] == 0) {
                    $color = '#ef1010';
                }
                $key_name = (strtolower($row['status']) == 'delivered') ? 'delivered' : 'returned';
                $delivery_fee = 0;
                $delivery_fee = $row['price'];
                $grand_total_charges = $row['grand_total_charges'];
                $pft_amount = $row['pft_amount'];
                $net_amount = $row['net_amount'];
                if ($row['status'] == 'Returned to Shipper') {
                    $customerDetail = getCustomerDetail($row['customer_id']);
                    $wave_off_return_delivery_fee = isset($customerDetail['wave_off_return_delivery_fee']) ? $customerDetail['wave_off_return_delivery_fee'] : '';
                    if ($wave_off_return_delivery_fee == 1) {
                        $delivery_fee = 0;
                        $grand_total_charges = 0;
                        $pft_amount = 0;
                        $net_amount = 0;
                    }
                }
                ?>
                <tr>
                    <td><input checked type="checkbox" class="orderid"
                        data-totalNetAmount="<?php echo isset($net_amount) ? $net_amount : 0 ?>"
                        data-totalFuelSurharges="<?php echo isset($row['fuel_surcharge']) ? $row['fuel_surcharge'] : 0 ?>"
                        data-totalCharges="<?php echo isset($grand_total_charges) ? $grand_total_charges : 0 ?>"
                        data-status="<?php echo $row['status'] ?>"
                        data-delivery="<?php echo $delivery_fee ?>"
                        data-cod="<?php echo $row['collection_amount'] ?>"
                        data-pft="<?php echo $pft_amount ?>" value="<?php echo $row['id'] ?>"
                        name="<?= $key_name; ?>[<?= $row['id']; ?>]"></td>
                        <td style="color: <?php echo $color; ?>"><?php echo $row['track_no']; ?></td>
                        <td><?php echo $row['rname']; ?></td>
                        <td><?php echo $row['rphone']; ?></td>
                        <td><?php echo  $row['destination']; ?></td>
                        <td><?php echo  $row['weight']; ?> KG</td>
                        <td><?php echo getConfig('currency'); ?> <?php echo $row['collection_amount']; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo $delivery_fee; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($row['special_charges']) ? $row['special_charges'] : 0; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($row['extra_charges']) ? $row['extra_charges'] : 0; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($row['insured_premium']) ? $row['insured_premium'] : 0; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($grand_total_charges) ? $grand_total_charges : 0; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($row['fuel_surcharge']) ? $row['fuel_surcharge'] : 0; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($pft_amount) ? $pft_amount : 0; ?></td>
                        <td><?php echo getConfig('currency'); ?> <?php echo isset($net_amount) ? $net_amount : 0; ?></td>
                        <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
        <table class="table table-striped table-bordered  " id="flyer_list">
            <thead>
                <tr>
                    <th style="display: none;"><input type="checkbox" class="select_all_flyer_sell"></th>
                    <th><?php echo getLange('invoiceno'); ?></th>
                    <th><?php echo getLange('date'); ?></th>
                    <th><?php echo getLange('description'); ?></th>
                    <th><?php echo getLange('grand_total_charges'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($flyer_query)) {
                    $flayer_order_index = $row['id'];
                    $flayer_order_query = mysqli_query($con, "SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=" . $flayer_order_index . " ");
                    $total = getTotal($row['id']);
                    ?>
                    <tr>
                        <td style="width: 5%; display: none;"><input checked type="checkbox" class="orderid"
                            data-flyer="<?php echo $total; ?>" value="<?php echo $row['id'] ?>"
                            name="flyer[<?= $row['id']; ?>]"></td>
                            <td><?php echo sprintf("%04d", $row['id']); ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <?php
                                while ($rec2 = mysqli_fetch_array($flayer_order_query)) {
                                    ?>
                                    <p><b>Flayer: </b><?php echo $rec2['flayer_name']; ?>, <b>Qty:
                                    </b><?php echo $rec2['qty']; ?></p>
                                <?php } ?>

                            </td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo getLange('prevbalance'); ?></th>
                        <th><?php echo getLange('totalcod'); ?></th>
                        <th><?php echo getLange('returned') . ' ' . getLange('cod'); ?></th>
                        <th><?php echo getLange('totalcharges'); ?></th>
                        <th><?php echo getLange('fuelsurcharge'); ?></th>
                        <th><?php echo getLange('gst'); ?></th>
                        <th><?php echo getLange('returnedfeeperparcel'); ?></th>
                        <th><?php echo getLange('cashhandlingfee') ?> <?php echo getConfig('cash_handling'); ?>
                    (%)</th>
                    <th><?php echo getLange('flyersell') ?></th>
                    <!-- <th>Total GST(<?php echo $total_gst['value']; ?>%)</th> -->
                    <th><?php echo getLange('netamount'); ?></th>
                    <th><?php echo getLange('totalpayable'); ?></th>
                    <!-- <th><?php echo getLange('payment') ?></th> -->
                    <th><?php echo getLange('balance'); ?></th>
                    <th><?php echo getLange('action') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?php echo number_format($customer_balance, getConfig('number_format')); ?></b></td>
                    <td><b><span id="totalCOD"></span></b></td>
                    <td><b><span id="totalReturnCod"></span></b></td>
                    <td><b><span id="totalCharges"></span></b></td>
                    <td><b><span id="totalfuelsurcharge"></span></b></td>
                    <td><b><span id="totalGST"></span></b></td>
                    <td><b><span id="totalreturnfee"></span></b></td>
                    <td><b><span id="totalChashhandling"></span></b></td>
                    <td><b><span id="totalFlyerSell"></span></b></td>
                    <td><b><span id="total_net_amount"></span></b></td>
                    <td><b><span id="totalPayables"></span></b></td>
                                <!-- <td>
                                    <input type="text" name="total_payments" class="form-control">
                                </td> -->
                                <td><b><span id="totalBalance">0.00</span></b></td>
                                <td><input type="submit" name="submit" class="btn btn-success"
                                    value="Generate Invoice " /></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
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
            $('body').on('change', '.customer_settle_period', function() {
                var customer_id = $(this).val();
                $.ajax({
                    type: 'POST',
                    dataType: 'Json',
                    data: {
                        customer_settle_period: 1,
                        customer_id_settle: customer_id
                    },
                    url: 'ajax.php',
                    success: function(response) {
                    // $('.orderfrom').val('');
                    $('.orderfrom').val(response.from);
                    $('.customer_settle_period_lable').html(response.payment_within);
                    // var payment_winthin=$('.customer_settle_period_lable').html();
                    // $('.customer_settle_period_lable').html(payment_winthin + response.payment_date);
                }
            })
            })
        </script>
        <?php if (isset($_GET['customer_id']) && $_GET['customer_id'] != '') { ?>
            <script type="text/javascript">
                var customer_id = $('.customer_settle_period').val();
                $.ajax({
                    type: 'POST',
                    dataType: 'Json',
                    data: {
                        customer_settle_period: 1,
                        customer_id_settle: customer_id
                    },
                    url: 'ajax.php',
                    success: function(response) {
                // $('.orderfrom').val('');
                $('.customer_settle_period_lable').html(response.payment_within);
            }
        })
    </script>
    <?php } ?>