
  <?php
if(isset($_POST['submit']))

{
    $footer = $_POST['footer'];

    $comapanyname = $_POST['companyname'];

    $contactno = $_POST['contactno'];

    $email = $_POST['email'];

    $address = $_POST['address'];
    $user_footer = $_POST['user_footer'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $invoicefooter = $_POST['invoicefooter'];
    $timezone = $_POST['timezone'];
    $mail_host = $_POST['mail_host'];
    $mail_username =$_POST['mail_username'];
    $mail_reply_name = $_POST['mail_reply_name'];
    $mail_from_email =$_POST['mail_from_email'];
    $mail_from_name = $_POST['mail_from_name'];
    $mail_password = $_POST['mail_password'];
    $mail_reply_email =$_POST['mail_reply_email'];
    $webtitle =$_POST['webtitle'];
    $currency =$_POST['currency'];
    $website =$_POST['website'];
    $first_new_footer =$_POST['first_new_footer'];
    $second_new_footer =$_POST['second_new_footer'];
    mysqli_query($con,"UPDATE config SET value='".$website."' WHERE `name`='website' ");
    mysqli_query($con,"UPDATE config SET value='".$footer."' WHERE `name`='footer' ");
    mysqli_query($con,"UPDATE config SET value='".$first_new_footer."' WHERE `name`='first_new_footer' ");
    mysqli_query($con,"UPDATE config SET value='".$second_new_footer."' WHERE `name`='second_new_footer' ");

    mysqli_query($con,"UPDATE config SET value='".$comapanyname."' WHERE `name`='companyname' ");

    mysqli_query($con,"UPDATE config SET value='".$contactno."' WHERE `name`='contactno' ");

    mysqli_query($con,"UPDATE config SET value='".$email."' WHERE `name`='email' ");

    mysqli_query($con,"UPDATE config SET value='".$address."' WHERE `name`='address' ");
    mysqli_query($con,"UPDATE config SET value='".$currency."' WHERE `name`='currency' ");
    mysqli_query($con,"UPDATE config SET value='".$user_footer."' WHERE `name`='user_footer' ");



    mysqli_query($con,"UPDATE config SET value='".$invoicefooter."' WHERE `name`='invoicefooter' ");
    mysqli_query($con,"UPDATE config SET value='".$timezone."' WHERE `name`='timezone' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_host."' WHERE `name`='mail_host' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_username."' WHERE `name`='mail_username' ");

    mysqli_query($con,"UPDATE config SET value='".$mail_reply_name."' WHERE `name`='mail_reply_name' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_from_email."' WHERE `name`='mail_from_email' ");


    mysqli_query($con,"UPDATE config SET value='".$mail_from_name."' WHERE `name`='mail_from_name' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_reply_email."' WHERE `name`='mail_reply_email' ");
    mysqli_query($con,"UPDATE config SET value='".$mail_password."' WHERE `name`='mail_password' ");
    mysqli_query($con,"UPDATE config SET value='".$webtitle."' WHERE `name`='webtitle' ");
    mysqli_query($con, "UPDATE config SET value='" . $country . "' WHERE `name`='country' ");
    mysqli_query($con,"UPDATE config SET value='" . $city . "' WHERE `name`='city' ");

     if (isset($_FILES["webfavicon"]["name"]) && !empty($_FILES["webfavicon"]["name"]))
    {
        $target_dir = "../assets/img/logo/";
        $target_file = $target_dir .uniqid(). basename($_FILES["webfavicon"]["name"]);
        $extension = pathinfo($target_file,PATHINFO_EXTENSION);
        if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
            if (move_uploaded_file($_FILES["webfavicon"]["tmp_name"], $target_file))
            {
                mysqli_query($con,"UPDATE config SET value='".$target_file."' WHERE `name`='webfavicon' ");

            }
        }
    }
     if (isset($_FILES["footer_logo"]["name"]) && !empty($_FILES["footer_logo"]["name"]))
    {
        $target_dir = "img/";
        $target_file = $target_dir .uniqid(). basename($_FILES["footer_logo"]["name"]);
        $extension = pathinfo($target_file,PATHINFO_EXTENSION);
        if($extension=='jpg'||$extension=='png'||$extension=='jpeg' || $extension=='JPG'||$extension=='PNG'||$extension=='JPEG') {
            if (move_uploaded_file($_FILES["footer_logo"]["tmp_name"], $target_file))
            {
                mysqli_query($con,"UPDATE config SET value='".$target_file."' WHERE `name`='footer_logo' ");

            }
        }
    }
     if (isset($_FILES["adminfavicon"]["name"]) && !empty($_FILES["adminfavicon"]["name"]))
    {
        $target_dir = "img/logo/";

        $target_file = $target_dir .uniqid(). basename($_FILES["adminfavicon"]["name"]);

        $extension = pathinfo($target_file,PATHINFO_EXTENSION);
        if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
            if (move_uploaded_file($_FILES["adminfavicon"]["tmp_name"], $target_file))
            {
                mysqli_query($con,"UPDATE config SET value='".$target_file."' WHERE `name`='adminfavicon' ");
            }
        }
    }

    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong> Branding updated successfuly.</div>';

}

$return_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='footer'  ");

$total_return = mysqli_fetch_array($return_query);



$cash_handling_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname'  ");

$total_cash_handling = mysqli_fetch_array($cash_handling_query);



$conatactno2 = mysqli_query($con,"SELECT value FROM config WHERE `name`='contactno'");

$conatactno = mysqli_fetch_array($conatactno2);



$invoicefooter2 = mysqli_query($con,"SELECT value FROM config WHERE `name`='invoicefooter'");

$invoicefooter = mysqli_fetch_array($invoicefooter2);



$email1 = mysqli_query($con,"SELECT value FROM config WHERE `name`='email'");

$email = mysqli_fetch_array($email1);



$address1 = mysqli_query($con,"SELECT value FROM config WHERE `name`='address'");

$address = mysqli_fetch_array($address1);



$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='website' ");

$total_gst = mysqli_fetch_array($gst_query);





$webfavicon = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='webfavicon' "));

$timezone = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='timezone' "));
$mail_host = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_host' "));
$mail_username = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_username' "));
$mail_reply_name = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_reply_name' "));
$mail_from_email = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_from_email' "));
$mail_from_name = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_from_name' "));
$mail_password = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_password' "));
$mail_reply_email = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='mail_reply_email' "));
$webtitle = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='webtitle' "));
$currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
$newinvoicefirstfooter = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='first_new_footer' "));
$newinvoicesecondfooter = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='second_new_footer' "));
$city = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='city' "));
$country = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='country' "));





$auto_assign_rider = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='rider_vendor_auto_assign' "));

 ?>
<form method="POST" action=""  enctype="multipart/form-data">

  <div class="panel panel-primary">

    <div class="panel-heading"><?php echo getLange('branding'); ?></div>

    <div class="panel-body">

     <div class="row">

 <div class="col-md-6 setting_padd" >

  <div class="form-group">

    <label><?php echo getLange('company').' '.getLange('name'); ?></label>

    <input type="text" name="companyname" value="<?php echo $total_cash_handling['value']; ?>" class="form-control" required="true">

  </div>



</div>
<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange('contactphone'); ?></label>

    <input type="text" name="contactno" value="<?php echo $conatactno['value']; ?>" class="form-control" required="true">

  </div>



</div>
      

     
      </div>

<div class="row">


<div class="col-md-6 setting_padd" >

  <div class="form-group">

    <label><?php echo getLange('email'); ?></label>

    <input type="text" name="email" value="<?php echo $email['value']; ?>" class="form-control" required="true">

  </div>



</div>
<div class="col-md-6 setting_padd" >

  <div class="form-group">

    <label><?php echo getLange('address'); ?></label>

    <input type="text" name="address" value="<?php echo $address['value']; ?>" class="form-control" required="true">

  </div>



</div>
</div>
<div class="row">
                      <div class="col-md-6 setting_padd">
                        <div class="form-group">
                            <label>Country</label>
                              <select class="form-control country js-example-basic-single" name="country" id="country">
                        
                        <?php
                        $country_query = mysqli_query($con, 'SELECT * FROM country ORDER BY country_name ASC');
                        while ($row = mysqli_fetch_array($country_query)) { ?>
                            <option <?php echo isset($country['value']) && $country['value']==$row['country_name'] ? 'selected' :'' ?> value="<?php echo $row['country_name']; ?>">
                                <?php echo getKeyWord($row['country_name']); ?></option>
                        <?php } ?>
                    </select>

                        </div>
                    </div>
                     <div class="col-sm-6 setting_padd">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city"
                                            name="city" id="city">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <option>Select City</option>
                                        </select>
                                    </div>
                                </div>

</div>


<div class="row">


<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange('currency'); ?></label>

    <input type="text" name="currency" value="<?php echo $currency['value']; ?>" class="form-control" required="true">

  </div>



</div>
<div class="col-md-6 setting_padd">
  <div class="form-group">
    <label><?php echo getLange('timezone'); ?></label>
    <select type="text" name="timezone" value="<?php echo $timezone['value']; ?>" class="form-control js-example-basic-single" required="true">
       <option value="Etc/GMT+12" <?php echo isset($timezone["value"])&&$timezone['value']=="Etc/GMT+12"?"selected":""; ?>>International Date Line West</option>
       <option value="Asia/Baku" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Baku"?"selected":""; ?>> Baku</option>
       <option value="Asia/Yerevan" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Yerevan"?"selected":""; ?>> Yerevan</option>
       <option value="Asia/Kabul" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Kabul"?"selected":""; ?>> Kabul</option>
       <option value="Asia/Yekaterinburg" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Yekaterinburg"?"selected":""; ?>> Yekaterinburg</option>
       <option value="Asia/Karachi"  <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Karachi"?"selected":""; ?>> Islamabad, Karachi, pakistan</option>
       <option value="Asia/Calcutta"  <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Calcutta"?"selected":""; ?>> Sri Jayawardenapura</option>
       <option value="Asia/Katmandu" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Katmandu"?"selected":""; ?>> Kathmandu</option>
       <option value="Asia/Almaty" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Almaty"?"selected":""; ?>> Almaty, Novosibirsk</option>
       <option value="Asia/Dhaka" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Dhaka"?"selected":""; ?>> Astana, Dhaka</option>
       <option value="Asia/Rangoon" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Rangoon"?"selected":""; ?>> Yangon (Rangoon)</option>
       <option value="Asia/Bangkok" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Bangkok"?"selected":""; ?>> Bangkok, Hanoi, Jakarta</option>
       <option value="Asia/Krasnoyarsk" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Krasnoyarsk"?"selected":""; ?>> Krasnoyarsk</option>
       <option value="Asia/Hong_Kong" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Hong_Kong"?"selected":""; ?>>Beijing, Chongqing, Hong Kong, Urumqi</option>
       <option value="Asia/Kuala_Lumpur" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Kuala_Lumpur"?"selected":""; ?>> Kuala Lumpur, Singapore</option>
       <option value="Asia/Taipei" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Taipei"?"selected":""; ?>> Taipei</option>
       <option value="Asia/Tokyo" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Tokyo"?"selected":""; ?>> Osaka, Sapporo, Tokyo</option>
       <option value="Asia/Seoul" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Seoul"?"selected":""; ?>> Seoul</option>
       <option value="Asia/Yakutsk" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Yakutsk"?"selected":""; ?>> Yakutsk</option>
       <option value="Australia/Adelaide" <?php echo isset($timezone["value"])&&$timezone['value']=="Australia/Adelaide"?"selected":""; ?>> Adelaide</option>
       <option value="Australia/Darwin" <?php echo isset($timezone["value"])&&$timezone['value']=="Australia/Darwin"?"selected":""; ?>>Darwin</option>
       <option value="Australia/Brisbane" <?php echo isset($timezone["value"])&&$timezone['value']=="Australia/Brisbane"?"selected":""; ?>>Brisbane</option>
       <option value="Australia/Canberra" <?php echo isset($timezone["value"])&&$timezone['value']=="Australia/Canberra"?"selected":""; ?>> Canberra, Melbourne, Sydney</option>
       <option value="Australia/Hobart" <?php echo isset($timezone["value"])&&$timezone['value']=="Australia/Hobart"?"selected":""; ?>>Hobart</option>
       <option value="Pacific/Guam" <?php echo isset($timezone["value"])&&$timezone['value']=="Pacific/Guam"?"selected":""; ?>> Guam, Port Moresby</option>
       <option value="Asia/Vladivostok" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Vladivostok"?"selected":""; ?>> Vladivostok</option>
       <option value="Asia/Magadan" <?php echo isset($timezone["value"])&&$timezone['value']=="Asia/Magadan"?"selected":""; ?>>Magadan, Solomon Is., New Caledonia</option>
       <option value="Pacific/Auckland" <?php echo isset($timezone["value"])&&$timezone['value']=="Pacific/AucklandEtc/GMT+12"?"selected":""; ?>>Auckland, Wellington</option>
       <option value="Pacific/Fiji" <?php echo isset($timezone["value"])&&$timezone['value']=="Pacific/Fiji"?"selected":""; ?>>Fiji, Kamchatka, Marshall Is.</option>
       <option value="Pacific/Tongatapu" <?php echo isset($timezone["value"])&&$timezone['value']=="Pacific/Tongatapu"?"selected":""; ?>>Pacific/Tongatapu</option>
    </select>
  </div>



</div>
</div>
<div class="row">
  <div class="col-md-6 setting_padd">

        <div class="form-group">

        <label><?php echo getLange('footer') ?></label>

        <input type="text" name="footer" value="<?php echo $total_return['value']; ?>" class="form-control" required="true">

      </div>

      </div>
      <div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange('invoice').' '.getLange('footer'); ?></label>

    <textarea type="text" name="invoicefooter" value="" class="form-control" required="true" style="height: 81px;"><?php echo $invoicefooter['value']; ?></textarea>

  </div>



</div>

</div>
<div class="row">





</div>
</div>

<div class="row">

<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange('newinvoicefirstfooter'); ?></label>

    <textarea type="text" name="first_new_footer" value="" class="form-control" required="true" style="height: 81px;"><?php echo $newinvoicefirstfooter['value']; ?></textarea>

  </div>



</div>
<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange('newinvoicesecondfooter'); ?></label>

    <textarea type="text" name="second_new_footer" value="" class="form-control" required="true" style="height: 81px;"><?php echo $newinvoicesecondfooter['value']; ?></textarea>

  </div>



</div>

</div>

<div class="row">

<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange('webtitle'); ?></label>

    <textarea type="text" name="webtitle"  class="form-control" required="true" style="height: 81px;"><?php echo $webtitle['value']; ?></textarea>

  </div>



</div>
<div class="col-md-6 setting_padd">
    <div class="form-group">
        <label><?php echo getlange('webfavicon'); ?></label>
         <br>
        <img src="../assets/<?php echo $webfavicon['value'] ?>" alt="Logo Image">
        <br>
        <input type="file" name="webfavicon" accept="image/jpg, image/jpeg, image/png" >
    </div>
    <br>
</div>
<div class="row">
<div class="col-md-6 setting_padd">
    <div class="form-group">
        <label><?php echo getlange(''); ?>Footer Logo</label>
         <br>
        <img src="<?php echo BASE_URL.'admin/'.getConfig('footer_logo'); ?>" alt="Logo Image">
        <br>
        <input type="file" name="footer_logo" accept="image/jpg, image/jpeg, image/png" >
    </div>
    <br>
</div>
<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label>Website</label>

    <input type="text" name="website" value="<?php echo getConfig('website'); ?>" class="form-control" required="true">
</div>

</div>
</div>
<div class="row">

<div class="col-md-6 setting_padd">

  <div class="form-group">

    <label><?php echo getLange(''); ?>User Footer</label>

    <textarea type="text" name="user_footer"  class="form-control" required="true" style="height: 81px;"><?php echo getConfig('user_footer'); ?></textarea>

  </div>



</div>

</div>
<!-- <div class="panel panel-primary" style="display: none;">
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
                    <label><?php echo getLange('hostname'); ?></label>
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
                <div class="form-group">
                    <label><?php echo getLange('mailreplyemail'); ?></label>
                    <input type="email" name="mail_reply_email" value="<?php echo $mail_reply_email['value']; ?>" class="form-control" required="true">
                  </div>
            </div>
          </div>
        </div>
      </div> -->

      <div class="row">

        <div class="col-md-4 setting_padd rtl_full">

          <input type="submit" name="submit" value="<?php echo getLange('save'); ?>" class="btn btn-info">

        </div>

      </div>

    </div>

  </div>

</form>
