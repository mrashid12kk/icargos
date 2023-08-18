
<?php

include_once 'includes/conn.php';
include_once 'includes/role_helper.php';

?>
<head>
  <link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
</head>
<style type="text/css">

    body{
            font-family: 'Raleway', sans-serif;
    }
    .print_this_page{
      background: #286fad;
      -webkit-print-color-adjust: exact;
      border-radius: 3px;
      color: #ffffff;
      font-size: 14px;
      border: none;
      cursor: pointer;
      /* width: 100%; */
      padding: 4px 4px 3px;
    }
    .table-box-sec tr td strong{
        margin-bottom: 3px;
        display: inline-block;
    }
    .reports h2{
        margin-top: 20px;
    }
    .pace-done{
        overflow-y: scroll;
        height: 700px;
    }
    th {
    text-align: left;
    padding: 5px;
}
    table{
        width: 100%;
    }
    .table-box  {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 98%;
    }
    .table-box thead tr th{
        border-bottom: 1px solid #dddddd;
        text-align: left;
    }
    .table-box tbody tr{
        width: 100%;
    }
    .table-box tbody tr td,.table-box tbody tr th{
        border: 1px solid #dddddd;
        text-align: left;
        padding: 4px 5px 4px ;
        font-size: 13px;
    }
    .table-box tbody tr:nth-of-type(2n) td {
        background-color: #f3f0f0;
    }
    .table-box thead tr th{
        /*background-color: #dedddd;  */
    }

    .ibox-content {
        padding-bottom: 10px;
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
.left_logo {
    float: left;
    width: 10%;
    margin-right: 8px;
}
.left_logo img {
   width: 123px !important;
    display: block;
    padding: 0;
    height: 123px !important;
    object-fit: contain;
    margin-top: -3px !important;
}
.barcodes_scane{
    text-align: left;
}
.user_info {
    float: right;
    width: 86%;
    padding-top: 15px;
}
.user_info > b > strong {
    font-size: 19px;
    padding: 0;
    color: #28559c !important;
    padding: 0 0 0 36px;
    font-size: 28px;
    /* padding: 0; */
    color: #000 !important;
    /* padding: 0; */
    -webkit-print-color-adjust: exact;
}
.user_info p {
    margin: 0 0 7px;
    width: 100%;
    font-weight: 500;
    float: left;
    font-size: 16px;
    color: #000;
    text-align: left;
}
.ibox-content {
    padding-bottom: 47px;
}
address {
    margin-bottom: 0;
}
.user_info p > b {
    float: left;
    width: 13%;
        -webkit-print-color-adjust: exact;
    margin-right: 8px;
    vertical-align: middle;
    font-size: 17px;
    color: #000;
    text-align: right;
}
.main_head {
    padding: 4px 0;
    margin: 0;
    text-align: center;
    border-bottom: 1px dotted #000 !important;
    border: none;
}
.main_head strong {
    font-size: 26px;
    color: #000000;
    -webkit-print-color-adjust: exact;
}
.install_info_left {
    float: left;
    width: 31.6%;
    margin: 0 19px 0 0;
}
.install_info_left p {
    margin: 0 0 2px;
    font-size: 14px;
    color: #5f5f5f;
    -webkit-print-color-adjust: exact;
    clear: both;
    border-bottom: 1px solid #cccccc94;
    padding: 3px 0;
}
.install_info_left p:last-child{
    border-bottom: none;
}
.install_info_left p b {
    margin-right: 6px;
    color: #000;
        font-size: 12px;
    -webkit-print-color-adjust: exact;
    margin-bottom: 7px;
}
.install_info_right  p  span{
        font-weight: 500;
    float: right;
    color: #000;
    -webkit-print-color-adjust: exact;
    width: 70%;
}
.install_info_right b {
    width: 50% !important;
}
.install_info_left p b span {
    font-weight: 500;
    float: right;
        -webkit-print-color-adjust: exact;
    width: 59%;
    color: #000;
}
.install_info_right {
    float: right;
    width: 46%;
}
.reports .table-box thead tr th, #reports_tables thead tr th {
    background: #e61c2a !important;
    color: #fff !important;
    font-size: 13px;
    -webkit-print-color-adjust: exact;
}

