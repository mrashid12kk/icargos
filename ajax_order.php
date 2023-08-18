<?php
  $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
  $filter_query = '';
  if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $filter_query = " AND (origin IN ($all_allowed_origins) OR current_branch = ".$_SESSION['branch_id'].")";
  }else{
    $filter_query = " AND (origin IN ($all_allowed_origins) OR current_branch = 1 OR booking_branch = 1)";
  }

  $filter_query_status = "";
  $active_tracking = "";
  $active_customer_name = "";
  $active_customer_phone = "";
  $active_customer_email = "";
  $active_customer_id = "";
  $pickup_rider = "";
  $delivery_rider = "";
  $active_order_status = "";
  $active_order_city = "";

  if (isset($_GET['type']) && !empty($_GET['type']) && $_GET['type']!='open') {
    $filter_query_status .=" AND status ='".$_GET['type']."'";

  }
  if (isset($_GET['type']) && !empty($_GET['type']) && $_GET['type']=='open') {
    $filter_query_status .=" AND status !='Delivered' AND status != 'Returned to Shipper'";
  }
  if(isset($_POST['submit'])){
    if(isset($_POST['tracking_no']) && !empty($_POST['tracking_no'])){
      $active_tracking = $_POST['tracking_no'];
      $query1 = mysqli_query($con,"SELECT * FROM orders WHERE track_no = '".$_POST['tracking_no']."' ");
    }else{
      if(isset($_POST['customer_name']) && !empty($_POST['customer_name'])){
        $filter_query .= " AND sname = '".$_POST['customer_name']."' ";
        $active_customer_name = $_POST['customer_name'];
      }
      if(isset($_POST['customer_phone']) && !empty($_POST['customer_phone'])){
        $filter_query .= " AND sphone = '".$_POST['customer_phone']."' ";
        $active_customer_phone = $_POST['customer_phone'];
      }
      if(isset($_POST['customer_email']) && !empty($_POST['customer_email'])){
        $filter_query .= " AND semail = '".$_POST['customer_email']."' ";
        $active_customer_email = $_POST['customer_email'];
      }
      if(isset($_POST['active_customer']) && !empty($_POST['active_customer'])){
        $filter_query .= " AND customer_id = '".$_POST['active_customer']."' ";
        $active_customer_id = $_POST['active_customer'];
      }
      if(isset($_POST['pickup_rider']) && !empty($_POST['pickup_rider'])){
        $filter_query .= " AND pickup_rider = '".$_POST['pickup_rider']."' ";
        $pickup_rider = $_POST['pickup_rider'];
      }
      if(isset($_POST['delivery_rider']) && !empty($_POST['delivery_rider'])){
        $filter_query .= " AND delivery_rider = '".$_POST['delivery_rider']."' ";
        $delivery_rider = $_POST['delivery_rider'];
      }
      if(isset($_POST['order_status']) && !empty($_POST['order_status'])){
        $filter_query .= " AND status = '".$_POST['order_status']."' ";
        $active_order_status = $_POST['order_status'];
      }
      if(isset($_POST['origin_city']) && !empty($_POST['origin_city'])){
        $filter_query .= " AND origin = '".$_POST['origin_city']."' ";
        $active_origin_city = $_POST['origin_city'];
      }
      if(isset($_POST['order_city']) && !empty($_POST['order_city'])){
        $filter_query .= " AND destination = '".$_POST['order_city']."' ";
        $active_order_city = $_POST['order_city'];
      }
      $from = date('Y-m-d',strtotime($_POST['from']));
      $to = date('Y-m-d',strtotime($_POST['to']));
      $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query $filter_query_status order by id desc ");
      $query2 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query $filter_query_status order by id desc ");

      $openCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status !='Delivered' AND status != 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' $filter_query"));

        $openCount = $openCountRes['open_id'];

        $delCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status ='Delivered' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'  $filter_query"));

        $deliverCount = $delCountRes['open_id'];

        $returnedCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where  status = 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query"));

        $returnedCount = $returnedCountRes['open_id'];

      // echo "SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query order by id desc ";
      // die();
    }
  }
  elseif(isset($_GET['order_status']) && !empty($_GET['order_status'])){

    $filter_query .= " AND status = '".$_GET['order_status']."' ";
    $active_order_status = $_GET['order_status'];
      $from = date('Y-m-d',strtotime($_GET['from']));
      $to = date('Y-m-d',strtotime($_GET['to']));
    if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
      $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query $filter_query_status  order by id desc ");


      $openCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status !='Delivered' AND status != 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query"));

        $openCount = $openCountRes['open_id'];

        $delCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status ='Delivered' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

        $deliverCount = $delCountRes['open_id'];

        $returnedCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where  status = 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

        $returnedCount = $returnedCountRes['open_id'];

    }else{
      $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query $filter_query_status order by id desc ");

      $openCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status !='Delivered' AND status != 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

        $openCount = $openCountRes['open_id'];

        $delCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status ='Delivered' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

        $deliverCount = $delCountRes['open_id'];

        $returnedCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where  status = 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

        $returnedCount = $returnedCountRes['open_id'];
    }
  }
  else{
    $from = date('Y-m-d', strtotime('today - 30 days'));
    $to = date('Y-m-d');
    // echo "SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query $filter_query_status  order by id desc ";
    // die;
    $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query $filter_query_status  order by id desc ");
// echo "SELECT count(id) as open_id from orders where status !='Delivered' AND status != 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query ";
// die;
    $openCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status !='Delivered' AND status != 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

      $openCount = $openCountRes['open_id'];

      $delCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where status ='Delivered' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

      $deliverCount = $delCountRes['open_id'];

      $returnedCountRes = mysqli_fetch_array(mysqli_query($con, "SELECT count(id) as open_id from orders where  status = 'Returned to Shipper' AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query "));

      $returnedCount = $returnedCountRes['open_id'];

  }
