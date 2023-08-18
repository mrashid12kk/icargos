<?php

session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] !== 'driver') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 35, 'view_only', $comment = null)) {
        header("location:access_denied.php");
    }
    include "includes/header.php";


?>

<?php

    if (!function_exists('getCustomer')) {
        function getCustomer($id)
        {
            $record = null;
            global $con;
            $cquery = mysqli_query($con, "select client_code,bname from customers where id=" . $id);
            if (mysqli_num_rows($cquery) > 0) {
                $record = mysqli_fetch_assoc($cquery);
            }
            return $record;
        }
    }
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
            <?php $customer_list = mysqli_query($con, "SELECT * FROM customers where status='1'"); ?>
            <div class="row">
                <div id="alerts">

                </div>
                <div class="col-sm-6" style="padding: 7px 0 0;">
                    <div class="page-header">
                        <h1>Bulk Payment Voucher
                            <small><?php echo getLange('letsgetquick'); ?></small>
                        </h1>
                    </div>
                </div>
                <div class="col-sm-6" id="ledger_payemnt_dropdown" style="padding: 0;">
                    <div class="form-group pull-right select_customer_box">
                        <input type="hidden" id="customer_id"
                            value="<?php echo isset($_GET['customer_id']) ? $_GET['customer_id'] : '' ?>">
                        <select class="form-control js-example-basic-single"
                            onchange="window.location.href='ledger_payments.php?customer_id='+this.value"
                            name="customer_id">
                            <option value="">Select Customer</option>
                            <?php while ($row_customer = mysqli_fetch_array($customer_list)) {
                                ?>
                            <option <?php if (isset($_GET['customer_id']) && $_GET['customer_id'] == $row_customer['id']) {
                                                echo "Selected";
                                            } ?> value="<?php echo $row_customer['id']; ?>">
                                <?php echo $row_customer['fname'] . " (" . $row_customer['bname'] . ")"; ?> </option>
                            <?php
                                }
                                ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- <div class="add_business_tabs">
                <ul>
                    <li><a class="active" href="ledger_payments.php">Invoices</a></li>
                    <li><a href="ledger_payments_detail.php">Payment</a></li>
                </ul>
            </div> -->

            <div class="panel panel-default" style="margin-top: 0; position: relative;">
                <div class="panel-heading">Bulk Payment Voucher
                    <a href="bulk_ledger_payment.php" class="add_form_btn"
                        style="float: right;font-size: 11px;"><?php echo getLange('addpayment'); ?> </a>
                </div>
                <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                    <?php

                        if (isset($_SESSION['update_message']) && !empty($_SESSION['update_message'])) {
                        ?>
                    <div class="alert alert-<?php echo $_SESSION['update_class'] ?> alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><?php echo $_SESSION['update_title'] ?>!</strong>
                        <?php echo $_SESSION['update_message'] ?>.
                    </div>
                    <?php

                            unset($_SESSION['update_class']);
                            unset($_SESSION['update_message']);
                            unset($_SESSION['update_title']);
                        }
                        ?>
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                        <form method="POST" action="" id="filter_form">
                            <div class="col-sm-12" style="margin-bottom:10px;">
                                <label>Client Name</label>
                                <select id="client_name" name="client_name[]"
                                    class="form-control select2 js-example-basic-single" multiple>

                                    <?php $clients_query = mysqli_query($con, "SELECT *FROM `customers`");
                                        while ($client = mysqli_fetch_assoc($clients_query)) {
                                        ?>
                                    <option value="<?php echo $client['id']; ?>">
                                        <?php echo $client['bname']; ?>
                                    </option>
                                    <?php } ?>

                                </select>
                            </div>


                            <div class="col-sm-2">
                                <label>Fitler Type</label>
                                <select id="select_filter" name="select_filter" class="form-control">
                                    <option value="all">Select Filter</option>
                                    <option value="1">With Diff</option>
                                    <option value="2">Without Diff</option>
                                    <option value="3">Positive Payable</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <label>Date Type</label>
                                <select id="date_type" name="date_type" class="form-control">
                                    <option value="order_date">Order date</option>
                                    <option value="action_date">Action date</option>
                                </select>
                            </div>


                            <?php $to_date = date('Y-m-d'); ?>

                            <div class="col-sm-2">
                                <label>From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    value="<?php echo date('Y-m-d', strtotime("-30 days")); ?>">
                            </div>

                            <div class="col-sm-2">
                                <label>To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control"
                                    value="<?php echo $to_date ?>">
                            </div>
                            <div class="col-sm-1">
                                <label>Limit</label>
                                <select id="limit" name="limit" class="form-control">
                                    <!-- <option value="2">2
                                    </option>
                                    <option value="5">5
                                    </option> -->
                                    <option value="10">10
                                    </option>
                                    <?php for ($i = 20; $i <= 100; $i += 20) : ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Order By</label>
                                <select id="order_by" name="order_by" class="form-control">
                                    <option value="ASC">Ascending</option>
                                    <option value="DESC">Decending</option>
                                </select>
                            </div>
                            <div class="col-sm-1 sidegapp-submit left_right_none" style="margin-bottom: 5px;">
                                <input type="submit" id="submit_order" style="margin-top: 9px;" name="submit"
                                    class="btn btn-info" value="<?php echo getLange('submit'); ?>">
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Voucher No:</label>
                                            <input type="text" value="" class="form-control datetimepicker4" name="to"
                                                id="to">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" value="<?php echo date('Y-m-d'); ?>"
                                                class="form-control datetimepicker4" name="invoice_date" id="to">
                                        </div>
                                    </div>

                                </div>


                                <form action="" id="table_form_data">
                                    <table id="ledeger_datatable" cellpadding="0" cellspacing="0" border="0"
                                        class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed"
                                        role="grid" aria-describedby="basic-datatable_info">
                                        <div class="fake_loader" id="image" style="text-align: center;display:none;">
                                            <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                                        </div>
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="check_all"></th>
                                                <th>Client Code</th>
                                                <th>Client Name</th>
                                                <th>Previous Balance</th>
                                                <th>Total COD</th>
                                                <th>Returned COD</th>
                                                <th>Total Charges</th>
                                                <th>Fuel Surcharge</th>
                                                <th>GST</th>
                                                <th>Returned Fee</th>
                                                <th>Cash Handling Fee</th>
                                                <th>Flyer Sell</th>
                                                <th>Net Amount</th>
                                                <th>Total Payable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <div class="save_print_btn">
                                                        <button  id="save_details"
                                                            class="submit_order btn btn-primary submit_btns">Save</button>
                                                        <!-- <button></button> -->
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <form method="POST" id="bulk_submit" action="mrf_report.php">
            <input type="hidden" name="order_id" id="print_data">

            <input type="hidden" name="save_print">
        </form>



        <div class="paynow_overlay"></div>
        <div class="paynow_inner">
            <div class="close_btn">
                <i class="fa fa-close"></i>
            </div>
            <form action="" method="post">
                <input type="hidden" value="" name="customer_payment_id" class="paynow_customer_payment_id">
                <div class="formbox">
                    <label><span style="color: red;">*</span>Date</label>
                    <input type="test" readonly class="datetimepicker4 paynow_payment_date"
                        value="<?php echo date('Y-m-d'); ?>" name="payment_date" required>
                </div>
                <div class="formbox">
                    <label>Customer</label>
                    <input type="text" class="customer_name" value="" readonly>
                    <input type="hidden" value="" name="customer_id" class="paynow_customer_id">
                </div>
                <div class="formbox">
                    <label><span style="color: red;">*</span>Transaction ID</label>
                    <input type="text" value="" name="transaction_id" class="paynow_transaction_id" required>
                </div>
                <div class="formbox">
                    <label>Invoice No</label>
                    <input type="text" value="" name="invoice_no" class="paynow_invoice_no" readonly>
                </div>
                <div class="formbox">
                    <label><span style="color: red;">*</span>Amount</label>
                    <input type="mynumber" value="" name="amount" class="paynow_amount"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" /
                        required>
                    <input type="hidden" value="" class="paynow_amount_payable">
                </div>
                <div class="total_msg formbox" style="display: none;">
                </div>
                <div class="formbox">
                    <button type="submit" value="" name="payment_submit" class="payment_submit">Save</button>
                </div>
            </form>
        </div>


        <!-- Warper Ends Here (working area) -->
        <?php
        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>


        <script>
        function saveDetailsAjax(form_data) {
            $('#alerts').html("");
            let length = form_data.length;
            recursive_save_bulk_payment(form_data,0,length);
        }

        function recursive_save_bulk_payment(form_data,index,length) {
            if(index<length){
                var item=form_data[index];
                $.ajax({
                    url: "ajax_save_bulk_payments.php",
                    type: "POST",
                    data: {
                        item: item
                    },
                    success: function(response) {
                        $("#save_details").prop('disabled', false);
                        $("#save_details").text("Save");
                        var json = JSON.parse(response);
                        var msg = json.message;
                        var customer_id = json.customer_id;
                        if (json.status == 1) {
                            
                            $(`input[type='checkbox'].child_checkboxes[value=${customer_id}]`).parent().parent()
                                .remove();
                            var msg_html = `<div class="alert alert-success">${msg}</div>`;
                            $('#alerts').append(msg_html);
                            recursive_save_bulk_payment(form_data,++index,length);
                        }
                    }
                    
                });
            }
        }

        $(document).ready(() => {

            $('#save_details').on('click', function() {
                // let form_data=$('#table_form_data').serialize();
                // saveDetailsAjax(form_data);
                // let =[];
                $("#save_details").prop('disabled', true);
                $("#save_details").text("Saving....");
                let from_date = $('body').find('#from_date').val();
                let to_date = $('body').find('#to_date').val();
                let form_data = [];
                let checkboxes = $('tbody input[type="checkbox"]:checked');
                $(checkboxes).each(function(index, element) {
                    let customer_id = $(element).val();
                    let client_code = $(element).data('client_code');
                    let client_name = $(element).data('bname');
                    let prev_balance = $(element).data('prev_balance');
                    let total_cod = $(element).data('total_cod');
                    let total_returned_cod = $(element).data('total_returned_cod');
                    let total_charge = $(element).data('total_charge');
                    let total_fuel_surcharge = $(element).data('total_fuel_surcharge');
                    let total_gst = $(element).data('total_gst');
                    let total_returned_fee = $(element).data('total_returned_fee');
                    let total_cash_handling_fee = $(element).data('total_cash_handling_fee');
                    let total_flyer_sell = $(element).data('total_flyer_sell');
                    let total_net_amount = $(element).data('total_net_amount');
                    let total_payable = $(element).data('total_payable');
                    form_data.push({
                        customer_id: customer_id,
                        client_code: client_code,
                        client_name: client_name,
                        total_cod: total_cod,
                        prev_balance: prev_balance,
                        total_returned_cod: total_returned_cod,
                        total_charge: total_charge,
                        total_fuel_surcharge: total_fuel_surcharge,
                        total_gst: total_gst,
                        total_returned_fee: total_returned_fee,
                        total_cash_handling_fee: total_cash_handling_fee,
                        total_flyer_sell: total_flyer_sell,
                        total_net_amount: total_net_amount,
                        total_payable: total_payable,
                        from_date: from_date,
                        to_date: to_date
                    });
                });
                saveDetailsAjax(form_data);
            });


            $('#check_all').on('click', function() {
                if ($(this).prop('checked') == true) {
                    $('.child_checkboxes').prop('checked', true);
                } else {
                    $('.child_checkboxes').prop('checked', false);
                }
            });


            getBulkPaymentDetailsAjax();
            $('#filter_form').on('submit', function(e) {
                e.preventDefault();
                $('#alerts').html("");
                getBulkPaymentDetailsAjax();
            });
        });

        function getBulkPaymentDetailsAjax() {
            $.ajax({
                url: "ajax_bulk_payments.php",
                type: "POST",
                data: $('#filter_form').serialize(),
                beforeSend: function() {
                    $('.fake_loader').show();
                    $('#ledeger_datatable').hide();
                },
                complete: function() {
                    $('.fake_loader').hide();
                    $('#ledeger_datatable').show();
                },
                success: function(response) {
                    $('tbody').html('').html(response);
                }
            });
        }
        </script>