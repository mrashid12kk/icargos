 <?php

session_start();

include_once 'includes/conn.php';
include_once 'includes/role_helper.php';

if (isset($_POST['vieworder'])) {


    $tracking_no = $_POST['tracking_no'];

    $customer_name = $_POST['customer_name'];

    $customer_phone = $_POST['customer_phone'];

    $date_type = $_POST['date_type'];

    $date_from = $_POST['date_from'];

    $date_to = $_POST['date_to'];

    $active_customer = $_POST['active_customer'];

    $track_no = $_POST['track_no'];

    $pickup_rider = $_POST['pickup_rider'];

    $delivery_rider = $_POST['delivery_rider'];

    $order_status = $_POST['order_status'];

    $order_city = $_POST['order_city'];

    $origin_city = $_POST['origin_city'];

    $delayed = $_POST['delayed'];
    $searchQuery = " ";

     if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $searchQuery .= " AND (origin IN ($all_allowed_origins) OR current_branch = ".$_SESSION['branch_id'].")";
      }else{
        $searchQuery .= " AND (origin IN ($all_allowed_origins) OR current_branch = 1 OR booking_branch = 1)";
      }
    if ($tracking_no != '') {

        $searchQuery .= " and (track_no='" . $tracking_no . "') ";

    }

    if ($customer_name != '') {

        $searchQuery .= " and (sname='" . $customer_name . "') ";

    }

    if ($customer_phone != '') {

        $searchQuery .= " and (sphone='" . $customer_phone . "') ";

    }



    if ($date_from != '' && $date_to != '') {

        $from = date('Y-m-d', strtotime($_POST['date_from']));

        $to = date('Y-m-d', strtotime($_POST['date_to']));

        $searchQuery .= " and DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') <= '" . $to . "' ";

    }

    if ($active_customer != '') {

        $searchQuery .= " and (customer_id='" . $active_customer . "') ";

    }

    if ($pickup_rider != '') {

        $searchQuery .= " and (pickup_rider='" . $pickup_rider . "') ";

    }

    if ($delivery_rider != '') {

        $searchQuery .= " and (delivery_rider='" . $delivery_rider . "') ";

    }

    if ($order_status != '') {

        $searchQuery .= " and (status='" . $order_status . "') ";

    }

    if ($order_city != '') {

        $searchQuery .= " and (destination='" . $order_city . "') ";

    }

    if ($origin_city != '') {

        $searchQuery .= " and (origin='" . $origin_city . "') ";

    }
    if ($track_no != '') {

        $searchQuery .= " and (track_no='" . $track_no . "') ";

    }
    if ($tracking_no != '') {

        $searchQuery = " and (track_no='" . $tracking_no . "') ";

    }
    if($delayed != ''){
        if ($delayed=='delayed') {
           $query_d=mysqli_query($con,"SELECT * from orders WHERE 1 AND status!='cancelled' AND status!='New Booked' AND status!='Delivered' AND status!='Returned to Shipper' ".$searchQuery."");
           while ($row2=mysqli_fetch_assoc($query_d)) {
              $action_date=strtotime($row2['action_date']);
              $date=strtotime(date('Y-m-d H:i:s'));
              $diff = $date - $action_date;
              $hours = $diff / ( 60 * 60 );
              $order_delat_time=getConfig('order_delayed_time');
              if($hours>=$order_delat_time){
                  $delayed_order .=''.$row2['id'].',';
              }
           }
           $trim_delayed_order = trim($delayed_order, ',');
           $searchQuery .= " and (id IN (".$trim_delayed_order.")) ";
        }
    }
      $data     = array();
    $empQueryd = "SELECT count(id) as delivery FROM orders WHERE 1 " . $searchQuery . "  AND (status ='Delivered')";

    $empRecordsd = mysqli_query($con, $empQueryd);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
        $data['delivery'] = $fetch1['delivery'];
    }

    $empQueryo = "SELECT count(id) as open FROM orders WHERE 1 " . $searchQuery . " AND (status !='Delivered' AND status != 'Returned to Shipper')";

    $empRecordso = mysqli_query($con, $empQueryo);
    while ($fetch1 = mysqli_fetch_assoc($empRecordso)) {
        $data['open'] = $fetch1['open'];
    }

    $empQueryr = "SELECT count(id) as returned FROM orders WHERE 1 " . $searchQuery . " AND (status ='Returned to Shipper')";

    $empRecordsr = mysqli_query($con, $empQueryr);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsr)) {
        $data['returned'] = $fetch1['returned'];
    }
    echo json_encode($data);
}