.center_info_box img {
    width: 161px;
    margin-top: 18px;
}
.center_info_box {
    padding: 0px;
    text-align: left;
    max-width: 938px;
    margin: 0 auto;
}
.head h2 {
    margin: 11px 0 9px;
}

.barcodes_scane {

}
.left_install_box b {
    font-weight: 700;
}

.barcodes_scane img {
    width: 115px;
    margin-top: 10px;
}
.barcodes_scane p {
    text-align: center;
    font-weight: bold;
}
tfoot tr td {
    padding: 5px;
    border: 1px solid #dddddd;
}
.signatue ul {
    padding: 0;
}
.signatue ul li {
    display: inline-block;
    list-style: none;
    width: auto;
    margin: 0 35px 0 0;
}
.signatue b {
    font-size: 14px;
}
.signatue p {
    margin: 0 0 0 9px;
    display: inline-block;
    font-size: 30px;
}



@media print {
body{
  font-family: arial, sans-serif;
}
@page{margin: 0;}
.install_info_left {
    width: 31.6%;
    margin: 0 7px 0 0;
}
  .left_logo img {
    width: 140px !important;
}
.install_info_left p b span {
    width:45%;
    -webkit-print-color-adjust: exact;
}
.install_info_left p b {
    font-size: 11px;
}
.user_info {
    width: 78%;
}
.signatue b {
    font-size: 12px;
    -webkit-print-color-adjust: exact;
}
.signatue p {
    margin: 0 0 0 9px;
    font-size: 19px;
}
.signatue ul li {
    margin: 0 8px 0 0;
}
.reports .table-box thead tr th, #reports_tables thead tr th {
    font-size: 10px;
}
th {
    padding: 3px;
}

