<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id'])){
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
        	
            <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div>
            
            <?php
            $customer_id=$_GET['customer_id'];
		if(isset($_POST['define_charges'])){
			$customer_id=$customer_id;
			$cn_charges=$_POST['cn_charges'];
			$patri_expensses=$_POST['patri_expensses'];
			$challan_fee = $_POST['challan_fee'];
			$insurance_fee=$_POST['insurance_fee'];
			$get_query = mysqli_query($con,"SELECT * FROM define_charges WHERE customer_id='".$customer_id."' ");
			$rowcount = mysqli_num_rows($get_query);
			if($rowcount == 0){
			mysqli_query($con,"INSERT INTO `define_charges`(`customer_id`,`cn_charges`,`patri_expensses`,`challan_fee`,`insurance_fee`)values('".$customer_id."','".$cn_charges."','".$patri_expensses."','".$challan_fee."','".$insurance_fee."')");
			}else{
				//update
				mysqli_query($con,"UPDATE `define_charges` SET `insurance_fee`='".$insurance_fee."',`cn_charges`='".$cn_charges."', `patri_expensses`='".$patri_expensses."', `challan_fee`='".$challan_fee."' WHERE  customer_id='".$customer_id."' ");
			}
			$rowscount=mysqli_affected_rows($con);
			if($rowscount>0){
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Added Define Charges successfully</div>';
				
			}
			else{
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a Define Charges .</div>';
				
			}
		}
?>
<div class="panel panel-default">
	<div class="panel-heading">Define Charges</div>
	<div class="panel-body" style="padding: 14px;">
	
		<form role="form" class=""  action="" method="post">
		<div class="row">
			 <div class="col-sm-6">
		 	<div class="form-group">
				<label for="exampleInputEmail1">CN Charges</label>
				<input type="number" class="form-control " name="cn_charges"   required >
				<div class="help-block with-errors "></div>
			</div>
		 </div>
		 <div class="col-sm-6">
		 	<div class="form-group">
				<label for="exampleInputEmail1">Patri Expenses</label>
				<input type="number" class="form-control " name="patri_expensses"   required >
				<div class="help-block with-errors "></div>
			</div>
		 </div>
		</div>
		
			 <div class="row">
			 	<div class="col-sm-6">
			 		<div class="form-group">
						<label for="exampleInputEmail1">Challan fee</label>
						<input type="number" class="form-control " name="challan_fee"   required >
						<div class="help-block with-errors "></div>
					</div>
			 	</div>
			 	<div class="col-sm-6">
			 		<div class="form-group">
						<label for="exampleInputEmail1">Insurance Fee (%)</label>
						<input type="number" class="form-control " name="insurance_fee"    required>
						<div class="help-block with-errors "></div>
					</div>
			 	</div>
			 </div> 
			  
			  
			
			
			<input type="hidden" name='customer_id' value="<?php echo $customer_id;?>">
				 <div class="row">
				 	<div class="col-sm-6">
				 		<button type="submit" name="define_charges" class="btn btn-purple" >Submit</button>
				 	</div>
				 </div>
		</div>
		</form>
	
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