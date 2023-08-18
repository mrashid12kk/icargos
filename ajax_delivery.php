 <?php
  require 'includes/conn.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_POST['vieworder'])) {


    $tracking_no = $_POST['tracking_no'];

    $date_type = $_POST['date_type'];

    $date_from = $_POST['date_from'];

    $date_to = $_POST['date_to'];

    $order_status = $_POST['order_status'];

    $order_city = $_POST['order_city'];

    $origin_city = $_POST['origin_city'];

    $searchQuery = " ";
     
$id = $_POST['customer_id'];
    if ($tracking_no != '') {

        $searchQuery .= " and (track_no='" . $tracking_no . "') ";

    }




    if ($date_from != '' && $date_to != '') {

        $from = date('Y-m-d', strtotime($_POST['date_from']));

        $to = date('Y-m-d', strtotime($_POST['date_to']));

        $searchQuery .= " and DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') <= '" . $to . "' ";

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

      $data     = array();
    $empQueryd = "SELECT count(id) as delivery FROM orders WHERE customer_id =".$id." " . $searchQuery . "  AND (status ='Delivered')";

    $empRecordsd = mysqli_query($con, $empQueryd);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
        $data['delivery'] = $fetch1['delivery'];
    }

    $empQueryo = "SELECT count(id) as open FROM orders WHERE customer_id =".$id." " . $searchQuery . " AND (status !='Delivered' AND status != 'Returned to Shipper')";

    $empRecordso = mysqli_query($con, $empQueryo);
    while ($fetch1 = mysqli_fetch_assoc($empRecordso)) {
        $data['open'] = $fetch1['open'];
    }

    $empQueryr = "SELECT count(id) as returned FROM orders WHERE customer_id =".$id." " . $searchQuery . " AND (status ='Returned to Shipper')";

    $empRecordsr = mysqli_query($con, $empQueryr);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsr)) {
        $data['returned'] = $fetch1['returned'];
    }
    echo json_encode($data);
}

?>
