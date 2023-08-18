 <div class="col-sm-12 outer_shadow">
    <?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
     $date=date('Y-m-d H:i:s');
     if(isset($_POST['add_pincode'])){
        $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

        $state_id=mysqli_real_escape_string($con,$_POST['state_id']);

        $city_id=mysqli_real_escape_string($con,$_POST['city_id']);

        $pincode=mysqli_real_escape_string($con,$_POST['pincode']);

        $query2=mysqli_query($con,"INSERT into `pincode`(country_id,state_id,city_id,pincode,created_on)values('$country_id','$state_id','$city_id','$pincode','$date')") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($query2){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Add a New Pincode successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Country unsuccessfully.</div>';
        }
    }

      //     ///Update template
           
           if(isset($_POST['update_pincode'])){
               $id=$_GET['edit_id'];
              $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

              $state_id=mysqli_real_escape_string($con,$_POST['state_id']);

              $city_id=mysqli_real_escape_string($con,$_POST['city_id']);

              $pincode=mysqli_real_escape_string($con,$_POST['pincode']);

              $query2=mysqli_query($con,"UPDATE `pincode` set country_id='$country_id',state_id= '$state_id',city_id= '$city_id',pincode= '$pincode' where id=$id") or die(mysqli_error($con));
              $rowscount=mysqli_affected_rows($con);
              if($query2){
                  echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Updated  Pincode Successfully</div>';
              }
              else{
                  echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated Pincode UnSuccessfully.</div>';
              }
          }

      $country=mysqli_query($con,"SELECT * from country order by id desc");
      $state=mysqli_query($con,"SELECT * from state order by id desc");
      $city=mysqli_query($con,"SELECT * from city order by id desc");
      if (isset($_GET['edit_id']) && $_GET['edit_id']!='') {
         $edit=mysqli_fetch_assoc(mysqli_query($con,"SELECT * from pincode WHERE id=".$_GET['edit_id']));
         
      }
 ?>
   <div class="row">
      <form method="post" action="" enctype="multipart/form-data">
         <div class="col-sm-8 template_form">
            <div class="top_heading">
               <h3><?php echo isset($edit['id']) ? 'Update' : 'Add'; ?> Pincode</h3>
            </div>
            <div class="row">
               <div class="col-sm-4 form_box">
                  <label>Country</label>
                  <select type="text" class="js-example-basic-single country" required name="country_id">
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
                  <label>State</label>
                  <div class="get_state">
                     <select type="text" class="js-example-basic-single state" required name="state_id" autocomplete="off" >
                     <option value="" disabled selected>Select State</option>
                     <?php 
                     while ($row=mysqli_fetch_array($state)) {
                        $selected=isset($edit['state_id']) && $edit['state_id']==$row['id'] ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['state_name']."</option>";
                     }
                      ?>
                     </select> 
                  </div>
               </div>
               <div class="col-sm-4 form_box">
                  <label>City</label>
                  <div class="get_city">
                     <select type="text" class="js-example-basic-single state" required name="city_id" autocomplete="off" >
                        <option value="" disabled selected>Select State</option>
                        <?php 
                        while ($row=mysqli_fetch_array($city)) {
                           $selected=isset($edit['city_id']) && $edit['city_id']==$row['id'] ? 'selected' : '';
                           echo "<option value='".$row['id']."' ".$selected.">".$row['city_name']."</option>";
                        }
                         ?>
                     </select> 
                  </div>
               </div>
               <div class="col-sm-4 form_box">
                  <label>Pincode</label>
                  <input type="text" class="" name="pincode" autocomplete="off" value="<?php echo isset($edit['pincode']) ? $edit['pincode'] : ''; ?>" required>
               </div>
            </div>
            <div class="row">
                  <div class="col-sm-6 send_btn">
                  <button class="send_button" name="<?php if(isset($edit['id'])){ echo 'update_pincode';}else{echo 'add_pincode';} ?>" type="SUBMIT"><?php echo isset($edit['id']) ? 'Update' : 'Submit'; ?></button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
$('body').on('change','.country',function(e){
    e.preventDefault();
    var country_id=$(this).val();
          $.ajax({
          type:'POST',
          data:{country_id:country_id,get_country:1},
          url:'ajax.php',
          success:function(response){
          $('.get_state').html('');
          $('.state').html('');
          $('.get_state').html(response);
          $('.js-example-basic-single').select2();
          }
          });
     })
$('body').on('change','.state',function(e){
    e.preventDefault();
    var state_id=$(this).val();
    var country_id=$('.country').val();
          $.ajax({
          type:'POST',
          data:{state_id:state_id,country_id:country_id,get_city:1},
          url:'ajax.php',
          success:function(response){
          $('.get_city').html('');
          $('.get_city').html(response);
          $('.js-example-basic-single').select2();
          }
          });
     })
}, false);
</script>