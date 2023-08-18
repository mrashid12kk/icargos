<?php
//  ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require '../includes/conn.php';
    // echo "sring";
    // die;
    ## Read value
$draw = 4;
$row = $cpage = isset($_POST['page'])?$_POST['page']:0;
if(!$row)
{
    $fullPath = __DIR__;
$path    = $fullPath;
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..'));
foreach($files as $file){
    $ext=pathinfo($file, PATHINFO_EXTENSION);
    if($ext == 'csv')
    {
        unlink($file);
    }

//   echo "<a href='$file'>$file</a>";  
}

}
    $rowperpage = 10; // Rows display per page
    $row = (($row * $rowperpage) - $rowperpage) +1;
    // $columnIndex = $_POST['order'][0]['column']; // Column index
    // $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    // $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    // $searchValue = $_POST['search']['value']; // Search value
    ## Custom Field value

    $tracking_no = $_POST['tracking_no'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $customer_id = $_POST['customer_id'];
    $customer_type = $_POST['customer_type'];
    $payment_status = $_POST['payment_status'];
    $status = $_POST['status'];
    $courier = $_POST['courier'];
    $from = $_POST['from'];
    $to = $_POST['to'];

    ## Search
    $searchQuery = " ";
    if(false){

     $searchQuery .= " and (orders.track_no like '%".$searchValue."%' or
     orders.origin like '%".$searchValue."%' or
     orders.destination like '%".$searchValue."%' or
     orders.sname like '%".$searchValue."%' or
     customers.bname like '%".$searchValue."%' or
     orders.payment_status like '%".$searchValue."%' or
     orders.status like '%".$searchValue."%' or
     orders.product_id like '%".$searchValue."%' or
     orders.assign_driver like'%".$searchValue."%' or
     orders.sphone like'%".$searchValue."%' or
     orders.sender_address like'%".$searchValue."%' or
     orders.payment_status like'%".$searchValue."%' or
     orders.rname like'%".$searchValue."%' or
     orders.rphone like'%".$searchValue."%' or
     orders.receiver_address like'%".$searchValue."%' or
     orders.ref_no like'%".$searchValue."%' or
     orders.quantity like'%".$searchValue."%' or
     orders.weight like'%".$searchValue."%' or
     orders.collection_amount like'%".$searchValue."%' or
     orders.price like'%".$searchValue."%' or
     services.service_type like'%".$searchValue."%' or
     orders.pft_amount like'%".$searchValue."%' or
     orders.rname like'%".$searchValue."%' or
     orders.rname like'%".$searchValue."%' or



     customers.bname like'%".$searchValue."%' ) ";

   }
   if($tracking_no != ''){

     $searchQuery .= " and (orders.track_no='".$tracking_no."') ";

   }else{
    if($origin != ''){

     $searchQuery .= " and (orders.origin='".$origin."') ";

   }
   if($destination != ''){

     $searchQuery .= " and (orders.destination='".$destination."') ";

   }
   if($customer_type != ''){

     $searchQuery .= " and (customers.customer_type='".$customer_type."') ";

   }
   if($customer_id != ''){

     $searchQuery .= " and (orders.customer_id='".$customer_id."') ";

   }
   if($payment_status != ''){

     $searchQuery .= " and (orders.payment_status='".$payment_status."') ";

   }
   if($status != ''){

     $searchQuery .= " and (orders.status='".$status."') ";

   }
   if($courier != ''){

     $searchQuery .= " and ( orders.pickup_rider='".$courier."' OR orders.delivery_rider = '".$courier."' OR orders.return_rider = '".$courier."') ";

   }

   if($from != '' && $to !=''){
    $from = date('Y-m-d',strtotime($_POST['from']));

    $to = date('Y-m-d',strtotime($_POST['to']));

    $searchQuery .= " and DATE_FORMAT(`".$_POST['date_type']."`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`".$_POST['date_type']."`, '%Y-%m-%d') <= '".$to."' ";

  }
}

## Total number of records without filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM orders inner Join customers on orders.customer_id=customers.id");
$records = mysqli_fetch_assoc($sel);
// var_dump($records);
$totalRecords = $records['allcount'];
## Total number of records with filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner Join customers on orders.customer_id=customers.id WHERE ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM orders WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
  //count total by raheel
   
  //count total by raheel
}

  //count total by raheel
  
