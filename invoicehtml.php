<?php
date_default_timezone_set("Asia/Karachi");
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
include_once "includes/conn.php";
if (!isset($_SESSION))
  session_start();
$webtitle = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='companyname' "));
$res = mysqli_query($con, "select * from config") or die(mysqli_error($con));
while ($row = mysqli_fetch_array($res)) {
  $cfg[$row['name']] = $row['value'];
}
function encrypt($string)
{
  $key = "usmannnn";
  $result = '';
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) + ord($keychar));
    $result .= $char;
  }
  return base64_encode($result);
}
function decrypt($string)
{
  $key = "usmannnn";
  $result = '';
  $string = base64_decode($string);
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) - ord($keychar));
    $result .= $char;
  }
  return $result;
}
$ex = explode('-', decrypt($_GET['id']));
$id_invoice = $ex[0];
$res = mysqli_query($con, "select * from orders where id='" . $id_invoice . "'") or die(mysqli_error($con));
$info = mysqli_fetch_array($res);
$order_type_id = $info['order_type'];
$get_service = mysqli_query($con, "SELECT service_type FROM services WHERE id='" . $order_type_id . "' ");
$servie_q_res = mysqli_fetch_array($get_service);
$service = $servie_q_res['service_type'];
// echo "<pre>"; print_r($info); exit();
if (isset($info['id'])) {
  $deliver = mysqli_query($con, "SELECT * FROM deliver WHERE order_id = " . $info['id']);
  $deliver = ($deliver) ? mysqli_fetch_object($deliver) : null;
  if (isset($deliver->driver_id)) {
    $driver = mysqli_query($con, "SELECT * FROM users WHERE id = " . $deliver->driver_id);
    $driver = ($driver) ? mysqli_fetch_object($driver) : null;
  }
}

if (isset($info['branch_id']) && $info['branch_id'] != null) {
  $branch = mysqli_query($con, "SELECT * FROM branches WHERE id = " . $info['branch_id']);
  $branch = mysqli_fetch_object($branch);
}
function getCustomerBus($customer_id)
{
  global $con;
  $sql = "SELECT * FROM  customers WHERE id='" . $customer_id . "'";

  $res = mysqli_query($con, $sql) or die(mysqli_error($con));
  $cusdata = mysqli_fetch_array($res);
  return $cusdata;
}
$logo_img = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='logo' "));
$invoicefooter = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='invoicefooter' "));
$currency = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='currency' "));
$print   = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='print' "));

