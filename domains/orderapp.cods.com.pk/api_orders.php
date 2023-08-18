<?php
session_start();
if (!isset($_SESSION['access_token'])) {
    header("Location: /");
}
$access_token = $_SESSION['access_token'];
// Get our helper functions
include_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);
// $token = "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";
$shop = SHOP_NAME;
$array = '';
// $collects = shopify_call($token, $shop, "/admin/api/2020-07/orders.json", $array, 'GET');
// $collects = json_decode($collects['response'], JSON_PRETTY_PRINT);
// echo "<pre>"; print_r($collects); exit();

$get_pref = mysqli_query($con, "SELECT * FROM  preferences WHERE `access_token`='" . $access_token . "' ");

if (mysqli_num_rows($get_pref) > 0) {
    $pref_res = mysqli_fetch_array($get_pref);
    $auth_key = $pref_res['auth_key'];
    $url = COURIER_URL . 'API/GetOrderList.php?auth_key=' . $auth_key;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result);
}
?>
<?php include_once("inc/sidebar.php"); ?>



<div class="col-sm-10 right_contents">
    <div class="main_head">
        <h3>Booked Packets</h3>
    </div>
    <div class="cont">
        <?php if (isset($_SESSION['response_message']) && !empty($_SESSION['response_message'])) { ?>
        <div class="alert alert-info">
            <?php echo isset($_SESSION['response_message']) ? $_SESSION['response_message'] : ''; ?>
        </div>
        <?php
            unset($_SESSION['response_message']);
        }
        ?>
        <div class="order_list api-order  table-responsive " id="create_order_main">
            <?php
            if (!isset($_GET['print'])) { ?>
            <div class="row">
                <a target="_blank" style="margin-left: 0;margin-bottom: 20px;" href=""
                    class=" btn btn-info booking_sheet booking_btn">Bulk Invoices</a>
            </div>
            <?php } ?>
            <table id="example" class="table table-striped table-bordered orders_tbl" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="" class="main_select"></th>
                        <th>Date</th>
                        <th>Tracking No</th>
                        <th>Order ID</th>
                        <th>Shipment Info</th>
                        <th>Consignee Info</th>
                        <th>Qty</th>
                        <th>Origin </th>
                        <th>Destination</th>
                        <th>Description</th>
                        <th>Weight</th>
                        <th>COD Amount</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <!-- <th>Receive Date</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($response) && !empty($response)) {
                        foreach ($response as $record) {
                    ?>
                    <tr>
                        <td><input type="checkbox" name="" class="order_check"
                                data-id="<?php echo $record->tracking_no; ?>"></td>
                        <td><?php echo isset($record->order_date) ? date('d M Y', strtotime($record->order_date)) : ''; ?>
                        </td>
                        <td><?php echo isset($record->tracking_no) ? $record->tracking_no : ''; ?></td>
                        <td><?php echo isset($record->order_id) ? $record->order_id : ''; ?></td>
                        <td>
                            <b>Name:</b><?php echo isset($record->sender_name) ? $record->sender_name : ''; ?><br>
                            <b>Company:</b><?php echo isset($record->sender_company) ? $record->sender_company : ''; ?><br>
                            <b>Phone:</b><?php echo isset($record->sender_phone) ? $record->sender_phone : ''; ?><br>

                        </td>
                        <td>
                            <b>Name:</b><?php echo isset($record->receiver_name) ? $record->receiver_name : ''; ?><br>
                            <b>Phone:</b><?php echo isset($record->receiver_phone) ? $record->receiver_phone : ''; ?><br>
                        </td>
                        <td><?php echo isset($record->quantity) ? $record->quantity : ''; ?></td>
                        <td><?php echo isset($record->origin) ? $record->origin : ''; ?></td>
                        <td><?php echo isset($record->destination) ? $record->destination : ''; ?></td>
                        <td><?php echo isset($record->product_descriptiption) ? $record->product_descriptiption : ''; ?>
                        </td>
                        <td><?php echo isset($record->weight) ? $record->weight : ''; ?></td>
                        <td>Rs <?php echo isset($record->collection_amount) ? $record->collection_amount : ''; ?></td>
                        <td style="text-transform: capitalize;">
                            <span
                                class="status_code"><?php echo isset($record->status) ? $record->status : ''; ?></span>
                        </td>
                        <td><span
                                class="status_code"><?php echo isset($record->payment_status) ? $record->payment_status : ''; ?></span>
                        </td>
                        <!-- <td> -->
                            <?php
                                    //if ($record->receive_date != 0) {
                                        //echo isset($record->receive_date) ? date('d M Y', strtotime($record->receive_date)) : '';
                                    //}
                                    ?>

                        <!-- </td> -->
                        <td class="view_invoice">

                            <a target="_blank" style="margin-top:2px;"
                                href="<?php echo COURIER_URL ?>track-details.php?track_code=<?php echo $record->tracking_no; ?>"
                                class="btn btn-info btn-sm track_order">Track Order</a>
                            <?php if ($record->status == 'booked') { ?>
                            <form method="POST" action="action.php">
                                <input type="hidden" name="track_no" value="<?php echo $record->tracking_no; ?>">
                                <input type="hidden" name="order_id" value="<?php echo $record->order_id; ?>">
                                <input type="submit" name="cancel_order" class="btn btn-danger btn-sm track_order"
                                    value="Cancel Order" style="margin-top: 5px;">
                            </form>
                            <?php } ?>

                        </td>
                    </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<form method="POST" id="bulk_submit" action="bulk_invoice.php" target="_blank">
    <input type="hidden" name="print_data" id="print_data">
    <input type="hidden" name="save_print">
</form>
</div>

<?php include_once("inc/footer.php"); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('body').on('click', '.main_select', function(e) {
        var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');
        if ($('.main_select').prop("checked") == true) {
            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked', true);
        } else {
            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked', false);
        }

        $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
    })
    var mydata = [];
    $('body').on('click', '.booking_sheet', function(e) {
        e.preventDefault();
        $('.orders_tbl > tbody  > tr').each(function() {
            var checkbox = $(this).find('td:first-child .order_check');
            if (checkbox.prop("checked") == true) {
                var order_id = $(checkbox).data('id');
                mydata.push(order_id);
            }
        });
        var order_data = JSON.stringify(mydata);
        $('#print_data').val(order_data);
        $('#bulk_submit').submit();
        $("#bulk_submit").attr('target', '_blank');
    })
})
</script>