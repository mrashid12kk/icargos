<?php
session_start();
$access_token = $_SESSION['access_token'];
// $access_token = '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';
$shop_url = $_SESSION['shop_url'];
// echo $shop_url;die();
// Get our helper functions
include_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
$order_id = '';
if (isset($_GET['order_id'])) {
  $order_id = $_GET['order_id'];
}
$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);
$array = '';
// $shop_1 = "primary-skincare";
$shop_1 = SHOP_NAME;

// $max_id_query =mysqli_query($con," SELECT MAX(order_id) AS order_id FROM shopify_orders WHERE is_proceed=0");
// if(mysqli_num_rows($max_id_query) > 0)
// {
//     $max_id = mysqli_fetch_array($max_id_query);
//     $max_id = isset($max_id['order_id']) ? $max_id['order_id']:'';
//     $collects = shopify_call_get_orders($access_token,$shop_1 ,"/admin/api/".API_DATE."/orders.json", array('since_id'=>$max_id,'limit'=>250), 'GET');
// }
// else
// {
// }

if (isset($_POST['is_request'])) {

  $collects = shopify_call_get_orders($access_token, $shop_1, "/admin/api/" . API_DATE . "/orders.json", array('limit' => 1000, 'fulfillment_status' => 'unfulfilled'), 'GET');
  $order_data['orders'] = orderList($collects);

  function orderList($collects_data = array(), $check = 0)
  {
    global $con;
    $ordersList = json_decode($collects_data['response'], true);
    $headers_parameters = $collects_data['headers'];
    if (isset($ordersList['orders']) && !empty($ordersList['orders'])) {
      foreach ($ordersList['orders'] as $order) {
        $order_id = $order['id'];
        $max_id_query = mysqli_query($con, " SELECT * FROM shopify_orders WHERE order_id='" . $order_id . "' ");

        $name = $order['name'];
        $customer = $order['customer'];
        $customer_name  = isset($customer['first_name']) ? $customer['first_name'] : '';
        if (isset($customer['last_name'])) {
          $customer_name .= isset($customer['last_name']) ? ' ' . $customer['last_name'] : '';
        }
        $total_price = $order['total_price'];
        $financial_status = $order['financial_status'];
        $last_order_id = isset($customer['last_order_id']) ? ' ' . $customer['last_order_id'] : '';
        $date = isset($order['created_at']) ? date('Y-m-d', strtotime($order['created_at'])) : '';
        $line_items = $order['line_items'];
        $title = '';
        if (isset($line_items) && !empty($line_items)) {
          foreach ($line_items as $key => $item) {
            if ($key == 0) {
              $title .= isset($item['title'])  ? $item['title'] : '';
            } else {
              $title .= isset($item['title'])  ? ' ' . $item['title'] : '';
            }
          }
        }
        if (mysqli_num_rows($max_id_query) > 0) {
          $query = "UPDATE `shopify_orders` SET order_id= '" . $order_id . "',name= '" . $name . "',date= '" . $date . "',customer= '" . $customer_name . "',title= '" . $title . "',total= '" . $total_price . "',shopify_status= '" . $financial_status . "',last_order_id= '" . $last_order_id . "' WHERE order_id= '" . $order_id . "'";
        } else {
          $query = "INSERT INTO `shopify_orders`(`order_id`,`name`,`date`,`customer`,`title`,`total`,`shopify_status`,`last_order_id`) VALUES('" . $order_id . "','" . $name . "','" . $date . "','" . $customer_name . "','" . $title . "','" . $total_price . "','" . $financial_status . "','" . $last_order_id . "') ";
        }
        mysqli_query($con, $query);
      }
    }
    if (isset($headers_parameters['link']) && $headers_parameters['link']) {
      recursiveFunctionGetOrder($headers_parameters['link']);
    }
  }
}
function recursiveFunctionGetOrder($url = null)
{
  if ($url != null) {
    $shop_1 = SHOP_NAME;
    $access_token = $_SESSION['access_token'];
    $explode_url = explode('?', $url);
    $explode_again_url = explode('&', $explode_url[1]);
    $explode_again1_url = explode('=', $explode_again_url[1]);
    $page_info = $explode_again1_url[1];
    $collects = shopify_call_get_orders($access_token, $shop_1, "/admin/api/" . API_DATE . "/orders.json", array('limit' => 250, 'page_info' => $page_info), 'GET');
    $ordersList = json_decode($collects_data['response'], true);
    orderList($collects, 1);
  }
}


$shop_1 = SHOP_NAME;
$token_1 = $access_token;
$query = array(
  "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
);

$get_orders_query = mysqli_query($con, " SELECT * FROM shopify_orders WHERE is_proceed=0 ORDER BY id DESC");
// $order_data = mysqli_fetch_array($get_orders_query);
?>
<input type="hidden" value="<?php echo $shop_url ?>" id="access_token">
<table id="example" class="table table-striped table-bordered orders_tbl" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" name="" class="main_select"></th>
            <th>Order</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Items</th>
            <th>Shopify status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($get_orders_query) && !empty($get_orders_query)) {
      while ($row = mysqli_fetch_array($get_orders_query)) {
        $order_id = $row['order_id'];
    ?>
        <tr>
            <td><input type="checkbox" name="" class="order_check" value="<?php echo $order_id; ?>"></td>
            <td><?php echo isset($row['name']) ? $row['name'] : ''; ?></td>
            <td><?php echo isset($row['date']) ? date('d M Y', strtotime($row['date'])) : ''; ?></td>
            <td><?php echo isset($row['customer']) ? $row['customer'] : ''; ?></td>
            <td><?php echo isset($row['total']) ? $row['total'] : ''; ?></td>
            <td>
                <?php
            echo isset($row['title']) ? $row['title'] : '';
            ?>
            </td>
            <td><span
                    class="status_code"><?php echo isset($row['shopify_status']) ? $row['shopify_status'] : ''; ?></span>
            </td>
        </tr>
        <?php }
    } ?>
    </tbody>
</table>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $('body').on('click', '.get_more_order', function() {
        var page_info = $(this).attr('data-page_info');

    });
    alert('here');

    function fetchRecordFromShopify() {
        alert('here');
        if ($('body').find('#example').length > 0) {
            alert('here');
            $.ajax({
                url: 'shopify_orders.php',
                type: 'POST',
                data: {
                    is_request: 1
                },
                success: function(response) {
                    var result = jQuery.parseJSON(response);
                    console.log(result);
                    if (result.orders) {
                        var table_tr = '';
                        for (var i in result.orders) {
                            table_tr += '<td></td>';
                            table_tr += '<td></td>';
                            table_tr += '<td></td>';
                            table_tr += '<td></td>';
                            table_tr += '<td></td>';
                            table_tr += '<td></td>';
                            table_tr += '<td></td>';
                        }
                        $('body').find('#example').DataTable();
                        $('body').find('#example').find('tbody').append(table_tr);
                    }
                }
            });
        }
    }
    fetchRecordFromShopify();
}, false);
</script>