.user_info p > b {
    width: 16%;
}
}
</style>
<?php
if(isset($_GET['id']) && !empty($_GET['id'])){
$manifest=$_GET['id'];

$query=mysqli_query($con,"SELECT * from `manifest_master` where id=".$manifest." ")or die(mysqli_error($con));

$manifestdata=mysqli_fetch_assoc($query);



?>


<div class="content" style="max-width: 1134px; margin: 0 auto; padding: 2px 0;">
 <div class="head" style="margin-left: 0;">
    <h2>
       <span>
          <button class="btn btn-primary print_this_page">Print Invoice</button>
       </span>
    </h2>
 </div>
 <div class="clearfix" style="border: 1px dotted #000;padding:1px 13px 2px;">
    <div class="left_logo">
       <address>
          <h5 style="    margin: 0;font-size: 18px; font-weight: bold;color:#000;-webkit-print-color-adjust: exact;"></h5>
       </address>
       <img src="<?php echo BASE_URL; ?>admin/<?php echo getconfig('logo'); ?>" alt="">
    </div>
    <div class="user_info">
       <address>
          <h5 style="margin: 0;font-size: 18px; font-weight: bold;color:#000;-webkit-print-color-adjust: exact;"></h5>
       </address>
       <b style="    text-align: left;float: left;width: 100%;margin:0 0 7px;">
          <strong><?php echo getconfig('companyname'); ?></strong>
       </b>
       <p><b>Address:</b>  <?php echo getconfig('address'); ?></p>
       <p><b>Phone No:</b> <?php echo getconfig('contactno'); ?>  </p>
    </div>
 </div>
 <div class="main_head">
    <strong>Manifest</strong>
 </div>
 <div class="clearfix" style="padding: 10px 0 12px;margin: 0;">
    <div class="install_info_left left_install_box">
       <p> <b style="text-align: left;padding-left: 0px;">Tran No: <span>
         <?php echo $manifestdata['manifest_no']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;">Bilty No: <span>
         <?php echo $manifestdata['bilty_no']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;">Origin: <span>
         <?php echo $manifestdata['origin']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;">Dep. Date & Time: <span>
         <?php echo $manifestdata['departure_date']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;">Remarks <span>
           <?php echo $manifestdata['remarks']; ?></span></b>
       </p>
    </div>

    <div class="install_info_left left_install_box">
      <p> <b style="text-align: left;padding-left: 0px;">Manifest Type:<span>
         <?php echo $manifestdata['check_manifest']; ?> </span></b>
       </p>
      <p> <b style="text-align: left;padding-left: 0px;">Date: <span>
         <?php echo $manifestdata['date']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;">Truck#/Transporter: <span>
         <?php echo $manifestdata['truck_no']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;">Destination: <span>
         <?php echo $manifestdata['destination']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;">Arr. Date & Time: <span>
         <?php echo $manifestdata['arrival_date'].''.$manifestdata['arrival_time']; ?></span></b>
       </p>
    </div>
 </div>
 <div class="row">
    <div class="col-sm-12 reports" style="padding-right: 0;">
       <table class="table-box">
          <thead>
             <tr>
                <th>Sr.No</th>
                <th>CN No.</th>
                <th>CN Date.</th>
                <th>Srv</th>
                <th>Shipper Name & Area</th>
                <th>Consingee Name & Area</th>
                <th>Origin</th>
                <th>Dest</th>
                <th>Mode</th>
                <th>Pcs</th>
                <th>Credit Weight</th>
                <th>Charges Amount</th>
                <th>AWB Charges</th>
                <th>Patri Expenses</th>
                <th>Total Amount</th>
             </tr>
          </thead>
          <tbody>
            <?php

if(!function_exists('collection_center_name')){
function collection_center_name($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from collection_centers where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}
if(!function_exists('getServiceType')){
function getServiceType($id=null)
{
    global $con;
    if($id)
    {
      $query_service_type = mysqli_query($con,"SELECT * from services WHERE id=".$id);
      $resposne_service_type = mysqli_fetch_assoc($query_service_type);
      return isset($resposne_service_type['service_type']) ? $resposne_service_type['service_type'] :'';
    }
}
}
if(!function_exists('modes')){
function modes($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT mode_name from modes where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['mode_name'];
    }
}
}
if(!function_exists('totalcharge')){
function totalcharge($id=null)
{
    global $con;
    if($id)
    {
      $totalcharges='';
      $query = mysqli_query($con,"SELECT charges_amount from order_charges where order_id=".$id);
      while ($resposne = mysqli_fetch_assoc($query)){
        $totalcharges +=$resposne['charges_amount'];
      }
      return  $totalcharges;
    }
}
}
              $query4=mysqli_query($con,"SELECT * from `manifest_detail` where manifest_no=".$manifestdata['manifest_no'])or die(mysqli_error($con));
              $sr=1;
              $total_weight=0;
              $total_quantity=0;
              $total_price=0;
              $pft_amount=0;
              $inc_amount=0;
              $grand_total_charges=0;
              while($row=mysqli_fetch_array($query4)){
              $track_no=$row['track_no'];
              $order=mysqli_query($con,"SELECT * from `orders` where track_no='".$track_no."'")or die(mysqli_error($con));
              $orderrow=mysqli_fetch_assoc($order);
              $total_weight+=$orderrow['weight'];
              $total_quantity+=$orderrow['quantity'];
              $total_price+=$orderrow['price'];
              $pft_amount+=$orderrow['pft_amount'];
              $inc_amount+=$orderrow['inc_amount'];
              $grand_total_charges+=$orderrow['grand_total_charges'];

 ?>
             <tr style="border-bottom: 1px solid #c3c3c3 !important;">
                <td><?php echo $sr ?></td>
                <td><?php echo $orderrow['track_no']; ?></td>
                <td><?php echo date('Y-m-d',strtotime($orderrow['order_date'])); ?></td>
                <td><?php echo getServiceType($orderrow['order_type']); ?></td>
                <td><?php echo $orderrow['sname']; ?></td>
                <td><?php echo $orderrow['rname']; ?></td>
                <td><?php echo $orderrow['origin']; ?></td>
                <td><?php echo $orderrow['destination']; ?></td>
                <td><?php echo modes($orderrow['booking_mode']); ?></td>
                <td><?php echo $orderrow['quantity']; ?></td>
                <td><?php echo $orderrow['weight']; ?></td>
                <td><?php echo $orderrow['price']; ?></td>
                <td><?php echo $orderrow['pft_amount']; ?></td>
                <td><?php echo $orderrow['grand_total_charges']; ?></td>
                  <td><?php echo $orderrow['inc_amount']; ?></td>
             </tr>
           <?php
           $sr++;
         } ?>
          </tbody>
          <tfoot>
            <tr style="-webkit-print-color-adjust: exact !important;background-color: #e61c2a !important;">
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="-webkit-print-color-adjust: exact !important;font-size: 18px !important;color: #fff !important;">Total</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="color: #fff;"><?php echo $total_quantity; ?></td>
              <td style="color: #fff;"><?php echo $total_weight; ?></td>
              <td style="color: #fff;"><?php echo number_format($total_price); ?></td>
              <td style="color: #fff;"><?php echo number_format($pft_amount); ?></td>
              <td style="color: #fff;"><?php echo number_format($grand_total_charges); ?></td>
              <td style="color: #fff;"><?php echo number_format($inc_amount); ?></td>
            </tr>
      </tfoot>
       </table>
    </div>
 </div>
 <div class="signatue">
     <ul>
         <li>
             <b>Quality Officer</b>
             <p>........................</p>
         </li>
         <li>
             <b>Supervisor</b>
             <p>........................</p>
         </li>
         <li>
             <b>Manager</b>
             <p>........................</p>
         </li>
         <li>
             <b>Received By GC/Driver</b>
             <p>........................</p>
         </li>
     </ul>
 </div>
</div>
<?php }

