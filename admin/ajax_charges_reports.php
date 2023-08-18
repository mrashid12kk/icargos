<?php
 ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
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
$date_type = $_POST['date_type'];
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

           $searchQuery .= " and DATE_FORMAT(`orders`.".$_POST['date_type'].", '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`orders`.".$_POST['date_type'].", '%Y-%m-%d') <= '".$to."' ";
        }
}

## Total number of records without filtering


if (isset($_POST['date_type']) && $_POST['date_type']=='created_on') {
  $sel = mysqli_query($con,"SELECT COUNT(DISTINCT order_logs.order_no) as allcount FROM orders inner Join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery."");

}else{
  $sel = mysqli_query($con,"SELECT count(*) as allcount FROM orders inner Join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery."");
}
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering
if (isset($_POST['date_type']) && $_POST['date_type']=='created_on') {

  $sel = mysqli_query($con,"SELECT COUNT(DISTINCT order_logs.order_no) as allcount,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner Join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery."");

}else{
  $sel = mysqli_query($con,"SELECT count(*) as allcount FROM orders inner Join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery."");


}


$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM orders WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}
 $empQuery = "SELECT orders.*,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery." order by id desc  limit ".$row.",".$rowperpage ;

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
    $rate = 0;
      $pmntMethod=$fetch1["payment_status"];
                 if($fetch1["customer_id"]=="1"  && $fetch1["payment_status"]=="Paid" || $fetch1["customer_id"]=="2")
                 {
                   $pmntMethod = "Invoiced";
                 }
    if(isset($fetch1['weight']) && $fetch1['weight'] > 0 && isset($fetch1['price']) && $fetch1['price'] > 0)
    {

      $price = str_replace(array('\'','`','``', '"'), '', $fetch1['price']);
      $weight = str_replace(array('\'','`','``', '"'), '', $fetch1['weight']);
      $rate = (float)($price/$weight);
    }
     $data[] = array(
      "id"=>"<input type='checkbox' name='' class='order_check' data-id='".$fetch1['id']."'>",
       "cnno"=>$fetch1['track_no'],
       "businessname"=>$fetch1['businessname'],
       "refno"=>$fetch1['ref_no'],
       "orderid"=>$fetch1['product_id'],
       "quantity"=>$fetch1['quantity'],
       "weight"=>$fetch1['weight'],
       "fragile"=>$fetch1['is_fragile'],
       "itemvalue"=>$fetch1['insured_item_value'],
       "collection_amount"=> number_format((float)$fetch1['collection_amount'],2),
       "price"=> number_format((float)$fetch1['price'],2),
       "specialcharges"=> number_format((float)$fetch1['special_charges'],2),
       "extracharges"=> number_format((float)$fetch1['extra_charges'],2),
       "insuredpremium"=> number_format((float)$fetch1['insured_premium'],2),
       "grandtotalcharges"=> number_format((float)$fetch1['grand_total_charges'],2),
       "fuelsurcharge"=> number_format((float)$fetch1['fuel_surcharge'],2),
       "pftamount"=> number_format((float)$fetch1['pft_amount'],2),
       "netamount" =>number_format((float)$fetch1['net_amount'],2),
       "customertype"=>'<span class="btn btn-info center action_btns">'.$pmntMethod.'</span>',
       "action"=>'<a href="order.php?id= echo $fetch1["id"];"> <i class="fa fa-eye" style="font-size: 14px;"></i></a>
           <a target="_blank" href=" echo BASE_URL track-details.php?track_code= echo $fetch1["track_no"] "> <i style="color: #da1414;font-size: 14px;" class="fa fa-trash"></i></a>'
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
