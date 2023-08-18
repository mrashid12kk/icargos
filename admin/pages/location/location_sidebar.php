<?php
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$current = end(explode('/', $url));

?>
<div class="col-lg-2 left_sidebar">
    <div class="inner_tabs sidebar_listing">
        <ul class="ng-scope">
            <?php if (checkRolePermission($user_role_id, 21, 'view_only', 'Business Module with add enabled')) {  ?>
            <li <?php if ($current == 'citiesdata.php') {
						echo "class='active'";
					} ?>><a href="citiesdata.php"><?php echo getLange('cities'); ?></a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 21, 'view_only', 'Business Module with add enabled')) {  ?>
            <li <?php if ($current == 'areaslist.php') {
						echo "class='active'";
					} ?>><a href="areaslist.php"><?php echo getLange('areas'); ?></a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 48, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'delivery_area_list.php' || $current == 'edit_delivery_area.php') {
						echo "class='active'";
					} ?>><a href="delivery_area_list.php">Delivery Route </a></li>
            <?php } ?>
            <li><a href="country.php" class="ng-binding 
                            <?php if ($current == 'country.php') {
								echo "active";
							} ?>">Country</a></li>
            <li><a href="state.php" class="ng-binding 
                            <?php if ($current == 'state.php') {
								echo "active";
							} ?>">State</a></li>
          <!--   <li><a href="add_city.php" class="ng-binding 
                            <?php if ($current == 'add_city.php' ) {
								echo "active";
							} ?>">City</a></li> -->
            <li><a href="pincode.php" class="ng-binding 
                            <?php if ($current == 'pincode.php') {
								echo "active";
							} ?>">Pincode</a></li>
            <!-- <li><a href="pincode_list.php" class="ng-binding 
                            <?php if ($current == 'pincode_list.php') {
								echo "active";
							} ?>">Pincode List</a></li> -->
          <!--   <li><a href="location_list.php" class="ng-binding 
                            <?php if ($current == 'location_list.php') {
								echo "active";
							} ?>">Location List</a></li> -->

        </ul>


    </div>
</div>