<?php

	session_start();

	require 'includes/conn.php';

	if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],46,'view_only',$comment =null)) {

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

        	

            <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getlange('letsgetquick'); ?></small></h1></div>

            

            <?php

	

			include "pages/orders/order_data.php";

			

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

<script type="text/javascript">

	

	$(document).ready(function(){

		$(".pick_rider_check").change(function(){

			var status_id = $(this).attr('data-id');

			var value = null;

			if ($(this).prop('checked')) {

            	value=1;

	        }

	        else {

	            value=0;

        	}



        	$.ajax({

		        url:'order_status_ajax.php',

		        method:'post',

		        data:{pick_up_rider_id:value,status_id:status_id},  // pass data 

		        dataType:'json',

		        success:function(data){

		            console.log(data);

		        }

		    });

		});

		$(".delivery_rider_check").change(function(){

			var status_id = $(this).attr('data-id');

			var value = null;

			if ($(this).prop('checked')) {

            	value=1;

	        }

	        else {

	            value=0;

        	}



        	$.ajax({

		        url:'order_status_ajax.php',

		        method:'post',

		        data:{delivery_rider_id:value,status_id:status_id},  // pass data 

		        dataType:'json',

		        success:function(data){

		            console.log(data);

		        }

		    });

		})

	})

</script>