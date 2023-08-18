<?php
session_start();
$access_token = $_SESSION['access_token'];
// Get our helper functions
if (!isset($_SESSION['access_token'])) {
    header("Location: /");
}
// Get our helper functions
require_once("inc/constants.php");
require_once("inc/functions.php");
$requests = $_GET;

// echo "<pre>"; print_r($_GET); exit();

?>
<?php include_once("inc/sidebar.php"); ?>
<?php
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


<div class="col-sm-10 right_contents">
    <div class="main_head">
        <h3>Integrations</h3>
    </div>
    <div class="row">
        <form method="POST" action="action.php">
            <div class="col-md-6" style="padding: 16px 0 0;">
                <div class="main_body" style="    background: #fff;">
                    <div class="form-group">
                        <label>Client Code:</label>
                        <input type="text" name="client_code" class="form-control" required="true" value="<?php echo isset($pref_q_res['client_code']) ? $pref_q_res['client_code'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Authentication Key:</label>
                        <input type="text" name="auth_key" class="form-control" required="true" value="<?php echo isset($pref_q_res['auth_key']) ? $pref_q_res['auth_key'] : ''; ?>">
                    </div>
                    <input type="submit" name="save_preference" class="btn btn-info" value="Update">
                </div>
            </div>
        </form>
    </div>
    <?php if ($pref_rows > 0) :
        // echo "<pre>";
        // print_r($shipperInfo);
        // die;

    ?>
        <div class="row">
            <form method="POST" action="action.php">
                <div class="col-md-12" style="padding: 16px 0 0;">
                    <div class="main_body" style="    background: #fff;">
                        <div class="row">
                            
                            <?php if (isset($_SESSION['succ_message'])) : ?>
                                <div class="alert alert-info" role="alert">
                                    <?php echo $_SESSION['succ_message']; ?>
                                    <?php unset($_SESSION['succ_message']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error_message'])) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION['error_message']; ?>
                                    <?php unset($_SESSION['error_message']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Product Type:</label>
                                    <select name="product" id="" required class="form-control">
                                        <option value="">Select Product</option>
                                        <?php if (isset($shipperInfo['products']) && !empty($shipperInfo['products'])) : ?>
                                            <?php foreach ($shipperInfo['products'] as $key => $product) : ?>
                                                <option <?php echo isset($shipperFetch) && $shipperFetch['product'] == $product['name'] ? "selected" : ""; ?> value="<?php echo $product['name'] ?>"><?php echo $product['name'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Service Type:</label>
                                    <select name="service" id="" required class="form-control">
                                        <option value="">Select Service Type</option>
                                        <?php if (isset($shipperInfo['services']) && !empty($shipperInfo['services'])) : ?>
                                            <?php foreach ($shipperInfo['services'] as $key => $service) : ?>
                                                <option <?php echo isset($shipperFetch) && $shipperFetch['service'] == $service['service_type'] ? "selected" : ""; ?> value="<?php echo $service['service_type'] ?>"><?php echo $service['service_type'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Origin:</label>
                                    <select name="origin" id="" required class="form-control">
                                        <option value="">Select Origin</option>
                                        <?php if (isset($shipperInfo['cities']) && !empty($shipperInfo['cities'])) : ?>
                                            <?php foreach ($shipperInfo['cities'] as $key => $city) : ?>
                                                <option <?php echo isset($shipperFetch) && $shipperFetch['origin'] == $city['city_name'] ? "selected" : ""; ?> value="<?php echo $city['city_name'] ?>"><?php echo $city['city_name'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Add COD amount for following payment method:</label>
                                    <select name="payment_method" id="" class="form-control">
                                        <option value="">Cash on Delivery</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Customer Detail:</label>
                                    <select name="customer_details" id="" class="form-control">
                                        <option value="">Billing Address</option>
                                    </select>
                                </div>
                            </div> -->
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Item Name in remarks:</label>
                                    <input <?php echo isset($shipperFetch['is_item_name']) && $shipperFetch['is_item_name']==1 ? "checked" :""; ?> type="checkbox" name="is_item_name" value="1">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Item SKU in remarks:</label>
                                    <input <?php echo isset($shipperFetch['is_item_sku']) && $shipperFetch['is_item_sku']==1 ? "checked" :""; ?> type="checkbox" name="is_item_sku" value="1">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Weight Calculated default 0.5kg:</label>
                                    <input <?php echo isset($shipperFetch['is_weight_default']) && $shipperFetch['is_weight_default']==1 ? "checked" :""; ?> type="checkbox" name="is_weight_default" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Profile ID:</label>
                                    <input type="text" placeholder="Profile ID" name="profile_id" value="<?php echo isset($shipperFetch['profile_id']) ? $shipperFetch['profile_id'] : ''  ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Special instructions:</label>
                                    <textarea name="special_instructions" id="" rows="2" class="form-control"><?php echo isset($shipperFetch['special_instructions']) ? $shipperFetch['special_instructions'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="client_code" value="<?php echo isset($pref_q_res['client_code']) ? $pref_q_res['client_code'] : ''; ?>">
                        <input type="hidden" name="edit_code" value="<?php echo isset($shipperFetch['client_code']) ? $shipperFetch['client_code'] : ''; ?>">
                        <input type="submit" name="save_shipper_info" class="btn btn-info" value="Update">
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
</div>
</div>
<?php include_once("inc/footer.php"); ?>