
<div class="col-sm-12 outer_shadow">
   <div class="row">
      <div class="col-sm-8 colums_gapp">
         <div class="top_heading">
            <h3 class="">Select Receipient Details</h3>
         </div>
         <?php 
           if(isset($_POST['submit'])){
                  $contact_number=mysqli_real_escape_string($con,$_POST['contact_number']);

                  $template_id=mysqli_real_escape_string($con,$_POST['template_id']);

                  $message=mysqli_real_escape_string($con,$_POST['message_content']);
                  //$customer_id=mysqli_real_escape_string($con,$_POST['customer_id']);

                    $insert=mysqli_query($con,"INSERT INTO `sms_detail`(`contact_number`, `message`,`template_id`) VALUES ('$contact_number','$message','$template_id')") or die(mysqli_error($con));

                  $rowscount=mysqli_affected_rows($con);
                  if($insert){
                      echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a New Template successfully</div>';
                  }
                  else{
                      echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Template unsuccessfully.</div>';
                  }
              }

            if(isset($_POST['updatetemplate'])){
             $id=mysqli_real_escape_string($con,$_POST['edit_id']);
               $contact_number=mysqli_real_escape_string($con,$_POST['contact_number']);

                   $voucher_type=mysqli_real_escape_string($con,$_POST['voucher_type']);

                   $template_name=mysqli_real_escape_string($con,$_POST['template_name']);

                   $template_code=mysqli_real_escape_string($con,$_POST['template_code']);
               $query2=mysqli_query($con,"UPDATE `sms_templates` set contact_number='$contact_number',  voucher_type= '$voucher_type',  template_name= '$template_name',  template_code= '$template_code' where id=$id") or die(mysqli_error($con));
               $rowscount=mysqli_affected_rows($con);
               if($query2){
                   echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a New Template successfully</div>';
               }
               else{
                   echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a New Template unsuccessfully.</div>';
               }
           }
         if (isset($_GET['id']) && !empty($_GET['id'])) {
          $query2=mysqli_query($con,"SELECT * FROM sms_templates WHERE id=".$_GET['id']);
                $edit=mysqli_fetch_assoc($query2);
        }
        $tempalte=mysqli_query($con,"SELECT * FROM sms_templates ORDER BY id DESC");

        // $customers = mysqli_query($con,"SELECT * FROM sms_detail inner Join customers on sms_detail.contact_id=customers.id");
         ?>
         <form action="" method="post">
         <div class="row">
            <div class="col-sm-4 form_box">
               <label for="" class="">Contact Number</label>
               <div class="searchBox"><input type="text" class=""  name="contact_number" autocomplete="off" required>
                  <ul>
                  </ul>
                  <div class="overlay" role="button" tabindex="0"></div>
               </div>
            </div>
            <div class="col-sm-4 form_box">
               <label for="" class="">Select Template</label>
               <select  class="js-example-basic-single template_id" name="template_id" required>
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
               <label for="" class="">Compose SMS</label>
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