<?php 
session_start();
require '../includes/conn.php';
if(isset($_POST['submit']))
{
    $api_name_web = isset($_POST['api_name_web']) ? $_POST['api_name_web']:'';
    $api_key_web = isset($_POST['api_key_web']) ? $_POST['api_key_web']:'';
    $api_url_web = isset($_POST['api_url_web']) ? $_POST['api_url_web']:'';
    $account_id = isset($_POST['account_id']) ? $_POST['account_id']:'';
    $res=mysqli_query($con,"SELECT * FROM sms_settings WHERE id=4") or die(mysqli_error($con));
    if(mysqli_num_rows($res) >= 1)
    {
        mysqli_query($con,"UPDATE `sms_settings` SET `api_name_web`='".$api_name_web."',`api_key_web`='".$api_key_web."',`api_url_web`='".$api_url_web."',`account_id`='".$account_id."' WHERE id=4") or die(mysqli_error($con));
        $_SESSION['success']='Detail updated successfully';
    }
    else
    {
        $insert_query = "INSERT INTO `sms_settings`(`api_name_web`,`api_key_web`,`api_url_web`,`account_id`) VALUES('".$api_name_web."','".$api_key_web."','".$api_url_web."','".$account_id."') ";
        mysqli_query($con,$insert_query) or die(mysqli_error($con));
        $insert_id=mysqli_insert_id($con);
        if($insert_id > 0)
        {
            $_SESSION['success']='Detail added successfully';
        }
        else
        {
            $_SESSION['errors']='Error try again!';
        }
    }
    header("Location: admin/watilio_whatsapp_api.php");
}
$sql=mysqli_query($con,"SELECT * FROM sms_settings WHERE id=4") or die(mysqli_error($con));
$result = mysqli_fetch_array($sql);
?>
<div class="col-sm-12 outer_shadow">
	<div class="row">
        <div class="col-sm-12">
            <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
             <?php if(isset($_SESSION['errors'])){ ?>
                <div class="alert alert-danger">
                    <?php 
                        echo $_SESSION['errors'];
                        unset($_SESSION['errors']);
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
	<form action="watilio_whatsapp_api.php" method="POST">
		<div class="row">
	        <div class="col-sm-6 form_box">
	            <label for="">API Name</label>
	            <input type="text" name="api_name_web" value="<?php echo isset($result['api_name_web']) ? $result['api_name_web']:''; ?>" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false" style="">
	        </div>
			<div class="col-sm-6 form_box">
				<label for="">API Key</label>
				<input type="text" name="api_key_web" value="<?php echo isset($result['api_key_web']) ? $result['api_key_web']:''; ?>" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false" style="">
			</div> 
	        <div class="col-sm-6 form_box">
	            <label for="">API URL</label>
	            <input type="text" name="api_url_web" value="<?php echo isset($result['api_url_web']) ? $result['api_url_web']:''; ?>" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false" style="">
	        </div>
	        <div class="col-sm-6 form_box">
				<label for="">Account ID</label>
				<input type="text" value="<?php echo isset($result['account_id']) ? $result['account_id']:''; ?>" name="account_id" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
			</div> 
	    </div>
	    <div class="row">
			<div class="col-sm-12 send_btn">
				<!-- <button class="refresh_btn" ng-click="resetForm()" type="button">Refresh</button> -->
				<button class="trash_button ng-binding" name="submit" type="submit">Save</button>
			</div>
		</div>
	</form>
</div>