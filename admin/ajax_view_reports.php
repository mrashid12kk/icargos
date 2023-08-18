<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require 'includes/conn.php';
    // echo "sring";
    // die;
    ## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    // $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value
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
    if($searchValue != ''){

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
$totalRecords = $records['allcount'];
## Total number of records with filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner Join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM orders WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}
$empQuery = "SELECT orders.*,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name,non_customer_ledger_payments.id as ledgerid FROM orders LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id LEFT JOIN non_customer_ledger_payments ON orders.id=non_customer_ledger_payments.ledger_orders WHERE 1 ".$searchQuery." order by id desc  limit ".$row.",".$rowperpage ;
// var_dump($empQuery);
// die();
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
$data = array();
$sr_no =1;

if($empRecords)
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) {
$invoice = '';


if($fetch1['payment_status'] == 'Paid')
{
  if(!empty($fetch1['payment_ledger_id']))
  {
    $invoice = "<a target='_blank' href='".BASE_URL."admin/ledger_payment_view.php?payment_id=".$fetch1['payment_ledger_id']."'  >00".$fetch1['payment_ledger_id']."</a>";
  }

  
}

// die("okk");

    $date_type = $fetch1['date_type'];
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
     "status_date"=>date('Y-m-d H:i:s', strtotime($fetch1['action_date'])),
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
     "invoice"=>$invoice,
     "paymentstatus"=>$fetch1['payment_status'],
     "action"=>"<a href='order.php?id=".$fetch1['id']." '> <i class='fa fa-eye' style='font-size: 14px;'></i></a><a target='_blank' href='".BASE_URL."track-details.php?track_code= ".$fetch1['track_no']."'  > <i style='font-size: 14px;' class='fa fa-truck'></i></a>".$internamtion_print.$internamtion_print_air.""
   );
    $sr_no++;
  }
}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);
echo json_encode($response);
