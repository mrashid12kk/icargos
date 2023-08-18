<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");
include_once "includes/conn.php";
$companyname = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname' "));





?>
<!DOCTYPE html>
<!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Customer Assignment Sheet</title>
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
@media print {
    .single_page {
    page-break-after:always
  }
}
   </style>



<style type="text/css">
.left_box {
    float: left;
    width: 50%;
    margin-bottom: 32px;
}
.left_box b, .right_box b {
    font-weight: bold;
    font-size: 15px;
    vertical-align: middle;
    margin-top: 1px;
    display: inline-block;
    width: 20%;
    text-align: right;
    margin-right: 5px;
}
.left_box p, .right_box p {
    display: inline-block;
    margin: 0;
    float: right;
    width: 78%;
}
.head_,.right_box .head_{
    margin: 0 0 5px;
}
.right_box {
    float: right;
    width: 38%;
}
.right_box b {
   width: 30%;
}
.right_box p{
width: 67%;
}


</style>

</head>
<body id="page-name">


<div id="main_page">
<?php
if(isset($_GET['assignment_no']) && !empty($_GET['assignment_no'])){
  $assignment_no = $_GET['assignment_no'];

    $result=mysqli_query($con,"select * from assignments where assignment_no='".$assignment_no."' ");
    $result_fetch = mysqli_fetch_array($result);
  }
  $date = $result_fetch['created_on'];
  $assignment_no = $result_fetch['assignment_no'];
$business_ids = explode(',', $result_fetch['business_ids']);
$rider_q = mysqli_query($con,"SELECT Name FROM users WHERE id='".$result_fetch['rider_id']."' ");
$rider_res = mysqli_fetch_array($rider_q);
$rider_name = $rider_res['Name'];

$get_branch_name = mysqli_query($con,"SELECT current_branch from orders where assignment_no=".$_GET['assignment_no']);
$current_branch = mysqli_fetch_assoc($get_branch_name);
if (isset($current_branch['current_branch']) && !empty($current_branch['current_branch'])) {
  $get_branch_q = mysqli_query($con,"SELECT name from branches where id=".$current_branch['current_branch']);

  $current_branch_name = mysqli_fetch_assoc($get_branch_q);
}else{
  $current_branch_name['name'] = 'Admin Branch';
}

foreach($business_ids as $single_company)
{
  $business_id = $single_company;
  $business_q = mysqli_query($con,"SELECT * FROM customers WHERE id='".$business_id."' ");
  $business_record = mysqli_fetch_array($business_q);
  // print_r($business_record);
  // die();
 ?>
  <div class="single_page">
    <div class="" style="text-align: center;    padding: 22px 0 0;">
      <img src="<?php echo BASE_URL ?>admin/<?php echo getConfig('logo'); ?>" alt="" style="width: 124px;">
    </div>
     <h1 style="text-align:center;"><?php print_r($companyname['value']);?></h1>
     <h4 style="text-align:center;">Pickup Sheet</h4>

<div class="clearfix">
  <div class="left_box">
      <div class="head_">
        <b>Account :</b>
        <p><?php echo $business_record['fname'] .' ('.$business_record['bname'].' )'; ?></p>
      </div>

      <div class="head_">
        <b>Email :</b>
        <p><?php echo $business_record['email']; ?></p>
      </div>
      <div class="head_">
        <b>Phone : </b>
        <p><?php echo $business_record['mobile_no']; ?></p>
      </div>
      <div class="head_">
        <b>Address :</b>
        <p><?php echo $business_record['address']; ?></p>
      </div>
      <div class="head_">
        <b>City :</b>
        <p><?php echo $business_record['city']; ?></p>
      </div>
  </div>

  <div class="right_box">
      <div class="head_">
        <b>Rider Name:</b>
        <p><?php echo $rider_name; ?></p>
      </div>

      <div class="head_">
        <b>Branch :</b>
        <p><?php echo $current_branch_name['name']; ?></p>
        <!-- <p><?php echo $business_record['city']; ?></p> -->
      </div>
      <div class="head_">
        <b>Date :</b>
        <p><?php echo date(DATE_FORMAT,strtotime($date)); ?></p>
      </div>
      <div class="head_">
        <b>Assignment No :</b>
        <p><?php echo $assignment_no; ?></p>
      </div>
  </div>



</div>


<table class="table">
  <tr>
    <th>S.No</th>
    <th>Tracking Number</th>
    <th>Name</th>
    <th>Address</th>
    <th>Phone Number</th>
    <th>Delivered to City</th>
    <th>Weight</th>
    <th>No. of Pieces</th>
    <th>COD Amount</th>
  </tr>
  <?php

  $queries = mysqli_query($con,"SELECT track_no,sname,sbname,sender_address,sphone,destination,weight,quantity,collection_amount FROM orders WHERE customer_id='".$business_id."' AND assignment_no='".$assignment_no."'  ");

  $sr=1;
$total_pieces = 0;
  $total_weight = 0;
  $total_cod = 0;
  $total_delivery = 0;
  while($single_order = mysqli_fetch_array($queries)){
    $total_pieces += $single_order['quantity'];
    $total_weight += $single_order['weight'];
    $total_cod += $single_order['collection_amount'];
   ?>
  <tr>
    <td><?php echo $sr; ?></td>
    <td><?php echo $single_order['track_no']; ?></td>
    <td>
         <?php echo $single_order['sname']; ?>
    </td>
    <td><?php echo $single_order['sender_address']; ?></td>
     <td>
        <?php echo $single_order['sphone']; ?>
    </td>
     <td>
        <?php echo $single_order['destination']; ?>
    </td>
    <td><?php echo isset($single_order['weight']) ? $single_order['weight'] : '0'; ?> Kg</td>
    <td><?php echo isset($single_order['quantity']) ? $single_order['quantity'] : '0'; ?> </td>

    <td>
      <?php echo number_format((float)$single_order['collection_amount'],2); ?></li>
      </ul>
    </td>
  </tr>
<?php $sr++; } ?>


</table>

<div class="total_itmes">
  <p><b>Total No. Of Pieces: </b> <?php echo $total_pieces; ?></p>
  <p><b>Total Weight: </b> <?php echo $total_weight; ?> Kg</p>
  <p><b>COD Amount: Rs </b> <?php echo number_format((float)$total_cod,2); ?></p>
</div>
<div class="clearfix">
  <div class="left_signature">
    <p>Signature:_________________</p>
  </div>
  <div class="left_signature right_signature">
    <p>Signature:_________________</p>
  </div>
</div>
  </div>
<?php } ?>
</div>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function(){
    window.print();
  }, false);
</script>
</body>
</html>
