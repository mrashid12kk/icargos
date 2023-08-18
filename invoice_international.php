<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
// var_dump($logo_img);
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
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $cfg['lang_invoice']?> No. #<?php echo $info['id']; ?></title>
	</head>
<style>
	body {
		font-family: 'Roboto', sans-serif;
	}
	
	.sect {
		border: 1px solid #000;
		background-color: #AAAAAA;
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
	}
	
	p {
		font-size: 13px;
	}
	
	h5 {
		margin: 5px 0;
		font-size: 15px;
	}
	
	.row {
		display: flex;
		width: 100%;
		border: 1px solid #000;
		text-align: center;
	}
	
	.sm-box2 p {
		margin: 5px 0;
	}
	
	.row1 p {
		margin: 0;
	}
	
	.row1,
	.row2,
	.row3 {
		display: flex;
		text-align: center;
	}
	
	.row10,
	.row8,
	.row9 {
		border: none;
	}
	
	.row0 {
		margin-top: 10px;
	}
	
	.row2 p,
	.row1 p,
	.row3 p {
		padding: 0 10px;
		margin: 0px;
	}
	/*.row2{
			    margin: 0 5%;
		}*/
	
	.b1 {
		background-color: #fff;
		width: 44%;
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
		border-right: 1px solid #000;
	}
	
	.b2 {
		width: 55%;
	}
	
	.b1 h5,
	.b2 h5 {
		text-decoration: underline;
	}
	
	.row10 h5 {
		text-decoration: underline;
		font-size: 15px;
		margin: 10px 0;
	}
	
	.b3 {
		background-color: #fff;
		/*border: 1px solid #000;*/
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
		margin: 0px 45px;
	}
	
	.text1 {
		font-size: 12px;
		margin: 0;
		text-transform: uppercase;
	}
	
	.sm-box {
		background-color: #AAAAAA;
		padding: 3px;
		font-size: 12px;
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
	}
	
	.row4,
	.row5,
	.row6,
	.row7 {
		background-color: #fff;
		border: none;
	}
	
	.lg_box {
		margin: 8px;
		width: 25%;
		border: 1px solid #000;
	}
	
	.lg_box1 {
		margin: 8px;
		width: 33.3%;
		border: 1px solid #000;
	}
	
	.sm-box1 {
		border-top: 1px solid black;
		padding: 2px 3px;
		font-size: 13px;
	}
	
	.lg_box2 {
		border: 1px solid #000;
		font-size: 12px;
	}
	
	.sm-box2 {
		background-color: #AAAAAA;
		padding: 0 10px;
		/*border-bottom: 1px solid black;*/
	}
	
	.sm-box10 {
		border-top: 1px solid black;
		height: 17px;
	}
	
	.sm-box1 p {
		margin: 4px;
	}
	
	img {
		width: 100%;
		height: auto;
	}
	
	.row8 .lg_box2 {
		padding: 5px 0;
	}
	
	.attn {
		text-align: left;
		font-size: 13px;
		margin-left: 25px;
	}

	.table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
		}

		.table th {
		    background-color: #AAAAAA;
		    -webkit-print-color-adjust: exact !important;
		    print-color-adjust: exact !important;
		    padding: 0 10px;
		}
		.sm-box2,.lg_box2{
			 -webkit-print-color-adjust: exact !important;
		    print-color-adjust: exact !important;
		}
		.table td, .table th {
		    border: 1px solid #000;
		    padding: 5px 8px;
		    font-size: 12px;
		}
		.table tfoot tr td{border-bottom: none;}
	

	
	@media print {
		@page {
			margin: 0;
		}
		/*#border_left {
			border-left: 1px solid #000 !important;
		}
		.row4,
		.row5,
		.row6,
		.row7,
		.row10,
		.row8,
		.row9 {
			border-top: 1px solid #000;
			border-left: 1px solid #000;
		}*/
		.b3 {
			margin: 0px 4px;
		}
		#border-bottom{
			border-bottom: 0 solid #000;
			margin-bottom: 0;
		}
		#border_left{
			border-left: 0 solid #000;
		}

	}
