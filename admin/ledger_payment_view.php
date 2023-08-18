<?php
session_start();
require 'includes/conn.php';
$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
$logo_img = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='logo' "));
$total_gst = mysqli_fetch_array($gst_query);
function formate_value($value = null)
{
    if ($value != null) {
        return number_format($value, 2);
    }
    return 0;
}
if (isset($_SESSION) && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    $query1 = mysqli_query($con, "SELECT customer_ledger_payments.*, customers.fname as customer  FROM customer_ledger_payments LEFT JOIN customers ON (customers.id = customer_ledger_payments.customer_id) where customer_ledger_payments.id = '" . $payment_id . "' order by id DESC");
    $query2_f = mysqli_query($con, "SELECT customer_ledger_payments.*, customers.fname as customer  FROM customer_ledger_payments LEFT JOIN customers ON (customers.id = customer_ledger_payments.customer_id) where customer_ledger_payments.id = '" . $payment_id . "' order by id DESC");
    //geting customer data
    $customer_is     = 0;
    $reference_no    = 0;
    $cod_amount      = 0;
    $total_payable   = 0;
    $gst_amount      = 0;
    $delivery_charges = 0;
    $returned_amount = 0;
    $total_returned_fee = 0;
    $sell_flyers_amount = 0;
    $query2_c = mysqli_query($con, "SELECT * FROM customer_ledger_payments  where id = '" . $payment_id . "' order by id DESC");
    $fetch2_c = mysqli_fetch_array($query2_c);

    $customer_is   = $fetch2_c['customer_id'];
    $reference_no  = $fetch2_c['reference_no'];
    $cod_amount    = $fetch2_c['cod_amount'];
    $total_payable = $fetch2_c['total_payable'];
    $gst_amount    = $fetch2_c['gst_amount'];
    $delivery_charges = $fetch2_c['delivery_charges'];
    $returned_amount = $fetch2_c['returned_amount'];
    $total_returned_fee = $fetch2_c['total_returned_fee'];
    $sell_flyers_amount = $fetch2_c['sell_flyers_amount'];
    $cash_handling = $fetch2_c['cash_handling'];
    $grand_total_charges = isset($fetch2_c['total_charges']) ? $fetch2_c['total_charges'] : 0;
    $grand_total_fuelSurcharge = isset($fetch2_c['fuel_surcharge']) ? $fetch2_c['fuel_surcharge'] : 0;
    $grand_total_net_amount = isset($fetch2_c['net_amount']) ? $fetch2_c['net_amount'] : 0;

    function getPaymentDate($ledger_id)
    {
        $data = "";
        global $con;
        $sql = "SELECT payment_date FROM  customer_ledger_payments_detail where customer_payment_id  = '" . $ledger_id . "'  ";
        $query_order = mysqli_query($con, $sql);
        $row_data = mysqli_fetch_array($query_order);
        return isset($row_data['payment_date']) ? $row_data['payment_date'] :'';
    }
    function getLedgerOrder($ledger_id)
    {
        $data = "";
        global $con;
        $sql = "SELECT * FROM orders   where id  = '" . $ledger_id . "'  ";
        $query_order = mysqli_query($con, $sql);
        while ($row_data = mysqli_fetch_array($query_order)) {
            $data = $row_data;
        }
        return $data;
    }
    function getFlyerOrder($flyer)
    {
        $data = "";
        global $con;
        $sql = "SELECT * FROM flayer_order_index   where id  = '" . $flyer . "'  ";
        $query_order = mysqli_query($con, $sql);
        while ($row_data = mysqli_fetch_array($query_order)) {
            $data = $row_data;
        }
        return $data;
    }
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
    function getCustomer($customer_id)
    {
        $data = "";
        global $con;
        $sql = "SELECT * FROM customers   where id  = '" . $customer_id . "'  ";
        $query_order = mysqli_query($con, $sql);
        while ($row_data = mysqli_fetch_array($query_order)) {
            $data = $row_data;
        }
        return $data;
    }
    $customerData = getCustomer($customer_is);
    // function getBarCodeImage($text = '', $code = null, $index)
    // {
    // 	require_once('../includes/BarCode.php');
    // 	$barcode = new BarCode();
    // 	$path = '../assets/barcodes/imagetemp'.$index.'.png';
    // 	$barcode->barcode($path, $text);
    // 	$folder_path='assets/barcodes/imagetemp'.$index.'.png';
    // 	return $folder_path;
    // }
    // $path = getBarCodeImage(sprintf('%06d', $payment_id),null,$payment_id);

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Ledger Payment Invoice</title>
        <style>
            body {
                font-family: 'Open Sans', sans-serif;
            }

            .table_wrapper,
            .wrapper_table_2,
            .table_tree {
                max-width: 86%;
                margin: 50px auto;
            }

            .table_wrapper table {
                border-collapse: collapse;
                width: 74.5%;
                float: left;
                margin-bottom: 15px;
            }

            h3,
            .table_wrapper table {
                font-family: 'Open Sans', sans-serif;
            }

            .table_wrapper tr th:nth-child(1) {
                text-align: left;
                font-weight: 800;
                font-size: 13px;
                background: #e8e8e8;
                -webkit-print-color-adjust: exact !important;
            }

            .table_wrapper tr th:nth-child(2) {
                background: #ebebeb;
                -webkit-print-color-adjust: exact !important;
            }

            .table_wrapper th:nth-child(3) {
                background: #ebebeb;
                -webkit-print-color-adjust: exact !important;
                font-size: 16px;
                font-weight: 300;
            }

            .table_wrapper td {
                font-size: 13px;
                border: 1px solid #08242c;
                text-align: left;
                padding: 3px 10px;
                -webkit-print-color-adjust: exact !important;
                background: #e8e8e8;
            }

            .costa_box {
                text-align: center;
                padding-bottom: 33px;
            }

            .costa_box a {
                text-decoration: none;
                color: #000;
            }

            .costa_box img {
                display: block;
                margin: 0 auto 15px;
            }


            .table_wrapper td:last-child {
                -webkit-print-color-adjust: exact !important;
                background: #fff;
            }

            .table_wrapper th {
                border: 1px solid #08242c;
                text-align: left;
                font-size: 12px;
                padding: 3px 10px;
            }

            .table_wrapper .table_height {}

            .table_tow {
                width: 25.4% !important;
            }

            .table_tow th {
                -webkit-print-color-adjust: exact !important;
                background: #ebebeb;
                font-size: 16px;
                font-weight: 300;
                padding: 10px 80px;
                text-align: center;
                border-left: none;
            }

            .table_tow td {
                border-left: none;
                height: 193.5px;
                text-align: center;
            }

            .wrapper_table_2 table {
                margin-top: 6px;
                width: 87.3%;
                font-family: 'Open Sans', sans-serif;
                border-collapse: collapse;
                float: left;
            }

            .wrapper_table_2 table th {
                -webkit-print-color-adjust: exact !important;
                background: #e8e8e8;
                border: 1px solid #08242c;
                font-size: 12px;
                padding: 10px 12.2px;
                text-align: center;
                font-weight: bold;
            }

            .wrapper_table_2 table td {
                border: 1px solid #08242c;
                font-size: 14px;
                text-align: center;
                padding: 5px 10px;
            }

            .table_tree table {
                /* margin-top: 25px;
          font-family: 'Open Sans', sans-serif;
          border-collapse: collapse;
          width: 50%;
          float: left;*/
          font-family: 'Open Sans', sans-serif;
          border-collapse: collapse;
          width: 50%;
          margin: 0 auto 0;
          clear: both;
      }

      .table_tree table th {
        -webkit-print-color-adjust: exact !important;
        background: #e8e8e8;
        border: 1px solid #08242c;
        font-size: 13px;
        text-align: left;
        padding: 4px 10px;
    }

            /*.table_tree table th:last-child{
        border-left: none;
        }*/
        .table_tree table td {
            border: 1px solid #08242c;
            font-size: 13px;
            text-align: left;
            padding: 5px 10px;
        }

        .table_tree table td:first-child {
            -webkit-print-color-adjust: exact !important;
            background: #e8e8e8;
        }

        .print_btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #5bc0de;
            -webkit-print-color-adjust: exact !important;
            border-color: #46b8da;
            text-decoration: none;
        }


        #costa_logo {
            width: 19.6% !important;
        }

        #costa_logo img {
            width: 150px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        @media print {
            table {
                page-break-after: always
            }

            @page {
                margin: 5px 10px;
                padding: 0px;
            }

            .table_wrapper,
            .wrapper_table_2,
            .table_tree {
                max-width: 98%;
                margin: 18px auto;
            }

            .table_wrapper table {
                width: 72.9%;
            }

            .wrapper_table_2 table th {
                font-size: 11px;
                padding: 4px 0.2px;
            }

            .wrapper_table_2 table td {
                font-size: 12px;
                padding: 4px 3px;
            }

            .table_tree table td {
                font-size: 12px;
                padding: 4px 6px;
            }

            .table_tow td {
                border-left: 1px solid #08242c;
            }

            .table_wrapper table {
                border-collapse: collapse;
                width: 74%;
                float: left;
                margin-bottom: 15px;
            }

        }
    </style>
