<?php
date_default_timezone_set("Asia/Karachi");
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
include_once "includes/conn.php";
if(!isset($_SESSION))
  session_start();
$webtitle = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname' "));
$res=mysqli_query($con,"select * from config") or die(mysqli_error($con));
while($row=mysqli_fetch_array($res)){
  $cfg[$row['name']]=$row['value'];
}
function encrypt($string) {
  $key="usmannnn";
  $result = '';
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }
  return base64_encode($result);
}
function decrypt($string) {
  $key="usmannnn";
  $result = '';
  $string = base64_decode($string);
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }
  return $result;
}
$ex = explode('-',decrypt($_GET['id']));
$id_invoice =$ex[0];
$res=mysqli_query($con,"select * from orders where id='".$id_invoice."'") or die(mysqli_error($con));
$info=mysqli_fetch_array($res);
$order_type_id = $info['order_type'];
$get_service = mysqli_query($con,"SELECT service_type FROM services WHERE id='".$order_type_id."' ");
$servie_q_res = mysqli_fetch_array($get_service);
$service = $servie_q_res['service_type'];
// echo "<pre>"; print_r($info); exit();
if(isset($info['id'])) {
  $deliver = mysqli_query($con, "SELECT * FROM deliver WHERE order_id = ".$info['id']);
  $deliver = ($deliver) ? mysqli_fetch_object($deliver) : null;
  if(isset($deliver->driver_id)) {
    $driver = mysqli_query($con, "SELECT * FROM users WHERE id = ".$deliver->driver_id);
    $driver = ($driver) ? mysqli_fetch_object($driver) : null;
  }
}

if(isset($info['branch_id']) && $info['branch_id'] != null) {
  $branch = mysqli_query($con, "SELECT * FROM branches WHERE id = ".$info['branch_id']);
  $branch = mysqli_fetch_object($branch);
}
function getCustomerBus($customer_id)
{
  global $con;
  $sql = "SELECT * FROM  customers WHERE id='".$customer_id."'";

  $res=mysqli_query($con,$sql) or die(mysqli_error($con));
  $cusdata=mysqli_fetch_array($res);
  return $cusdata;
}
$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
$invoicefooter = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='invoicefooter' "));
$currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
$print   = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='print' "));