$empQuery = "SELECT orders.*,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery." order by order_date desc";
$query = mysqli_query($con,$empQuery);
$trecord = $query->num_rows;
$tpages = $trecord /$rowperpage;
$cpage = $cpage;
$npage = $cpage +1;
$cookie_name = 'exp_orderFile';
if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name])
{
    $fname = $_COOKIE[$cookie_name];
}
else
{
$fname ="order_".time().".csv";
setcookie($cookie_name, $fname, time() + (86400 * 30), "/");
}
$ret = array(
    'file_name' => $fname,
    'trecord' => $trecord,
    'cpage' => $cpage,
    'npage' => $npage,
    );
    $pr = $cpage * $rowperpage;
    
    if($pr >= $trecord)
    {
     $ret ['npage'] = 0;   
     $ret ['complete'] = 1;   
     $ret ['url'] = BASE_URL.'admin/reports/'.$fname;   
     $ret ['percent'] = 100;
     if (isset($_COOKIE[$cookie_name])) {
    unset($_COOKIE['key']);
    setcookie($cookie_name, '', time() - 3600, '/'); // empty value and old timestamp
}
    }
    else
    {
        
        $prcent = ($pr/$trecord) *100;
        $ret ['percent'] = ceil($prcent);
    }
if(!file_exists($fname))
{

$fp = fopen($fname,"wb");
if( $fp == false ){
    //do debugging or logging here
}
}
else
{
    $fp = fopen($fname,"a");
}
  //count total by raheel
if($cpage)
{
    $row = $row - 1;
    
$empQuery = "SELECT orders.*,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery." order by orders.id desc  limit ".$rowperpage." offset ".$row ;
if(!function_exists('customers')){
  function customers($id=null)
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
     return isset($resposne_service_type['service_type']) ? $resposne_service_type['service_type'] :'';
   }
 }
}
if(!function_exists('insurance_type')){
 function insurance_type($id=null)
 {
   global $con;
   if($id)
   {
     $query_service_type = mysqli_query($con,"SELECT * from insurance_type WHERE id=".$id);
     $resposne_service_type = mysqli_fetch_assoc($query_service_type);
     return isset($resposne_service_type['name']) ? $resposne_service_type['name'] :'';
   }
 }
}




