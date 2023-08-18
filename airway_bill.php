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
      /*background: #f4f4f4;*/
      -webkit-print-color-adjust: exact !important;
    }
    .table_invoice tbody tr th {
      background: #f4f4f4;
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
    <a href="<?php echo $url ?>&print=1" style="position: absolute;"  class="print_btn" target="_blank" >Print</a>
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
        $commercial_invoice_query=mysqli_query($con,"SELECT * FROM order_commercial_invoice WHERE order_id=".$info['id']);
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
              <th colspan="2" style="text-align: center;">Shipper</th>
              <th colspan="2" style="text-align: center;">Consignee</th>
            </tr>

            <tr>
              <th class="w-custom">Account ID</th>
              <td class="td_width"><?php echo $customerData['client_code']; ?></td>
              <th class="w-custom">Company Name</th>
              <td class="td_width"><?php echo $info['rname']; ?></td>
            </tr>

            <tr>
              <th class="w-custom">Shipper Name</th>
              <td class="td_width"><?php echo isset($info['sname']) ? $info['sname'] : $customerData['bname'];
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
              <th>Shipment Details</th>
              <td></td>
              <th >IS - INT'L STANDARD</th>
              <td></td>
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
            <th>Received By</th>
            <td></td>
          </tr>
          <tr>
            <th>Pieces /Dimensional Weight /Chargable Weight</th>
            <td><?php echo $info['quantity'].' / '.$info['weight'] .'/'.$info['cweight']; ?></td>
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
            <td>
            <?php
      $total_qty=0;
      $total_amount=0;
      while ($row=mysqli_fetch_array($commercial_invoice_query)) { 
        $total_qty+=$row['c_i_pieces'];
        $total_amount+=$row['c_i_hs_total'];?>
        <span><?php echo isset($total_amount) ? $total_amount : '0' ?> <?php echo isset($info['customer_currency']) ? $info['customer_currency'] : getConfig('currency'); ?></span>,
      <?php } ?>
          </td>
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
