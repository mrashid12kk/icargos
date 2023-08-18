<?php
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
include_once "includes/conn.php";
$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
$invoicefooter = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='invoicefooter' "));
$currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
$print   = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='print' "));


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
  $sql = "select * from  customers where id='".$customer_id."'";

  $res=mysqli_query($con,$sql) or die(mysqli_error($con));
  $cusdata=mysqli_fetch_array($res);
  return $cusdata;
}


 $data_cus = getCustomerBus($info['customer_id']);
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
html,body,div,span,
h1,h2,h3,h4,h5,h6,
p,blockquote,q,em,img,small,strong,
dl,dt,dd,ol,ul,li,fieldset,form,label,legend{border:0;outline:0;margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent}
body{line-height:1}
ol,ul{list-style:none}
:focus{outline:0}
input,textarea{margin:0;outline:0;}
textarea{overflow:auto; resize:none;}
table{border-collapse:collapse;border-spacing:0}
/* End Reset */
/* html5 */
article, aside, details, figcaption, figure, footer, header, hgroup, nav, section { display: block; }
/* Default Font Styles
______________________*/
body, input, select, textarea, p, a, b{
   font-family: 'Roboto', sans-serif;
  color:#000;
  line-height:1.4;
}
*,
*:after,
*:before{
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
p{
  font-size: 15px;
  margin-bottom:0;
}
body, input, select, textarea, p, a, b{
   font-family: 'Roboto', sans-serif;
    color:#000;
    line-height:1.4;
}
p{
    font-size: 15px;
    margin:0;
}


.table_invoice table {
  border-collapse: collapse;
  width: 100%;
}
.table_invoice td, .table_invoice th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 0;
}
.barcode_images img{
    /*width: 101px;*/
    padding-top: 3px;
    }
.table_invoice{
    padding: 0px 25px;
    width: 921px;
}
.table_invoice .logo{
    width: 9%;
    padding:10px 12px;
}
/*.table_invoice .logo img{
        width: 95px;
}*/
.table_invoice .barcode {
    /*width: 24%;*/
    text-align: center;
    padding: 3px 14px 0 !important;
}
/*.table_invoice .barcode{
    text-align: center;
    width: 14%;
    padding: 0;
}*/
/*.table_invoice .barcode img{
        width: 99px;
}*/
.table_invoice .barcode p{
    font-size: 12px;
    margin-bottom: 0;
}
.table_invoice  ul{
        padding: 0;
}
.table_invoice ul li {
    border-bottom: 1px solid #ccc;
    padding: 3px 11px;
    font-size: 12px;
    height: 24px;
}
.table_invoice  ul li:last-child{
    border-bottom: none;
}
.date_info{
    width: 7.7%;
}
.shipper_Table{
}
.shipper_Table td,.address_Table td,.pieces_table td,.declared_table td,.product_table td{
    border-top: none;
    padding: 2px 10px;
    font-size: 12px;
}
.shipper_Table .width10{
    width: 15.3%;
}
.shipper_Table .width21{
    width: 21.7%;
}
p{
    font-size: 12px;
    margin-bottom: 0;
}
.shipper_Table .width27{
    width: 24.6%;
}
.address_Table .width30{width: 37%;}
.pieces_table .logo{
        width: 15.4%;
}
.pieces_table .barcode{
text-align: left;
    padding: 0 11px;
}
.empty_width{
    width: 24.5%;
}
.declared_table .logo{
    width: 21%;
}
.declared_table .barcode {
    width: 23%;
    text-align: left;
    padding: 0 11px;
}

