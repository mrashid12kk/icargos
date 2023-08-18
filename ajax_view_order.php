<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require 'includes/conn.php';



## Read value

$draw = $_POST['draw'];

$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page

$columnIndex = $_POST['order'][0]['column']; // Column index

$columnName = $_POST['columns'][$columnIndex]['data']; // Column name

$columnSortOrder = 'desc'; // asc or desc

$searchValue = $_POST['search']['value']; // Search value



## Custom Field value

$tracking_no = $_POST['tracking_no'];

$date_type = $_POST['date_type'];

$date_from = $_POST['date_from'];

$date_to = $_POST['date_to'];

$order_status = $_POST['order_status'];

$order_city = $_POST['order_city'];

$origin_city = $_POST['origin_city'];



## Search

$searchQuery = " ";
if (isset($_POST['type']) && !empty($_POST['type']) && $_POST['type']=='Delivered') {
  $searchQuery .=" AND (status ='Delivered')";

}
if (isset($_POST['type']) && !empty($_POST['type']) && $_POST['type']=='open') {
  $searchQuery .=" AND (status !='Delivered' AND status != 'Returned to Shipper')";
}
if (isset($_POST['type']) && !empty($_POST['type']) && $_POST['type']=='Returned') {
 $searchQuery .=" AND (status ='Returned to Shipper')";
}
if($tracking_no != ''){

 $searchQuery .= " and (track_no='".$tracking_no."') ";

}

if($customer_name != ''){

 $searchQuery .= " and (sname='".$customer_name."') ";

}

if($customer_phone != ''){

 $searchQuery .= " and (sphone='".$customer_phone."') ";

}



if($date_from != '' && $date_to !=''){

  $from = date('Y-m-d',strtotime($_POST['date_from']));

  $to = date('Y-m-d',strtotime($_POST['date_to']));

  $searchQuery .= " and DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') <= '".$to."' ";

}

if($pickup_rider != ''){

 $searchQuery .= " and (pickup_rider='".$pickup_rider."') ";

}

if($delivery_rider != ''){

 $searchQuery .= " and (delivery_rider='".$delivery_rider."') ";

}

if($order_status != ''){

 $searchQuery .= " and (status='".$order_status."') ";

}

if($order_city != ''){

 $searchQuery .= " and (destination='".$order_city."') ";

}

if($origin_city != ''){

 $searchQuery .= " and (origin='".$origin_city."') ";

}
if($track_no != ''){

 $searchQuery .= " and (track_no='".$track_no."') ";

}

if($searchValue != ''){

 $searchQuery .= " and (track_no like '%".$searchValue."%' or

 origin like '%".$searchValue."%' or

 destination like'%".$searchValue."%' or

 sname like'%".$searchValue."%' or

 rname like'%".$searchValue."%' or

 rphone like'%".$searchValue."%' or

 sphone like'%".$searchValue."%' ) ";

}



## Total number of records without filtering
$id = $_SESSION['customers'];
$sel = mysqli_query($con,"select count(*) as allcount from orders ");

$records = mysqli_fetch_assoc($sel);

$totalRecords = $records['allcount'];



## Total number of records with filtering

$sel = mysqli_query($con,"select count(*) as allcount from orders WHERE customer_id =".$id." ".$searchQuery);

$records = mysqli_fetch_assoc($sel);

$totalRecordwithFilter = $records['allcount'];



## Fetch records

