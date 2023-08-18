<?php 
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$current = end(explode('/', $url));

?>
<div class="col-sm-12 left_sidebar_box">
    <div ng-include="public_url+'partials/sidebar.html'" class="ng-scope">
    	<ul class="ng-scope">
    		<li><a href="sent_list_email.php" class="ng-binding <?php if($current=='sent_list_email.php' || $current=='single_email.php'){echo "active";}?>">Email List</a></li>
   			<li><a href="templates_email.php" class="ng-binding <?php if($current=='templates_email.php' || $current=='template_form_email.php'){echo "active";}?>">Email Templates</a></li>
     		<li><a href="smtp_setting.php" class="ng-binding <?php if($current=='smtp_setting.php'){echo "active";}?>">SMTP Settings</a></li>
    		<!-- <li><a href="api_settings_email.php" class="ng-binding <?php if($current=='api_settings_email.php'){echo "active";}?>">Email API Settings</a></li> -->
    		<!-- <li><a href="third_party_api_setting_email.php" class="ng-binding <?php if($current=='third_party_api_setting_email.php'){echo "active";}?>">Third Party API</a></li>
    		<li><a href="mobile_gateway_email.php" class="ng-binding <?php if($current=='mobile_gateway_email.php'){echo "active";}?>">Mobile Gatwey</a></li> -->
		</ul>
	</div>
</div>