</head>

<body>
    <?php $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
    <?php if (!isset($_GET['print'])) { ?>
        <a href="<?php echo $url ?>&print=1" class="print_btn btn btn-info" target="_blank"><?php echo getLange('print'); ?></a>
        <button class="print_btn btn btn-info send_email_to_customer" >Send Email To Customer</button>
        <div class="pdf_msg"></div>
    <?php } else { ?>
        <script type="text/javascript">
            window.print();
        </script>
    <?php } ?>

    <div class="pdf_copy">
        <div class="table_wrapper">
            <table>
                <tr>
                    <input type="hidden" value="<?php echo $customerData['email'] ?>" class="customer_email">
                    <th colspan="2"><b><?php echo getLange('paymentdetail'); ?></b></th>
                </tr>
                <tr>
                    <td><b><?php echo getLange('clientcode'); ?></b></td>
                    <td><?= $customerData['client_code'] ?></td>
                </tr>
                <tr>
                    <td><b>Invoice Date</b></td>
                    <td><?php echo date('Y-m-d', strtotime($fetch2_c['payment_date'])); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('paymentdate'); ?></b></td>
                    <td><?php echo getPaymentDate($fetch2_c['id']); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('client'); ?></b></td>
                    <td><?php echo $customerData['bname']; ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('clientbank'); ?></b></td>
                    <td><?= $customerData['bank_name']; ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('accountitle'); ?></b></td>
                    <td><?= $customerData['acc_title']; ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('iban'); ?></b></td>
                    <td><?= $customerData['iban_no']; ?></td>
                </tr>

                <tr>
                    <td><b><?php echo getLange('invoiceno'); ?></b></td>
                    <td><?= $reference_no ?></td>
                </tr>

            </table>
            <table class="table_tow">

                <tr>
                    <td><img src="<?php echo $logo_img['value'] ?>" width="150px;"></td>
                </tr>
            </table>
        </div>
        <?php
        $countrow = mysqli_num_rows($query1);
        ?>
        <div class="wrapper_table_2">
            <h3 style="clear: both;margin: 0;    padding-top: 6px;">Order's Information</h3>
            <table style=" width: 100%;margin-right: 10%;margin-bottom: 32px;    margin-top: 5px;">
                <tr>
                    <th><?php echo getLange('srno'); ?>.</th>
                    <th><?php echo getLange('trackingno'); ?>.</th>
                    <th><?php echo getLange('deliveryname'); ?></th>
                    <th><?php echo getLange('deliveryphone'); ?></th>
                    <th><?php echo getLange('deliverycity'); ?></th>
                    <th><?php echo getLange('orderdate'); ?></th>
                    <th><?php echo getLange('orderid'); ?></th>
                    <th><?php echo getLange('weightkg'); ?></th>
                    <th><?php echo getLange('codamount'); ?></th>
                    <th><?php echo getLange('deliverycharges'); ?></th>
                    <th><?php echo getLange('fuelsurcharge'); ?></th>
                    <th><?php echo getLange('gst'); ?></th>
                    <th><?php echo getLange('netamount'); ?></th>
                    <th><?php echo getLange('status') ?></th>
                </tr>
                <?php
                $col_t = 0;
                $del_t = 0;
                $weight = 0;
                $total_special_charges = 0;
                $total_extra_charges = 0;
                $total_insurance = 0;
                $total_charges = 0;
                $total_fuel = 0;
                $total_gst = 0;
                $total_net_amount = 0;
                $sr = 1;
                while ($fetch1 = mysqli_fetch_array($query1)) {

                    $ordernumber = explode(",", $fetch1['ledger_orders']);

                    for ($i = 0; $i < count($ordernumber); $i++) {
                        $oderData =    getLedgerOrder($ordernumber[$i]);
                        if (!empty($oderData)) {
                            ?>
                            <tr>
                                <td><?php echo $sr; ?></td>
                                <td><?php echo $oderData['track_no']; ?></td>
                                <td><?php echo $oderData['rname']; ?> </td>
                                <td><?php echo $oderData['rphone']; ?></td>
                                <td><?php echo $oderData['destination']; ?></td>

                                <td><?php echo date('d/m/Y', strtotime($oderData['order_date'])); ?></td>

                                <td><?php echo $oderData['product_id']; ?></td>
                                <td><?php echo $oderData['weight']; ?></td>
                                <td><?php echo $oderData['collection_amount']; ?></td>
                                <td>
                                    <?php echo isset($oderData['grand_total_charges']) ? $oderData['grand_total_charges'] : 0; ?>
                                </td>
                                <td>
                                    <?php echo isset($oderData['fuel_surcharge']) ? $oderData['fuel_surcharge'] : 0; ?>
                                </td>
                                <td>
                                    <?php echo isset($oderData['pft_amount']) ? $oderData['pft_amount'] : 0; ?>
                                </td>
                                <td>
                                    <?php echo isset($oderData['net_amount']) ? $oderData['net_amount'] : 0; ?>
                                </td>
                                <td><?= ucfirst($oderData['status']); ?></td>
                                <?php
                                $total_special_charges += $oderData['special_charges'];
                                $total_extra_charges += $oderData['extra_charges'];
                                $total_insurance += $oderData['insured_premium'];
                                $total_charges += $oderData['grand_total_charges'];
                                $total_fuel += $oderData['fuel_surcharge'];
                                $total_gst += $oderData['pft_amount'];
                                $total_net_amount += $oderData['net_amount'];
                                ?>
                            </tr>
                            <?php
                            $sr++;
                            $col_t = $oderData['collection_amount'] + $col_t;
                            $weight = (float)($oderData['weight'] + $weight);
                        }
                    }
                }
                ?>
                <tr>
                    <td colspan="6"></td>
                    <td style="background: #c8c8c8;"><b>Total</b></td>
                    <td style="background: #e8e8e8;"><b><?php echo $weight; ?></b></td>
                    <td style="background: #e8e8e8;"><b><?php echo formate_value($col_t); ?></b></td>
                    <td style="background: #e8e8e8;"><b><?php echo formate_value($total_charges); ?></b></td>
                    <td style="background: #e8e8e8;"><b><?php echo formate_value($total_fuel); ?></b></td>
                    <td style="background: #e8e8e8;"><b><?php echo formate_value($total_gst); ?></b></td>
                    <td style="background: #e8e8e8;"><b><?php echo formate_value($total_net_amount); ?></b></td>
                    <td> </td>
                </tr>
            </table>
        </div>
        <?php if ($fetch2_c['ledger_flyers'] != '') : ?>
            <div class="wrapper_table_2">
                <h3 style="clear: both;margin: 0;">Flyer's Information</h3>
                <table style="    margin-right: 10px;    margin-top: 5px;">
                    <tr>
                        <th><?php echo getLange('srno'); ?>.</th>
                        <th><?php echo getLange('invoiceno'); ?></th>
                        <th><?php echo getLange('date'); ?></th>
                        <th><?php echo getLange('description'); ?></th>
                        <th><?php echo getLange('totalamount'); ?></th>

                    </tr>
                    <?php
                    $total_f = 0;
                    $sr = 1;
                    while ($fetch_flyer = mysqli_fetch_array($query2_f)) {
                        $flyer_ids =  explode(",", $fetch_flyer['ledger_flyers']);
                        for ($j = 0; $j < count($flyer_ids); $j++) {
                            $flyerData =    getFlyerOrder($flyer_ids[$j]);

                            if (!empty($flyerData)) {
                                $flayer_order_query = mysqli_query($con, "SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=" . $flyerData['id'] . " ");

                                $total = getTotal($flyerData['id']);
                                $total_f = $total + $total_f;
                                ?>
                                <tr>
                                    <td><?= $sr; ?></td>
                                    <td><?= sprintf("%04d", $flyerData['id']); ?></td>
                                    <td><?= $flyerData['order_date']; ?></td>
                                    <td>
                                        <?php
                                        while ($rec2 = mysqli_fetch_array($flayer_order_query)) {
                                            ?>
                                            <p><b>Flayer: </b><?php echo $rec2['flayer_name']; ?>, <b>Qty: </b><?php echo $rec2['qty']; ?></p>
                                        <?php } ?>

                                    </td>
                                    <td><?= $total; ?></td>
                                </tr>
                                <?php
                                $sr++;
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="3"></td>
                        <td style="    background: #c8c8c8;"><b><?php echo getLange('total'); ?></b></td>
                        <td style="    background: #e8e8e8;"><b><?= $total_f; ?></b></td>

                    </tr>
                </table>
            </div>
        <?php endif; ?>
        <div class="table_tree">
            <h3 style="clear: both;text-align: center;margin: 0 0 8px;padding-top: 6px;">Payment Summary</h3>
            <table style="margin-bottom: 15px;">
                <tr>
                    <th style="width: 68%;" colspan="2"><?php echo getLange('chargessummary'); ?>
                    (<?php echo getConfig('currency'); ?>)</th>

                </tr>

                <tr>
                    <td><b><?php echo getLange('prevbalance'); ?></b></td>
                    <td><?php echo formate_value($fetch2_c['prev_balance_history']); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('totalcod'); ?> </b></td>
                    <td><?php echo formate_value($cod_amount) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('returnocod'); ?></b></td>
                    <td><?php echo formate_value($returned_amount) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('totalcharges'); ?></b></td>
                    <td><?php echo formate_value($grand_total_charges) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('fuelsurcharge'); ?></b></td>
                    <td><?php echo formate_value($grand_total_fuelSurcharge) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('gst'); ?></b></td>
                    <td><?php echo formate_value($gst_amount) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('returnedfeeperparcel'); ?></b></td>
                    <td><?php echo formate_value($total_returned_fee) ?></td>
                </tr>

                <tr>
                    <td><b><?php echo getLange('cashhandlingfee'); ?> (<?php echo getConfig('cash_handling'); ?>%)</b></td>
                    <td><?php echo formate_value($cash_handling) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('flyersell'); ?></b></td>
                    <td><?php echo formate_value($sell_flyers_amount) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('netamount'); ?></b></td>
                    <td><?php echo formate_value($grand_total_net_amount) ?></td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('totalpayable'); ?> </b></td>
                    <td style="font-weight: 600;font-size: 16px;"><?php echo formate_value($total_payable, 0); ?> </td>
                </tr>
                <tr>
                    <td><b><?php echo getLange('payment'); ?> </b></td>
                    <td style="font-weight: 600;font-size: 16px;"><?php echo formate_value($fetch2_c['total_paid'], 0); ?>
                </td>
            </tr>
            <tr>
                <td><b><?php echo getLange('balance'); ?> </b></td>
                <td style="font-weight: 600;font-size: 16px;">
                    <?php echo formate_value((float)$total_payable - (float)$fetch2_c['total_paid']); ?> </td>
                </tr>

            </table>
        </div>
    </div>
</body>

</html>
<?php
} else {
    header("location:index.php");
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $('body').on('click', '.send_email_to_customer', function() {
       $('.send_email_to_customer').prop("disabled",false);
       var data=$(".pdf_copy").html();
       var customer_email=$(".customer_email").val();
       $('.pdf_msg').html('');
       $.ajax({
        type:'POST',
        url:'send_pdf.php',
        data:{data:data,customer_email:customer_email},
        beforeSend: function() {
            $('.send_email_to_customer').html('Processing');
            // $('.send_email_to_customer').prop('disabled',true);
        },
        success:function(response){
            if (response=='true') {
                $('.send_email_to_customer').html('Send Email To Customer');
                $('.pdf_msg').html('<div class="alert alert-success">Email Send Successfully</div>');
            }
            if (response=='false') {
                $('.send_email_to_customer').html('Send Email To Customer');
                $('.pdf_msg').html('<div class="alert alert-danger">Email Not Send Successfully</div>');
            }
            $('.send_email_to_customer').prop('disabled',false);
        }
    });
   });
</script>