$data_cus = getCustomerBus($info['select']);
if(!function_exists('chargedefine')){
  function chargedefine($id=null)
  {
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT * from charges where id=".$id);
      while ($resposne = mysqli_fetch_assoc($query)){
        $result=$resposne['charge_name'];
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
  <title><?php echo $cfg['lang_invoice']?> No. #<?php echo $info['id']; ?></title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900' rel='stylesheet' type='text/css'>
  <style>


/* Default Font Styles
______________________*/
body, input, select, textarea, p, a, b{
 font-family: 'Roboto', sans-serif;
 color:#000;
 line-height:1.4;
}
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
/* p, blockquote, address
______________________*/
p{
  font-size: 15px;
  margin-bottom:0;
}
.booked_packge{
  max-width: 100%;
  margin: 24px auto 13px;
}
.pacecourier_logo {
  float: left;
  width: 30%;
}
.pacecourier_logo img {
  padding-top: 19px;
}
.booked_packges {
  float: right;
  width: 65%;
}
.booked_packges h4 {
  margin: 0;
  font-size: 20px;
}
.booked_packges ul{
  padding: 0;
  margin: 9px 0 0;
}
.booked_packges ul li {
  list-style: none;
  margin-bottom: 5px;
}
.booked_packges ul li b {
  float: left;
  width: 25%;
}
.table {
  border-collapse: collapse;
  width: 100%;
}
.table th{

  color: #000;
}
.table th{
  border: 2px solid;
}
.table td, .table th {
  border: 1px solid;
  text-align: left;
  padding: 6px 6px;
  vertical-align: top;
  font-size: 12px;
}

.table tr li {
  list-style: none;
  font-size: 12px;
  margin-bottom: 3px;
  color: #000 !important;
}
.table tr ul{
  padding-left: 0;
  margin: 0;
}
.total_itmes p {
  margin: 0 0 7px;
  border-bottom: 2px solid #000;
  padding: 0 0 7px;
  color: #000 !important;
}
.total_itmes {
  padding: 13px 0 0;
}
.left_signature {
  float: left;
  width: 50%;
}
.right_signature {
  text-align: right;
}
.left_signature p {
  font-weight: 500;
  font-size: 12px;
}
b,.booked_packges p{
  font-weight: 500;
  font-size: 12px;
  margin: 0 0 3px;
}
body{
  color: #000 !important;
}

</style>    
<style type="text/css">
  .left_box {
    float: left;
    width: 50%;
    margin-bottom: 10px;
  }
  .bottom_f{
    padding: 20px 0 0;
  }
  .box_box b {
    font-size: 20px;
  }
  .box_box span{
   font-size: 20px;
 }
 .bottom_f .right_box b,.bottom_f .left_box b{
  width: auto;
}
.bottom_f .left_box p,.bottom_f .left_box p{
  width: 65%;
}
.bottom_f .left_box b, .bottom_f .right_box b{
  font-weight: 600;
}
.left_box b, .right_box b {
  font-weight: 400;
  font-size: 15px;
  vertical-align: middle;
  margin-top: 1px;
  display: inline-block;
  width: 30%;
  text-align: left;
  margin-right: 5px;
}
.track_image_code{
  margin: 0;
  margin-top: -5px;
  text-align: center;
}
.left_box p, .right_box p {
  display: inline-block;
  margin: 0;
  float: right;
  width: 65%;
}
.head_,.right_box .head_{
  margin: 0 0 4px;
  display: flex;
}
.right_box {
  float: right;
  width: 38%;
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
.right_box b {
 width: 30%;
}
.right_box p{
  width: 67%;
}
.icargo_box li {
  display: inline-block;
  width: 33%;
}
.icargo_box h1 {
  box-shadow: 0 0 4px 0px #00000038;
  margin: 15px 0 !important;
  padding: 5px 10px;
  font-size: 25px;
}
.icargo_box ul{
  padding: 0;
  text-align: center;
}
.icargo_box li h5{
  margin: 0;
  margin-top: -5px;
}
.text-center{
  text-align: center !important;
}
.undertaking {
  letter-spacing: 7px;
}
@media print {
  .single_page {
    page-break-after:always
  }
  @page{
   margin: 0.5px;
 }
 .icargo_box li {

  width: auto;
}
.right_box p {
  width: 63%;
}
.right_box b {
  width: 32%;
}
.bottom_f .right_box b, .bottom_f .left_box b {
  width: auto !important;
}
.bottom_f .left_box p, .bottom_f .left_box p {
  width: 58% !important;
}
.right_box {
  float: right;
  width: 45%;
}
.left_box p, .right_box p {
  font-size: 12px;
}
.left_box b, .right_box b {
  font-size: 12px;
}
}
</style>
</head>
<body id="page-name">
  <?php
  function getCopyName($id)
  {
    global $con;
    $sql= "SELECT * FROM invoice_name WHERE id  = ".$id;
    $query_order = mysqli_query($con,$sql);
    $data = mysqli_fetch_array($query_order);
    if (isset($_GET['booking']) && $_GET['booking']==1) {
      return isset($data['name']) ? getLange($data['name']) : getLange('shippercopy');
    }else{
      return getLange('shippercopy');
    }



  }
  function getOrderDetail($order_id)
  {
    $data = "";
    global $con;
    $sql= "SELECT * FROM orders   WHERE id  = '".$order_id."'  ";
    $query_order = mysqli_query($con,$sql);
    $data = mysqli_fetch_array($query_order);
    return $data;
  }
  $order_detail = getOrderDetail($order_id);

  function getOrderCharges($order_id)
  {
    $data = "";
    global $con;
    $sql= "SELECT * FROM order_charges WHERE order_id  = '".$order_id."'  ";
    $query_order = mysqli_query($con,$sql);
    $data=[];
    if(isset($query_order) && !empty($query_order)){
      while($row = mysqli_fetch_array($query_order)){
        if(isset($row['charges_amount']) && $row['charges_amount'] > 0){
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
    $sql= "SELECT * FROM customers   WHERE id  = '".$customer_id."'  ";
    $query_order_cus = mysqli_query($con,$sql);
    $cust_detail = mysqli_fetch_array($query_order_cus);
    return $cust_detail;
  }

  if(!isset($_GET['print'])){ ?>
    <a href="<?php echo $url ?>&print=1"  class="print_btn" target="_blank" >Print</a>
  <?php }else{ ?>
    <script type="text/javascript">window.print();</script>
  <?php } ?>
  <div id="fb-root"></div>
  <script>
    /* Facebook Setup */
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '928362647290774',
        xfbml      : true,
        version    : 'v2.7'
      });
    };
    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
    function share(){
      FB.ui({
        method: 'share',
        display: 'popup',
        href:''
      }, function(response){
        if (response) {
          var postdata="action=fbshare&id=<?php echo $id_invoice; ?>";
          $.ajax({
            type:'POST',
            dataType:'json',
            data:postdata,
            url:'ajax.php',
            success:function(fetch){
              if(fetch[0]){
                window.location.href=window.location;
              }
              else{
                alert('your package already has been discounted');
              }
            }
          });

        }
        else{
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
    twttr.ready(function (twttr) {
      twttr.events.bind('tweet', function (event) {
        if(event){
        }
      });
    });
  </script>

  <?php
  if(isset($_GET['order_id']) && !empty($_GET['order_id'])){

    if (!$_GET['booking']) {
      $invoice_ids = explode(',',$_GET['order_id']);
    }else{
      $invoice_ids= array();

      $invoice = $_GET['order_id'];

      $count = getConfig('print');

      for ($i=0; $i < $count ; $i++)
      {
        array_push($invoice_ids, $invoice);
      }
    }

    $invoice_print=1;

    foreach($invoice_ids as $key => $id_invoice)
    {
     $info = getOrderDetail($id_invoice);
     $customer_id = isset($info['customer_id']) ? $info['customer_id']:'';
     $customerData = getCustomer($customer_id);
     $order_type_id = $info['order_type'];
     $get_service = mysqli_query($con,"SELECT service_type FROM services WHERE id='".$order_type_id."' ");
     $servie_q_res = mysqli_fetch_array($get_service);
     $service=$servie_q_res['service_type'];
     $origin_city=mysqli_fetch_assoc($area_q = mysqli_query($con, "SELECT * FROM areas WHERE id=" . $info['origin_area_id']));

     ?>
     <div id="main_page">
      <div class="single_page">
       <div class="icargo_box">
        <h1 style="text-align: left;margin: 9px 0 0;">Performa Invoice</h1>
      </div>
      <div class="clearfix">
        <div class="left_box">
         <div class="head_">
          <strong>SHIPPER</strong>
        </div>

        <div class="head_">
          <b>Company Name :</b>
          <p><?php echo $customerData['bname']; ?></p>
        </div>

        <div class="head_">
          <b  >Address :</b>
          <p><?php echo $info['sender_address']; ?></p>
        </div>

        <div class="head_">
          <b>State :</b>
          <p><?php echo $info['sstate']; ?></p>
        </div>

        <div class="head_">
          <b>Zip/Country :</b>
          <p><?php echo isset($info['szip']) && $info['szip']!='' ? $info['szip'].' /' : ''; ?>  <?php echo $info['origin']; ?></p>
        </div>

        <div class="head_">
          <b>Contact Name :</b>
          <p><?php echo $info['sname']; ?></p>
        </div>

        <div class="head_">
          <b>Phone/Fax No. :</b>
          <p><?php echo $info['sphone']; ?></p>
        </div>

        <div class="head_">
          <b><strong>CNIC/NTN No :</strong></b>
          <p><strong><?php echo $info['scnic']; ?></strong></p>
        </div>
        <div class="head_">
          <b><strong>Consignment No :</strong></b>
          <p><strong><?php echo $info['track_no']; ?></strong></p>
        </div>

        <div class="head_">
          <b>No of Pieces :</b>
          <p><?php echo $info['quantity']; ?></p>
        </div>

        <div class="head_">
          <b>Total Weight :</b>
          <p><?php echo $info['cweight']; ?> KG</p>
        </div>


      </div>
      <div class="right_box">
       <div class="head_">
        <strong>RECEIVER</strong>
      </div>

      <div class="head_">
        <b>Company Name :</b>
        <p><?php echo $info['rname']; ?></p>
      </div>

      <div class="head_">
        <b  >Address :</b>
        <p><?php echo $info['receiver_address']; ?></p>
      </div>

      <div class="head_">
        <b>City/State :</b>
        <p><?php echo $info['rcity']; ?>  <?php echo isset($info['rstate']) && $info['rstate']!='' ? '/ '.$info['rstate'] : ''; ?></p>
      </div>

      <div class="head_">
        <b>Zip/Country :</b>
        <p><?php echo isset($info['rzip']) ? $info['rzip'].' /' : ''; ?>  <?php echo $info['destination']; ?></p>
      </div>

      <div class="head_">
        <b>Contact Name :</b>
        <p><?php echo $info['rname']; ?></p>
      </div>

      <div class="head_">
        <b>Phone/Fax No. :</b>
        <p><?php echo $info['rphone']; ?> <?php echo isset($info['rfax']) && $info['rfax']!='' ? '/ '.$info['rfax'] : ''; ?></p>
      </div>
      <div class="head_">
        <b>Booking Date :</b>
        <p><?php echo isset($info['order_date']) ? date('d/m/Y', strtotime($info['order_date'])) : ''; ?></p>
      </div>



    </div>
  </div>
  <?php 
    $sql_q = "SELECT * FROM order_commercial_invoice WHERE order_id= '".$info['id']."' ORDER BY  id ";
  $commercial_invoice_query=mysqli_query($con,$sql_q);
  if (mysqli_affected_rows($con) > 0) {
    ?>
    <table class="table">
      <tbody>
       <tr>
        <th>#.</th>
        <th>DESCRIPTION</th>
        <th>QTY</th>
        <th>UNIT VALUE</th>
        <th>COD</th>
        <th>HS Code</th>
        <th>AMOUNT</th>
        <!-- <th>DIMENSIONS</th> -->
      </tr>
      <?php
      $srno=1;
      $total_qty=0;
      $total_amount=0;
      while ($row=mysqli_fetch_array($commercial_invoice_query)) { 
        $total_qty+=$row['c_i_pieces'];
        $total_amount+=$row['c_i_hs_total'];?>        
        <tr>
          <td><?php echo $srno++; ?></td>
          <td><?php echo $row['c_i_discription']; ?></td>
          <td><?php echo $row['c_i_pieces']; ?></td>
          <td><?php echo $row['c_i_price']; ?></td>
          <td><?php echo $row['c_i_coo']; ?></td>
          <td><?php echo $row['c_i_hs_code']; ?></td>
          <td><?php echo $row['c_i_hs_total']; ?></td>
          <!-- <td><?php // echo $row['c_i_length'] .'X'.$row['c_i_width'] .'X'.$row['c_i_height']; ?></td> -->
        </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">Total</td>
        <td><?php echo isset($total_qty) ? $total_qty : '0' ?></td>
        <td colspan="3"></td>
        <td><?php echo isset($total_amount) ? $total_amount : '0' ?></td>
      </tr>
    </tfoot>
  </table>
<?php } ?>
<div class="clearfix">
  <div class="center_box">
   <div class="box_box">
    <b>Country of Origin</b> &nbsp;  &nbsp;  
    <span><?php echo $info['origin']; ?></span>
    <p>I/we..................... Here by undertake that the above mentioned particulars are true and correct as per my statement and there is nothing dangerous goods, antiques, liquid, nacotics, flammable , if any such material found from this shipment at any stage or duty taxes and storage chefes at destination  I/we shell be held responsible</p>
  </div>

  <div class="box_box">
    <h3 class="undertaking" style="text-align:center;text-decoration:underline;"><?php echo getConfig('gift_invoice_title'); ?></h3>  
            <p><!-- I under son Undertake ful responsibility of my Parcel #, 000511511955. < do not contain any contraband items. Narcotics or all JATA Restricted Rents, and assure that my parcel contents, aind declared value and proof of payment is correct and true.</p>
            <p>In case of any misdedarations or discrepancy and any Duty Taxes at the tination, if not paid by the consignse, it would be the sole respo under son. --><?php echo getConfig('gift_invoice_footer'); ?></p>
          </div>
        </div>
      </div>
      <div class="clearfix bottom_f">
        <div class="left_box">
         <div class="head_">
          <b>Shipper Name</b>
          <p>________________</p>
        </div>
        <div class="head_">
          <b>Signature / Stamps</b>
          <p>________________</p>
        </div>

        <div class="head_">
          <b>Thumb Impressions</b>
          <p>________________</p>
        </div>
      </div>

    </div>
  </div>


</div>


<?php } $invoice_print++;} ?>
<?php if (isset($_GET['airway_bill']) && !empty($_GET['airway_bill'])) {?>
  <style>
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
    .table_width {
      width: 800px;
      margin: 0 auto;
      font-family: 'Roboto', sans-serif;
    }
    .table_invoice {
      -webkit-print-color-adjust: exact !important;
      width: 100%;
      border-spacing: 0;
      border-collapse: collapse;
      font-size: 13px;
      page-break-after:always;
    }
    .table_invoice thead tr th {
      text-align: left;
      padding: 5px 0;
    }
    .table_invoice tbody tr th,.table_invoice tbody tr td{
      border: 1px solid #3333;
      text-align: left;
      padding:4px 8px;
      vertical-align: top;
      background: #f4f4f4;
      -webkit-print-color-adjust: exact !important;
    }
    .table_invoice tbody tr th {
      background: #e3e3e3;
      font-weight: 500;
      -webkit-print-color-adjust: exact !important;
    }
    .table_invoice tfoot tr td {
      padding: 10px;
      font-size: 12px;
      vertical-align: top;
    }
    .width_c{
      width: 50%;
    }
    .w-custom {
      min-width: 150px;
    }
    .table_width{
      padding: 0 15px;
    }

    @media print {
      @page{
        margin: 0;
      }
    }


  </style>
  <div class="page-wrap">
    <?php
    if(isset($_GET['order_id']) && !empty($_GET['order_id'])){

      if (!$_GET['booking']) {
        $invoice_ids = explode(',',$_GET['order_id']);
      }else{
        $invoice_ids= array();

        $invoice = $_GET['order_id'];

        $count = getConfig('print');

        for ($i=0; $i < $count ; $i++)
        {
          array_push($invoice_ids, $invoice);
        }
      }

      $invoice_print=1;

      foreach($invoice_ids as $key => $id_invoice)
      {
       $info = getOrderDetail($id_invoice);
       $customer_id = isset($info['customer_id']) ? $info['customer_id']:'';
       $customerData = getCustomer($customer_id);
       $order_type_id = $info['order_type'];
       $get_service = mysqli_query($con,"SELECT service_type FROM services WHERE id='".$order_type_id."' ");
       $servie_q_res = mysqli_fetch_array($get_service);
       $service=$servie_q_res['service_type'];
       $origin_city=mysqli_fetch_assoc($area_q = mysqli_query($con, "SELECT * FROM areas WHERE id=" . $info['origin_area_id']));
       ?>

       <div class="table_width">
        <table class="table_invoice">
          <thead>
            <tr>
              <th><img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="" style="width: auto; max-height:60px;"></th>
              <th style="text-align: center;"><?php echo getConfig('address'); ?><br><?php echo getConfig('contactno'); ?><br><?php echo getConfig('website'); ?></th>
              <?php
              if(isset($info['barcode_image']))
              {
                ?>
                <th colspan="2" style="text-align: center;"><img  src="<?php echo $info['barcode_image']; ?>" style="" /><h2 style="text-align: center; font-size:15px;margin: 0;"><?php echo $info['barcode'] ?></h2></th>
              <?php } ?>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td colspan="2" class="width_c">COUNTRY</td>
              <td colspan="2" class="width_c"><?php echo $info['origin']; ?></td>

            </tr>

            <tr>
              <th colspan="2" style="text-align: center;">Consignor</th>
              <th colspan="2" style="text-align: center;">Consignee</th>
            </tr>

            <tr>
              <th class="w-custom">Account Number</th>
              <td class="td_width"><?php echo $customerData['client_code']; ?></td>
              <th class="w-custom">Company Name</th>
              <td class="td_width"><?php echo $info['rname']; ?></td>
            </tr>

            <tr>
              <th class="w-custom">Shipper Name</th>
              <td class="td_width"><?php echo isset($customerData['bname']) ? $customerData['bname'] : $info['sname'];
              ?></td>
              <th class="w-custom">Attention</th>
              <td class="td_width"><?php echo $info['rname']; ?></td>
            </tr>
            <tr>
              <th class="w-custom" rowspan="1">Street Address</th>
              <td rowspan="1" class="td_width"><?php echo $info['sender_address']; ?></td>
              <th class="w-custom" rowspan="1">Street Address</th>
              <td rowspan="1" class="td_width"><?php echo $info['receiver_address']; ?></td>
            </tr>
            <tr>
              <th class="w-custom">City/State/Zip Code</th>
              <td class="td_width"><?php echo $info['scity'].' /'.$info['sstate'].' /'.$info['szip']; ?></td>
              <th class="w-custom">State / Zip Code</th>
              <td class="td_width"><?php echo $info['rstate'].' /'.$info['rzip']; ?></td>
            </tr>
            <tr>
              <th class="w-custom">Tel/Cell No.</th>
              <td class="td_width"><?php echo $info['sphone']; ?></td>
              <th class="w-custom">Country</th>
              <td class="td_width"><?php echo $info['destination']; ?></td>
            </tr>
            <tr>
              <th class="w-custom">CNIC/NTN</th>
              <td class="td_width"><?php echo $info['scnic']; ?></td>
              <th class="w-custom">Tel No.</th>
              <td class="td_width"><?php echo $info['rphone']; ?></td>
            </tr>
            <tr>
              <th class="w-custom">Shipper's Reference</th>
              <td class="td_width"><?php echo $info['shipper_reference']; ?></td>
              <th class="w-custom">Mobile No.</th>
              <td class="td_width"><?php echo $info['rphone']; ?></td>
            </tr>
            <tr>
              <th class="w-custom">Reference</th>
              <td class="td_width"></td>
              <th class="w-custom">Email Address:</th>
              <td class="td_width"><?php echo $info['remail']; ?></td>
            </tr>
            <tr>
              <th class="w-custom">Email Address:</th>
              <td class="td_width"><?php echo $info['semail']; ?></td>
              <th class="w-custom">Service Type</th>
              <td class="td_width"><?php echo $service; ?></td>
            </tr>

            <tr>
              <th colspan="2">Shipment Details</th>
              <th colspan="2">IS - INT'L STANDARD</th>
            </tr>
        <!-- <tr>
            <th>Skybill Date</th>
            <td>28-Sep-21 17:43:43</td>
            <th>Insurance</th>
            <td>Lorem ipsum dolor sit amet consectetur, adipisicing, elit. Provident ratione nulla, hic culpa odit blanditiis, </td>
          </tr> -->
          <tr>
            <th>Alternate Reference</th>
            <td><?php echo $info['ref_1'].' '.$info['ref_2']; ?></td>
            <th colspan="2">Received By</th>
          </tr>
          <tr>
            <th>Pieces / Weight (kg)</th>
            <td><?php echo $info['quantity'].' / '.$info['weight']; ?></td>
            <th>Print Name</th>
            <td><?php echo $info['received_by']; ?></td>
          </tr>
        <!-- <tr>
            <th>Shipment Type</th>
            <td>NON DOCS</td>
            <th>Date / Time</th>
            <td></td>
          </tr> -->
          <tr>
            <th>Contents</th>
            <td><?php echo $info['product_desc']; ?></td>
           <!--  <th rowspan="3">Signature</th>
            <td rowspan="3"></td> -->
            <th>Value / Currency</th>
            <td><?php echo $info['collection_amount']; ?> <?php echo isset($info['customer_currency']) ? $info['customer_currency'] : getConfig('currency'); ?></td>
          </tr>
          <tr>
            <th>Booking Date</th>
            <td><?php echo isset($info['order_date']) ? date('d/m/Y', strtotime($info['order_date'])) : ''; ?></td>
           <!--  <th rowspan="3">Signature</th>
            <td rowspan="3"></td> -->
            <th>Reference Number</th>
            <td><?php echo isset($info['ref_no']) ? $info['ref_no'] : ''; ?></td>
          </tr>
        <!-- <tr>
            <th>Value / Currency</th>
            <td><?php echo $info['collection_amount']; ?> <?php echo getConfig('currency'); ?></td>
          </tr> -->
        <!-- <tr>
            <th>Reason of Export</th>
            <td>Gift</td>
          </tr> -->
        </tbody>

        <tfoot>
          <tr>
            <td colspan="2"><b style="    font-size: 20px;">
              <?php
              if(isset($info['barcode_image']))
              {
                ?>
                <img  src="<?php echo $info['barcode_image']; ?>" style="display: block;margin: 0 0 0px -22px;" />
              <?php } ?>
              <?php echo $info['track_no']; ?></b></td>
              <td colspan="2" style="text-align: center;    position: relative;">
                <span style="float: left;width: 68%;"><b><?php echo getConfig('air_waybill_invoice_title'); ?></span>
                 <img style="    width: 105px;position: static;top: 7px;" src="<?php echo BASE_URL ?>admin/<?php echo getConfig('logo'); ?>" alt=""></b> <br><b>
                 </b><br>

                 <?php echo getConfig('air_waybill_invoice_footer'); ?>

               </td>
             </tr>
             <tr>
             <td style="padding: 0;" colspan="4"><div class="terms_conditions">
           <img style="width: 100%;" src="<?php echo BASE_URL ?>/admin/images/terms.jpg" alt="">
         </div></td> 
             </tr>
           </tfoot>
         </table>
       </div>

     <?php } $invoice_print++;} ?>


   <?php } ?>
   <script type="text/javascript" src="assets/js/jquery-2.2.4.min.js" > </script>
   <script type="text/javascript">

    function Popup(elem)
    {
      var data = $('#'+elem).html();
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
   jQuery(document).bind("keyup keydown", function(e){
    e.preventDefault();
    if(e.ctrlKey && e.keyCode == 80){
     $('.print_btn').trigger('click');
   }
 });
   $('body').on('click','.print_btn',function(e){
    e.preventDefault();
    var invoice = $(this).attr('href');
    window.open(invoice,'mywindow','width = 800, height = 800');
  })
</script>
</body>
</html>
