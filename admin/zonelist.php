<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],18,'view_only',$comment =null)){
        header("location:access_denied.php");
    }
	include "includes/header.php";
	
?>
<body data-ng-app>
 	<style type="text/css">
 		.label {
    display: inline;
    padding: .2em .6em .3em;
    font-size: 100%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
    float: left;
    margin: 2px;
    width: 100%;
}
.city_dropdown {
    max-height: 186px;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: auto;
}
 	</style>
    
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
            <div class="row">
            		<?php
					 	require_once "setup-sidebar.php";
					?>
					<div class="col-sm-10 table-responsive" id="setting_box">
            <?php
	$msg="";
	if(isset($_POST['addcities'])){
		for($i=0;$i<count($_POST['city']);$i++){
		
		$query1=mysqli_query($con,"INSERT INTO `cities`(`city_name`) VALUES ('".$_POST['city'][$i]."')") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new City successfully</div>';
		
			}
		else{
			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new City unsuccessfully.</div>';
		}
		}
	}
echo $msg;
?>	



					
			<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('zonelist'); ?> 
		<a href="addzone.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('addzone'); ?> </a>
	</div>
		<div class="panel-body" id="same_form_layout" style="padding: 11px;">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				
					
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
									<th style="width: 2%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('srno'); ?></th>

								   <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('zone'); ?></th>
									<th style="width: 2%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Product</th>
								   
								    <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('servicetype'); ?> </th>
								   
								   <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('0.5kg'); ?></th>
								   <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('upto1kg'); ?></th>
								    <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('upto3kg'); ?> </th>
								     <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('upto10kg'); ?> </th>
								   
								   <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('additionalkg'); ?> </th>
								    <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('additionalo.5kg'); ?></th>

								  
								  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
								
							
								$query1=mysqli_query($con,"Select zone.* from zone order by id desc");
								$sr=1;
								while($fetch1=mysqli_fetch_array($query1)){
									$zone_id = $fetch1['id'];
									$service_type = $fetch1['service_type'];
									$product_id = $fetch1['product_id'];
									$zone_cities_query = mysqli_query($con,"SELECT * FROM zone_cities WHERE zone='".$zone_id."' ");
									$service_query = mysqli_query($con,"SELECT * FROM services WHERE id='".$service_type."' ");
									$services_fetch = mysqli_fetch_array($service_query);
									$service_type = $services_fetch['service_type'];
									$product_query = mysqli_query($con,"SELECT * FROM products WHERE id=".$product_id);
									$product_fetch = mysqli_fetch_array($product_query);
									$product_name = $product_fetch['name'];

								?>
								<tr class="gradeA odd" role="row">
									<td><?php echo $sr; ?></td>
									<td class="sorting_1"><?php echo $fetch1['zone']; ?></td>
									<td class="sorting_1" ><?php echo $product_name; ?></td>
									<td class="sorting_1" ><?php echo $service_type; ?></td>
									<td class="sorting_1"><?php echo $fetch1['point_5_kg']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['upto_1_kg']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['upto_3_kg']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['upto_10_kg']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['other_kg']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['additional_point_5_kg']; ?></td>
									<td class="center">
										<a href="editzone.php?zone_id=<?php echo $fetch1['id']; ?>"   >
											  <span class="glyphicon glyphicon-edit"></span> 
											</a>
											<a href="deletezone.php?zone_id=<?php echo $fetch1['id']; ?>" onclick="return confirm('Are you sure you want to delete this zone?');"  >
											  <span class="glyphicon glyphicon-trash"></span> 
											</a>
									</td>
								</tr>
								<?php
									$sr++;
								
							  }
								
								?>
							</tbody>
						</table>
			
			</div>
		</div>
	</div>
</div>		
</div>		
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>
