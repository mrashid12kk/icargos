<?php

session_start();

if(isset($_SESSION['customers'])) {
	header('Location: profile.php');
	exit();
}

$banks_list = array(

);
include_once "includes/conn.php";
$cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");

$page_title = 'Please Register Here';
include "includes/header.php";
function addQuote($value){
	return(!is_string($value)==true) ? $value : "'".$value."'";
}
 $companyname = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname' "));

 // $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
 // $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
 ?>


			<div>
				  <div class="register_title">

				  <?php

           if(isset($_POST['fname']))
                  {
                    if(trim($_POST['password']) == trim($_POST['repassword'])){
                            $send = true;
                        }else{
                            $send = false;
                        }

            if($send) {
                // $_POST['emirates_id']==$target_file;
                
                // $_POST['address']=implode(',,',$_POST['address']);

                 $password= md5($_POST['password']);
                 $_POST['password']=$password;
                 $account_type_q=mysqli_fetch_assoc(mysqli_query($con,"SELECT id FROM account_types WHERE account_type='Walk in Customer'"));
                 $_POST['customer_type']=isset($account_type_q['id']) ? $account_type_q['id'] : '';
                $data = $_POST;
                $data['cnic_copy'] = $target_file;
                if(isset($data['submit']))
                    unset($data['submit']);
                    unset($data['repassword']);
                $email = $data['email'];
                $index = 0;
                foreach ($data as $key => &$value) {
                    if(trim($value) == '') {
                        array_splice($data, $index, 1);
                        $index--;
                    }
                    $index++;
                }
                foreach ($data as $k => &$value) {
                    $value =addQuote($value);
                }

                $keys = implode(", ", array_keys($data));
                $values = implode(",",$data);
                $sql = "INSERT INTO customers ($keys) VALUES($values)";
                $query=mysqli_query($con,$sql) or die(mysqli_error($con));
                $customer_id = mysqli_insert_id($con);
                $code = 1000 + $customer_id;
                $query5=mysqli_query($con, "UPDATE customers SET client_code = '".$code."'  WHERE id = ".$customer_id);
                $rowscount=mysqli_affected_rows($con);
                  // Reference key code
                if (isset($_POST['merchant_key']) && !empty($_POST['merchant_key'])) {
                    $check_query = mysqli_query($con,"SELECT merchant_key, is_merchant, id from customers where merchant_key='".$_POST['merchant_key']."'");
                    $merKeyRes = mysqli_fetch_assoc($check_query);
                    $merchantKey = isset($merKeyRes['merchant_key']) ? $merKeyRes['merchant_key'] : '';
                    $is_merchant = isset($merKeyRes['is_merchant']) ? $merKeyRes['is_merchant'] : '';
                    $mer_id = isset($merKeyRes['id']) ? $merKeyRes['id'] : ''; 
                    if (isset($is_merchant) && $is_merchant == 1) {
                        mysqli_query($con, "UPDATE customers set reference_with=".$mer_id." where id=".$customer_id);
                    }
                }
             
            }
            if($send == true && $rowscount>0 ){
                $code = 1000 + $customer_id;
                if(isset($data['email']) ) {
                    $data['email'] = $email;
                    $customer_name = $_POST['fname'];
                    $message['subject'] = 'Account Registration';
                    $message['body'] = "<b>Hello ".$customer_name." </b>";
                    $message['body'] .= '<p>Thank you for registering with '.$companyname['value'].'</p>';
                    $message['body'] .= '<p>Your account has been created but must be activated before you can start booking your shipments. Our admin will review your information and approve within 24 hours.</p>';
                    require_once 'admin/includes/functions.php';
                    sendEmail($data, $message);
                    // Admin
                    $path = BASE_URL.'admin/customer_detail.php?customer_id='.$customer_id;
                    $message['body'] = '<p>New User Account has been created</p>';
                    $message['body'] .= '<p>Click below link to view customer.</p>';
                    $message['body'] .= "<a href='$path'>$path</a>";
                    sendEmailToAdmin($data, $message);
                }
                 $id=mysqli_insert_id($con);
                  $query=mysqli_query($con,"Select * from customers where id=$id") or die(mysqli_error($con));
                $fetch=mysqli_fetch_array($query);



            echo '<div style="width: 663px; margin: 0px auto;" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Your registration is successful. Please wait for account approval email by '.$companyname['value'].'</div>';

            }else{
            echo '<div style="width: 663px; margin: 0px auto;" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> your registration is unsuccessful, please try again.</div>';
        }

    }

          $query = mysqli_query($con, "SELECT COUNT(*) as total FROM customers");
          $code = 1;
          $row = mysqli_fetch_object($query);
          if(isset($row->total)) {
              $code = (int)$row->total;
              $code++;
          }
          $code = $code+1000;
				  ?>
				   </div>


			</div>
		</div>
		<style>
			.form-control, .input-group-addon, .bootstrap-select .btn {
			    background-color: #ffffff;
			    border-color: #ccc;
			    border-radius: 3px;
			    box-shadow: none;
			    color: #000;
			    font-size: 14px;
			    height: 34px;
			    padding: 0 20px;
			    font-weight: 300;
			}
		label {
		    font-weight: normal;
		    margin: 0;
		    color: #000;
		    margin-bottom: 7px;
		    font-weight: bold;
		}
		.modal-header {
		    padding: 6px 11px;
		    border-bottom: 1px solid #e5e5e5;
		    margin-top: 0;
		}
		.profile-page-title, .col-sm-4 {
		    padding: 0 15px;
		}
		.modal-title {
			text-align: center;
		}
		.register_page{
			    max-width: 660px;
		}
        .wizard {
        max-width: 600px;
    }
		.form-group input, input.emaill {
		    background-color: #f8fbff7d !important;
		}
		label {
		    margin: 6px 0;
		    font-weight: 500;
		    font-size: 14px;
		}
		.term_label{
			color: #0a68bb;
		}

