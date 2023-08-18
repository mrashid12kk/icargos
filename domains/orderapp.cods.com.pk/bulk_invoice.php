<?php
session_start();
// Get our helper functions
include_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);
$token = "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";
$shop = "it-vision-dev";
$array = '';

$get_pref = mysqli_query($con, "SELECT * FROM  preferences WHERE 1 ");
if (isset($_POST['save_print']) && !empty(json_decode($_POST['print_data']))) {
    $order_ids = json_decode($_POST['print_data']);
    $track_nos = implode(',', $order_ids);
}
if (mysqli_num_rows($get_pref) > 0 && !empty($track_nos)) {
    $pref_res = mysqli_fetch_array($get_pref);
    $auth_key = $pref_res['auth_key'];
    $url = COURIER_URL . 'API/Loadsheet.php?auth_key=' . $auth_key . '&search=' . $track_nos;
    // echo $url; exit();
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result);
    $response =  json_decode(json_encode($response), true);
    $customer_data = $response[0]['customer'];
    unset($response[0]);
}
?>
<!DOCTYPE html>
<!--<![endif]-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $cfg['lang_invoice'] ?> No. #<?php echo $info['id']; ?></title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900' rel='stylesheet'
        type='text/css'>
    <style>
    /* Default Font Styles
______________________*/
    html,
    body,
    div,
    span,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    q,
    em,
    img,
    small,
    strong,
    dl,
    dt,
    dd,
    ol,
    ul,
    li,
    fieldset,
    form,
    label,
    legend {
        border: 0;
        outline: 0;
        margin: 0;
        padding: 0;
        font-size: 100%;
        vertical-align: baseline;
        background: transparent
    }

    body {
        line-height: 1
    }

    ol,
    ul {
        list-style: none
    }

    :focus {
        outline: 0
    }

    input,
    textarea {
        margin: 0;
        outline: 0;
    }

    textarea {
        overflow: auto;
        resize: none;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0
    }

    /* End Reset */
    /* html5 */
    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    nav,
    section {
        display: block;
    }

    /* Default Font Styles
______________________*/
    body,
    input,
    select,
    textarea,
    p,
    a,
    b {
        font-family: 'Roboto', sans-serif;
        color: #000;
        line-height: 1.4;
    }

    *,
    *:after,
    *:before {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    p {
        font-size: 15px;
        margin-bottom: 0;
    }

    body,
    input,
    select,
    textarea,
    p,
    a,
    b {
        font-family: 'Roboto', sans-serif;
        color: #000;
        line-height: 1.4;
    }

    p {
        font-size: 15px;
        margin: 0;
    }


    .table_invoice table {
        border-collapse: collapse;
        width: 100%;
    }

    .table_invoice td,
    .table_invoice th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 0;
    }

    .barcode_images img {
        /*width: 101px;*/
        padding-top: 3px;
    }

    .table_invoice {
        padding: 0px 25px;
        width: 921px;
    }

    .table_invoice .logo {
        width: 9%;
        padding: 10px 12px;
    }

    .table_invoice .logo img {
        width: 66px;
    }

    .table_invoice .barcode {
        /*width: 24%;*/
        text-align: center;
        padding: 5px 14px 0 !important;
    }

    /*.table_invoice .barcode{
    text-align: center;
    width: 14%;
    padding: 0;
}*/
    /*.table_invoice .barcode img{
        width: 99px;
}*/
    .table_invoice .barcode p {
        font-size: 12px;
        margin-bottom: 0;
    }

    .table_invoice ul {
        padding: 0;
    }

    .table_invoice ul li {
        border-bottom: 1px solid #ccc;
        padding: 3px 11px;
        font-size: 12px;
        height: 24px;
    }

    .table_invoice ul li:last-child {
        border-bottom: none;
    }

    .date_info {
        width: 7.7%;
    }

    .shipper_Table {}

    .shipper_Table td,
    .address_Table td,
    .pieces_table td,
    .declared_table td,
    .product_table td {
        border-top: none;
        padding: 2px 10px;
        font-size: 12px;
    }

    .shipper_Table .width10 {
        width: 15.3%;
    }

    .shipper_Table .width21 {
        width: 21.7%;
    }

    p {
        font-size: 12px;
        margin-bottom: 0;
    }

    .shipper_Table .width27 {
        width: 24.6%;
    }

    .address_Table .width30 {
        width: 37%;
    }

    .pieces_table .logo {
        width: 15.4%;
    }

    .pieces_table .barcode {
        text-align: left;
        padding: 0 11px;
    }

    .empty_width {
        width: 24.5%;
    }

    .declared_table .logo {
        width: 21%;
    }

    .declared_table .barcode {
        width: 23%;
        text-align: left;
        padding: 0 11px;
    }

    .declared_table .date_info {
        width: 27.6%;
        text-align: center;
    }

    .declared_table .date_info img {
        width: 139px;
    }

    .product_table .logo {
        width: 1.6%;
    }

    .product_table .barcode {
        width: 14.3%;
        text-align: left;
        padding: 6px 12px;
    }

    .footer_Center tr td {
        text-align: center;
    }

    .footer_Center b {
        padding: 0 29px 5px;
        display: block;
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
        border-color: #46b8da;
        text-decoration: none;
    }

    .table_invoice {
        margin: 0px 0px;
        margin-top: 18px;
        /*height: 417px;*/
    }

    @media print {
        .table_invoice {
            page-break-inside: avoid;
        }
    }

    table,
    tr,
    td,
    li {
        border-color: black !important;
    }
    </style>
