<?php
session_start();
// Get our helper functions
if (!isset($_SESSION['access_token'])) {
    header("Location: /");
}
require_once("inc/constants.php");
require_once("inc/functions.php");
?>
<?php include_once("inc/sidebar.php"); ?>
<?php
$access_token = $_SESSION['access_token'];

$pref_q = mysqli_query($con, "SELECT * FROM preferences WHERE access_token='" . $access_token . "'  ");
if (isset($_SESSION['return_data'])) {
    $_SESSION['return_data'] = array();
    $_SESSION['set_order_id'] = array();
}
if (mysqli_num_rows($pref_q) > 0) {
    $pref_res = mysqli_fetch_array($pref_q);
    $auth_key = $pref_res['auth_key'];
    $url = COURIER_URL . 'API/Dashboard.php?auth_key=' . $auth_key;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result);
    $response = (array)$response;
}

?>
<div class="col-sm-10 right_contents">
    <div class="main_head">
        <h3>Dashboard</h3>
    </div>
    <div class="row">
        <div class="col-sm-4 dash_icons">
            <div class="icon_box">
                <h4>TOTAL ORDERS</h4>
                <svg viewBox="0 0 24 24">
                    <path
                        d="M11.5 14c4.142 0 7.5 1.567 7.5 3.5V20H4v-2.5c0-1.933 3.358-3.5 7.5-3.5zm6.5 3.5c0-1.38-2.91-2.5-6.5-2.5S5 16.12 5 17.5V19h13v-1.5zM11.5 5a3.5 3.5 0 1 1 0 7a3.5 3.5 0 0 1 0-7zm0 1a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5z"
                        fill="#fff" />
                </svg>
                <h5>Total</h5>
                <b><?php echo isset($response['total_orders']) ? $response['total_orders'] : '0'; ?></b>
            </div>
        </div>
        <div class="col-sm-4 dash_icons">
            <div class="icon_box">
                <h4>BOOKED PACKETS</h4>
                <svg viewBox="0 0 24 24">
                    <path
                        d="M11.5 14c4.142 0 7.5 1.567 7.5 3.5V20H4v-2.5c0-1.933 3.358-3.5 7.5-3.5zm6.5 3.5c0-1.38-2.91-2.5-6.5-2.5S5 16.12 5 17.5V19h13v-1.5zM11.5 5a3.5 3.5 0 1 1 0 7a3.5 3.5 0 0 1 0-7zm0 1a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5z"
                        fill="#fff" />
                </svg>
                <h5>Total</h5>
                <b><?php echo isset($response['total_booked_orders']) ? $response['total_booked_orders'] : '0'; ?></b>
            </div>
        </div>

    </div>
</div>
</div>
</div>
<?php include_once("inc/footer.php"); ?>