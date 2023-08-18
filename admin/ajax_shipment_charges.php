 <?php

session_start();

include_once 'includes/conn.php';
include_once 'includes/role_helper.php';

if (isset($_POST['charges_report'])) {

    $tracking_no = $_POST['tracking_no'];

    $courier = $_POST['courier'];

    $from = $_POST['from'];

    $to = $_POST['to'];

    $customer_id = $_POST['customer_id'];

    $customer_type = $_POST['customer_type'];

    $payment_status = $_POST['payment_status'];

    $date_type = $_POST['date_type'];

    $status = $_POST['status'];

    $destination = $_POST['destination'];

    $origin = $_POST['origin'];

    $searchQuery = " ";
   if($tracking_no != ''){

        $searchQuery .= " and (orders.track_no='".$tracking_no."') ";

    }
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

           $searchQuery .= " and DATE_FORMAT(`orders`.order_date, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`orders`.order_date, '%Y-%m-%d') <= '".$to."' ";

        }
      $data     = array();
      $noofpiece=0;
      $parcelweight=0;
      $fragile=0;
      $insureditemdeclare=0;
      $codamount=0;
      $deliveryfee=0;
      $specialcharges=0;
      $extra_charges=0;
      $insurancepremium=0;
      $grand_total_charges=0;
      $fuelsurcharge=0;
      $salestax=0;
      $netamount=0;

    $empQueryd =  "SELECT orders.*,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM orders LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id WHERE 1 ".$searchQuery."";

    $empRecordsd = mysqli_query($con, $empQueryd);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {

        $parcelweight +=$fetch1['weight'];
      
        $codamount +=$fetch1['collection_amount'];
       
    }
  
    $data['parcelweight']=$parcelweight;
   
    $data['codamount']=number_format((float)$codamount,2);
    
    echo json_encode($data);
}
?>