?>
<?php
  if(isset($message) && !empty($message)){
    echo $message;
  }
  $courier_query=mysqli_query($con,"Select * from users where type='driver'");
  $delivery_courier_query=mysqli_query($con,"Select * from users where type='driver'");
  $status_query=mysqli_query($con,"Select * from order_status where active='1'");
  $city_query=mysqli_query($con,"Select * from cities where 1");
  $city_querys=mysqli_query($con,"Select * from cities where 1");
  $branch_query=mysqli_query($con,"Select * from branches where 1");
  $currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
  $status_data_fr_dl = mysqli_fetch_array(mysqli_query($con,"Select * from order_status where sts_id=1  "));
?>
<style type="text/css">
  .zones_main{
    margin-bottom: 20px;
  }
  .badge {
    width: 100%;
    border-radius: 2px;
    padding: 6px 5px;
    line-height: 1.6;
  }
</style>
<?php
  function getBranchNameById($id)
  {
    global $con;
    $id = isset($id) ? $id : 1;
    $branchQ = mysqli_query($con, "SELECT name from branches where id = $id");
    $res = mysqli_fetch_array($branchQ);
    if($res['name']){
      return $res['name'];
    }
  }
  function getServiceType($id)
  {
    global $con;
    $branchQ = mysqli_query($con, "SELECT * from services where id = $id");
    $res = mysqli_fetch_array($branchQ);
    return $res['service_code'];
  }
  function customdata($id)
  {
    global $con;
    $branchQ = mysqli_query($con, "SELECT * from customers where id = $id");
    $res = mysqli_fetch_array($branchQ);
    if($res>0)
      return $res;
    else{
      return '';
    }
  }
  function getDeliveryZoneById($id)
  {
    global $con;
    $branchQ = mysqli_query($con, "SELECT zone_name from delivery_zone where id = $id");
    $res = mysqli_fetch_array($branchQ);
    if($res['zone_name']){
      return $res['zone_name'];
    }
  }
  function getDeliveryRiderById($id)
  {
    global $con;
    $branchQ = mysqli_query($con, "SELECT Name from users where id = $id");
    $res = mysqli_fetch_array($branchQ);
    if($res['Name']){
      return $res['Name'];
    }
  }
  function encrypt($string) {
    $key="usmannnn";
    $result = '';
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
    }
    return base64_encode($result);
  }
  function getcustomerNameById($id)
  {
    global $con;
    $branchQ = mysqli_query($con, "SELECT * from customers where id = $id");
    $res = mysqli_fetch_array($branchQ);
    return $res['bname'];
  }
  if(isset($_GET['message']) && !empty($_GET['message'])){
    echo $_GET['message'];
  }
   function getCustomer($customer_id)
    {
      $cust_detail = "";
      global $con;
      $sql= "SELECT * FROM customers   WHERE id  = '".$customer_id."'  ";
      $query_order_cus = mysqli_query($con,$sql);
      $cust_detail = mysqli_fetch_array($query_order_cus);
      return $cust_detail;
    }


