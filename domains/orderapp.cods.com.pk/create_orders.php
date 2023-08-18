<?php
// Get our helper functions

include_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);
$access_token = $_SESSION['access_token'];
$shop = SHOP_NAME;
$array = '';
$pref_q = mysqli_query($con, "SELECT * FROM preferences WHERE access_token='" . $access_token . "' ");

$pref_rows = mysqli_num_rows($pref_q);
$pref_q_res = mysqli_fetch_array($pref_q);
$shipperInfo = array();
if (isset($pref_rows) && $pref_rows > 0) {
  $shipper_fetch_q = mysqli_query($con, "SELECT * FROM shipper_info WHERE client_code='" . $pref_q_res['client_code'] . "' ");
  $shipperFetch = mysqli_fetch_array($shipper_fetch_q);
  $url = COURIER_URL . 'API/ProductAndService.php';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  $data_Array = array(
    'auth_key' => $pref_q_res['auth_key'],
  );
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_Array));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
  ]);
  $result = curl_exec($ch);
  curl_close($ch);
  $shipperInfo = json_decode($result, true);
}


?>
<?php include_once("inc/sidebar.php"); ?>



<div class="col-sm-10 right_contents">
  <div class="main_head">
    <h3> Orders</h3>
  </div>
  <div class="cont" style="margin: 15px 0 0;">
    <div class="alert alert-info">
      <strong>Alert!</strong> select multiple orders checkbox for processing
    </div>
    <div id="order_responses"></div>
    <div class="row">
      <div class="col-sm-2">
      <a href="#" class="btn btn-success btn_process">Process Orders</a>
      </div>
      <div class="col-sm-4" style="padding-top: 5px;">
      <select name="select_service" id="select_service" class="form-control">
            <option value="">Select Service Type</option>
            <?php if (isset($shipperInfo['services']) && !empty($shipperInfo['services'])) : ?>
              <?php foreach ($shipperInfo['services'] as $key => $service) : ?>
                <option  value="<?php echo $service['service_type'] ?>"><?php echo $service['service_type'] ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
      </div>
    </div>
    
    <div class="order_list" id="create_order_main"> 
      <?php
      $pref_q = mysqli_query($con, "SELECT * FROM preferences WHERE access_token='" . $access_token . "'");
      $pref_q_res = mysqli_fetch_array($pref_q);

      $check_Q = mysqli_query($con, "SELECT * from shipper_info where client_code = '" . $pref_q_res['client_code'] . "'");
      $num_rows = mysqli_num_rows($check_Q);
      if ($num_rows > 0) {
        include('shopify_orders.php');
      } else {
        echo '<div class="alert alert-danger"><strong>Alert!</strong> Please add your shipping info first! Click this link to add your shipping information <a href="preferences.php">Integrations</a></div>';
      }

      ?>
    </div>
  </div>
</div>
</div>
</div>


<?php include_once("inc/footer.php"); ?>
<script type="text/javascript">
  $('body').on('click', '.main_select', function(e) {
    var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');
    if ($('.main_select').prop("checked") == true) {
      $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked', true);
    } else {
      $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked', false);
    }

    $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
  })

  /////////////////////////////////

  $('body').on('click', '.btn_process', function(e) {
    var mydata = [];
    $('#order_responses').html('');
    e.preventDefault();
    var ajaxresponse = '';
    var service_val = $('#select_service').val();
    if (service_val == "" || !service_val) {
      alert('Please Select Service Type!!');
      return;
    }
    $('.btn_process').attr("disabled", true);
    $('.orders_tbl > tbody > tr').each(function() {
      var current = $(this);
      var checkbox = $(this).find('td:first-child .order_check');

      if (checkbox.prop("checked") == true) {
        var order_id = $(checkbox).val();
        $.ajax({
          type: "POST",
          url: "post_orders.php",
          dataType: "json",
          data: {
            ajax: 1,
            order_id: order_id,
            service_type: service_val
          },
          success: function(response) {
            if (response.status == 1) {
              $('#order_responses').append(response.msg);
              $(current).remove();
              $('.btn_process').attr("disabled", false);
            } else {
              $('#order_responses').append(response.msg);
              $('.btn_process').attr("disabled", false);
            }
          }
        });
      }
    });
  })
</script>