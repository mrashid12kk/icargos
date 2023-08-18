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
        font-size: 60%;
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
        line-height: 1.2;
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
        line-height: 1.23;
    }

    p {
        font-size: 15px;
        margin: 0;
    }



</style>
<style type="text/css">
    .fl{ float:left}
    .fr{ float:right}
    .cl{ clear:both; font-size:0; height:0; }
    .clearfix:after {
        clear: both;
        content: ' ';
        display: block;
        font-size: 0;
        line-height: 0;
        visibility: hidden;
        width: 0;
        height: 0;
    }
    .inv_termal {
        text-align: left;
        width: 360px;
        padding: 0;
    }
    .logobox {
        text-align: center;
    }
    .logobox img {
        display: block;
        margin: 0 auto 8px;
    }
    .logobox b {
        font-size: 17px;
    }
    .company_info {
        text-align: center;
        padding: 0;
        border-bottom: 1px solid black;
    }
    .company_info p {
        margin: 0 0 2px;
    }
    .company_info p b {
        width: 42%;
        display: inline-block;
        text-align: right;
        font-size: 13px;
    }
    .company_info p span {
        width: 50%;
        display: inline-block;
        text-align: left;
        font-size:14px;
    }
    .sender_info {
        text-align: center;
    }
    .sender_info b {
        font-size: 13px;
    }
    .sender_info p {
        font-size: 13px;
        margin: 0;
    }
    .priority {
        float: right;
        width: 46%;
        text-align: center;
        border: 1px solid #000;
    }
    .priority p {
        border-bottom: 1px solid #000;
        padding: 3px 0;
        margin: 0;
        font-weight: bold;
    }
    .priority b {
        font-size: 34px;
    }
    .barcode {
        float: left;
        width: 48%;
        text-align: center;
        padding: 6px 0 0;
    }
    .barcode p{
        font-weight: bold;
        color: #000;
    }
    .receiver_info {
        float: right;
        width:48%;
    }
    .barcode_box {
        padding: 0;
    }
    .parcelnote {
        text-align: center;
        padding: 0;
    }
    .ymd_box {
        padding: 0 0 25px;
    }
    .ymd_box p {
        font-size: 13px;
    }

    .parcelnote p {
        text-align: left;
        border: 1px solid #000;
        padding: 1px 13px;
        margin: 6px 0 9px;
        font-weight: 500;
        font-size: 13px;
    }
    .table_box table {
      border-collapse: collapse;
      width: 100%;
  }
  .table_box  td, .table_box  th {
    border-bottom: 1px solid #000;
    text-align: left;
    padding: 2px 5px;
    font-size: 12px;
    font-weight: 600;
}
.table_box .table td, .table_box  .table th{
    border: 1px solid #000;
}
.table_box .table{
    margin-top: 5px;
}
.table_box  tr:nth-child(even) {
  background-color: #dddddd;
  -webkit-print-color-adjust: exact !important;
}

</style>
</head>