.wizard .form-control {
    border-color: #bcbbbb;
    height: 42px;
}
.email_errorr ul li{
        position: absolute;
}
.select2-container .select2-selection--single {
    height: 42px;
    padding: 6px 12px 0;
    border: 1px solid #bcbbbb;
}
.list-unstyled {
    margin: 0;
}
.help-block {
    margin-top: 0;
    margin-bottom: 0;
}
.list-inline   li button{
        background: #c91717 !important;
    opacity: 1;
    border: none;
    padding: 10px 54px;
    font-size: 18px;
    margin: 15px 0 0;
}
@media (max-width: 1250px){
    .container{
        width: 100%;
    }


}

@media (max-width: 1024px){
    .container{
        width: 100%;
    }


}

@media (max-width: 767px){
    .container{
        width: auto;
    }
	.register_title {
	    margin-top: 0;
	}
}

</style>
		<div class="modal-body">

		<!-- steps -->

		<section>
        <div class="wizard">
        	<h3 class="modal-title modal-title-center hide-register-title" >Quick Registeration</h4>
            

           <form autocomplete="off" class="validateform" id="contactForm" action="" method="post" class="City:" role="form"   enctype="multipart/form-data">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">

                        <div class="row" style="margin-left: 0px; margin-right: 0px;">
                            <div class="form-group col-sm-6" id="bname" >
                      <label for="usr"><span style="color: red;">*</span> <?php echo getLange('companynamebrandname'); ?></label>
                      <input  type="text" class="form-control bname_check bname" placeholder="<?php echo getLange('companynamebrandname'); ?>" name="bname"  required>
                    </div>
					<div class="form-group col-sm-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('personofconatct'); ?></label>
					  <input type="text" class="form-control bname_check fname"  placeholder="<?php echo getLange('personofconatct'); ?>" name="fname" required>
					</div>


					<div class="form-group col-sm-6"  >
					  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('phoneno'); ?></label>
					  <input  type="text" class="form-control bname_check mobile_no" placeholder="<?php echo getLange('phoneno'); ?>" name="mobile_no" required>
					</div>




					<div class="form-group col-sm-6">
					  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('email'); ?>:</label>
					  <input  type="email" class="form-control bname_check emailleee email"  name="email" required>
                      <input type="hidden" value="" class="msg_email">
						<div class="help-block with-errors email_errorr"></div>
					</div>
                    <div class="form-group col-sm-6" id="bname" >
                      <label for="usr"><span style="color: red;">*</span> <?php echo getLange('select').' '.getLange('city'); ?></label>
                      <select  class="form-control bname_check2 js-example-basic-single city" name="city"  required>
                        <option value="" disabled selected>Select</option>
                        <?php while ($city=mysqli_fetch_array($cities)) {
                            ?>
                            <option value="<?php echo $city['city_name']; ?>"><?php echo $city['city_name']; ?></option>
                            <?php
                        } ?>
                    </select>
                    </div>
					<div class="form-group col-sm-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('companypickupaddress'); ?></label>
					  <textarea   name="address" placeholder=" <?php echo getLange('companypickupaddress'); ?>" style="height: 42px;" type="text" class="form-control address bname_check" required></textarea>
					</div>
                    <div class="row">
                    <div class="form-group col-sm-6" id="bname" >
                      <label for="usr"><span style="color: red;">*</span><?php echo getLange('password'); ?></label>
                      <input  type="password" class="form-control password"  name="password" required>
                    </div>
                    <div class="form-group col-sm-6" id="bname" >
                      <label for="usr"><span style="color: red;">*</span><?php echo getLange('comfirmpassword'); ?></label>
                      <input  type="password" class="form-control repassword" name="repassword" required>
                      <div class="msg_pass"></div>
                    </div>
                    </div>
					</div>
                         <ul class="list-inline">
                            <li><button type="submit" class="btn btn-primary btn-info-full" id="final_submit"><?php echo getLange('submit'); ?></button></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    
		</div>
        </section>
	</div>

  	</div>