</style>

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
    function getCustomer($customer_id)
  {
    $cust_detail = "";
    global $con;
    $sql= "SELECT * FROM customers   WHERE id  = '".$customer_id."'  ";
    $query_order_cus = mysqli_query($con,$sql);
    $cust_detail = mysqli_fetch_array($query_order_cus);
    return $cust_detail;
  }

  $track_no='';
 if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $invoice_id = explode(',', $_GET['order_id']);
    foreach ($invoice_id as $key=>$value) {
        $track_no_q=mysqli_fetch_assoc(mysqli_query($con,"SELECT track_no from orders WHERE id=".$value));
        $track_no.=$track_no_q['track_no'].',';
    }
}
$track_no=trim($track_no , ',');
 if(!isset($_GET['print'])){ ?>
    <a href="<?php echo $url ?>&print=1"  class="print_btn" target="_blank" >Print</a>
  <?php }else{ ?>
    <script type="text/javascript">window.print();</script>
  <?php
   }?>
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
   <?php if(isset($_GET['order_id']) && !empty($_GET['order_id'])){

    if (!$_GET['booking']) { $invoice_ids = explode(',',$_GET['order_id']);
    }
    else{
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
     // var_dump($customerData);
     $order_type_id = $info['order_type'];
     $get_service = mysqli_query($con,"SELECT service_type FROM services WHERE id='".$order_type_id."' ");
     $servie_q_res = mysqli_fetch_array($get_service);
     $service=$servie_q_res['service_type'];
     $origin_city=mysqli_fetch_assoc($area_q = mysqli_query($con, "SELECT * FROM areas WHERE id=" . $info['origin_area_id']));
     // var_dump($info);
 ?>
     
						<div class="sect" style="background-color: #AAAAAA;    -webkit-print-color-adjust: exact !important;
					    print-color-adjust: exact !important;">
						<div class="row row0" style="
					    border-left: none;
					">
									<div class="b1" style="
					    text-align: center;
					    padding: 0 66px;
					    font-size: 13px;
					">
										<?php echo getConfig('address'); ?><br><?php echo getConfig('contactno'); ?><br><?php echo getConfig('website'); ?>
									</div>
									<div class="b2">
										<div class="row2" style="
					    display: inline-block;
					">
											<p style="
					    display: inline-block;
					"><b>MTN NO. <?php echo $track_no; ?></b></p>
											<!-- <p style="
					    display: inline-block;
					"><b>SRB NO. S875534W354-6</b></p> -->
										</div>
										<div class="b3" id="border-bottom">
											<h5>SALES TAX INVOICE</h5>
											<div class="row3" style="
					    display: inline-block;
					">
												<p style="
					    display: inline-block;
					"><b>INVOICE NO.</b><?php echo $track_no; ?></p>
							<p style="float: right;display: inline-block;"><b>DATE.</b> <?php echo date("Y-m-d");?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row row4">
				<div class="lg_box">
					<div class="sm-box"><b>MAWB / MBL NO:</b></div>
					<div class="sm-box1"><?php echo $track_no; ?></div>
				</div>
				<div class="lg_box">
					<div class="sm-box"><b>HAWB / HBL NO:</b></div>
					<div class="sm-box1"><?php echo $track_no; ?></div>
				</div>
				<div class="lg_box">
					<div class="sm-box"><b>REF NO:</b></div>
					<div class="sm-box1">
						<!-- <div>GBPL-00111</div> -->
						<div><b>REF NO. <?php echo isset($info['ref_no']) ? $info['ref_no'] : ''; ?></b></div>
					</div>
				</div>
				<div class="lg_box">
					<div class="sm-box"><b>SHIPMENT DATE</b></div>
					<div class="sm-box1"><?php echo  date(DATE_FORMAT, strtotime($info['order_date'])); ?></div>
				</div>
			</div>
			<div class="row row4" style=";">
				<div class="lg_box">
					<div class="sm-box"><b>ORIGIN AIRPORT</b></div>
					<div class="sm-box1"><?php echo $info['sstate']; ?></div>
				</div>
				<div class="lg_box">
					<div class="sm-box"><b>DESTINATION </b></div>
					
					<div class="sm-box1"><?php echo $info['destination']; ?></div>
				</div>
				<div class="lg_box">
					<div class="sm-box"><b>SERVICE</b></div>
					<div class="sm-box1"><?php echo $service; ?></div>
				</div>
				<div class="lg_box">
					<div class="sm-box"><b>EQUIPMENT</b></div>
					<div class="sm-box1">BY AIR</div>
				</div>
			</div>
			<div class="row row5">
				<div class="lg_box1">
					<div class="sm-box"><b>PAYEE NAME &amp; ADDRESS </b></div>
					<div class="sm-box1"><?php echo $customerData['fname'].','.$customerData['address']; ?></div>
					<div class="attn">
						<!-- <div><b>ATTN: </b>MR. MUKARRAM </div> -->
						<div><b>TEL #: </b><?php echo $customerData['mobile_no']; ?> </div>
					</div>
				</div>
				<div class="lg_box1">
					<div class="sm-box"><b>SHIPPER NAME &amp; ADDRESS </b></div>
					<div class="sm-box1">
						<p><?php echo $info['sname'].','.$info['sender_address']; ?></p>

						 </div>
					<div class="attn">
						<!-- <div><b>ATTN: </b>Candy</div> -->
						<div><b>TEL: </b><?php echo $info['sphone']; ?></div>
					</div>
				</div>
				<div class="lg_box1">
					<div class="sm-box"><b>CONSIGNEE NAME &amp; ADDRESS</b></div>
					<div class="sm-box1">
						<p><?php echo $info['rname'].','.$info['receiver_address']; ?></p>
					</div>
					<div class="attn">
						<!-- <div><b>ATTN: </b>MR. MUKARRAM </div> -->
						<div><b>TEL: </b><?php echo $info['rphone']; ?> </div>
					</div>
				</div>
			</div>
		</div>
		<div class="sect2">
			<div class="row row6">
				<div class="lg_box2" style="width:9%;border-top: none;border-right: none;">
					<div class="sm-box2"><b>PCs</b></div>
					<div class="sm-box1"><?php echo isset($info['quantity']) ? $info['quantity'] : '0'; ?></div>
				</div>
				<div class="lg_box2" style="width:46%;border-top: none;border-right: none;">
					<div class="sm-box2"><b>DESCRIPTION OF GOODS</b></div>
					<div class="sm-box1"><?php echo $info['description']; ?></div>
				</div>
				<div class="lg_box2" style="width:45%;border-top: none;">
					<div class="sm-box2"><b>GROSS WEIGHT</b></div>
					<div class="sm-box1"><?php echo $info['cweight']; ?> KG</div>
				</div>
				<!-- <div class="lg_box2" style="width:30%;border-top: none;">
					<div class="sm-box2"><b>DIMENSIONS</b></div>
					<div class="sm-box1"><?php // echo $info['length'].'X'.$info['width'].'X'.$info['height']; ?> </div>
				</div> -->
			</div>

			<div class="row row7" style="">
				  <?php 
				  	  $sql_q = "SELECT * FROM order_commercial_invoice WHERE order_id= '".$info['id']."' ORDER BY  id ";
  $commercial_invoice_query=mysqli_query($con,$sql_q);
// var_dump($commercial_invoice_query);
  if (mysqli_affected_rows($con) > 0) {
    ?>
				<table class="table">
      <tbody>
       <tr>
        <th style="border-top: none;">#.</th>
        <!-- <th>DIMENSIONS</th> -->
        <th style="border-top: none;">DESCRIPTION</th>
        <th style="border-top: none;">QTY</th>
        <th style="border-top: none;">UNIT VALUE</th>
        <th style="border-top: none;">AMOUNT</th>
        <!-- <th>DIMENSIONS</th> -->
      </tr>
      <?php
      $srno=1;
      $total_qty=0;
      $total_amount=0;
      while ($row=mysqli_fetch_array($commercial_invoice_query)) { 
      	// var_dump($row);
        $total_qty+=$row['c_i_pieces'];
        $total_amount+=$row['c_i_hs_total'];?>        
        <tr>
          <td><?php echo $srno++; ?></td>
          <td><?php echo $row['c_i_discription']; ?></td>
          <td><?php echo $row['c_i_pieces']; ?></td>
          <td><?php echo $row['c_i_price']; ?></td>
          <td><?php echo $row['c_i_hs_total']; ?></td>
          <!-- <td><?php // echo $row['c_i_length'] .'X'.$row['c_i_width'] .'X'.$row['c_i_height']; ?></td> -->
        </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3">Please Pay This Amount</td>
        <!-- <td><?php echo isset($total_qty) ? $total_qty : '0' ?></td> -->
        <td colspan="2" style="background-color: yellow;    -webkit-print-color-adjust: exact !important;"><?php echo $info['customer_currency'].' '.$total_amount;?></td>
        <!-- <td ><?php echo isset($total_amount) ? $total_amount : '0' ?></td> -->
      </tr>
    </tfoot>
  </table>
  <?php
}
  ?>
			</div>
			<div class="row row9" style="text-align: left;">
				<div class="lg_box2" style="width:47%;">
					<div class="sm-box2" style="background-color: #fff;">
						
						<p><b>Name :</b><?php
						if(isset($customerData['bname'])){
						echo $customerData['bname'];
						}
						else {
							echo $customerData['fname'];
						}
						?>
						</p>
						<p><b>Phone :</b> <?php echo $info['sphone'];?></p>
						<p><b>Email :</b> <?php echo $info['semail'];?></p>
						<p><b>CNIC :</b> <?php echo $info['scnic'];?></p>
						
					</div>
					<!-- <div class="sm-box2" style="background-color: #fff;">
						<p><b>Benificiary:</b> GREEN BOX PRIVATE LIMITED</p>
						<p><b>Currency:</b> PKR</p>
						<p><b>Account No:</b> 9981-0106247090</p>
						<p><b>Bank :</b> MEEZAN BANK</p>
						<p><b>Branch : </b>CAUSEWAY, KORANGI, KARACHI</p>
						<p><b>Branch Code :</b></p>
						<p><b>Swift Code :</b></p>
						<p><b>IBAN:</b></p>
					</div> -->
				</div>
				<div id="border_left" style="width:6%;background-color: #AAAAAA;border-top: 1px solid #000;border-bottom: 1px solid #000;-webkit-print-color-adjust: exact !important;
  print-color-adjust:exact !important;"> </div>
				<div class="lg_box2" style="width:47%;">
					<div class="sm-box2" style="background-color: #fff;">
						<p><b>Name:</b> <?php echo $info['rname'];?></p>
						<p><b>phone:</b> <?php echo $info['rphone'];?></p>
						<p><b>Email :</b> <?php echo $info['remail'];?></p>
						<p><b>Reciever Address :</b> <?php echo $info['receiver_address'];?></p>
						
					</div>
				</div>
			</div>
		</div>
		<div class="sect2" style="page-break-after: always;">
			<div class="row row10">
				<div class="lg_box2" id="border_left" style="width: 82%;border-top: none;border-right: none;border-left: 1px solid #000;">
					<div class="sm-box2" style="border: none;background-color: #fff;text-align: left;">
						<h5><b>Terms &amp; Conditions:</b></h5>
						<p style=""><?php
						 echo getConfig('invoicefooter'); ?></p>
					</div>
				</div>
				<div class="lg_box2" style=" background: #e1e1e1;
					    -webkit-print-color-adjust: exact !important;
					    print-color-adjust: exact !important;width: 19%;border-top: honeydew;"> <img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="image" style="
					        display: block;
					    height: 120px;
					    object-fit: contain;
					    padding: 4px;
					    width: 94%;
					">
				</div>
			</div>
		</div>
<?php } $invoice_print++;

} ?>
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