if (isset($_POST['myorder'])) {


    $tracking_no = $_POST['tracking_no'];

    $customer_name = $_POST['customer_name'];

    $customer_phone = $_POST['customer_phone'];

    $customer_email = $_POST['customer_email'];

    $date_from = $_POST['date_from'];

    $date_to = $_POST['date_to'];

    $active_customer = $_POST['active_customer'];

    $track_no = $_POST['track_no'];

    $pickup_rider = $_POST['pickup_rider'];

    $delivery_rider = $_POST['delivery_rider'];

    $order_status = $_POST['order_status'];

    $order_city = $_POST['order_city'];

    $user_id = $_POST['user_id'];

    $user_role_id = $_POST['user_role_id'];

    $brach_id = $_POST['brach_id'];

    $searchQuery = " ";

      if (isset($user_role_id) && $user_role_id==getfranchisemanagerId()) {

        $searchQuery .="AND user_id=". $user_id;
    }
    if(isset($brach_id) && !empty($brach_id) && $brach_id!='')
    {
        $searchQuery .= " AND current_branch = ". $brach_id;
    }else
    {
        $searchQuery .= " AND current_branch = 1 ";
    }
    if ($tracking_no != '') {

        $searchQuery .= " and (track_no='" . $tracking_no . "') ";

    }

    if ($customer_name != '') {

        $searchQuery .= " and (sname='" . $customer_name . "') ";

    }

    if ($customer_phone != '') {

        $searchQuery .= " and (sphone='" . $customer_phone . "') ";

    }

    if ($customer_email != '') {

        $searchQuery .= " and (semail='" . $customer_email . "') ";

    }

    if ($date_from != '' && $date_to != '') {

        $from = date('Y-m-d', strtotime($_POST['date_from']));

        $to = date('Y-m-d', strtotime($_POST['date_to']));

        $searchQuery .= " and DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $to . "' ";

    }

    if ($active_customer != '') {

        $searchQuery .= " and (customer_id='" . $active_customer . "') ";

    }

    if ($pickup_rider != '') {

        $searchQuery .= " and (pickup_rider='" . $pickup_rider . "') ";

    }

    if ($delivery_rider != '') {

        $searchQuery .= " and (delivery_rider='" . $delivery_rider . "') ";

    }

    if ($order_status != '') {

        $searchQuery .= " and (status='" . $order_status . "') ";

    }

    if ($order_city != '') {

        $searchQuery .= " and (destination='" . $order_city . "') ";

    }

    if ($origin_city != '') {

        $searchQuery .= " and (origin='" . $origin_city . "') ";

    }
    if ($track_no != '') {

        $searchQuery .= " and (track_no='" . $track_no . "') ";

    }

      $data     = array();
    $empQueryd = "SELECT count(id) as delivery FROM orders WHERE 1 " . $searchQuery . "  AND (status ='Delivered')";
    $empRecordsd = mysqli_query($con, $empQueryd);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
        $data['delivery'] = $fetch1['delivery'];
    }

    $empQueryo = "SELECT count(id) as open FROM orders WHERE 1 " . $searchQuery . " AND (status !='Delivered' AND status != 'Returned to Shipper')";
  
    $empRecordso = mysqli_query($con, $empQueryo);
    while ($fetch1 = mysqli_fetch_assoc($empRecordso)) {
        $data['open'] = $fetch1['open'];
    }

    $empQueryr = "SELECT count(id) as returned FROM orders WHERE 1 " . $searchQuery . " AND (status ='Returned to Shipper')";

    $empRecordsr = mysqli_query($con, $empQueryr);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsr)) {
        $data['returned'] = $fetch1['returned'];
    }
    echo json_encode($data);
}



// cooment report


if (isset($_POST['comments_report'])) {



    $from = $_POST['from'];

    $to = $_POST['to'];

    $customer_id = $_POST['customer_id'];

    if ($from != '' && $to != '') {

        $from = date('Y-m-d', strtotime($_POST['from']));

        $to = date('Y-m-d', strtotime($_POST['to']));

        $searchQuery .= " and DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "' ";

    }

    if ($customer_id != '') {

        $searchQuery .= " and (order_comments.customer_id='" . $customer_id . "') ";

    }

      $data     = array();
    $empQueryd = "SELECT count(*) as readdata from order_comments where is_read=1 ".$searchQuery."";
    // echo $empQueryd;die;
    $empRecordsd = mysqli_query($con, $empQueryd);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
        $data['read'] = $fetch1['readdata'];
    }

    $empQueryo = "SELECT count(*) as unread from order_comments where is_read=0 ".$searchQuery."";

    $empRecordso = mysqli_query($con, $empQueryo);
    while ($fetch1 = mysqli_fetch_assoc($empRecordso)) {
        $data['unread'] = $fetch1['unread'];
    }

    $empQueryr = "SELECT count(*) as data from order_comments WHERE 1 ".$searchQuery ."";
    // echo $empQueryr;
    // die;
    $empRecordsr = mysqli_query($con, $empQueryr);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsr)) {
        $data['all'] = $fetch1['data'];
    }
    echo json_encode($data);
}

?>
