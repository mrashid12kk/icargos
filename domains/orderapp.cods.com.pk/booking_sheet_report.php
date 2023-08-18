<?php 
session_start();
$access_token = $_SESSION['access_token'];
// Get our helper functions
include_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
$requests = $_GET;


$get_pref = mysqli_query($con,"SELECT * FROM  preferences WHERE `access_token`='".$access_token."' ");
if(isset($_POST['save_print']) && !empty(json_decode($_POST['print_data']))){
  $order_ids = json_decode($_POST['print_data']);
  $track_nos = implode(',',$order_ids);

  }
if(mysqli_num_rows($get_pref) >0 && !empty($track_nos)){
    $pref_res = mysqli_fetch_array($get_pref);
    $auth_key = $pref_res['auth_key'];
    $url = COURIER_URL.'API/Loadsheet.php?auth_key='.$auth_key.'&search='.$track_nos;
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
    <title>Booking Sheet</title>
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

.table td, .table th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 6px 6px;
  vertical-align: top;
  font-size: 11px;
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



</head>
<body id="page-name">


<div class="clearfix booked_packge">
  <div class="pacecourier_logo">
    <img src="<?php echo COURIER_URL; ?>assets/img/logo/happ2.png" alt="" height="100px;">
  </div>
  <div class="booked_packges">
    <h4>Booking Sheet:</h4>
    <ul>
      <li><b>Date:</b> <p><?php echo date("d M Y h:i") ?></p></li>
      <li><b>Company:</b> <p><?php echo isset($customer_data['bname']) ? $customer_data['bname'] : ''; ?></p></li>
      <li><b>Client Code:</b> <p><?php echo isset($customer_data['client_code']) ? $customer_data['client_code'] : ''; ?></p></li>
      <li><b>Phone:</b> <p><?php echo isset($customer_data['mobile_no']) ? $customer_data['mobile_no'] : ''; ?></p></li>
      <li><b>Address:</b> <p><?php echo isset($customer_data['address']) ? $customer_data['address'] : ''; ?></p></li>
    </ul>
  </div>
</div>

<table class="table">
  <tr>
  	<th>S.No</th>
    <th>Date</th>
    <th>Tracking No</th>
    <th>Consignee Name</th>
    <th>Consignee Phone</th>
    <th>Qty</th>
    <th>Destination</th>
    <th>Weight</th>
    <th>COD Amount</th>
  </tr>
  <?php 
  $sr=1;
  foreach($response as $fetch1){
  	$totla_pieces += $fetch1['quantity'];
  	$total_weight += $fetch1['weight'];
  	$total_cod += $fetch1['collection_amount'];
   ?>
  <tr>
  	<td><?php echo $sr; ?></td>
    <td><?php echo date('d M Y',strtotime($fetch1['order_date'])); ?></td>
    <td><?php echo $fetch1['tracking_no']; ?></td>
    
    <td>
         <?php echo $fetch1['receiver_name']; ?>
    </td>
     <td>
        <?php echo $fetch1['receiver_phone']; ?>
    </td>
    
    <td><?php echo isset($fetch1['quantity']) ? $fetch1['quantity'] : '0'; ?> </td>
    <td><?php echo isset($fetch1['destination']) ? $fetch1['destination'] : ''; ?></td>
    <td><?php echo isset($fetch1['weight']) ? $fetch1['weight'] : ''; ?> Kg</td>
    <td>
      Rs <?php echo number_format((float)$fetch1['collection_amount'],2); ?></li>
      </ul>
    </td>
  </tr>
<?php $sr++; } ?>

  
</table>

<div class="total_itmes">
  <p><b>Total No. Of Pieces: </b> <?php echo $totla_pieces; ?></p>
  <p><b>Total Weight: </b> <?php echo $total_weight; ?> Kg</p>
  <p><b>Total COD Amount: Rs </b> <?php echo number_format((float)$total_cod,2); ?></p>
</div>
<div class="clearfix">
  <div class="left_signature">
    <p>Client Signature:_________________</p>
  </div>
  <div class="left_signature right_signature">
    <p>Receiving Staff Signature:_________________</p>
  </div>
</div>
<script type="text/javascript">window.print(); setTimeout(function() { window.close(); }, 2000); </script>
</body>
</html>