.declared_table   .date_info{
width: 27.6%;
text-align: center;
}
.declared_table   .date_info img{
    width: 139px;
}
.product_table .logo{
        width: 1.6%;
}
.product_table .barcode {
    width:14.3%;
    text-align: left;
    padding: 6px 12px;
}
.footer_Center tr td{
text-align: center;
}
.footer_Center b{
    padding: 0 29px 5px;
    display: block;
}
.print_btn{
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
  margin:0px 0px;
    margin-top: 18px;
  /*height: 417px;*/
}
@media print {
    .table_invoice {
    page-break-inside: avoid;
  }
}
table, tr, td, li {border-color:black !important;}
</style>
</head>
<body id="page-name">
<?php if(!isset($_GET['print'])){ ?>
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
<div class="page-wrap">
   <?php
        if($print['value']>=1)
        {

      ?>
  <div class="table_invoice" id="table_invoice">
  <table>
  <tr>
    <td class="logo" style="padding: 20px 25px;"><img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="" style="width: 124px;"></td>
    <td class="barcode"  style="width: 22%;">
      <?php
        if(isset($info['barcode_image']))
        {
            echo '<img  src="'.$info['barcode_image'].'" style="width: 124px;" />';
            echo '<h2 style="text-align: center; font-size:10px;">'.$info['barcode'].'</h2>';
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
        <li><?php echo date(DATE_FORMAT, strtotime($info['order_date'])); ?></li>
        <li><?php echo isset($service) ? $service :  ''; ?></li>
        <li><?php  if(isset($info['origin'])) { echo $info['origin']; } ?></li>
      </ul>
    </td>
    <td>
      <ul>
        <li><b></b></li>
        <li><b>COD Amount:</b></li>
        <li><b>Destination</b></li>
      </ul>
    </td>
    <td>
      <ul>
        <li>Shipper Copy</li>
        <li><b style="font-size:14px;"><?php echo $currency['value']; ?>: <?php echo $info['collection_amount']; ?></b></li>
        <li><?php  if(isset($info['destination'])) { echo $info['destination']; } ?></li>
      </ul>
    </td>
  </tr>
</table>
<table class="shipper_Table">
  <tr>
    <td class="width10"  style="width:36.3%; text-align:center; background-color:#e0e0e0;"><b>Shipper</b></td>


    <td class="width27" style="width: 50%; text-align:center; background-color:#e0e0e0;">
      <b>Consignee</b>
    </td>


  </tr>
</table>
<table class="address_Table">
  <tr>
    <td class="width50"  style="width: 42.1%;padding: 0;    vertical-align: top;">
      <table >
        <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
          <th style="border:none; padding: 3px 5px;    width: 30%;">Company:</th>
          <td style="border:none; padding: 3px 5px;"><?php
             echo isset($webtitle['value']) ? $webtitle['value']:'';
          ?></td>
        </tr>
         <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
          <th style="border: none; padding: 3px 5px;">Phone No.:</th>
          <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['sphone'])) { echo $info['sphone']; } ?></td>
        </tr>
        <tr>
          <th style="border: none; padding: 3px 5px;">Address:</th>
          <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['sender_address'])) { echo $info['sender_address']; } ?></td>
        </tr>
      </table>

    </td>
    <td style="padding: 0;vertical-align: top;">
      <table>
        <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
        <th style="border: none; padding: 3px 5px;">Name:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['rname'])) { echo $info['rname']; } ?></td>
      </tr>
       <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
        <th style="border: none; padding: 3px 5px;">Phone No.:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['rphone'])) { echo $info['rphone']; } ?></td>
      </tr>
       <tr>
        <th style="border: none; padding: 3px 5px;">Address:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['receiver_address'])) { echo $info['receiver_address']; } ?></td>
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
    <td class="logo" >
    <b>Reference No#</b>
    </td>
    <td class="barcode" >
      <?php  echo isset($info['ref_no']) ? $info['ref_no'] : ''; ?>
    </td>
    <td class="barcode" >
      <b>Order ID: </b><?php echo $info['product_id']; ?>
    </td>
      <td class="barcode" colspan="1"><b>No of flyers= </b> <?php  if(isset($info['flyer_qty'])) { echo $info['flyer_qty']; } ?>
    </td>

  </tr>
         <tr>
    <td class="logo">
    <b>COD Amount</b>
    </td>
    <td class="barcode">
      <b><?php echo $currency['value']; ?>: <?php echo $info['collection_amount']; ?></b>
    </td>
    <td class="barcode">
      <b>Weight = <?php  if(isset($info['weight'])) { echo $info['weight']; } ?> Kg</b>
    </td>

    <td class="barcode">
      <b>No of pieces = <?php echo isset($info['quantity']) ? $info['quantity'] : '0'; ?></b>
    </td>
</tr>
      </table>
    </td>

  </tr>
</table>
<table class="product_table">


  </tr>
  <tr>
    <td class="logo" >
    <b>Product Description:</b>
    </td>
    <td class="barcode" colspan="5">
       <p><?php  if(isset($info['product_desc'])) { echo $info['product_desc']; } ?></p>
    </td>

  </tr>
  <tr>
    <td class="logo">
    <b>Special Instruction</b>
    </td>
    <td class="barcode"  colspan="5">
      <p><?php  if(isset($info['special_instruction'])) { echo $info['special_instruction']; } ?></p>
    </td>

  </tr>
<tr>
  <td colspan="6">
<?php echo $invoicefooter['value'] ?>
</td>
</tr>
<tr>

</td>
</tr>
</table>