$empQuery = "SELECT * FROM orders WHERE customer_id =".$id." ".$searchQuery." order by ".$columnName."  ".$columnSortOrder." limit ".$row.",".$rowperpage;
//functions
// echo $empQuery;
// die;
if(!function_exists('collection_center_name')){
  function collection_center_name($id=null)
  {
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT * from collection_centers where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['name'];
    }
  }
}
if(!function_exists('customer_image')){
  function customer_image($id=null)
  {
    global $con;
    if($id)
    {

      $query = mysqli_query($con,"SELECT * from customers where id=".$id);

      $resposne = mysqli_fetch_assoc($query);
      return $resposne['image'];
    }
  }
}
if(!function_exists('customer_fname')){
  function customer_fname($id=null)
  {
    global $con;
    if($id)
    {

      $query = mysqli_query($con,"SELECT * from customers where id=".$id);

      $resposne = mysqli_fetch_assoc($query);
      return $resposne['fname'];
    }
  }
}
if(!function_exists('customer_bname')){
  function customer_bname($id=null)
  {
    global $con;
    if($id)
    {
      $query = mysqli_query($con,"SELECT * from customers where id=".$id);
      $resposne = mysqli_fetch_assoc($query);
      return $resposne['bname'];
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
      return isset($resposne_service_type['service_code']) ? $resposne_service_type['service_code'] :'';
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
// echo $empQuery;

$empRecords = mysqli_query($con, $empQuery);

$data = array();

$sr_no =$_POST['start']+1;

while ($fetch1 = mysqli_fetch_assoc($empRecords)) {

  // print_r($row);

  // die();
  $order_booking_type='';
  if ($fetch1['order_booking_type'] == 1) {
    $order_booking_type ="<td class='price-box'><div class='ribbon'><span>internation</span></div>  </td>";
  }
  $data[] = array(
   "id"=>"<td class='all_brands'><div class='brand_logo'>".$sr_no."</div></td>",
   "srno"=>"<td class='all_brands'><div class='brand_logo'><img src='".BASE_URL."".customer_image($fetch1['customer_id'])."'></div></td>",

   "pickup_deatil"=>"<td class='brand_info'><div class='notes_details'><h3>".customer_bname($fetch1['customer_id'])." </h3><span>".$fetch1['sname']."</span><h5>".$fetch1['sphone']."</h5><h6  class='view_detail_show' data-id='".$fetch1['id']."'>".getLange('viewmoredetail')." <i class='fa fa-angle-down'></i></h6><div class='main_wrapper_table' id='".$fetch1['id']."'><ul class='fix_ul_li'><li><b> ".getLange('accountname')." <span class='float-right'> :</span></b> <span>".$fetch1['sname']."</span></li><li><b>".getLange('bussinessname')." <span class='float-right'> :</span></b> <span>".customer_bname($fetch1['customer_id'])."</span></li><li><b>".getLange('phoneno')." <span class='float-right'> :</span></b> <span>".$fetch1['sphone']."</span></li><li><b>".getLange('email')." <span class='float-right'> :</span></b> <span>".$fetch1['semail']."</span></li><li><b>".getLange('address')."<span class='float-right'> :</span></b> <span>".$fetch1['sender_address']."</span></li></ul></div></div></td>",

   "order_detail"=>"<td class='date_bx'><div class='listing_boxes'><ul><li><h5>".$fetch1['track_no']."</h5><h4>". date(DATE_FORMAT,strtotime($fetch1['order_date']))." - ".date('h:i A',strtotime($fetch1['order_time']))."</h4><b><i class='fa fa-lightbulb-o'></i> ".getServiceType($fetch1['order_type'])." - <i class='fa fa-balance-scale'></i> ".$fetch1['weight']." Kg</b><span><i class='fa fa-credit-card'></i>".number_format((float)$fetch1['net_amount'],2)."</span></li></ul></div></td>",

   "tracK_image"=> "<td class='cod_box'><div class='cod_imgbox'><svg  viewBox='0 0 24 24'><path d='M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z' fill='#626262'/></svg></div></td>",

   "delivery_detail"=>"<td class='client_info'><div class='listing_boxes'><ul><li><h5>".$fetch1['rname']."</h5><h4>".$fetch1['rphone']."</h4><h6>". $fetch1['receiver_address']."</h6><b>".getLange('codamount').": ". number_format((float)$fetch1['collection_amount'],2)."</b></li></ul></div></td>",

   "orgin_destination"=>"<td class='from_to1'><div class='from_to1'><div class='divider_location'><b class='from_info'>".$fetch1['origin']."</b><b class='to_info'>".$fetch1['destination']."</b><span class='middle_area'></span></div></div></td>",

   "action"=>"<td class='percel_box'><div class='checkin_box'><button class='view_detail' data-id='".$fetch1['id']."'><svg  viewBox='0 0 24 24'><path d='M11.5 18c3.989 0 7.458-2.224 9.235-5.5A10.498 10.498 0 0 0 11.5 7a10.498 10.498 0 0 0-9.235 5.5A10.498 10.498 0 0 0 11.5 18zm0-12a11.5 11.5 0 0 1 10.36 6.5A11.5 11.5 0 0 1 11.5 19a11.5 11.5 0 0 1-10.36-6.5A11.5 11.5 0 0 1 11.5 6zm0 2a4.5 4.5 0 1 1 0 9a4.5 4.5 0 0 1 0-9zm0 1a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7z' fill='#fff'/></svg> ".getLange('viewdetail')."</button><button  class='live_tracking' data-track='".$fetch1['track_no']."'><svg viewBox='0 0 24 24'><path d='M11.5 7a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm-4.7 4.357l4.7 7.73l4.7-7.73a5.5 5.5 0 1 0-9.4 0zm10.254.52L11.5 22.012l-5.554-9.135a6.5 6.5 0 1 1 11.11 0h-.002z' fill='#fff'/></svg> ".getLange('livetrackig')."</button><ul><li><i class='fa fa-check' ></i> ".getKeyWordCustomer($fetch1['customer_id'],$fetch1['status'])."</li></ul></div></td>",

   "payment"=>"".($fetch1['payment_status']=='Paid' ? "<td class='price-box'><div class='ribbon'><span>".getLange('paid')."</span></div>  </td>" : $order_booking_type)."",
 );
  $sr_no ++;
}

## Response

$response = array(

  "draw" => intval($draw),

  "iTotalRecords" => $totalRecords,

  "iTotalDisplayRecords" => $totalRecordwithFilter,

  "aaData" => $data

);
// echo '<pre>';
// print_r($response);
// die;

echo json_encode($response);