if(!function_exists('booking_type')){
  function booking_type($id=null)
  {
    global $con;
    if($id)
    {
     if($id == '2'){
      return 'Cash' ;
    }elseif($id == '3'){
      return  'To Pay';
    }else{
     return 'Invoice';
   }
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
$empRecords = mysqli_query($con, $empQuery);
if(mysqli_error($con)){
  echo mysqli_error($con);
}
$data = array();
$sr_no =1;
if($empRecords)
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) {
    //   var_dump($fetch1);
    //   die();
    $fetch1 = str_replace(",", " ", $fetch1); 
    $fetch1 = str_replace("/\r|\n/", "", $fetch1);
    // preg_replace( , $yourString );

     
    // $track = number_format($track,0,'','');
    $nums = array('track_no','rphone');
    foreach($nums as $k=> $v)
    {
        $key = $v;
        $fetch1[$key] = number_format($fetch1[$key],0,'','');
        $fetch1[$key] = "".$fetch1[$key]."";
    }
    $track = "'".$track." ";
    // $fetch1['receiver_address'] = preg_replace('/[\n\r]+/', ' ', trim($fetch1['receiver_address']));
       foreach ($fetch1 as $k => $v) {
        $fetch1[$k] = preg_replace('/[\n\r]+/', ' ', trim($fetch1[$k]));
    }

    // if($fetch1['track_no'] == '123')
    // {
    //      $fetch1['receiver_address'] = preg_replace('/[\n\r]+/', ' ', trim($fetch1['receiver_address']));

    //     // $fetch1['receiver_address'] = 'testing';
         
    // }
    //  var_dump($fetch1['price']);
        //   die();
    // var_dump($fetch1);
    // die();
    $arr = array(
        $fetch1['track_no']
    ,$fetch1['order_type_name']
    ,booking_type($fetch1['booking_type'])
    ,getusernameById($fetch1['user_id'])
    ,$fetch1['status']
    ,date('Y-m-d'
    ,strtotime($fetch1['action_date']))
     ,date('Y-m-d'
    ,strtotime($fetch1['order_date']))
    ,$fetch1['sname']
    ,$fetch1['businessname']
    ,$fetch1['sphone']
    ,$fetch1['sender_address']
    ,$fetch1['rname']
    ,$fetch1['rphone']
    ,$fetch1['receiver_address']
    ,$fetch1['origin']
    ,$fetch1['destination']
    ,$fetch1['ref_no']
    ,$fetch1['product_id']
    ,$fetch1['quantity']
    ,$fetch1['weight']
    ,insurance_type($fetch1['is_fragile'])
    ,$fetch1['insured_item_value']
    ,$fetch1['collection_amount']
    ,$fetch1['price']
    ,$fetch1['special_charges']
    ,$fetch1['extra_charges']
    ,$fetch1['insured_premium']
    ,$fetch1['grand_total_charges']
    ,$fetch1['fuel_surcharge']
    ,$fetch1['pft_amount']
    ,$fetch1['net_amount']
    ,$fetch1['payment_status']
    );
    $content = $im = implode(',', $arr).PHP_EOL;
    // var_dump($arr);
    //  die();
    
    
    fwrite($fp,$content);
    // fwrite($fp,$content);
    /*$date_type = $fetch1['date_type'];
    if (isset($_POST['date_type']) && $_POST['date_type']=="action_date") {
      $date_type = $fetch1['action_date'];
    }
    $rate = 0;
    if(isset($fetch1['weight']) && $fetch1['weight'] > 0 && isset($fetch1['price']) && $fetch1['price'] > 0)
    {

      $price = str_replace(array('\'','`','``', '"'), '', $fetch1['price']);
      $weight = str_replace(array('\'','`','``', '"'), '', $fetch1['weight']);
      $rate = (float)($price/$weight);
    }
    $internamtion_print=isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "<a target='_blank' href='../invoicehtml_new.php?order_id=".$fetch1['id']." '> <i class='fa fa-print' style='font-size: 14px;'></i></a>" : "<a target='_blank' href='../invoicehtml.php?order_id=".$fetch1['id']." '> <i class='fa fa-print' style='font-size: 14px;'></i></a>";
    $internamtion_print_air=isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "<a target='_blank' href='../airway_bill.php?order_id=".$fetch1['id']." '> <i class='fa fa-plane' style='font-size: 14px;'></i></a>" : "";
    $data[] = array(
     "id"=>"<input type='checkbox' name='' class='order_check' data-id='".$fetch1['id']."'>",
     "cnno"=>$fetch1['track_no'],
     "service_type"=>$fetch1['order_type_name'],
     "order_type"=>booking_type($fetch1['booking_type']),
     "user"=>getusernameById($fetch1['user_id']),
     "status"=>$fetch1['status'],
     "cndate"=> date(DATE_FORMAT,strtotime($fetch1['order_date'])),
     "pickupname"=>$fetch1['sname'],
     "pickupcompany"=>$fetch1['businessname'],
     "pickupphone"=>$fetch1['sphone'],
     "pickupaddress"=>$fetch1['sender_address'],
     "deliveryname"=>$fetch1['rname'],
     "deliveryphone"=>$fetch1['rphone'],
     "deliveryaddress"=>$fetch1['receiver_address'],
     "pickupcity"=>$fetch1['origin'],
     "deliverycity"=>$fetch1['destination'],
     "refernceno"=>$fetch1['ref_no'],
     "orderid"=>$fetch1['product_id'],
     "noofpiece"=>$fetch1['quantity'],
     "parcelweight"=>$fetch1['weight'],
     "fragile"=>insurance_type($fetch1['is_fragile']),
     "insureditemdeclare"=>$fetch1['insured_item_value'],
     "codamount"=>$fetch1['collection_amount'],
     "deliveryfee"=>$fetch1['price'],
     "specialcharges"=>$fetch1['special_charges'],
     "extra_charges"=>$fetch1['extra_charges'],
     "insurancepremium"=>$fetch1['insured_premium'],
     "grand_total_charges"=>$fetch1['grand_total_charges'],
     "fuelsurcharge"=>$fetch1['fuel_surcharge'],
     "salestax"=>$fetch1['pft_amount'],
     "netamount"=>$fetch1['net_amount'],
     "paymentstatus"=>$fetch1['payment_status'],
     "action"=>"<a href='order.php?id=".$fetch1['id']." '> <i class='fa fa-eye' style='font-size: 14px;'></i></a><a target='_blank' href='".BASE_URL."track-details.php?track_code= ".$fetch1['track_no']."'  > <i style='color: #da1414;font-size: 14px;' class='fa fa-trash'></i></a>".$internamtion_print.$internamtion_print_air.""
   );
    $sr_no++;*/
  }
}
}
else
{
    if($cpage == 0)
    {
    $head = array(
    'Tracking No',
    'Service Type'
    ,'Order Type'
    , 'User'
    ,'Status'
    ,'Update Date'
    ,'Order Date'
    ,'Pickup name'
    ,'Pickup Company'
    ,'Pickup Phone'
    ,'Pickup Address'
    ,'Delivery Name'
    ,'Delivery Phone'
    ,'Delivery Address'
    ,'Pickup City'
    ,'Delivery City'
    ,'Reference No.'
    ,' Order ID.'
    ,' No. of Pieces'
    ,'Parcel Weight'
    ,'Insurance Type'
    ,'Insured Items Declared Value'
    ,' COD Amount'
    , 'Delivery Fees '
    ,'Special Charges'
    ,'Extra Charges '
    ,'Insurance Premium '
    ,'Grand Total Amount'
    ,'Fuel Surcharge '
    ,'Sales tax'
    ,'Net Amount '
    ,'Payment Status'


);
    $content = $im = implode(',', $head).PHP_EOL;
    
    fwrite($fp,$content);
    }
}
//var_dump($content);

    fclose($fp);
echo json_encode($ret);
exit();