?>
<div class="warper container-fluid padd_none">
             <div class="myTextMessage"></div>
             <div class="filter_box_view">
                         <form method="POST" action="">
                            <div class="row" >
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('trackingno'); ?> </label>
                                        <input type="text" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no" id="tracking_no">
                                        <div class="field_svg">
                                          <svg viewBox="0 0 24 24"><path d="M11.5 7a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm-4.7 4.357l4.7 7.73l4.7-7.73a5.5 5.5 0 1 0-9.4 0zm10.254.52L11.5 22.012l-5.554-9.135a6.5 6.5 0 1 1 11.11 0h-.002z" fill="#626262"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('pickupname'); ?> </label>
                                        <input type="text" value="<?php echo $active_customer_name; ?>" class="form-control " name="customer_name" id="customer_name">
                                    </div>
                                    <div class="field_svg">
                                        <svg viewBox="0 0 24 24"><path d="M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z" fill="#626262"/></svg>
                                      </div>

                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('pickupphone'); ?></label>
                                        <input type="text" value="<?php echo $active_customer_phone; ?>" class="form-control " name="customer_phone" id="customer_phone">
                                        <div class="field_svg">
                                        <svg  viewBox="0 0 24 24"><path d="M19.5 22c.827 0 1.5-.673 1.5-1.5V17c0-.827-.673-1.5-1.5-1.5c-1.17 0-2.32-.184-3.42-.547a1.523 1.523 0 0 0-1.523.363l-1.44 1.44a14.655 14.655 0 0 1-5.885-5.883L8.66 9.436c.412-.382.56-.963.384-1.522A10.872 10.872 0 0 1 8.5 4.5C8.5 3.673 7.827 3 7 3H3.5C2.673 3 2 3.673 2 4.5C2 14.15 9.85 22 19.5 22zM3.5 4H7a.5.5 0 0 1 .5.5c0 1.277.2 2.531.593 3.72a.473.473 0 0 1-.127.497L6.01 10.683c1.637 3.228 4.055 5.646 7.298 7.297l1.949-1.95a.516.516 0 0 1 .516-.126c1.196.396 2.45.596 3.727.596c.275 0 .5.225.5.5v3.5c0 .275-.225.5-.5.5C10.402 21 3 13.598 3 4.5a.5.5 0 0 1 .5-.5z" fill="#626262"/></svg>
                                      </div>

                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label>Date Type </label>
                                        <select class="form-control" name="date_type" id="date_type">
                                            <option value="order_date">Order Date</option>
                                            <option value="action_date">Status Date</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="col-sm-1 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('from'); ?></label>
                                        <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from" id="date_from">
                                    </div>
                                    <div class="field_svg">
                                        <svg  viewBox="0 0 24 24"><path d="M7 2h1a1 1 0 0 1 1 1v1h5V3a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3V3a1 1 0 0 1 1-1zm8 2h1V3h-1v1zM8 4V3H7v1h1zM6 5a2 2 0 0 0-2 2v1h15V7a2 2 0 0 0-2-2H6zM4 18a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V9H4v9zm8-5h5v5h-5v-5zm1 1v3h3v-3h-3z" fill="#626262"/></svg>
                                      </div>

                                </div>
                                <div class="col-sm-1 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('to'); ?></label>
                                        <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to"  id="date_to">
                                    </div>
                                    <div class="field_svg">
                                        <svg  viewBox="0 0 24 24"><path d="M7 2h1a1 1 0 0 1 1 1v1h5V3a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3V3a1 1 0 0 1 1-1zm8 2h1V3h-1v1zM8 4V3H7v1h1zM6 5a2 2 0 0 0-2 2v1h15V7a2 2 0 0 0-2-2H6zM4 18a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V9H4v9zm8-5h5v5h-5v-5zm1 1v3h3v-3h-3z" fill="#626262"/></svg>
                                      </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('customer'); ?></label>
                                        <select class="form-control active_customer_detail js-example-basic-single" name="active_customer"  id="active_customer">
                                            <option selected value=""><?php echo getLange('all').' '.getLange('customer'); ?></option>
                                            <?php foreach($customers as $customer){ ?>
                                            <option  <?php if($customer['id'] == $active_customer_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('pickuprider'); ?> </label>
                                        <select class="form-control courier_list js-example-basic-single" name="pickup_rider" id="pickup_rider">
                                            <option selected value=""><?php echo getLange('select').' '.getLange('rider'); ?></option>
                                            <?php while($row=mysqli_fetch_array($courier_query)){ ?>
                                            <option <?php if($row['id'] == $pickup_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('deliveryrider'); ?> </label>
                                        <select class="form-control courier_list js-example-basic-single" name="delivery_rider"  id="delivery_rider">
                                            <option selected value=""><?php echo getLange('select').' '.getLange('rider'); ?></option>
                                            <?php while($row=mysqli_fetch_array($delivery_courier_query)){ ?>
                                            <option <?php if($row['id'] == $delivery_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('orderstatus'); ?> </label>
                                        <select class="form-control courier_list js-example-basic-single" id="order_status" name="order_status"  >
                                            <option selected value=""><?php echo getLange('select').' '.getLange('status'); ?></option>
                                            <?php while($row=mysqli_fetch_array($status_query)){ ?>
                                            <option <?php if($row['status'] == $active_order_status ){ echo "selected"; } ?> value="<?php echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('origin'); ?></label>
                                        <select class="form-control courier_list js-example-basic-single" name="origin_city"  id="origin_city">
                                            <option value="" selected><?php echo getLange('all').' '.getLange('city'); ?></option>
                                            <?php while($row=mysqli_fetch_array($city_query)){ ?>
                                            <option  <?php if($row['city_name'] == $active_origin_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none" >
                                    <div class="form-group">
                                        <label><?php echo getLange('destination'); ?></label>
                                        <select class="form-control courier_list js-example-basic-single" name="order_city"  id="order_city">
                                            <option value="" selected><?php echo getLange('all').' '.getLange('city'); ?></option>
                                            <?php while($row=mysqli_fetch_array($city_querys)){ ?>
                                            <option  <?php if($row['city_name'] == $active_order_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 sidegapp-submit " style="margin: 0;">
                                    <input type="button"  id="submit_order" name="submit" class="btn btn-success search_filter" value="<?php echo getLange('search'); ?>">
                                </div>
                            </div>

                        </form>
                     </div>
             <div class="orders_btns">
                 <ul>
                     <li>
                         <div class="orders_items active_orders" id="rtl_active_orders" data-status="open"  style='border-radius:  35px 0px 0px 35px;'>
                             <b><?php echo getLange('openorders'); ?></b>
                             <p ><?php echo getLange('donetotal') ?> <span class="openCount"></span></p>
                         </div>
                     </li>
                     <li class="delivered_status_active">
                         <div id="second_tab" class="orders_items" data-status="Delivered">
                             <b><?php echo getLange('delivered'); ?></b>
                             <p><?php echo getLange('total'); ?><span class="deliverCount"></span></p>
                         </div>
                     </li>
                     <li>
                         <div id="third_tab" class="active_return_orders orders_items" data-status="Returned" style='border-radius:0 35px 35px 0;'>
                             <b><?php echo getLange('returned'); ?></b>
                             <p><?php echo getLange('donetotal') ?><span class="returnedCount"></span></p>
                         </div>
                     </li>
                 </ul>
             </div>
                <!-- <div class="page-header"><h1>Order List </h1></div> -->
                <div class="manifest_box_">
                    <div class="row">
                        <div class="col-sm-9 order_listing_views table-responsive">
                          <table class="order_list_view" id="unique_order_datatable" >
              <!-- <table class="" > -->
                <thead style="display: none">
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </thead>

                            </table>
              </div>
              <div class="cargo_banner"></div>
                          <div class="col-sm-3 order_info_box hidden" id="view_box_detail">

                                  <div class="fix_wrapper_h" id="fix_wrapper_h">
                                  </div>
                          </div>

                      </div>

                </div>
            </div>

        </div>
