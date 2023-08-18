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
  <title>CDF FORM# | <?php echo $_GET['cash_id']; ?></title>
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
  background: #000;
  -webkit-print-color-adjust: exact !important;
  color: #fff;
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



<style type="text/css">
  .left_box {
    float: left;
    width: 50%;
    margin-bottom: 10px;
  }
  .bottom_f{
    padding: 20px 0 0;
  }

  .box_box{
    text-align: center;
  }
  .box_box b {
    font-size: 20px;
  }
  .box_box span{
   font-size: 20px;
 }
 .bottom_f .right_box b,.bottom_f .left_box b{
  width: 30%;
}
.bottom_f .left_box p,.bottom_f .left_box p{
  width: 65%;
}
.left_box b, .right_box b {
  font-weight: bold;
  font-size: 15px;
  vertical-align: middle;
  margin-top: 1px;
  display: inline-block;
  width: 30%;
  text-align: right;
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
  margin: 0 0 10px;
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
.text-center{
  text-align: center !important;
}

@media print {
  .single_page {
    page-break-after:always
  }
  .icargo_box li {

    width: auto;
  }
  .page-number:before{
    counter-increment: page;
    content: "Page " counter(page)
    ;
    position: absolute;
    right: 0;
    top: 6px;
    visibility: visible;
    overflow: visible;
    margin-top: -7px;
    z-index: 999;
  }
  .right_box p {
    width: 63%;
  }
  .right_box b {
    width: 32%;
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

  <div class="page-number"></div>
  <div id="main_page">
    <?php if (isset($_GET['cash_id']) && $_GET['cash_id']!='') {
      $row=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM cash_deposit_form_master WHERE id=".$_GET['cash_id']));
      $rider_name=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM users WHERE user_name='".$row['courier_code']."'"));
      ?>
      <div class="single_page">
       <div class="icargo_box">
        <ul>
         <li></li>
         <li>
          <h1 style="text-align: center;margin: 9px 0 0;">COD Cash Deposit Form</h1>
        </li>
        <li></li>
      </ul>
    </div>
    <div class="clearfix">
      <div class="left_box">
       <div class="head_">
        <b>Delivery Date</b>
        <p><?php echo date('Y-m-d', strtotime($row['date'])); ?></p>
      </div>
      <div class="head_">
        <b>Courier Name</b>
        <p><?php echo $rider_name['Name']; ?></p>
      </div>
    </div>
    <div class="right_box">
     <div class="head_">
      <b>Report ID</b>
      <p><?php echo $row['report_id']; ?></p>
    </div>
    <div class="head_">
      <b>Courier code</b>
      <p><?php echo $row['courier_code']; ?></p>
    </div>
  </div>
</div>
<table class="table">
  <tbody>
   <tr>
    <th>Sr.#</th>
    <th>Consignment</th>
    <th>DS Sr #</th>
    <th>Delivery Status</th>
    <th>COD Amount</th>
    <th>Delivery Sheet No</th>
  </tr>
  <?php
  $srno=1;
  $total_cod=0;
  $track_no_q=mysqli_query($con,"SELECT * FROM cash_deposit_form WHERE master_id=".$_GET['cash_id']);
  foreach ($track_no_q as $key => $value) {
    $order=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM orders WHERE track_no='".$value['track_no']."'"));
    $total_cod+=$order['collection_amount'];
    ?>
    <tr>
      <td><?php echo $srno++; ?></td>
      <td><?php echo $order['track_no']; ?></td>
      <td></td>
      <td> OK  </td>
      <td> <?php echo $order['collection_amount']; ?></td>
      <td> <?php echo $order['delivery_assignment_no']; ?></td>
    </tr>
  <?php } ?>
</tbody>
</table>
<div class="clearfix bottom_f">
  <div class="center_box">
   <div class="box_box">
    <b>Total Amount</b>&nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  
    <span><?php echo $total_cod; ?></span>
  </div>
</div>
</div>
<div class="clearfix bottom_f">
  <div class="left_box">
   <div class="head_">
    <b>Submitted By Name</b>
    <p>________________</p>
  </div>
  <div class="head_">
    <b>Signed By</b>
    <p>________________</p>
  </div>
</div>
<div class="right_box">
 <div class="head_">
  <b>Received By Name</b>
  <p>________________</p>
</div>
<div class="head_">
  <b>Signed by Date</b>
  <p>________________</p>
</div>
</div>
</div>
</div>
<?php } ?>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function(){
    window.print();
  }, false);
</script>
</body>
</html>
