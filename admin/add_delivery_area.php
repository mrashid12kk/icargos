<?php

	session_start();

	require 'includes/conn.php';

	if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){

	include "includes/header.php";



	$destcitydata=mysqli_query($con,"Select * from cities order by city_name");

	

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

        	

            <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>

             <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">

            <?php

	

			include "pages/area/addarea.php";

			

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

	$('#addmorearea').click(function(){

		var area_name = $(".city_name").html();

		var areas = '<div class="row" style="margin-bottom:21px !important">'+

						'<div class="col-lg-6 add_delivery_padd">'+

							'<input type="text" class="form-control area_name" name="area[]" placeholder="Enter City/Area Name" required>'+

						'</div>'+

							

						'<div class="col-lg-6 add_delivery_padd">'+

							area_name+

						'</div>'+

					'</div>';

		$("#areas").append(areas);

	});

</script>