<body id="page-name" style="page-break-after: always;">
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
        

        <div class="inv_termal">
            <div class="logobox">
                <img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt=""
                style="    width: 211px;height: 56px; object-fit: contain;margin-bottom: 0px;">
                <!---<b><?php echo getConfig('companyname'); ?></b>---->
            </div>
            <div class="company_info">
                <p><b>TEL:</b> <span><?php echo getConfig('contactno'); ?></span></p>
                <span></span>
            <!-- <p><b>PARCEL NO:</b> <span> <?php echo isset($info['track_no']) ? $info['track_no'] : ''; ?></span></p>
            <p><b>TOTAL ITEMS:</b> <xspan> <?php echo isset($info['quantity']) ? $info['quantity'] : '0'; ?></span></p>
            <p><b><?php echo getLange('Services'); ?>:</b> <span> <?php echo isset($service) ? $service :  ''; ?></span></p>
            <p><b><?php echo getLange('date'); ?>:</b> <span> <?php echo date(DATE_FORMAT, strtotime($info['order_date'])); ?></span></p> -->
        </div>
        <div class="clearfix">
            <div class="sender_info">
                <b>SENDER DETAILS</b>
                <p><b>Name:</b><?php echo isset($customerData['bname']) ? $customerData['bname'] : $info['sname'];?></p>
                <p><b>Phone:</b> <?php if (isset($info['sphone'])) {echo $info['sphone'];} ?> </p>
                <p><b>Origin:</b><?php if (isset($info['origin'])) {
                    echo $info['origin'];
                } ?>
            </p>
            <p><?php if (isset($info['Pick_location']) && !empty($info['Pick_location']) && $info['Pick_location'] != "") {
                echo $info['Pick_location'];
            } else {
                echo $info['sender_address'];
            }
            ?></p>
        </div>
        <div class="priority" style="display: none;">
            <p>PRIORITY</p>
            <b>A</b>
        </div>
    </div>
    <div class="clearfix barcode_box">
        <div class="barcode" style="width: 100%;float:none;">
            <img src="<?php echo $info['barcode_image']; ?>" style="" alt="">
            <p><?php echo $info['barcode']; ?></p>
        </div>
        <div class="sender_info receiver_info" style="float: none;width:auto;">
            <b>RECEIVER DETAILS</b>
            <p><b>Name</b> <?php if (isset($info['rname'])) {echo $info['rname'];} ?></p>
            <p><b>Phone:</b> <?php if (isset($info['rphone'])) {echo $info['rphone'];} ?> </p>
            <p><b>Destination:</b> <?php if (isset($info['destination'])) {echo $info['destination'];} ?> </p>
            <p><?php if (isset($info['receiver_address'])) {echo $info['receiver_address'];} ?> </p>
        </div>
    </div>
    <div class="parcelnote">
        <b>PARCEL NOTE</b>
        <p><?php if (isset($info['product_desc'])) {echo $info['product_desc'];} ?></p>
    </div>
    <div class="table_box">
        <table>
          <tr>
            <td>Quantity:<?php if (isset($info['quantity'])) {echo $info['quantity'];} ?></td>
            <td style="border: 1px solid #000;border-bottom: none;background: #dddddd;-webkit-print-color-adjust: exact !important;"></td>
        </tr>
        <tr>
            <td style="border-bottom: none;">Value: <?php echo $currency['value']; ?> <?php echo $info['collection_amount']; ?></td>
            <td style="    border-left: 1px solid #000; border-right: 1px solid #000;text-align: center;"><b style="font-size: 21px;font-weight: bold;position: relative;top: -12px;"><?php if (isset($info['cweight'])) {echo $info['cweight'];} ?> KG</b></td>
        </tr>
    </table>
</div>

<div class="table_box ">
    <table class="table">
              <!-- <tr>
                <td><?php echo getLange('refernceno'); ?> #</td>
                <td><?php echo isset($info['ref_no']) ? $info['ref_no'] : ''; ?></td>
                <td><?php echo getLange('orderid'); ?></td>
                <td><?php echo $info['product_id']; ?></td>
            </tr> -->
              <tr><!-- 
                <td><?php echo getLange('noofflyers'); ?></td>
                <td><?php if (isset($info['flyer_qty'])) {echo $info['flyer_qty'];} ?></td> -->
                <td><?php echo getLange('deliverycharges'); ?></td>
                <td><?php echo number_format((float)$info['price'], 2); ?></td>
            </tr>
              <!-- <tr>
                <td colspan="2"><?php echo getLange('specialinstruction'); ?></td>
                <td colspan="2"><?php if (isset($info['special_instruction'])) {echo $info['special_instruction'];} ?></td>
            </tr> -->
        </table>
        <table class="table">
          <tr>
            <td><?php echo getLange('net_amount'); ?></td>
            <td><?php echo number_format((float)$info['net_amount'], 2); ?></td>
            <td>TAXABLE</td>
            <td><?php echo $info['pft_amount']; ?></td>
        </tr>
        <tr>
            <td>
                <?php if (isset($info['booking_type']) && $info['booking_type']==1) {echo 'Invoice';} ?>
                <?php if (isset($info['booking_type']) && $info['booking_type']==2) {echo 'Cash';} ?>
                <?php if (isset($info['booking_type']) && $info['booking_type']==3) {echo 'To Pay';} ?>
            </td>
            <td>TOTAL</td>
            <td><?php echo $info['grand_total_charges']; ?></td>
        </tr>
    </table>
</div>
<div class="parcelnote">
    <b style="font-size:12px;">TERMS & CONDITIONS</b>
    <p style="margin:0px;"><?php echo getConfig('invoicefooter'); ?></p>
</div>
<div class="ymd_box">
    <p><b>Print Date:</b> <?php echo date('Y-m-d'); ?></p>
</div>
</div>
</div>
<?php if (isset($info['customer_id']) && $info['customer_id'] == 1) { ?>


    
</div>
<?php }
$invoice_print++;
}
} ?>

<!-- inv css -->




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