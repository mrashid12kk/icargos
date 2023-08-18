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

<style>



.wizard_box {
    text-align: center;
    padding: 130px 0 0 0;
}
.wizard_box h3 {
    margin: 0 0 31px;
    color: #202945;
    font-size: 41px;
    font-weight: bold;
}
.wizard_box a {
	color: #fff !important;
    margin: 0 5px;
    font-size: 17px;
    border: 1px solid #fff;
    width: auto;
    padding: 7px 14px;
}
.customer_type{
	background: #f9f9f9;
    background-image: url(assets/img/shape_angle.png);
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.customer_img{
	padding: 0;
}
.customer_img img{
	width: 100%;
}

@media (max-width: 1250px){
    .container{
        width: 100%;
    }
    .wizard_box {
	    padding: 136px 0 0;
	}
}

@media (max-width: 1024px){
    .container{
        width: 100%;
    }
    .wizard_box a {
	    margin: 0 0 4px;
	}
    .hide-register-title {
	    display: block;
	    font-size: 24px !important;
	}
    .wizard_box {
	    padding: 70px 0 0 0;
	}


}

@media (max-width: 767px){
    .container{
        width: auto;
    }
    .wizard_box a {
	    margin: 0 auto 5px;
	    font-size: 20px;
	    width: 81%;
	    padding: 10px 0;
	}
	
}

</style>


<section class="customer_type">
	<div class="container">
		<div class="row">
		 	<div class="col-sm-5 customer_info">
		 		<div class="wizard_box">
		        	<h3 class="modal-title modal-title-center hide-register-title" >Select Account Type</h3>
		                <a href="quick_register.php" class="btn btn-success" style="background: #202945;">Walk In Customer</a>
		                <a href="register.php" class="btn btn-primary" style="background: #c91717;">Business Account</a>
		        </div>
		 	</div>
		 	<div class="col-sm-7 customer_img">
		 		<img src="assets/img/select_business-type_03.png" alt="">
		 	</div>
		 </div>
	</div>
</section>

    
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