</div>



 <?php }
        if($print['value']>=2)
        {
      ?>






<div class="table_invoice" id="table_invoice">
  <table>
  <tr>
    <td class="logo" style="padding: 20px 25px;"><img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="" style="width: 124px;"></td>
    <td class="barcode"  style="width: 22%;">
      <?php
        if(isset($info['barcode_image']))
        {
            echo '<img  src="'.$info['barcode_image'].'" style="width: 124px;" />';
            echo '<h2 style="text-align: center; font-size:10px;">'.$info['barcode'].'</h2>';
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
        <li><?php echo date(DATE_FORMAT, strtotime($info['order_date'])); ?></li>
        <li><?php echo isset($service) ? $service :  ''; ?></li>
        <li><?php  if(isset($info['origin'])) { echo $info['origin']; } ?></li>
      </ul>
    </td>
    <td>
      <ul>
        <li><b></b></li>
        <li><b>COD Amount:</b></li>
        <li><b>Destination</b></li>
      </ul>
    </td>
    <td>
      <ul>
        <li>Account Copy</li>
        <li><b style="font-size:14px;"><?php echo $currency['value']; ?>: <?php echo $info['collection_amount']; ?></b></li>
        <li><?php  if(isset($info['destination'])) { echo $info['destination']; } ?></li>
      </ul>
    </td>
  </tr>
</table>
<table class="shipper_Table">
  <tr>
    <td class="width10"  style="width:36.3%; text-align:center; background-color:#e0e0e0;"><b>Shipper</b></td>


    <td class="width27" style="width: 50%; text-align:center; background-color:#e0e0e0;">
      <b>Consignee</b>
    </td>


  </tr>
</table>
<table class="address_Table">
  <tr>
    <td class="width50"  style="width: 42.1%;padding: 0;    vertical-align: top;">
      <table >
        <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
          <th style="border:none; padding: 3px 5px;    width: 30%;">Company:</th>
          <td style="border:none; padding: 3px 5px;"><?php 
              if(isset($info['sbname'])){ echo $info['sbname'];
          ?></td>
        </tr>
         <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
          <th style="border: none; padding: 3px 5px;">Phone No.:</th>
          <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['sphone'])) { echo $info['sphone']; } ?></td>
        </tr>
        <tr>
          <th style="border: none; padding: 3px 5px;">Address:</th>
          <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['sender_address'])) { echo $info['sender_address']; } ?></td>
        </tr>
      </table>

    </td>
    <td style="padding: 0;vertical-align: top;">
      <table>
        <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
        <th style="border: none; padding: 3px 5px;">Name:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['rname'])) { echo $info['rname']; } ?></td>
      </tr>
       <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
        <th style="border: none; padding: 3px 5px;">Phone No.:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['rphone'])) { echo $info['rphone']; } ?></td>
      </tr>
       <tr>
        <th style="border: none; padding: 3px 5px;">Address:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['receiver_address'])) { echo $info['receiver_address']; } ?></td>
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
    <td class="logo" >
    <b>Reference No#</b>
    </td>
    <td class="barcode" >
      <?php
       echo isset($info['ref_no']) ? $info['ref_no'] : '';
        ?>
    </td>
    <td class="barcode" >
      <b>Order ID: </b><?php echo $info['product_id']; ?>
    </td>
      <td class="barcode" colspan="1"><b>No of flyers= </b> <?php  if(isset($info['flyer_qty'])) { echo $info['flyer_qty']; } ?>
    </td>

  </tr>
         <tr>
    <td class="logo">
    <b>COD Amount</b>
    </td>
    <td class="barcode">
      <b><?php echo $currency['value']; ?>: <?php echo $info['collection_amount']; ?></b>
    </td>
    <td class="barcode">
      <b>Weight = <?php  if(isset($info['weight'])) { echo $info['weight']; } ?> Kg</b>
    </td>

    <td class="barcode">
      <b>No of pieces = <?php echo isset($info['quantity']) ? $info['quantity'] : '0'; ?></b>
    </td>
</tr>
      </table>
    </td>

  </tr>
</table>
<table class="product_table">


  </tr>
  <tr>
    <td class="logo" >
    <b>Product Description:</b>
    </td>
    <td class="barcode" colspan="5">
       <p><?php  if(isset($info['product_desc'])) { echo $info['product_desc']; } ?></p>
    </td>

  </tr>
  <tr>
    <td class="logo">
    <b>Special Instruction</b>
    </td>
    <td class="barcode"  colspan="5">
      <p><?php  if(isset($info['special_instruction'])) { echo $info['special_instruction']; } ?></p>
    </td>

  </tr>
<tr>
  <td colspan="6">
<?php echo $invoicefooter['value'] ?>
</td>
</tr>
<tr>

</td>
</tr>
</table>