$data_cus = getCustomerBus($info['select']);
if (!function_exists('chargedefine')) {
  function chargedefine($id = null)
  {
    global $con;
    if ($id) {
      $query = mysqli_query($con, "SELECT * from charges where id=" . $id);
      while ($resposne = mysqli_fetch_assoc($query)) {
        $result = $resposne['charge_name'];
      }
      return  $result;
    }
  }
}
?>
<!DOCTYPE html>
<?php
$track_no='';
 if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $invoice_id = explode(',', $_GET['order_id']);
    foreach ($invoice_id as $key=>$value) {
        $track_no_q=mysqli_fetch_assoc(mysqli_query($con,"SELECT track_no from orders WHERE id=".$value));
        $track_no.=$track_no_q['track_no'].',';
    }
}
$track_no=trim($track_no , ',');
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $cfg['lang_invoice'] ?> No. #<?php echo $track_no; ?></title>
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
        vertical-align: top;
    }

    .barcode_images img {
        /*width: 101px;*/
        padding-top: 3px;
    }

    .table_invoice {
        padding: 0px 0 0 25px;
        width: 867px;
        float: left;
    }

    #charges_box {
        float: left;
        width: 190px;
        padding: 18px 0 0 1px;
        margin-left: -2px;
    }

    .table_invoice .logo {
        width: 9%;
        padding: 5px 4px;
    }

    /*.table_invoice .logo img{
        width: 95px;
}*/
    .table_invoice .barcode {
        /*width: 24%;*/
        text-align: center;
        padding: 3px 6px 0 !important;
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
        padding: 3px 4px;
        font-size: 11px;
        height: 24px;
        line-height: 1.3;
    }

    .table_invoice ul li:last-child {
        border-bottom: none;
    }

    .date_info {
        width: 9.7%;
    }

    #charges_box .shipper_Table {
        height: 424px;
    }

    .shipper_Table td,
    .address_Table td,
    .pieces_table td,
    .declared_table td,
    .product_table td {
        border-top: none;
        padding: 2px 4px;
        font-size: 13px;
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
        width: 17%;
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
        width: 146px;
        background-color: #c8c8c8;
        -webkit-print-color-adjust: exact !important;
    }

    .product_table .barcode {
        width: 587px;
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
        -webkit-print-color-adjust: exact !important;
        border-color: #46b8da;
        text-decoration: none;
    }

    .table_invoice {
        margin: 0px 0px;
        margin-top: 6px;
        /*height: 417px;*/
    }

    #charges_box table {
        width: 100% !important;
    }

    #charges_box tr th {
        font-size: 14px;
        padding: 7px 9px;
        border: 1px solid #000;
    }

    #charges_box tr td {
        border: 1px solid #000;
        padding: 10px 7px;
    }


    @media print {
        @page {
            margin: 10px 0;
        }

        .table_invoice {
            page-break-inside: avoid;
        }

        .barcode {
            padding-top: 5px;
            vertical-align: middle;
        }

        .barcode img {
            margin: 0 auto;
        }

        .logo img {
            width: 95px !important;
        }

        .barcode h2 {
            font-size: 12px !important;
        }

        .table_invoice {
            width: 795px;
        }

        #charges_box {
            width: 70px;
            margin-left: 2px;
        }

        #charges_box tr th {
            padding: 5px 3px;
        }

        .table_invoice .barcode {
            padding: 3px 3px 0 !important;
        }

        #charges_box tr td b {
            font-size: 11px !important;
        }

        #charges_box tr td {
            padding: 5px 6px;
            font-size: 11px !important;
        }

        #charges_box .shipper_Table {
            height: 440px;
        }

        .table_invoice .logo {
            padding: 2px 4px !important;
        }

        /* #para_graph h6 {
            height: 16px;
            overflow: hidden;
        }*/

        .shipper_Table td,
        .address_Table td,
        .pieces_table td,
        .declared_table td,
        .product_table td {
            padding: 2px 10px;
        }

        #shipper_Table td {
            border-top: 1px solid #000;
        }

        #shipper_Table:nth-child(3) td {
            border: none !important;
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
    <?php
  function getCopyName($id)
  {
    global $con;
    $sql = "SELECT * FROM invoice_name WHERE id  = " . $id;
    $query_order = mysqli_query($con, $sql);
    $data = mysqli_fetch_array($query_order);
    if (isset($_GET['booking']) && $_GET['booking'] == 1) {
      return isset($data['name']) ? getLange($data['name']) : getLange('shippercopy');
    } else {
      return getLange('shippercopy');
    }
  }
  function getOrderDetail($order_id)
  {
    $data = "";
    global $con;
    $sql = "SELECT * FROM orders   WHERE id  = '" . $order_id . "'  ";
    $query_order = mysqli_query($con, $sql);
    $data = mysqli_fetch_array($query_order);
    return $data;
  }
  $order_detail = getOrderDetail($order_id);

  function getOrderCharges($order_id)
  {
    $data = "";
    global $con;
    $sql = "SELECT * FROM order_charges WHERE order_id  = '" . $order_id . "'  ";
    $query_order = mysqli_query($con, $sql);
    $data = [];
    if (isset($query_order) && !empty($query_order)) {
      while ($row = mysqli_fetch_array($query_order)) {
        if (isset($row['charges_amount']) && $row['charges_amount'] > 0) {
          $data[$row['charges_id']] = $row;
        }
      }
      unset($data[count($data) - 1]);
    }
    return $data;
  }
  function getCustomer($customer_id)
  {
    $cust_detail = "";
    global $con;
    $sql = "SELECT * FROM customers   WHERE id  = '" . $customer_id . "'  ";
    $query_order_cus = mysqli_query($con, $sql);
    $cust_detail = mysqli_fetch_array($query_order_cus);
    return $cust_detail;
  }

  if (!isset($_GET['print'])) { ?>
    <a href="<?php echo $url ?>&print=1" class="print_btn" target="_blank">Print</a>
    <?php } else { ?>
    <script type="text/javascript">
    window.print();
    </script>
    <?php } ?>
    <div id="fb-root"></div>
    <script>
    /* Facebook Setup */
    window.fbAsyncInit = function() {
        FB.init({
            appId: '928362647290774',
            xfbml: true,
            version: 'v2.7'
        });
    };
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function share() {
        FB.ui({
            method: 'share',
            display: 'popup',
            href: ''
        }, function(response) {
            if (response) {
                var postdata = "action=fbshare&id=<?php echo $id_invoice; ?>";
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: postdata,
                    url: 'ajax.php',
                    success: function(fetch) {
                        if (fetch[0]) {
                            window.location.href = window.location;
                        } else {
                            alert('your package already has been discounted');
                        }
                    }
                });

            } else {
                alert('you did not share your receipt at that moment,please try again later');
            }
        });
    }
    /* twitter Setup */
    window.twttr = (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function(f) {
            t._e.push(f);
        };

        return t;
    }(document, "script", "twitter-wjs"));
    twttr.ready(function(twttr) {
        twttr.events.bind('tweet', function(event) {
            if (event) {}
        });
    });
    </script>
    <div class="page-wrap">
        <?php
    if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {

      if (!$_GET['booking']) {
        $invoice_ids = explode(',', $_GET['order_id']);
      } else {
        $invoice_ids = array();

        $invoice = $_GET['order_id'];

        $count = getConfig('print');

        for ($i = 0; $i < $count; $i++) {
          array_push($invoice_ids, $invoice);
        }
      }

      $invoice_print = 1;

      foreach ($invoice_ids as $key => $id_invoice) {
        $info = getOrderDetail($id_invoice);
        $customer_id = isset($info['customer_id']) ? $info['customer_id'] : '';
        $customerData = getCustomer($customer_id);
        $order_type_id = $info['order_type'];
        $get_service = mysqli_query($con, "SELECT service_type FROM services WHERE id='" . $order_type_id . "' ");
        $servie_q_res = mysqli_fetch_array($get_service);
        $service = $servie_q_res['service_type'];

    ?>
        <div class="table_invoice" id="table_invoice">
            <table>
                <tr>
                    <td class="logo" style="padding: 7px 7px;vertical-align: middle;"><img
                            src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt=""
                            style="width: auto; max-height:59px;"></td>
                    <td class="barcode" style="width: 25%  ;">
                        <?php
                if (isset($info['barcode_image'])) {
                  echo '<img  src="' . $info['barcode_image'] . '" />';
                  echo '<h2 style="text-align: center; font-size:15px;">' . $info['barcode'] . '</h2>';
                }
                ?>

                    </td>
                    <td class="date_info" style="background: #c8c8c8;-webkit-print-color-adjust: exact !important;">
                        <ul>
                            <li><b><?php echo getLange('date'); ?></b></li>
                            <li><b><?php echo getLange('Services'); ?> </b></li>
                            <li><b><?php echo getLange('origin'); ?></b></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><?php echo date(DATE_FORMAT, strtotime($info['order_date'])); ?></li>
                            <li><?php echo isset($service) ? $service :  ''; ?></li>
                            <li><?php if (isset($info['origin'])) {
                        echo $info['origin'];
                      } ?></li>
                        </ul>
                    </td>
                    <td style="background: #c8c8c8;-webkit-print-color-adjust: exact !important;">
                        <ul>
                            <li><b><?php echo getLange('weight'); ?> : </b></li>
                            <li><b><b>Booking Type : </b> </b></li>
                            <li><b><?php echo getLange('destination'); ?></b></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><?php if (isset($info['cweight'])) {
                                 echo $info['cweight'];
                           } ?> Kg</li>
                            <!-- <li><?php echo getCopyName($invoice_print); ?></li> -->
                            <li>
                                <?php if (isset($info['booking_type']) && $info['booking_type']==1) {
                        echo 'Invoice';
                      }if (isset($info['booking_type']) && $info['booking_type']==2) {
                        echo 'Cash';
                      }if (isset($info['booking_type']) && $info['booking_type']==3) {
                        echo 'To Pay';
                      } ?></li>
                            <li><?php if (isset($info['destination'])) {
                        echo $info['destination'];
                      } ?></li>
                        </ul>
                    </td>
                </tr>
            </table>
            <table class="shipper_Table" id="shipper_Table">
                <tr>
                    <td class="width10"
                        style="width:357px; text-align:center; background-color:#c8c8c8;-webkit-print-color-adjust: exact !important;">
                        <b><?php echo getLange('shipper'); ?></b>
                    </td>


                    <td class="width27"
                        style="width: 341px; text-align:center; background-color:#c8c8c8;-webkit-print-color-adjust: exact !important;">
                        <b><?php echo getLange('consignee'); ?></b>
                    </td>


                </tr>
            </table>

            <table class="address_Table">
                <tr>
                    <td
                        style="    width: 15.3%;-webkit-print-color-adjust: exact !important; padding: 3px 5px;background: #ebebeb;">
                        <?php echo getLange('company'); ?>:</td>
                    <td><?php echo isset($customerData['bname']) ? $customerData['bname'] : $info['sname'];
                        ?>
                    </td>
                    <td style="-webkit-print-color-adjust: exact !important; padding: 3px 5px;background: #ebebeb;">
                        <?php echo getLange('name'); ?>:</td>
                    <td><?php if (isset($info['rname'])) {
                          echo $info['rname'];
                        } ?>
                    </td>
                </tr>

                <tr>
                    <td
                        style="     width: 15%;-webkit-print-color-adjust: exact !important; padding: 3px 5px;background: #ebebeb;">
                        <?php echo getLange('phoneno'); ?>:</td>
                    <td><?php if (isset($info['sphone'])) {
                          echo $info['sphone'];
                        } ?>
                    </td>
                    <td style="-webkit-print-color-adjust: exact !important; padding: 3px 5px;background: #ebebeb;">
                        <?php echo getLange('phoneno'); ?> :</td>
                    <td><?php if (isset($info['rphone'])) {
                      echo $info['rphone'];
                    } ?>
                    </td>
                </tr>


                <tr>
                    <td
                        style="     width: 15%;-webkit-print-color-adjust: exact !important; padding: 3px 5px;background: #ebebeb;">
                        <?php echo getLange('address'); ?>:</td>
                    <td style="width: 35.8%;">
                        <?php if (isset($info['Pick_location']) && !empty($info['Pick_location']) && $info['Pick_location'] != "") {
                        echo $info['Pick_location'];
                      } else {
                        echo $info['sender_address'];
                      }
                      ?>
                    </td>
                    <td style="-webkit-print-color-adjust: exact !important; padding: 3px 5px;background: #ebebeb;">
                        <?php echo getLange('address'); ?>:</td>
                    <td style="width: 35.8%;">
                        <?php echo isset($info['receiver_address']) ? $info['receiver_address'] : $info['receiver_address'] ?>
                    </td>
                </tr>





            </table>

            <!-- <table class="address_Table">
                <tr>
                    <td class="width50" style="width: 42.1%;padding: 0;    vertical-align: top;">
                        <table>
                            <tr
                                style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                                <th
                                    style="-webkit-print-color-adjust: exact !important; border: 1px solid #000;padding: 3px 5px;width: 30%;background: #ebebeb;border-left: none;border-top: none;">
                                    <?php echo getLange('company'); ?>:</th>
                                <td style="border:none; padding: 3px 5px;"><?php
                                                                echo isset($customerData['bname']) ? $customerData['bname'] : $info['sname'];
                                                                ?></td>
                            </tr>
                            <tr
                                style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                                <th
                                    style="  -webkit-print-color-adjust: exact !important;  padding: 3px 5px;background: #ebebeb;border: 1px solid #000;border-top: none;border-left: none;">
                                    <?php echo getLange('phoneno'); ?>:</th>
                                <td style="border: none; padding: 3px 5px;"><?php if (isset($info['sphone'])) {
                                                                  echo $info['sphone'];
                                                                } ?></td>
                            </tr>
                            <tr>
                                <th
                                    style="-webkit-print-color-adjust: exact !important;padding: 3px 5px;background: #ebebeb;border: 1px solid #000;border-top: none;border-left: none;border-bottom: none;">
                                    <?php echo getLange('address'); ?>:</th>
                                <td style="border: none; padding: 3px 5px;">
                                    <?php if (isset($info['Pick_location']) && !empty($info['Pick_location']) && $info['Pick_location'] != "") {
                        echo $info['Pick_location'];
                      } else {
                        echo $info['sender_address'];
                      }
                      ?></td>
                            </tr>
                        </table>

                    </td>
                    <td style="padding: 0;vertical-align: top;">
                        <table>
                            <tr
                                style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                                <th
                                    style="    padding: 3px 5px;-webkit-print-color-adjust: exact !important;background: #ebebeb;border: 1px solid #000;border-top: none;border-left: none;">
                                    <?php echo getLange('name'); ?>:</th>
                                <td style="border: none; padding: 3px 5px;"><?php if (isset($info['rname'])) {
                                                                  echo $info['rname'];
                                                                } ?></td>
                            </tr>
                            <tr
                                style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
                                <th
                                    style="padding: 3px 5px;-webkit-print-color-adjust: exact !important;background: #ebebeb;border: 1px solid #000;border-top: none;border-left: none;">
                                    <?php echo getLange('phoneno'); ?> :</th>
                                <td style="border: none; padding: 3px 5px;"><?php if (isset($info['rphone'])) {
                                                                  echo $info['rphone'];
                                                                } ?></td>
                            </tr>
                            <tr>
                                <th
                                    style="padding: 3px 5px;-webkit-print-color-adjust: exact !important;background: #ebebeb;border: 1px solid #000;border-top: none;border-left: none;border-bottom: none;">
                                    <?php echo getLange('address'); ?>:</th>
                                <td style="border: none; padding: 3px 5px;"><?php echo isset($info['receiver_address']) ? $info['receiver_address'] : $info['receiver_address'] ?></td>
                            </tr>

                        </table>
                    </td>

                </tr>
            </table> -->

            <table class="declared_table">

                <tr>
                    <td style="padding: 0px; border: 0px;">
                        <table>
                            <tr>
                                <td class=""
                                    style="width: 17.4%;background-color: #c8c8c8;-webkit-print-color-adjust: exact !important;">
                                    <b><?php echo getLange('refernceno'); ?> #</b>
                                </td>
                                <td class="">
                                    <?php echo isset($info['ref_no']) ? $info['ref_no'] : ''; ?>
                                </td>
                                <td class="" style="background-color: #c8c8c8;
    -webkit-print-color-adjust: exact !important;">
                                    <b><?php echo getLange('orderid'); ?> : </b>
                                </td>
                                <td><?php echo $info['product_id']; ?></td>


                                <td class=""
                                    style="background-color: #c8c8c8;-webkit-print-color-adjust: exact !important;">
                                    <b><?php echo getLange('codamount'); ?> </b>
                                </td>
                                <td class="">
                                    <?php echo $currency['value']; ?>:
                                    <?php echo number_format((float)$info['collection_amount'], 2); ?>
                                </td>


                            </tr>
                            <tr>


                            </tr>
                            <?php if ($info['customer_id'] == 1 || $info['booking_type'] == 2 || $info['booking_type'] == 3) { ?>
                            <tr>
                                <td class="logo">
                                    <b><?php echo getLange('deliverycharges'); ?> </b>
                                </td>
                                <td class="barcode">
                                    <b><?php echo $currency['value']; ?>:
                                        <?php echo number_format((float)$info['price'], 2); ?></b>
                                </td>
                                <td class="barcode">
                                    <b><?php echo getLange('gst') . ' ' . getLange('amount'); ?> :
                                        <?php echo number_format((float)$info['pft_amount'], 2); ?></b>
                                </td>

                                <td class="barcode">
                                    <b><?php echo getLange('net_amount'); ?> :
                                        <?php echo number_format((float)$info['net_amount'], 2); ?></b>
                                </td>
                                 <td class="barcode">
                                        <b><?php echo getLange('noofpiece'); ?> </b>
                                    </td>
                                    <td class="barcode">
                                        <?php echo $info['quantity']; ?>
                                    </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>

                </tr>
            </table>
            <table class="product_table">


                </tr>
                <tr>
                    <td class="logo">
                        <b><?php echo getLange('productdescription'); ?> :</b>
                    </td>
                    <td class="barcode" colspan="5">
                        <p><?php if (isset($info['product_desc'])) {
                      echo $info['product_desc'];
                    } ?></p>
                    </td>

                </tr>
                <tr>
                    <td class="logo">
                        <b><?php echo getLange('specialinstruction'); ?> </b>
                    </td>
                    <td class="barcode" colspan="5">
                        <p><?php if (isset($info['special_instruction'])) {
                      echo $info['special_instruction'];
                    } ?></p>
                    </td>

                </tr>
                <tr>
                    <td id="para_graph" style="    text-align: center;
    padding: 3px 3px;" colspan="6">
                        <h6 style="font-weight: 400;"><?php echo $invoicefooter['value'] ?></h6>
                    </td>
                </tr>
                <tr>

                    </td>
                </tr>
            </table>

        </div>
        <?php if (isset($info['customer_id']) && $info['customer_id'] == 1) { ?>


        <div id="charges_box">
            <table class="shipper_Table" style="display: none;">
                <thead>
                    <tr>
                        <th
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="color: #000">Charges</b>
                        </th>
                        <th
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="color: #000">Amount</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                if (isset($id_invoice)) {

                  $charquery = mysqli_query($con, "SELECT * from order_charges where order_id='" . $id_invoice . "'");
                  while ($charges = mysqli_fetch_array($charquery)) {
                ?>
                    <tr>
                        <td><b><?php echo chargedefine($charges['charges_id']); ?></b></td>
                        <td>
                            <b><?php echo $charges['charges_amount']; ?></b>
                        </td>
                    </tr>
                    <?php }
                } ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 15px;">Total Charges</b>
                        </td>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 15px;"><?php echo $info['net_amount']; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 13px;">Delivery Charges</b>
                        </td>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 15px;"><?php echo $info['price']; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 14px;">Sales Tax</b>
                        </td>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 15px;"><?php echo $info['pft_amount']; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 15px;">Net Amount</b>
                        </td>
                        <td
                            style="text-align:left; background-color:#e0e0e0;-webkit-print-color-adjust: exact !important;">
                            <b style="font-size: 15px;"><?php echo $info['grand_total_charges']; ?></b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php }
        $invoice_print++;
      }
    } ?>


    <script type="text/javascript" src="assets/js/jquery-2.2.4.min.js"> </script>
    <script type="text/javascript">
    function Popup(elem) {
        var data = $('#' + elem).html();
        var mywindow = window.open('', 'my div', 'height=400,width=600');

        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.print();
        mywindow.close();
        return true;
    }
    </script>
    <script type="text/javascript">
    jQuery(document).bind("keyup keydown", function(e) {
        e.preventDefault();
        if (e.ctrlKey && e.keyCode == 80) {
            $('.print_btn').trigger('click');
        }
    });
    $('body').on('click', '.print_btn', function(e) {
        e.preventDefault();
        var invoice = $(this).attr('href');
        window.open(invoice, 'mywindow', 'width = 800, height = 800');
    })
    </script>
</body>

</html>