if(!function_exists('collection_center_name')){
function collection_center_name($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from collection_centers where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}
if(!function_exists('getServiceType')){
function getServiceType($id=null)
{
    global $con;
    if($id)
    {
      $query_service_type = mysqli_query($con,"SELECT * from services WHERE id=".$id);
      $resposne_service_type = mysqli_fetch_assoc($query_service_type);
      return isset($resposne_service_type['service_type']) ? $resposne_service_type['service_type'] :'';
    }
}
}
if(!function_exists('modes')){
function modes($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from `modes` where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}

if(!function_exists('receivename')){
function receivename($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from `users` where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}
if(!function_exists('types')){
function types($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from `types` where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}
if(!function_exists('branches')){
function branches($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from `branches` where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}
if(!function_exists('printManifestStatus')){
function printManifestStatus($id=null)
{
    global $con;
    $status = "Manifested";
    $manifest_id = isset($_GET['print_id'])?$_GET['print_id']:$id;
    if($manifest_id)
    {
      $query_total = mysqli_query($con,"SELECT *  from `manifest_detail` where manifest_id=".$manifest_id);
      $total_manifest_records = mysqli_num_rows($query_total);
      $query_demanifest = mysqli_query($con,"SELECT *  from `manifest_detail` where manifest_id=".$manifest_id." AND is_demanifest=1");
      $total_demanifest_records = mysqli_num_rows($query_demanifest);
      if($total_manifest_records==$total_demanifest_records){
        $status = "De-Manifested";
      }
      if($total_demanifest_records >0 && $total_manifest_records > $total_demanifest_records){
        $status = "Partially De-Manifested";
      }
      return $status;
    }
}
}
if(!function_exists('transportco')){
function transportco($id=null)
{
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT name from `transport_company` where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
}
}
if(!function_exists('totalcharge')){
function totalcharge($id=null)
{
    global $con;
    if($id)
    {
      $totalcharges='';
      $query = mysqli_query($con,"SELECT charges_amount from order_charges where order_id=".$id);
      while ($resposne = mysqli_fetch_assoc($query)){
        $totalcharges +=$resposne['charges_amount'];
      }
      return  $totalcharges;
    }
}
}
if(isset($_GET['print_id']) && !empty($_GET['print_id'])){
$manifest=$_GET['print_id'];

$query=mysqli_query($con,"SELECT * from `manifest_master` where id=".$manifest." ")or die(mysqli_error($con));

$manifestdata=mysqli_fetch_assoc($query);



?>



<div class="content" style="max-width: 1134px; margin: 0 auto; padding: 2px 0;">
 <div class="head" style="margin-left: 0;">
    <h2>
       <span>
          <button class="btn btn-primary print_this_page"><?php echo getLange('print').' '.getLange('invoice'); ?></button>
       </span>
    </h2>
 </div>
 <div class="clearfix" style="border: 1px dotted #000;padding:1px 13px 2px;">
    <div class="left_logo">
       <address>
          <h5 style="  -webkit-print-color-adjust: exact;  margin: 0;font-size: 18px; font-weight: bold;color:#000;"></h5>
       </address>
       <img src="<?php echo BASE_URL; ?>admin/<?php echo getconfig('logo'); ?>" alt="">
    </div>
    <div class="user_info">
       <address>
          <h5 style="-webkit-print-color-adjust: exact;margin: 0;font-size: 18px; font-weight: bold;color:#000;"></h5>
       </address>
       <b style=" -webkit-print-color-adjust: exact;   text-align: left;float: left;width: 100%;margin:0 0 7px;">
          <strong><?php echo getconfig('companyname'); ?></strong>
       </b>
       <p><b style="-webkit-print-color-adjust: exact;"><?php echo getLange('address'); ?>:</b>  <?php echo getconfig('address'); ?></p>
       <p><b style="-webkit-print-color-adjust: exact;"><?php echo getLange('phoneno'); ?>:</b> <?php echo getconfig('contactno'); ?>  </p>
    </div>
 </div>
 <div class="main_head">
    <strong><?php echo getLange('manifest'); ?></strong>
 </div>
 <div class="clearfix" style="padding: 10px 0 12px;margin: 0;">
    <div class="install_info_left left_install_box">
       <p> <b style="text-align: left;padding-left: 0px;">    <?php echo getLange('manifestno'); ?>: <span>
         <?php echo $manifestdata['manifest_no']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;">Manifest Type: <span>
         <?php echo $manifestdata['check_manifest']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('type'); ?>: <span>
         <?php echo types($manifestdata['type']); ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('sealno'); ?>: <span>
         <?php echo $manifestdata['seal_no']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('sendingbranch'); ?>: <span>
         <?php echo branches($manifestdata['sending_branch']); ?> </span></b>
       </p>
        <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('biltyno'); ?>: <span>
         <?php echo $manifestdata['bilty_no']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('receiverpersonname'); ?>: <span>
         <?php echo receivename($manifestdata['receiver_name']); ?> </span></b>
       </p>
    </div>


    <div class="install_info_left left_install_box">
      
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('origin'); ?>: <span>
         <?php echo $manifestdata['origin']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('departuredate'); ?>: <span>
         <?php echo $manifestdata['departure_date']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('remarks'); ?> <span>
           <?php echo $manifestdata['remarks']; ?></span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('date'); ?>: <span>
         <?php echo $manifestdata['date']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('truckno'); ?>: <span>
         <?php echo $manifestdata['truck_no']; ?> </span></b>
       </p>
         <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('transportcompany'); ?>: <span>
         <?php echo transportco($manifestdata['transport_co']);  ?> </span></b>
       </p>
        <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('receivingbranch'); ?>: <span>
         <?php echo branches($manifestdata['receiving_branch']); ?> </span></b>
       </p>
    </div>



    <div class="install_info_left left_install_box">
       
      
          <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('mode'); ?>: <span>
         <?php echo modes($manifestdata['mode']); ?> </span></b>
       </p>
         <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('serviceby'); ?>: <span>
         <?php echo getServiceType($manifestdata['service_by']); ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('destination'); ?>: <span>
         <?php echo $manifestdata['destination']; ?> </span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('arrivaldate').'  '.getLange('time'); ?>: <span>
         <?php echo $manifestdata['arrival_date'].' '.$manifestdata['arrival_time']; ?></span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('pickvia'); ?>: <span>
         <?php echo $manifestdata['pick_via']; ?></span></b>
       </p>
       <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('status'); ?>: <span>
         <?php echo printManifestStatus($manifest['manifest_no']); ?></span></b>
       </p>
    </div>
 </div>
 <div class="row">
    <div class="col-sm-12 reports" style="padding-right: 0;">
       <table class="table-box">
          <thead>
             <tr>
                <th><?php echo getLange('srno'); ?>.</th>
                <th><?php echo getLange('cnno'); ?>.</th>
                <!-- <th><?php echo getLange('shipper').' '.getLange('name').' '.getLange('areas'); ?></th> -->
                <th><?php echo getLange('shipper'); ?></th>
                <th><?php echo getLange('consignee').' '.getLange('name').' '.getLange('areas'); ?></th>
                <th><?php echo getLange('origin'); ?></th>
                <th><?php echo getLange('destination'); ?></th>
                <th><?php echo getLange('pcs'); ?></th>
                <th><?php echo getLange('credit').' '.getLange('weight'); ?></th>
                <!-- <th><?php echo getLange('codamount'); ?></th>
                <th><?php echo getLange('netamount'); ?></th> -->
             </tr>
          </thead>
          <?php
          $sr=1;
          $total_weight=0;
                $total_quantity=0;
                $total_net_amount=0;
                $total_cod=0;
          $query4=mysqli_query($con,"SELECT * from `manifest_detail` where manifest_no=".$manifestdata['manifest_no'])or die(mysqli_error($con));
              while($row=mysqli_fetch_array($query4)){
              $track_no=$row['track_no'];
              $order=mysqli_query($con,"SELECT * from `orders` where track_no='".$track_no."'")or die(mysqli_error($con));
              while ($orderrow=mysqli_fetch_array($order)) {
                $total_weight+=$orderrow['weight'];
                $total_quantity+=$orderrow['quantity'];
                $total_net_amount+=$orderrow['net_amount'];
                $total_cod+=$orderrow['collection_amount'];

           ?>
          <tbody>
             <tr style="border-bottom: 1px solid #c3c3c3 !important;">
              <td><?php echo $sr; ?></td>
                <td><?php echo $orderrow['track_no']; ?></td>
                <td><?php echo getBusinessName($orderrow['customer_id']); ?></td>
                <td><?php echo $orderrow['rname']; ?></td>
                <td><?php echo $orderrow['origin']; ?></td>
                <td><?php echo $orderrow['destination']; ?></td>
                <td><?php echo $orderrow['quantity']; ?></td>
                <td><?php echo $orderrow['weight']; ?></td>
                <!-- <td><?php echo getConfig('currency').' '.$orderrow['collection_amount']; ?></td>
                <td><?php echo getConfig('currency').' '.$orderrow['net_amount']; ?></td> -->
                 </tr>
               <?php } $sr++;}?>

          </tbody>
          <tfoot>
                 <tr>
                   <td colspan="6"> <?php echo getLange('total'); ?></td>
                   <td><?php echo  $total_quantity; ?></td>
                   <td><?php echo $total_weight; ?></td>
                   <!-- <td><?php echo $total_cod; ?></td>
                   <td><?php echo $total_net_amount; ?></td> -->
                 </tr>
               </tfoot>
       </table>
    </div>
 </div>
 <div class="signatue">
     <ul>
         <li>
             <b><?php echo getLange('quality_officer'); ?></b>
             <p>........................</p>
         </li>
         <li>
             <b><?php echo getLange('supervisor') ?></b>
             <p>........................</p>
         </li>
         <li>
             <b><?php echo getLange('manager'); ?></b>
             <p>........................</p>
         </li>
         <li>
             <b><?php echo getLange('received_by_gc_driver'); ?></b>
             <p>........................</p>
         </li>
     </ul>
 </div>
</div>


<?php } ?>

<?php include_once 'includes/footer.php'; ?>
<script type="text/javascript">
<!--
window.print();
//-->

$(document).on('click','.print_this_page',function(){

window.print();
})
</script>
