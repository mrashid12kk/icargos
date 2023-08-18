<?php echo $msg; ?>
	
<div class="panel panel-default"> 
		<div class="panel-heading"><?php echo getLange('editprofile'); ?></div> 
		<div class="panel-body">  
			<form role="form" action="" method="POST" data-toggle="validator"  enctype="multipart/form-data" class=""> 
			    <div class="box-body">
			    <div class="row">
			    	<div class="col-sm-4">
			    		<div class="form-group">
		                  <label for="exampleInputEmail1"><?php echo getLange('name'); ?></label>
		                  <input type="text" class="form-control"  name="Name" value="<?php echo $fetch['Name']; ?>" required>
						  <div class="help-block with-errors"></div>
						</div>
			    	</div>
			    	<div class="col-sm-4">
			    		<div class="form-group">
		                  <label for="exampleInputPassword1"><?php echo getLange('username'); ?></label>
		                  <input type="text" class="form-control "  name="user_name" value="<?php echo $fetch['user_name']; ?>" disabled>
		                	<div class="help-block with-errors "></div>
						</div>
			    	</div>
			    	<div class="col-sm-4">
			    		<div class="form-group">
		                  <label for="exampleInputPassword1"><?php echo getLange('emailaddress'); ?></label>
		                  <input type="email" class="form-control "  name="email" value="<?php echo $fetch['email']; ?>" >
							<div class="help-block with-errors "></div>
						</div>
			    	</div>
			    	<div class="col-sm-4">
			    		<div class="form-group" <?php if($fetch['type']=='driver') echo 'hidden';?>>
		                  <label for="exampleInputPassword1"><?php echo getLange('phoneno'); ?> .</label>
		                  <input type="text" class="form-control"   name="phone" value="<?php echo $fetch['phone']; ?>"  >
							<div class="help-block with-errors"></div>  
					   </div>
			    	</div>
			    	<!-- <div class="col-sm-4">
			    		<div class="form-group">
		                  <label for="exampleInputPassword1">Staff ID #</label>
		                  <input type="text" class="form-control"  name="staff_id" value="<?php echo $fetch['staff_id']; ?>" >
							<div class="help-block with-errors"></div> 
					   </div>
			    	</div> -->
			    	<div class="col-sm-4">
			    		<div class="form-group">
		                  <label for="exampleInputPassword1"><?php echo getLange('carplateno'); ?>.</label>
		                  <input type="text" class="form-control"  name="plate_no" value="<?php echo $fetch['plate_no']; ?>" >
							<div class="help-block with-errors"></div>
					   </div>
			    	</div>
			    	<div class="col-sm-12">
			    		<div class="form-group">
		                  <label><?php echo getLange('changeimage'); ?> </label>
							<input type="file" id="logo" name="fileToUpload" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])"> 
							<div id="msg"></div>
							 <img src="<?php echo $fetch['image']; ?>" id="blah1" class="img-rounded" width="200" border="2">
					   </div>
			    	</div>
			    </div>
			   	<div class="box-footer"> 
                	<input type="submit" name="editp" value="<?php echo getLange('submit'); ?>" class="btn btn-primary "> 
              	</div> 
			</form>
	
	</div>
</div>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
     $(document).ready( function (){
  $("#logo").change(function () {
    var validExtensions = ["jpg","jpeg","gif","png","JPG","JPEG","GIF","PNG"]
    var file = $(this).val().split('.').pop();
    if (validExtensions.indexOf(file) == -1) {
        var msg=("Only formats are allowed : "+validExtensions.join(', '));
        $('#msg').html('');
        $('#msg').html(msg);
        $(this).val("");
    }
    else{
       $('#msg').html('');
    }
});
});
  
}, false);
</script>