<?php
$sql = "SELECT balance FROM rider_wallet_ballance WHERE rider_id=".$_SESSION['users_id'];

$response = mysqli_query($con,$sql);
$result = mysqli_fetch_assoc($response);
$rider_ballance = isset($result['balance']) ? $result['balance'] : 0;
?>

<div class="panel panel-default">

    <div class="panel-heading">
        Rider Wallet
    </div>

    <div class="panel-body">
        Rider Wallet : <?php echo $rider_ballance; ?>
    </div>

</div>

