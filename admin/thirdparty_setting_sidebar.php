<div class="col-xs-3 third_party_sidebar">
    <ul class="nav nav-tabs tabs-left">
        <li class="<?php echo isset($current) && $current=='thirdparty_setting.php' ? 'active' : '' ?>"><a href="thirdparty_setting.php" ><?php echo getLange('api'); ?></a></li>
        <li class="<?php echo isset($current) && $current=='thirdparty_general.php' ? 'active' : '' ?>"><a href="thirdparty_general.php" ><?php echo getLange('citymaping'); ?> </a></li>
        <li class="<?php echo isset($current) && $current=='thirdparty_service_mapping.php' ? 'active' : '' ?>"><a href="thirdparty_service_mapping.php" ><?php echo getLange('servicemaping'); ?></a>
        </li>
        <li class="<?php echo isset($current) && $current=='thirdparty_status_mapping.php' ? 'active' : '' ?>"><a href="thirdparty_status_mapping.php" ><?php echo getLange('statusmapping'); ?></a>
            <li class="<?php echo isset($current) && $current=='thirdparty_configration.php' ? 'active' : '' ?>"><a href="thirdparty_configration.php" ><?php echo getLange('Configration'); ?></a>
            </li>
            <!-- <li><a href="#tab4" data-toggle="tab">Tab 4</a></li> -->
        </ul>
    </div>