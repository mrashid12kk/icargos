
<div class="col-sm-12 outer_shadow">
   <div class="row">
    <?php if (isset($_POST['submit'])) {
    $mail_host = $_POST['mail_host'];
    $mail_username =$_POST['mail_username'];
    $mail_reply_name = $_POST['mail_reply_name'];
    $mail_from_email =$_POST['mail_from_email'];
    $mail_from_name = $_POST['mail_from_name'];
    $mail_password = $_POST['mail_password'];
    $mail_reply_email =$_POST['mail_reply_email'];
    mysqli_query($con,"UPDATE config SET value='".$mail_host."' WHERE `name`='mail_host' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_username."' WHERE `name`='mail_username' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_reply_name."' WHERE `name`='mail_reply_name' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_from_email."' WHERE `name`='mail_from_email' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_from_name."' WHERE `name`='mail_from_name' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_reply_email."' WHERE `name`='mail_reply_email' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_password."' WHERE `name`='mail_password' ");
    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong> SMTP Setting updated successfuly.</div>';
    }

      $mail_host = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_host' "));
      $mail_username = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_username' "));
      $mail_reply_name = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_reply_name' "));
      $mail_from_email = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_from_email' "));
      $mail_from_name = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_from_name' "));
      $mail_password = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_password' "));
      $mail_reply_email = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_reply_email' "));
       ?>
       <style type="text/css">
         .panel-body .form-group{
          margin-bottom: 7px;
         }
       </style>
      <form method="post">
         <div class="panel panel-primary">
        <div class="panel-heading"><?php echo getLange('email').' '.getLange('setting'); ?></div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-4 setting_padd">
                <div class="form-group">
                    <label><?php echo getLange('hostname'); ?></label>
                    <input type="text" name="mail_host" value="<?php echo $mail_host['value']; ?>" class="form-control" required="true">
                  </div>
            </div>
            <div class="col-md-4 setting_padd">
                <div class="form-group">
                    <label><?php echo getLange('username'); ?></label>
                    <input type="text" name="mail_username"  class="form-control" value="<?php echo $mail_username['value']; ?>" required="true" autocomplete="off">
                  </div>
            </div>
            <div class="col-md-4 setting_padd">
                <div class="form-group">
                    <label><?php echo getLange('password'); ?></label>
                    <input type="text" name="mail_password" value="<?php echo $mail_password['value']; ?>" class="form-control" required="true" autocomplete="off">
                  </div>
            </div>
            <div class="col-md-4 setting_padd">
                <div class="form-group">
                    <label><?php echo getLange('mailformname'); ?></label>
                    <input type="text" name="mail_from_name" value="<?php echo $mail_from_name['value']; ?>" class="form-control" required="true">
                  </div>
            </div>
            <div class="col-md-4 setting_padd">
                <div class="form-group">
                    <label><?php echo getLange('mailfromemail'); ?></label>
                    <input type="email" name="mail_from_email" value="<?php echo $mail_from_email['value']; ?>" class="form-control" required="true">
                  </div>
            </div>
            <div class="col-md-4 setting_padd">
                <div class="form-group">
                    <label><?php echo getLange('mailreplyname'); ?></label>
                    <input type="text" name="mail_reply_name" value="<?php echo $mail_reply_name['value']; ?>" class="form-control" required="true">
                  </div>
            </div>
             <div class="col-md-4 setting_padd">
                <div class="form-group ">
                    <label><?php echo getLange('mailreplyemail'); ?></label>
                    <input type="email" name="mail_reply_email" value="<?php echo $mail_reply_email['value']; ?>" class="form-control" required="true">
                  </div>
            </div>
          </div>
          <div class="row" style="float: right;">
        <div class="col-md-12 setting_padd rtl_full">

          <input  type="submit" name="submit" value="<?php echo getLange('save'); ?>" class="btn btn-info" style="width: 100px;">

        </div>

      </div>
        </div>

      </div>
      

      </form>
     
   </div>
</div>