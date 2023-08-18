 <div class="col-sm-12 outer_shadow">
    <?php 

     $date=date('Y-m-d H:i:s');
     if(isset($_POST['add_route'])){
        $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

        $state_id=mysqli_real_escape_string($con,$_POST['state_id']);

        $city_id=mysqli_real_escape_string($con,$_POST['city_id']);

        $route_code=mysqli_real_escape_string($con,$_POST['route_code']);

        $route=mysqli_real_escape_string($con,$_POST['route']);
        
        $query2=mysqli_query($con,"INSERT into `route`(country_id,city_id,state_id,route_code,route,created_on)values('$country_id','$city_id','$state_id','$route_code','$route','$date')") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($query2){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Add a New Route successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Route unsuccessfully.</div>';
        }
    }
    if(isset($_POST['update_route'])){
        $id=$_GET['edit_id'];
        $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

        $state_id=mysqli_real_escape_string($con,$_POST['state_id']);

        $city_id=mysqli_real_escape_string($con,$_POST['city_id']);

        $route_code=mysqli_real_escape_string($con,$_POST['route_code']);

        $route=mysqli_real_escape_string($con,$_POST['route']);

        $query2=mysqli_query($con,"UPDATE `route` set country_id='$country_id',state_id= '$state_id',city_id= '$city_id',route_code= '$route_code',route= '$route' where id=$id") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($query2){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Updated  Route Successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Route Pincode UnSuccessfully.</div>';
        }
    }
      $country=mysqli_query($con,"SELECT * from country order by id desc");
      $state=mysqli_query($con,"SELECT * from state order by id desc");
      $city=mysqli_query($con,"SELECT * from city order by id desc");
      if (isset($_GET['edit_id']) && $_GET['edit_id']!='') {
         $edit=mysqli_fetch_assoc(mysqli_query($con,"SELECT * from route WHERE id=".$_GET['edit_id']));
      }
 ?>
   <div class="row">
      <form method="post" action="" enctype="multipart/form-data">
         <div class="col-sm-8 template_form">
            <div class="top_heading">
               <h3>Add City</h3>
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
                        <option value="" disabled selected>Select City</option>
                         <?php 
                     while ($row=mysqli_fetch_array($city)) {
                         $selected=isset($edit['city_id']) && $edit['city_id']==$row['id'] ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['city_name']."</option>";
                     }
                      ?>
                     </select> 
                  </div>
               </div>
               </div>
               <br>
               <div class="row">
               <div class="col-sm-6 form_box">
                  <label>Route Code</label>
                  <input type="text" class="" name="route_code" autocomplete="off" value="<?php echo isset($edit['route_code']) ? $edit['route_code'] : ''; ?>" required>
               </div>
               <div class="col-sm-6 form_box">
                  <label>Route</label>
                  <input type="text" class="" name="route" autocomplete="off" value="<?php echo isset($edit['route']) ? $edit['route'] : ''; ?>" required>
               </div>
            </div>
            <div class="row">
                  <div class="col-sm-6 send_btn">
                  <button class="send_button" name="<?php if(isset($edit['id'])){ echo 'update_route';}else{echo 'add_route';} ?>" type="SUBMIT">Submit</button>
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