<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
	include "includes/header.php";
?>
<style>

.center  form{
	margin-bottom: 0 !important;
}
#basic-datatable button {
    width: auto !important;
}
#basic-datatable form {
    display: inline;
}
</style>


<body data-ng-app>
<?php
	include "includes/sidebar.php";
?>
    <!-- Aside Ends-->
<section class="content">
        <?php  include "includes/header2.php";?>
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid"> 
        	<div class="row">
           	<div class="col-md-12">
           		 

           		<?php if (isset($_POST['edit']))
           		{ 
           			$flayer_id = $_POST['flayer_id'];
           			 
					 
					$query1=mysqli_query($con,"Select * from flayers where id= $flayer_id  ");
					
					while($fetch1=mysqli_fetch_array($query1))
					{ 
						?> 
						<form method="POST" action="update_flayer.php">
								
								<h3><?php echo getLange('updateflyer'); ?></h3>
								<div class="panel panel-primary">
									<div class="panel-heading"><?php echo getLange('updateflyer'); ?></div>
									<div class="panel-body" id="same_form_layout">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label><?php echo getLange('flyername'); ?></label>
													<input type="text" name="flayer_name" class="form-control" value="<?php echo $fetch1['flayer_name']; ?>" required="true">
												</div>
											</div> 

											<div class="col-md-6">
												<div class="form-group">
													<label><?php echo getLange('price'); ?></label>
													<input type="number" name="flayer_price" class="form-control" value="<?php echo $fetch1['flayer_price']; ?>" required="true">
												</div>
											</div>
										</div>
										
										
										<input type="hidden" name="flayer_id" value="<?php echo $fetch1['id']; ?>">
										<div class="form-group" >
											<input type="submit" name="update" class="btn btn-info" value="<?php echo getLange('update'); ?>">
										</div>
									</div>
								</div> 
						</form> 
						<?php
					} 
				}else{ ?>
					<form method="POST" action="save_flayer.php">
							
							<h3><?php echo getLange('addnewflyer'); ?></h3>
							<div class="panel panel-primary">
								<div class="panel-heading"> <?php echo getLange('addnewflyer'); ?></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo getLange('flyername'); ?></label>
												<input type="text" name="flayer_name" class="form-control" required="true">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo getLange('price'); ?></label>
												<input type="number" name="flayer_price" class="form-control" required="true">
											</div>
										</div>
									</div>
									
									
									
									<div class="form-group" style="margin:0 0 0  14px;">
										<input type="submit" name="submit" class="btn btn-info" value="<?php echo getLange('save'); ?>">
									</div>
								</div>
							</div> 
					</form> 
				<?php } ?>
			</div>

			<div class="col-md-12">
				<?php 
					if(isset($_POST['delete']))
					{

						$id=mysqli_real_escape_string($con,$_POST['flayer_id']);

						$query2=mysqli_query($con,"delete from flayers where id=$id");

						 
						$rowscount=mysqli_affected_rows($con);

						if($rowscount>0)
						{

							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button>'.getLange('you_have_deleted_flayer_successfully').'</div>';

						}   
					}
				?>
				<div class="panel panel-default">

					<div class="panel-heading"><?php echo getLange('flyerdata'); ?></div>

						<div class="panel-body" id="same_form_layout" style="padding: 11px;">

							<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

								<div class="row">

									<div class="col-sm-12 table-responsive">

										<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

											<thead>

												<tr role="row">

												   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%!important;" ><?php echo getLange('srno'); ?></th>
		  
												   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" ><?php echo getLange('name'); ?></th>

												   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  ><?php echo getLange('price'); ?></th>
												   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"  style="width: 15%;"  ><?php echo getLange('action'); ?></th>
		  
												</tr>

											</thead>

											<tbody>

												<?php 

														$query1=mysqli_query($con,"Select * from flayers  ");
														$counting = 1;
														while($fetch1=mysqli_fetch_array($query1))
														{

															?>

																<tr class="gradeA odd" role="row">

																	<td class="sorting_1"><?php echo $counting; ?></td>
			 
																	<td><?php echo $fetch1['flayer_name']; ?></td>

																	<td class="center"><?php echo $fetch1['flayer_price']; ?></td> 
																	<td class="center">
																		<form method="post">
																			<input type="hidden" name="flayer_id" value="<?php echo $fetch1['id']; ?>">
																			<button type="submit" name="edit" class="btn_stye_custom"> <span class="glyphicon glyphicon-edit"></span>   </button>
																		</form>

																		<form method="post">
																			<input type="hidden" name="flayer_id" value="<?php echo $fetch1['id']; ?>">
																			<button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are you sure you want to delete this flyer??')"><span class="glyphicon glyphicon-trash"></span>  </button>
																		</form>

 																	</td>
			 
																</tr>

															<?php
															$counting++;

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
			</div>
        </div>

        <div class="container"> 
       
	        
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