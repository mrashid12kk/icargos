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
		.profile-page-title, .col-lg-4 {
		    padding: 0 15px;
		}
		.modal-title {
			text-align: center;
		}
		.register_page{
			    max-width: 660px;
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
        .wizard h3 {
		    margin: 0 0 31px;
		    color: #fff;
		}

        
	.wizard a {
    color: white !important;
    margin: 0 5px;
    font-size: 17px;
    border: 1px solid #fff !important;
    width: auto;
    padding: 10px 20px;
}
        .wizard {
	    	padding: 47px 21px;
		    background: #202945;
		    max-width: 469px;
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
    .wizard a {
	    margin: 0 auto 13px;
	    font-size: 20px;
	    width: 100%;
	    padding: 10px 0;
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
        	<h3 class="modal-title modal-title-center hide-register-title" >Select Account Type</h4>
                <a href="quick_register.php" class="btn btn-success" style="background: #ffffff;color: #202945 !important;">Walk In Customer</a>
                <a href="register.php" class="btn btn-primary" style="background: #c91717;">Business Account</a>
        </div>
    
		</div>
        </section>
	</div>

  	</div>
<?php include "includes/footer.php"; ?>
    <script type = "text/javascript">
	$(document).ready(function () {


		$("#logo").change(function () {
			var validExtensions = ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"]
			var file = $(this).val().split('.').pop();

			if (validExtensions.indexOf(file) == -1) {
				var msg = ("Only formats are allowed : " + validExtensions.join(', '));
				$('#msg').html('');
				$('#msg').html(msg);
				$(this).val("");
			} else {
				$('#msg').html('');
			}
		});
		$("#image").change(function () {
			var validExtensions = ["jpg", "jpeg", "png", "JPG", "JPEG", "PNG"]
			var file = $(this).val().split('.').pop();
			if (validExtensions.indexOf(file) == -1) {
				var msg = ("Only formats are allowed : " + validExtensions.join(', '));
				$('.msg').html('');
				$('.msg').html(msg);
				$(this).val("");
			} else {
				$('.msg').html('');
			}

		});

		if (($('.expected_shipment').val()) == '') {
			step1_submit2();
		}
		$('body').on('keyup change', '.bname_check2', function (e) {
			e.preventDefault();
			step1_submit2();
		});
		function step1_submit2() {
             var validation = true;
            var product_type       =       $('body').find('.product_type').val();
            var customer_type       =       $('body').find('.customer_type').val();
            var city       =       $('body').find('.city').val();
            var expected_shipment       =       $('body').find('.expected_shipment').val();
            if (product_type== null || customer_type== null || city== null || expected_shipment=='')
            {
                validation=false;
            }
            if (validation==false) {
              $('#submit_step_data12').prop('disabled', true);
            }else{
              $('#submit_step_data12').prop('disabled', false);
            }
		}
		if (($('.password').val()) == '') {
			step1_submit3();
		}
		$('body').on('keyup change', '.bname_check3', function (e) {
			e.preventDefault();
			step1_submit3();
		});
		function step1_submit3() {
            var validation = true;
            var password       =       $('body').find('.password').val();
            var repassword       =       $('body').find('.repassword').val();
            if (password=='' || repassword=='')
            {
                validation=false;
            }
            if (validation==false) {
              $('.submit_step_data3').prop('disabled', true);
            }else{
                 if (password !== repassword) {
                    $('body').find('.msg_pass').html('Password does not match.');
                    $('.submit_step_data3').prop('disabled', true);
                }else{
                    $('body').find('.msg_pass').html('');
                    $('.submit_step_data3').prop('disabled', false);
                }
            }
         }
		
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
                    $('input[type="submit"]').attr('disabled' , true);
                    var wringmsg='wringmsg';
                      $('.msg_email').val('');
                      $('.msg_email').val(wringmsg);
                       step1_submit();
                    }else{
                        $('input[type="submit"]').attr('disabled' , false);
                        $('.msg_email').val('');
                        step1_submit();
                    }
                }
            });
        }
});
		if (($('.bname').val()) == '') {
			step1_submit();
		}
		$('body').on('keyup change', '.bname_check', function (e) {
			e.preventDefault();
			step1_submit();
		});
		function step1_submit() {
			var validation = true;
            var fname       =       $('body').find('.fname').val();
            var bname       =       $('body').find('.bname').val();
            var mobile_no   =       $('body').find('.mobile_no').val();
            var address     =       $('body').find('.address').val();
            var email       =       $('body').find('.emailleee').val();
            var cnic        =       $('body').find('.cnic').val();
            var msg_email   =       $('body').find('.msg_email').val();
            if (fname=='' || bname=='' || mobile_no == "" || address == "" || email == "" || cnic == "" || msg_email !='' )
            {
                validation=false;
            }
            if (validation==false) {
               
                $('#submit_step_data1').prop('disabled', true);
            }else{
                $('#submit_step_data1').prop('disabled', false);
            }
		}
		$('.nav-tabs > li a[title]').tooltip();
		$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

			var $target = $(e.target);

			if ($target.parent().hasClass('disabled')) {
				return false;
			}
		});

		$(".next-step").click(function (e) {

			var $active = $('.wizard .nav-tabs li.active');
			$active.next().removeClass('disabled');
			nextTab($active);

		});
		$(".prev-step").click(function (e) {

			var $active = $('.wizard .nav-tabs li.active');
			prevTab($active);

		});
	});

function nextTab(elem) {
	$(elem).next().find('a[data-toggle="tab"]').click();
}

function prevTab(elem) {
	$(elem).prev().find('a[data-toggle="tab"]').click();
} </script>

