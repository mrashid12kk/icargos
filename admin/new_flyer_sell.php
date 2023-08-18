<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
		if(isset($_POST['getflayer']) && !empty($_POST['getflayer'])){
		$active_id = $_POST['productid'];
		$data_query = mysqli_query($con,"SELECT flayer_price FROM flayers WHERE id=".$active_id." ");
		$response_data = mysqli_fetch_array($data_query);
		echo $response_data['flayer_price']; exit();
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
        		
            
            
            <?php
	
			include "pages/flyer/flyersell.php";
			
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
    $(function () {
        $('.datetimepicker4').datetimepicker({
        	format: 'YYYY/MM/DD',
        });
    });
 
    
</script>