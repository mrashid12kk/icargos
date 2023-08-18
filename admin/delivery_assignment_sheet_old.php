<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
include_once "includes/conn.php";
$companyname = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname' "));


$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));



?>
<!DOCTYPE html>
<!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delivery Assignment Sheet</title>
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


.icargo_box li {
    display: inline-block;
    width: 33%;
}
.icargo_box ul{
      padding: 0;
    text-align: center;
}
.icargo_box li h5{
      margin: 0;
    margin-top: -5px;
}
.track_image_code{
  margin: 0;
    margin-top: -5px;
    text-align: center;
}
.text-center{
  text-align: center !important;
}


@media print {
    .single_page {
    page-break-after:always
  }
   .icargo_box li {

    width: 32%;
}
.right_box p {
    width: 63%;
}
.right_box b {
    width: 32%;
}
.right_box {
    width: 45%;
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
.left_box p, .right_box p {
    font-size: 12px;
}
.left_box b, .right_box b {
    font-size: 12px;
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
   $barcode_image = $result_fetch['barcode_image'];
   $destination_name = $result_fetch['destination'];
$rider_q = mysqli_query($con,"SELECT id,Name FROM users WHERE id='".$result_fetch['rider_id']."' ");
$rider_res = mysqli_fetch_array($rider_q);
$rider_id = $rider_res['id'];
$rider_name = $rider_res['Name'];
// echo "SELECT id,Name FROM users WHERE id='".$result_fetch['rider_id']."' ";
//   die();
// echo "SELECT branch_id from users where Name=".$rider_name;
// die();
$get_branch_name = mysqli_query($con,"SELECT branch_id from users where id=".$rider_res['id']);
  $current_branch = mysqli_fetch_assoc($get_branch_name);
  // print_r($current_branch);
  // die();
  if (isset($current_branch['branch_id']) && !empty($current_branch['branch_id'])) {
    $get_branch_q = mysqli_query($con,"SELECT name from branches where id=".$current_branch['branch_id']);

    $current_branch_name = mysqli_fetch_assoc($get_branch_q);
  }else{
    $current_branch_name['name'] = 'Admin Branch';
  }
?>
  <div class="single_page">

    <div class="icargo_box">
       <ul>
         <li>
           <img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="" style="width: 124px;">
         </li>
         <li>
           <h1 style="text-align:center;"><?php print_r($companyname['value']);?></h1>
            <h4 style="text-align:center;"><?php echo getLange('deliveryrunsheet'); ?></h4>
         </li>
         <li><img src="<?php echo $barcode_image; ?>"><h5><?php echo $result_fetch['assignment_no'] ?></h5></li>

       </ul>
     </div>


<div class="clearfix">
  <div class="left_box">

  </div>

  <div class="right_box">
      <div class="head_">
        <b><?php echo getLange('rider').' '.getLange('name'); ?>:</b>
        <p><?php echo $rider_name; ?></p>
      </div>

      <div class="head_">
        <b><?php echo getLange('date'); ?> :</b>
        <p><?php echo date('d M Y',strtotime($date)); ?></p>
      </div>
       <div class="head_">
        <b><?php echo getLange('assignmentno'); ?> :</b>
        <p><?php echo $assignment_no; ?></p>
      </div>
      <?php if (isset($destination_name) && !empty($destination_name)): ?>
        <div class="head_">
        <b><?php echo getLange('destination'); ?> :</b>
        <p><?php echo $destination_name; ?></p>
      </div>
      <?php endif ?>


      <div class="head_">
        <b><?php echo getLange('branch'); ?> :</b>
        <p><?php echo $current_branch_name['name']; ?></p>
      </div>
  </div>



</div>


<table class="table">
  <tr>
     <th><?php echo getLange('srno'); ?></th>
    <th><?php echo getLange('trackingno'); ?></th>
    <th><?php echo getLange('bussinessacco'); ?></th>
      <th><?php echo getLange('name'); ?></th>
    <th><?php echo getLange('address'); ?></th>
    <th><?php echo getLange('phoneno'); ?></th>
     <th><?php echo getLange('delivered').' '.getLange('city'); ?></th>
    <th><?php echo getLange('codamount'); ?></th>
    <th><?php echo getLange('signature'); ?></th>
  </tr>
  <?php

  $queries = mysqli_query($con,"SELECT barcode_image, track_no,rname,receiver_address,customer_id,rphone,destination,weight,quantity,collection_amount FROM orders WHERE delivery_rider='".$rider_id."' AND delivery_assignment_no='".$assignment_no."'  ");



  $sr=1;
$total_pieces = 0;
  $total_weight = 0;
  $total_cod = 0;
  $total_delivery = 0;
  while($single_order = mysqli_fetch_array($queries)){
    $customer_id = $single_order['customer_id'];
    $cus_q = mysqli_query($con,"SELECT fname,bname FROM customers WHERE id='".$customer_id."' ");
    $cus_q_res = mysqli_fetch_array($cus_q);
    $business_acc = $cus_q_res['bname'];
    $total_pieces += $single_order['quantity'];
    $total_weight += $single_order['weight'];
    $total_cod += $single_order['collection_amount'];
   ?>
  <tr>
    <td><?php echo $sr; ?></td>
    <td class="text-center">
      <img src="<?php echo BASE_URL.$single_order['barcode_image'];?>" class="text-center">
      <h3 class="track_image_code"><?php echo $single_order['track_no']; ?></h3>

      </td>
    <td>
         <?php echo $business_acc; ?>
    </td>
    <td>
         <?php echo $single_order['rname']; ?>
    </td>
    <td><?php echo $single_order['receiver_address']; ?></td>
     <td>
        <?php echo $single_order['rphone']; ?>
    </td>
     <td>
        <?php echo $single_order['destination']; ?>
    </td>
    <td>
      <?php echo number_format((float)$single_order['collection_amount'],2); ?>
    </td>
    <td>
      <textarea rows="4"></textarea>
    </td>
  </tr>
<?php $sr++; } ?>


</table>

<div class="total_itmes">

  <p><b><?php echo getLange('totalcodamount'); ?>: <?php echo getConfig('currency'); ?> </b> <?php echo number_format((float)$total_cod,2); ?></p>
</div>

  </div>
</div>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function(){
    window.print();
  }, false);
</script>
<!-- <script type="text/javascript">window.print(); setTimeout(function() { window.close(); }, 2000); </script> -->
</body>
</html>