<?php include "includes/footer.php"; ?>
    <script type = "text/javascript">
	$(document).ready(function () {

    $(document).on('keyup','.repassword',function(){
        var validation = true;
        var password       =       $('body').find('.password').val();
        var repassword       =       $('body').find('.repassword').val();
        if (password=='' || repassword=='')
        {
            validation=false;
        }
        if (validation==false) {
          $('#final_submit').prop('disabled', true);
        }else{
             if (password !== repassword) {
                $('body').find('.msg_pass').html('Password does not match.');
                $('#final_submit').prop('disabled', true);
            }else{
                $('body').find('.msg_pass').html('');
                $('#final_submit').prop('disabled', false);
            }
        }
		  
    }); 
    $(document).on('keyup','.password',function(){
        var validation = true;
        var password       =       $('body').find('.password').val();
        var repassword       =       $('body').find('.repassword').val();
        if (password=='' || repassword=='')
        {
            validation=false;
        }
        if (validation==false) {
          $('#final_submit').prop('disabled', true);
        }else{
             if (password !== repassword) {
                $('body').find('.msg_pass').html('Password does not match.');
                $('#final_submit').prop('disabled', true);
            }else{
                $('body').find('.msg_pass').html('');
                $('#final_submit').prop('disabled', false);
            }
        }
          
    }); 
    $(document).on('blur','.emailleee',function(){
    var email=$(this).val();
    var email_current=$(this);
    error=$(this).parent().find("div.help-block");
    if(email!=""){
        var postdata="action=email&email="+email;
        $.ajax({
            type:'POST',
            data:postdata,
            url:'ajax.php',
            success:function(fetch){
            error.html(fetch);
                    if(error.html()!==""){
                        $(email_current).parent().addClass("has-error").addClass("has-danger");
                    $('#final_submit').prop('disabled' , true);
                    var wringmsg='wringmsg';
                      $('.msg_email').val('');
                      $('.msg_email').val(wringmsg);
                    }else{
                         $('#final_submit').prop('disabled' , false);
                        $('.msg_email').val('');
                    }
                }
            });
        }
}); 
}); 
</script>