</head>

<body id="page-name">

    <div id="fb-root"></div>

    <?php

    if (!empty($response)) {

        foreach ($response as $key => $info) {



            if ($key % 3 == 0 && $key != 0)
                echo '</div>';
            if ($key % 3 == 0)
                echo '<div class="page-wrap">';


    ?>

    <div class="table_invoice" id="table_invoice">
        <table>
            <tr>
                <td class="logo" style="    padding: 5px 12px;"><img src="<?php echo  $info['logo'] ?>" alt=""></td>
                <td class="barcode">
                    <?php
                            if (isset($info['barcode_image'])) {
                                echo '<img  src="' . COURIER_URL . $info['barcode_image'] . '" />';
                                echo '<h2 style="text-align: center; font-size:10px;">' . $info['tracking_no'] . '</h2>';
                            }
                            ?>

                </td>
                <td class="date_info">
                    <ul>
                        <li><b>Date</b></li>
                        <li><b>Service </b></li>
                        <li><b>Origin</b></li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><?php echo date(date('Y-m-d'), strtotime($info['order_date'])); ?></li>
                        <li><?php
                                    if ($info['order_type'] == 'overlong') {
                                        echo 'Overland';
                                    } else {
                                        echo ucfirst($info['order_type']);
                                    }
                                    ?></li>
                        <li><?php if (isset($info['origin'])) {
                                        echo $info['origin'];
                                    } ?></li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><b>COD Amount:</b></li>
                        <li><b></b></li>
                        <li><b>Destination</b></li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><b style="font-size:14px;">Rs: <?php echo $info['collection_amount']; ?></b></li>
                        <li></li>
                        <li><?php if (isset($info['destination'])) {
                                        echo $info['destination'];
                                    } ?></li>
                    </ul>
                </td>
            </tr>
        </table>
        <table class="shipper_Table">
            <tr>
                <td class="width10" style="width: 50.4%; text-align:center; background-color:#e0e0e0;"><b>Shipper</b>
                </td>


                <td class="width27" style="width: 50%; text-align:center; background-color:#e0e0e0;">
                    <b>Consignee</b>
                </td>


            </tr>
        </table>
        <table class="address_Table">
            <tr>
                <td class="width50" style="width: 50.4%;padding: 0;    vertical-align: top;
">
                    <table>
                        <tr
                            style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                            <th style="border:none; padding: 3px 5px;    width: 30%;">Company Name:</th>
                            <td style="border:none; padding: 3px 5px;"><?php
                                                                                if (isset($info['sender_company']) and !empty($info['sender_company'])) echo $info['sender_company'];
                                                                                ?></td>
                        </tr>
                        <tr
                            style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                            <th style="border: none; padding: 3px 5px;">Phone No.:</th>
                            <td style="border: none; padding: 3px 5px;"><?php if (isset($info['sender_phone'])) {
                                                                                    echo $info['sender_phone'];
                                                                                } ?></td>
                        </tr>
                        <tr>
                            <th style="border: none; padding: 2px 5px;">Address:</th>
                            <td style="border: none; padding: 2px 5px;"><?php if (isset($info['sender_address'])) {
                                                                                    echo $info['sender_address'];
                                                                                } ?></td>
                        </tr>
                    </table>

                </td>
                <td style="padding: 0;vertical-align: top;">
                    <table>
                        <tr
                            style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                            <th style="border: none; padding: 3px 5px;">Name:</th>
                            <td style="border: 1px solid #000; padding: 6px 10px;"><?php if (isset($info['receiver_name'])) {
                                                                                                echo $info['receiver_name'];
                                                                                            } ?></td>
                        </tr>
                        <tr
                            style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                            <th style="border: none; padding: 3px 5px;">Phone No.:</th>
                            <td style="border: none; padding: 3px 5px;"><?php if (isset($info['receiver_phone'])) {
                                                                                    echo $info['receiver_phone'];
                                                                                } ?></td>
                        </tr>
                        <tr>
                            <th style="border: none; padding: 2px 5px;">Address:</th>
                            <td style="border: none; padding: 2px 5px;"><?php if (isset($info['receiver_address'])) {
                                                                                    echo $info['receiver_address'];
                                                                                } ?></td>
                        </tr>

                    </table>
                </td>

            </tr>
        </table>
        <table class="declared_table">

            <tr>
                <td style="padding: 0px; border: 0px;">
                    <table>
                        <tr>
                            <td class="logo">
                                <b>Customer Ref.#</b>
                            </td>
                            <td class="barcode" colspan="2">

                                <?php if (isset($info['customer_id'])) {
                                            echo $info['customer_id'] + 1000;
                                        } ?> </td>
                            <td class="barcode" colspan="1"><b>Order ID= </b> <?php if (isset($info['order_id'])) {
                                                                                            echo $info['order_id'];
                                                                                        } ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="logo">
                                <b>COD Amount</b>
                            </td>
                            <td class="barcode">
                                <b>Rs: <?php echo $info['collection_amount']; ?></b>
                            </td>
                            <td class="barcode">
                                <b>Weight = <?php if (isset($info['weight'])) {
                                                        echo $info['weight'];
                                                    } ?> Kg</b>
                            </td>

                            <td class="barcode">
                                <b>Quantity = <?php echo isset($info['quantity']) ? $info['quantity'] : '0'; ?></b>
                            </td>
                        </tr>
                    </table>
                </td>


                <td class="barcode barcode_images"
                    style="text-align: center;    padding: 0 !important;    border-left: none;">
                    <?php
                            if (isset($info['barcode_image'])) {
                                echo '<img  src="' . COURIER_URL . $info['barcode_image'] . '" />';
                                echo '<h2 style="text-align: center; font-size:10px;">' . $info['tracking_no'] . '</h2>';
                            }
                            ?>

                </td>
            </tr>
        </table>
        <table class="product_table">


            </tr>
            <tr>
                <td class="logo">
                    <b>Product Description:</b>
                </td>
                <td class="barcode" colspan="5">
                    <p><?php if (isset($info['product_descriptiption'])) {
                                    echo $info['product_descriptiption'];
                                } ?></p>
                </td>

            </tr>
            <tr>
                <td class="logo">
                    <b>Special Instruction</b>
                </td>
                <td class="barcode" colspan="5">
                    <p><?php if (isset($info['special_instruction'])) {
                                    echo $info['special_instruction'];
                                } ?></p>
                </td>

            </tr>
            <tr>
                <td colspan="6">
                    <?php if (isset($info['second_new_footer'])) {
                                echo $info['second_new_footer'];
                            } ?>
                </td>
            </tr>
            <tr>

                </td>
            </tr>
        </table>

    </div>


    <?php
        } //loop close

    }


    ?>
    <script type="text/javascript">
    window.print();
    setTimeout(function() {
        window.close();
    }, 2000);
    </script>
</body>

</html>