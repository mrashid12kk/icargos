<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver'){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],34,'view_only',$comment =null)) {

        header("location:access_denied.php");
    }
	include "includes/header.php";
?>
<body data-ng-app>
 	
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Pending Business Accounts <small>Let's get a quick overview...</small></h1></div>
            
            <?php
	
			include "pages/business/pending_business.php";
			
			?>
					
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>