</div>

 <?php }
        if($print['value']>=3)
        {

      ?>





<div class="table_invoice" id="table_invoice">
  <table>
  <tr>
    <td class="logo" style="padding: 20px 25px;"><img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="" style="width: 124px;"></td>
    <td class="barcode"  style="width: 22%;">
      <?php
        if(isset($info['barcode_image']))
        {
            echo '<img  src="'.$info['barcode_image'].'" style="width: 124px;" />';
            echo '<h2 style="text-align: center; font-size:10px;">'.$info['barcode'].'</h2>';
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
        <li><?php echo date(DATE_FORMAT, strtotime($info['order_date'])); ?></li>
        <li><?php echo isset($service) ? $service :  ''; ?></li>
        <li><?php  if(isset($info['origin'])) { echo $info['origin']; } ?></li>
      </ul>
    </td>
    <td>
      <ul>
        <li><b></b></li>
        <li><b>COD Amount:</b></li>
        <li><b>Destination</b></li>
      </ul>
    </td>
    <td>
      <ul>
        <li>POD Copy</li>
        <li><b style="font-size:14px;"><?php echo $currency['value']; ?>: <?php echo $info['collection_amount']; ?></b></li>
        <li><?php  if(isset($info['destination'])) { echo $info['destination']; } ?></li>
      </ul>
    </td>
  </tr>
</table>
<table class="shipper_Table">
  <tr>
    <td class="width10"  style="width:36.3%; text-align:center; background-color:#e0e0e0;"><b>Shipper</b></td>


    <td class="width27" style="width: 50%; text-align:center; background-color:#e0e0e0;">
      <b>Consignee</b>
    </td>


  </tr>
</table>
<table class="address_Table">
  <tr>
    <td class="width50"  style="width: 42.1%;padding: 0;    vertical-align: top;">
      <table >
        <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
          <th style="border:none; padding: 3px 5px;    width: 30%;">Company:</th>
          <td style="border:none; padding: 3px 5px;"><?php 
              if(isset($info['sbname'])){ echo $info['sbname'];
          ?></td>
        </tr>
         <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
          <th style="border: none; padding: 3px 5px;">Phone No.:</th>
          <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['sphone'])) { echo $info['sphone']; } ?></td>
        </tr>
        <tr>
          <th style="border: none; padding: 3px 5px;">Address:</th>
          <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['sender_address'])) { echo $info['sender_address']; } ?></td>
        </tr>
      </table>

    </td>
    <td style="padding: 0;vertical-align: top;">
      <table>
        <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
        <th style="border: none; padding: 3px 5px;">Name:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['rname'])) { echo $info['rname']; } ?></td>
      </tr>
       <tr style="    border: 1px solid #dddddd;border-top: none;border-left: none;border-right: none;">
        <th style="border: none; padding: 3px 5px;">Phone No.:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['rphone'])) { echo $info['rphone']; } ?></td>
      </tr>
       <tr>
        <th style="border: none; padding: 3px 5px;">Address:</th>
        <td style="border: none; padding: 3px 5px;"><?php  if(isset($info['receiver_address'])) { echo $info['receiver_address']; } ?></td>
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
    <td class="logo" >
    <b>Reference No#</b>
    </td>
    <td class="barcode" >
      <?php echo isset($info['ref_no']) ? $info['ref_no'] : ''; ?>
    </td>
    <td class="barcode" >
      <b>Order ID: </b><?php echo $info['product_id']; ?>
    </td>
      <td class="barcode" colspan="1"><b>No of flyers= </b> <?php  if(isset($info['flyer_qty'])) { echo $info['flyer_qty']; } ?>
    </td>

  </tr>
         <tr>
    <td class="logo">
    <b>COD Amount</b>
    </td>
    <td class="barcode">
      <b><?php echo $currency['value']; ?>: <?php echo $info['collection_amount']; ?></b>
    </td>
    <td class="barcode">
      <b>Weight = <?php  if(isset($info['weight'])) { echo $info['weight']; } ?> Kg</b>
    </td>

    <td class="barcode">
      <b>No of pieces = <?php echo isset($info['quantity']) ? $info['quantity'] : '0'; ?></b>
    </td>
</tr>
      </table>
    </td>

  </tr>
</table>
<table class="product_table">


  </tr>
  <tr>
    <td class="logo" >
    <b>Product Description:</b>
    </td>
    <td class="barcode" colspan="5">
       <p><?php  if(isset($info['product_desc'])) { echo $info['product_desc']; } ?></p>
    </td>

  </tr>
  <tr>
    <td class="logo">
    <b>Special Instruction</b>
    </td>
    <td class="barcode"  colspan="5">
      <p><?php  if(isset($info['special_instruction'])) { echo $info['special_instruction']; } ?></p>
    </td>

  </tr>
<tr>
  <td colspan="6">
<?php echo $invoicefooter['value'] ?>
</td>
</tr>
<tr>

</td>
</tr>
</table>

</div>


 <?php }

      ?>
</div>




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
