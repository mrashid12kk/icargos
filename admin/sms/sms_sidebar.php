<?php 
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$current = end(explode('/', $url));

?>
<div class="col-sm-12 left_sidebar_box">
    <div ng-include="public_url+'partials/sidebar.html'" class="ng-scope">
    	<ul class="ng-scope">
    		<li><a href="sent_list.php" class="ng-binding <?php if($current=='sent_list.php'){echo "active";}?>">SMS List</a></li>
   			<li><a href="templates.php" class="ng-binding <?php if($current=='templates.php' || $current=='template_form.php'){echo "active";}?>">SMS Templates</a></li>
   			  <li><a href="api_settings.php" class="ng-binding <?php if($current=='api_settings.php'){echo "active";}?>">SMS API Settings</a></li> 
   			  <li><a href="third_party_api_setting.php" class="ng-binding <?php if($current=='third_party_api_setting.php'){echo "active";}?>">Third Party API</a></li>
    		 <li><a href="mobile_gateway.php" class="ng-binding <?php if($current=='mobile_gateway.php'){echo "active";}?>">Mobile Gatwey</a></li>

			 <li><a href="watilio_sms_api.php" class="ng-binding <?php if($current=='watilio_sms_api.php'){echo "active";}?>">Watilio SMS API</a></li>

			 <li><a href="watilio_whatsapp_api.php" class="ng-binding <?php if($current=='watilio_whatsapp_api.php'){echo "active";}?>">Watilio Whatsapp API</a></li>
		</ul>
</div>
        </div>