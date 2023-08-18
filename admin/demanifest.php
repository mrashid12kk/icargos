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
        -webkit-print-color-adjust: exact;
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
    -webkit-print-color-adjust: exact;
    font-size: 28px;
    /* padding: 0; */
    color: #000 !important;
    /* padding: 0; */

}
.user_info p {
    margin: 0 0 7px;
    width: 100%;
    float: left;
    font-size: 17px;
    color: #5f5f5f;
    -webkit-print-color-adjust: exact;
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
    margin-right: 8px;
    vertical-align: middle;
    font-size: 17px;
    color: #000;
    -webkit-print-color-adjust: exact;
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
    width: 50%;
}
.install_info_left p {
    margin: 0 0 2px;
    font-size: 14px;
    color: #5f5f5f;
    -webkit-print-color-adjust: exact;
    clear: both;
        border-bottom: 1px solid #cccccc94;
    padding: 7px 0;
}
.install_info_left p:last-child{
    border-bottom: none;
}
.install_info_left p b {
    margin-right: 6px;
    color: #000;
    -webkit-print-color-adjust: exact;
    margin-bottom: 7px;
}
.install_info_right  p  span{
        font-weight: 400;
    float: right;
    width: 70%;
}
.install_info_right b {
    width: 50% !important;
}
.install_info_left p b span {
    font-weight: 400;
    float: right;
    width: 70%;
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
    border: 1px solid #f38e95;
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
  .print_this_page{
    display: none;
  }
  body{
  font-family: arial, sans-serif;
}
  .left_logo img {
    width: 100px !important;
}
.install_info_left p b span {
    width: 58%;
}
.signatue b {
    font-size: 12px;
}
.signatue p {
    margin: 0 0 0 9px;
    font-size: 19px;
}
.signatue ul li {
    margin: 0 8px 0 0;
}
}
</style>
<?php
 if(isset($_GET['print_id']) && !empty($_GET['print_id'])){
  $manifest=$_GET['print_id'];

  $query=mysqli_query($con,"SELECT * from `demanifest_master` where id=".$manifest." ")or die(mysqli_error($con));

  $manifestdata=mysqli_fetch_assoc($query);


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
 if(!function_exists('receiv_report')){
  function receiv_report($id=null)
  {
      global $con;
      if($id)
      {
      if($id==1){
        return 'With Address';
      }
      else{
        return 'With Out Address';
      }
      }
  }
}


 ?>
 <div class="content" style="max-width: 1134px; margin: 0 auto; padding: 2px 0;">
   <div class="head" style="margin-left: 0;">
      <h2>
         <span>
            <button class="btn btn-primary print_this_page"><?php echo getLange('print').' '.getLange('invoice'); ?></button>
         </span>
      </h2>
   </div>
   <div class="clearfix" style="border: 1px dotted #000; -webkit-print-color-adjust: exact;padding:1px 13px 2px;">
      <div class="left_logo">
         <address>
            <h5 style="    margin: 0;font-size: 18px; font-weight: bold;color:#000;"></h5>
         </address>
         <img src="<?php echo BASE_URL ?>admin/<?php echo getconfig('logo'); ?>" alt="">
      </div>
      <div class="user_info">
         <address>
            <h5 style="margin: 0;font-size: 18px; font-weight: bold;color:#000; -webkit-print-color-adjust: exact;"></h5>
         </address>
         <b style="    text-align: left;float: left;width: 100%;margin:0 0 7px;">
            <strong><?php echo getConfig('companyname'); ?></strong>
         </b>
         <p><b><?php echo getLange('address'); ?>:</b>  <?php echo getConfig('address'); ?></p>
         <p><b><?php echo getLange('phoneno'); ?>:</b> <?php echo getConfig('contactno'); ?>  </p>
      </div>
   </div>
   <div class="main_head">
      <strong><?php echo getLange('demanifest'); ?></strong>
   </div>
   <div class="clearfix" style="padding: 10px 0 12px;margin: 0;">
      <div class="install_info_left left_install_box">
         <p> <b style="text-align: left;padding-left: 0px;"><?php echo getLange('demanifest'); ?>: <span>
           <?php echo $manifestdata['demanifest_no']; ?> </span></b>
         </p>
            <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('arrivaldate').' & '.getLange('time'); ?>: <span>
           <?php echo $manifestdata['arrive_date']; ?></span></b>
         </p>
          <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('branch'); ?>: <span>
           <?php echo branches($manifestdata['branch_id']); ?></span></b>
         </p>
      </div>
      <div class="install_info_left install_info_right">
        <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('manifestno'); ?>: <span>
           <?php echo $manifestdata['manifest_no']; ?> </span></b>
         </p>
         <p> <b style="text-align: left;padding-left: 0;"><?php echo getLange('truckno'); ?>: <span>
           <?php echo $manifestdata['truck_no']; ?> </span></b>
         </p>


      </div>
   </div>
   <div class="row">
      <div class="col-sm-12 reports" style="padding-right: 0;">
         <table class="table-box">
            <thead>
               <tr>
                  <th><?php echo getLange('srno'); ?> </th>
                  <th><?php echo getLange('trackingno'); ?></th>
                  <th><?php echo getLange('noofpiece'); ?></th>
                  <th><?php echo getLange('weight'); ?></th>
               </tr>
             <?php
            $sr=1;
            $total_weight=0;
                  $total_quantity=0;
            $query4=mysqli_query($con,"SELECT * from `demanifest_detail` where demanifest_no=".$manifestdata['demanifest_no'])or die(mysqli_error($con));
                while($row=mysqli_fetch_array($query4)){
                $track_no=$row['track_no'];
                $order=mysqli_query($con,"SELECT * from `orders` where track_no='".$track_no."'")or die(mysqli_error($con));
                while ($orderrow=mysqli_fetch_array($order)) {
                  $total_weight+=$orderrow['weight'];
                  $total_quantity+=$orderrow['quantity'];

             ?>
            <tbody>
               <tr style="border-bottom: 1px solid #c3c3c3 !important;">
                <td><?php echo $sr; ?></td>
                  <td><?php echo $orderrow['track_no']; ?></td>
                  <td><?php echo $orderrow['quantity']; ?></td>
                  <td><?php echo $orderrow['weight']; ?></td>
                   </tr>
                 <?php } $sr++;}?>

            </tbody>
            <tfoot>
                   <tr>
                     <td colspan="2"> <?php echo getLange('total'); ?></td>
                     <td><?php echo  $total_quantity; ?></td>
                     <td><?php echo $total_weight; ?></td>
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
