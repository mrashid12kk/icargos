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
$shop_1 = SHOP_NAME;



$shop_1 = SHOP_NAME;
$token_1 = $access_token;
$query = array(
    "Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
);
// $get_orders_query =mysqli_query($con," SELECT * FROM shopify_orders WHERE is_proceed=0 ORDER BY id DESC");
// $order_data = mysqli_fetch_array($get_orders_query);
?>

<style type="text/css">
.loader_box {
    display: none;
    position: absolute;
    z-index: 99;
    left: 0;
    right: 0;
    top: 0;
    text-align: center;
    background: #ffffff94;
    height: 499px;
    padding: 108px 0 0;
}

.loader_box img {
    width: 442px;
}
</style>

<input type="hidden" value="<?php echo $shop_url ?>" id="access_token">

<div class="row">
    <div class="col-sm-12">
        <a href="#" data-page_info="" class="btn btn-info get_more_order" style="display:none;">Get More order</a>
    </div>
</div>
<div class="row" style="margin-top: 17px;">
    <div class="col-sm-12 sidegapp">
        <div id="table_html">

        </div>
        <div class="loader_box">
            <img src="images/loading.gif" alt="">
        </div>
    </div>
</div>
<!-- <table id="example" class="table table-striped table-bordered orders_tbl" style="width:100%">
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
    </tbody>
</table> -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $('body').on('click', '.get_more_order', function(event) {
        event.preventDefault();
        var href_url = $(this).attr('data-page_info');
        if (href_url) {
            fetchRecordFromShopify(href_url)
        }
    });

    function fetchRecordFromShopify(param1 = null) {
        if ($('body').find('#table_html').length > 0) {
            $('body').find('.loader_box').show();
            $.ajax({
                url: 'get_shopify_orders.php',
                type: 'POST',
                data: {
                    is_request: 1,
                    param1: param1
                },
                dataType: 'json',
                success: function(response) {
                    var result = response;
                    if (result.table_data) {
                        $('body').find('.loader_box').hide();
                        $('body').find('#table_html').html('');
                        $('body').find('#table_html').html(result.table_data);
                        $('#example').DataTable({
                            "destroy": true,
                        });
                    } else {
                        $('body').find('.loader_box').hide();
                    }
                    if (result.headers_link) {
                        $('body').find('.get_more_order').show();
                        $('body').find('.get_more_order').attr('data-page_info', result
                            .headers_link);
                    } else {
                        $('body').find('.get_more_order').attr('data-page_info', '');
                        $('body').find('.get_more_order').hide();
                    }
                },
                error: function() {
                    alert('Processus Echoué!');
                    $('body').find('.loader_box').hide();
                },
                afterSend: function() {
                    var table = $('#example').DataTable();
                    table.ajax.reload(null, false);
                    $('body').find('.loader_box').hide();
                }
            });
        }
    }
    fetchRecordFromShopify();
}, false);
</script>