<div class="col-lg-2 left_sidebar">
    <div class="inner_tabs">
        <ul>
            <li class="has-submenu <?php if ($current == 'addzone.php' || $current == 'zonelist.php' || $current == 'addservice.php' || $current == 'servicelist.php' || $current == 'editzone.php' || $current == 'order_status.php' || $current == 'edit_order_status.php'  || $current == 'add_delivery_zone.php' || $current == 'delivery_area_list.php' || $current == 'language_keyword.php' || $current == 'transport_companies.php' || $current == 'edit_transport_company.php') {
                                        echo "active";
                                    } ?>"><a class="main_head_bold" href="#"><i class="lnr lnr-cog"></i> <span
                        class="nav-label"><?php echo getLange('setup'); ?></span>
                    <!-- <i class="fa fa-angle-down"></i> --></a>
            </li>
            <?php if (checkRolePermission($user_role_id, 22, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'setting.php') {
                        echo "class='active'";
                    } ?>><a href="setting.php"><?php echo getLange('generalsetting'); ?> </a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 42, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'branding.php') {
                        echo "class='active'";
                    } ?>><a href="branding.php"><?php echo getLange('branding'); ?></a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 46, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'order_status.php') {
                        echo "class='active'";
                    } ?>><a href="order_status.php"><?php echo getLange('status'); ?></a></li>


            <!--   <li <?php if ($current == 'voucher_type.php') {
                                echo "class='active'";
                            } ?>><a href="voucher_type.php"><?php //echo getLange('status'); 
                                                            ?>VoucherType
                </a></li> -->
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 47, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'transport_companies.php') {
                        echo "class='active'";
                    } ?>><a href="transport_companies.php"><?php echo getLange('transportcompany'); ?></a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 41, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'language_keyword.php') {
                        echo "class='active'";
                    } ?>><a href="language_keyword.php"><?php echo getLange('languagekeyword'); ?></a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 60, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'charges_setting.php') {
                        echo "class='active'";
                    } ?>><a href="charges_setting.php"><?php echo getLange('chargessetting'); ?></a></li>
                    <li <?php if ($current == 'vendors.php') {
                        echo "class='active'";
                    } ?>><a href="vendors.php"><?php echo getLange('Vendor'); ?></a></li>
            <?php } ?>
            <!-- <?php if (checkRolePermission($user_role_id, 61, 'view_only', 'Setting Module with add enabled')) {  ?>
            <li <?php if ($current == 'cn_allocation.php') {
                            echo "class='active'";
                        } ?>><a href="cn_allocation.php">CN Allocation</a></li>
            <?php } ?> -->
            <li style="margin-bottom: 2px;" id="divider_box"><a href="#" class="main_head_bold"><i
                        class="lnr lnr-cog"></i> <?php echo getLange('zonesetup'); ?></a></li>
            <?php if (checkRolePermission($user_role_id, 17, 'view_only', 'Service Module with add enabled')) {  ?>
            <li <?php if ($current == 'servicelist.php') {
                        echo "class='active'";
                    } ?>><a href="servicelist.php"><?php echo getLange('Services'); ?></a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 62, 'view_only', 'Service Module with add enabled')) {  ?>
            <li <?php if ($current == 'productlist.php') {
                        echo "class='active'";
                    } ?>><a href="productlist.php">Product</a></li>
            <?php } ?>
            <!-- <?php if (checkRolePermission($user_role_id, 18, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'zonelist.php' || $current == 'editzone.php') {
                            echo "class='active'";
                        } ?>><a href="zonelist.php"><?php echo getLange('pricezone'); ?> </a></li>
            <?php } ?> -->

            <?php if (checkRolePermission($user_role_id, 48, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'zone_type.php') {
                        echo "class='active'";
                    } ?>><a href="zone_type.php">Zone </a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 63, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'tariff-list.php' || $current == 'add-tarif-setup.php') {
                        echo "class='active'";
                    } ?>><a href="tariff-list.php">Tariff Setup </a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 64, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'pay_mode.php') {
                        echo "class='active'";
                    } ?>><a href="pay_mode.php">Pay Mode </a></li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 65, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'settlement_period.php') {
                        echo "class='active'";
                    } ?>><a href="settlement_period.php">Settlement Period </a></li>
            <?php } ?>
                <?php if (checkRolePermission($user_role_id, 65, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'custom_tariff_pricing.php') {
                        echo "class='active'";
                    } ?>><a href="custom_tariff_pricing.php">Custom Tariff</a></li>
            <?php } ?>
                 <!-- <li><a href="custom_tariff_pricing.php">Custom Tariff</a></li> -->
            <!--   <?php if (checkRolePermission($user_role_id, 66, 'view_only', 'Zone Module with add enabled')) {  ?>
            <li <?php if ($current == 'account_type.php') {
                            echo "class='active'";
                        } ?>><a href="account_type.php">Account Type </a></li>
            <?php } ?> -->
            <li style="margin-bottom: 2px;" id="divider_box"><a href="#" class="main_head_bold"><i
                        class="fa fa-dollar"></i> <?php echo getLange('charges'); ?></a></li>
            <?php if (checkRolePermission($user_role_id, 37, 'view_only', 'Dashboard Module is enabled')) {  ?>
            <li <?php if ($current == 'chargesLists.php') {
                        echo "class='active'";
                    } ?>><a href="chargesLists.php"><?php echo getLange('charges'); ?></a></li>
            <?php } ?>

            <?php if (checkRolePermission($user_role_id, 49, 'view_only', 'Dashboard Module is enabled')) {  ?>
            <li <?php if ($current == 'insuranceLists.php') {
                        echo "class='active'";
                    } ?>><a href="insuranceLists.php"><?php echo getLange('insurance'); ?></a></li>
            <?php } ?>

            <li style="margin-bottom: 2px;" id="divider_box"><a href="#" class="main_head_bold"><i
                        class="fa fa-user-circle"></i> <?php echo getLange('user') ?></a></li>

            <?php if (checkRolePermission($user_role_id, 23, 'view_only', 'Business Module with add enabled')) {  ?>
            <li <?php if ($current == 'user_role.php') {
                        echo "class='active'";
                    } ?>><a href="user_role.php"><?php echo getLange('userroles'); ?> </a></li>
            <?php } ?>

            <?php if (checkRolePermission($user_role_id, 24, 'view_only', 'Business Module with add enabled')) {  ?>
            <li <?php if ($current == 'user_permission.php') {
                        echo "class='active'";
                    } ?>><a href="user_permission.php?role_id=1"><?php echo getLange('userpermission'); ?> </a></li>
            <?php } ?>


            <li style="margin-bottom: 2px;" id="divider_box"><a href="#" class="main_head_bold"><i class="fa fa-money" aria-hidden="true"></i> <?php echo getLange('accounting') ?></a></li>

            <?php if (checkRolePermission($user_role_id, 23, 'view_only', 'Business Module with add enabled')) {  ?>
            <li <?php if ($current == 'accounting.php') {
                        echo "class='active'";
                    } ?>><a href="accounting.php"><?php echo getLange('accountingsetting'); ?> </a></li>
            <?php } ?>

           
            <!--    <?php if (checkRolePermission($user_role_id, 24, 'view_only', 'Business Module with add enabled')) {  ?>
              <li <?php if ($current == 'sent_list.php') {
                            echo "class='active'";
                        } ?> ><a href="sent_list.php?role_id=1"><?php echo getLange(''); ?> Communication</a></li>
            <?php } ?> -->


        </ul>
    </div>
</div>