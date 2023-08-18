 <div class="col-sm-12 outer_shadow">
    <?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
     $date=date('Y-m-d H:i:s');
     if(isset($_POST['add_state'])){
        $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

        $state_name=mysqli_real_escape_string($con,$_POST['state_name']);

        $title=mysqli_real_escape_string($con,$_POST['title']);

        $tax=mysqli_real_escape_string($con,$_POST['tax']);

        $description=mysqli_real_escape_string($con,$_POST['description']);

        $keyword=mysqli_real_escape_string($con,$_POST['keyword']);
        
        $query2=mysqli_query($con,"INSERT into `state`(country_id,state_name,title,description,keyword,created_on,tax)values('$country_id','$state_name','$title','$description','$keyword','$date','$tax')") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($query2){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Add a New Country successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Country unsuccessfully.</div>';
        }
    }
      if(isset($_POST['update_state'])){
           $id=$_GET['edit_id'];
           $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

           $state_name=mysqli_real_escape_string($con,$_POST['state_name']);

           $title=mysqli_real_escape_string($con,$_POST['title']);

           $tax=mysqli_real_escape_string($con,$_POST['tax']);

           $description=mysqli_real_escape_string($con,$_POST['description']);

           $keyword=mysqli_real_escape_string($con,$_POST['keyword']);

              $query2=mysqli_query($con,"UPDATE `state` set country_id='$country_id',state_name= '$state_name',title= '$title',tax= '$tax',description= '$description',keyword= '$keyword' where id=$id") or die(mysqli_error($con));
              $rowscount=mysqli_affected_rows($con);
              if($query2){
                  echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Updated  State Successfully</div>';
              }
              else{
                  echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated State UnSuccessfully.</div>';
              }
          }
      $country=mysqli_query($con,"SELECT * from country order by id desc");
      if (isset($_GET['edit_id']) && $_GET['edit_id']!='') {
         $edit=mysqli_fetch_assoc(mysqli_query($con,"SELECT * from state WHERE id=".$_GET['edit_id']));
      }
              
 ?>
   <div class="row">
      <form method="post" action="" enctype="multipart/form-data">
         <div class="col-sm-8 template_form">
            <div class="top_heading">
               <h3><?php echo isset($edit['id']) ? 'Update' : 'Add'; ?> State</h3>
            </div>
            <div class="row">
               <div class="col-sm-4 form_box">
                  <label>Country</label>
                  <select type="text" class="js-example-basic-single" required name="country_id" autocomplete="off" >
                     <option value="" disabled selected>Select Country</option>
                     <?php 
                     while ($row=mysqli_fetch_array($country)) {
                         $selected=isset($edit['country_id']) && $edit['country_id']==$row['id'] ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['country_name']."</option>";
                     }
                      ?>
                  </select>
               </div>
               <div class="col-sm-4 form_box">
                  <label>State Name</label>
                  <input type="text" class="" name="state_name" autocomplete="off" value="<?php echo isset($edit['state_name']) ? $edit['state_name'] : ''; ?>" required>
               </div>
                <div class="col-sm-4 form_box">
                  <label>Tax/VAT</label>
                  <input type="text" class="" name="tax" autocomplete="off" value="<?php echo isset($edit['tax']) ? $edit['tax'] : ''; ?>" required>
               </div>
               <div class="col-sm-4 form_box">
                  <label>Title</label>
                  <input type="text" class="" name="title" autocomplete="off" value="<?php echo isset($edit['title']) ? $edit['title'] : ''; ?>" required>
               </div>

               <div class="col-sm-12 form_box">
                  <label>Description</label>
                     <textarea name="description" cols="30" rows="10" required><?php echo isset($edit['description']) ? $edit['description'] : ''; ?></textarea>
               </div>

               <div class="col-sm-12 form_box">
                  <label>Keyword</label>
                     <textarea name="keyword" cols="30" rows="10" required><?php echo isset($edit['keyword']) ? $edit['keyword'] : ''; ?></textarea>
               </div>

            </div>
            <div class="row">
                  <div class="col-sm-6 send_btn">
                  <button class="send_button" name="<?php if(isset($edit['id'])){ echo 'update_state';}else{echo 'add_state';} ?>" type="SUBMIT">Submit</button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>