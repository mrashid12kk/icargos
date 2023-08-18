
<div class="col-sm-12 outer_shadow">
   	<div class="row">
      	<div class="col-sm-8 colums_gapp">
         	<div class="top_heading">
            	<h3 class="">Select Receipient Details</h3>
         	</div>
         	<?php 
           if(isset($_POST['submit'])){
                  $contact_number=mysqli_real_escape_string($con,$_POST['contact_number']);

                  $message=mysqli_real_escape_string($con,$_POST['message_content']);
                  //$customer_id=mysqli_real_escape_string($con,$_POST['customer_id']);

                    $insert=mysqli_query($con,"INSERT INTO `email_detail`(`contact_email`, `message`) VALUES ('$contact_number','$message')") or die(mysqli_error($con));

                  $rowscount=mysqli_affected_rows($con);
                if($insert){
                      echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Sent A EMAIL successfully</div>';
                }
                else{
                      echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You Have NOT Sent A EMAIL unsuccessfully.</div>';
                  }
            }

        $tempalte=mysqli_query($con,"SELECT * FROM email_templates ORDER BY id DESC");

        // $customers = mysqli_query($con,"SELECT * FROM sms_detail inner Join customers on sms_detail.contact_id=customers.id");
         ?>
         <form action="" method="post">
         <div class="row">
            <div class="col-sm-4 form_box">
               <label for="" class="">Contact Email</label>
               <div class="searchBox"><input type="text" class=""  name="contact_number" autocomplete="off" required>
                  <ul>
                  </ul>
                  <div class="overlay" role="button" tabindex="0"></div>
               </div>
            </div>
            <div class="col-sm-4 form_box">
               <label for="" class="">Select Template</label>
               <select  class="js-example-basic-single template_id" name="template_id">
                  <option value="" selected disabled>Choose</option>
                <?php 
                while ($row=mysqli_fetch_assoc($tempalte)) {
                  ?><option value="<?php echo $row['id']; ?>"><?php echo $row['template_name']; ?></option><?php
                }
                 ?>
               </select>
            </div>
           <!--  <div class="col-sm-4 form_box">
               <label for="" class="">Select Customer</label>
               <select  class="js-example-basic-single customer_id" name="customer_id" required>
                  <option value="" selected disabled>Choose</option>
                <?php 
                //while ($row=mysqli_fetch_assoc($customers)) {
                  ?><option value="<?php //echo $row['id']; ?>"><?php //echo $row['bname']; ?></option><?php
                //}
                 ?>
               </select>
            </div> -->
            <div class="col-sm-12 form_box">
               <label for="" class="">Compose Email</label>
               <textarea id="messageArea" type="text" class="message_content" id="messageArea" name="message_content" required></textarea>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-3 form_box">
               <label class="">Letter Count</label>
               <input type="text" autocomplete="off" disabled="disabled"  id="count">
            </div>
            <div class="col-sm-3 form_box">
               <label for="" class="">SMS Count</label>
               <input type="text"value="0" autocomplete="off" disabled="disabled">
            </div>
            <div class="col-sm-6 send_btn">
               <button class="refresh_btn" type="button">Refresh</button>
               <button  class="send_button" name="submit" type="submit">Submit</button>
            </div>
         </div>
         </form>
      </div>
      <div class="col-sm-4 parametres_box">
         <?php include "partials/shortcodes.php";  ?>
      </div>
   </div>
</div>