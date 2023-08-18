<?php

include_once "includes/conn.php";
// $zone_id = getConfig('rate_calculator_zone');

$zone_id_q = mysqli_query($con, "SELECT zone_id from customer_pricing where customer_id = 1");

$zone_id_rs = mysqli_fetch_assoc($zone_id_q);
$zone_id = $zone_id_rs['zone_id'];


// $origin_cities      = mysqli_query($con,"SELECT DISTINCT origin FROM zone_cities WHERE zone='".$zone_id."' ");
// $destination_cities = mysqli_query($con,"SELECT DISTINCT destination FROM zone_cities WHERE zone='".$zone_id."' ");
  $origin_cities      = mysqli_query($con," SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_id.") ");
  $destination_cities = mysqli_query($con," SELECT DISTINCT destination FROM zone_cities WHERE zone IN(".$zone_id.") ");

$destination_cities_list = '';
while($destination_r = mysqli_fetch_array($destination_cities))
{
    $city = $destination_r['destination'];
    if($city == 'Other' or $city == 'Others' ){
        $city_q = mysqli_query($con,"SELECT DISTINCT city_name FROM cities WHERE city_name !='Other' AND city_name !='LAHORE' ");
        while($city_q_r = mysqli_fetch_array($city_q))
        {
            $city = $city_q_r['city_name'];
            $destination_cities_list .= "<option>".$city."</option>";
        }
    }else{
        $destination_cities_list .= "<option>".$city."</option>";
    }
}
// echo "SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_id.") ";
// // print_r($destination_cities_list);
// die();

// $booking_zones_wic = mysqli_query($con,"SELECT * FROM booking_zones_wic WHERE is_active = 1 ");
$service_q = mysqli_query($con,"SELECT * FROM services WHERE 1 ");


$page_title = 'RATE CALCULATOR';
include "includes/header.php";
function addQuote($value){
	return(!is_string($value)==true) ? $value : "'".$value."'";
}


$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
$total_gst = mysqli_fetch_array($gst_query);

?>
<style>.navbar-nav .active a {

    padding: 0 !Important;

}</style>

			<div>
				  <div class="register_title">
					<h3 class="modal-title modal-title-center hide-register-title" style="color: black;font-size: 26px;font-weight: bold;">RATE CALCULATOR</h4>

				   </div>


			</div>
		<style>
      .term_label{
        color: #0a68bb;
      }
      .select2-container--default .select2-selection--single {
          border: 1px solid #cccccc;
      }
      .rate_btn{
        text-align: left ;
      }
      .padd_right{
        padding-right: 0 !important;
      }
      .col-lg-2 {
          margin-left: 0;
      }
      .customer_gapp {
          border-radius: 6px;
          padding: 10px 25px 8px 9px;
          border: 1px solid #ccccccc7;
      }
      .register_btn {
          padding: 9px 27px;
      }
      .select2-container--default .select2-selection--single .select2-selection__rendered {
          line-height: 35px;
      }
      .form-group input {
          padding: 0 13px;
      }
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
  .register_page {
      max-width: 660px;
      padding: 0 ;
  }
  .form-group input, input.emaill {
      background-color: #f8fbff7d !important;
  }
  label {
      margin: 6px 0;
      font-size: 14px;
      font-weight: bold;
  }

@media (max-width: 1250px){
    .container{
        width: 100%;
    }
    .register_page .col-lg-1,
.register_page .col-lg-10, .register_page.col-lg-11,
.register_page .col-lg-12,
.register_page .col-lg-2,
.register_page .col-lg-3,
.register_page .col-lg-4,
.register_page .col-lg-5,
.register_page .col-lg-6,
.register_page .col-lg-7,
.register_page .col-lg-8, .register_page .col-lg-9,
.register_page .col-md-1, .register_page .col-md-10,
.register_page .col-md-11,
.register_page .col-md-12,
.register_page .col-md-2,
.register_page .col-md-3,
.register_page .col-md-4,
.register_page .col-md-5,
.register_page .col-md-6,
.register_page .col-md-7, .register_page
.col-md-8, .register_page .col-md-9,
.register_page .col-sm-1,.register_page  .col-sm-10,
.register_page .col-sm-11,
.register_page .col-sm-12,
.register_page  .col-sm-2, .col-sm-3,
.register_page .col-sm-4, .register_page .col-sm-5,
 .register_page .col-sm-6,
 .register_page .col-sm-7,
  .register_page .col-sm-8,
  .register_page .col-sm-9,
  .register_page .col-xs-1,
  .register_page .col-xs-10,
   .register_page .col-xs-11,
    .register_page .col-xs-12,
     .register_page .col-xs-2,
      .register_page .col-xs-3,
       .register_page .col-xs-4,
        .register_page .col-xs-5,
        .register_page .col-xs-6,
         .register_page .col-xs-7,
         .register_page .col-xs-8,
          .register_page .col-xs-9{
    padding:  0 8px;
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
      .modal-body {
        padding-top: 0;
    }
    .register_page {
      box-shadow: none;
      border: none;
      margin: 0;
      padding: 0 !important;
  }

}

</style>
		<div class="modal-body">
			<div class="clearfix gray-bg gray-bg1 gray-bg2 register-items register_page">
  			<form >
  				<div class="col-lg-12 customer_gapp" >
  					<input type="hidden" name="" class="total_gst" value="<?php echo isset($total_gst['value']) ? $total_gst['value'] : '0'; ?>">
  					<div class="row" style="margin-left: 0px; margin-right: 0px;">
      					<div class="form-group col-lg-12 padd_right" id="" >
    				        <label for="usr"> Service Type:</label>
    				        <select style="      height: 38px;text-transform: capitalize;  padding: 0 5px !important;" class="form-control rate_service" name="service_type">
          					  	<option selected disabled>Select service type</option>
          					  	<?php while($row=mysqli_fetch_array($service_q)){ ?>
          					  		<option value="<?php echo $row['id']; ?>"><?php echo $row['service_type']; ?></option>
          					  	<?php } ?>
    				        </select>
      					</div>


  					</div>
  					<div class="row" style="margin-left: 0px; margin-right: 0px;">
              <div class="form-group col-lg-4 padd_right" id="" >
                    <label for="usr"> Origin:</label>
                    <select class="form-control js-example-basic-single rate_origin" name="origin">
                        <option selected disabled value="">Select Origin</option>
                        <?php while($row=mysqli_fetch_array($origin_cities)){ ?>
                          <option <?php if($row['origin'] == 'KARACHI'){ echo "selected"; } ?> value="<?php echo $row['origin']; ?>"><?php echo $row['origin']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-lg-4 padd_right" id="" >
                    <label for="usr"> Destination:</label>
                    <select class="form-control js-example-basic-single rate_destination" name="destination">
                                <option selected disabled value="">Select Destination</option>
                                <?php echo $destination_cities_list; ?>

                            </select>
                        </div>
      					<div class="form-group col-lg-4 padd_right" id="" >
                   <label for="usr"> Weight:</label>
                   <input type="text" name="weight" class="form-control rate_weight" onkeypress="return isNumberKey(this, event);" value="0">
                </div>




  					</div>
            <div class="row">

            </div>
  					<div class="rate_btn">
         <button id="rate_calculate" type="button" class="btn btn-info register_btn " style="width:auto;font-size: 16px;letter-spacing: 1.5px;background-color:#0a759c; margin: 7px 14px 21px;text-transform: capitalize;">Calculate</button>
            </div>

            <div class="row">
              <div class="col-sm-12 padd_right" >
                    <div class="form-group">
                      <label> Delivery Charges (PKR)</label>
                      <input type="text" value="0" name="price"  class="form-control rate_total_amount allownumericwithdecimal" readonly="true">
                    </div>
                  </div>
                  <!-- <div class="col-sm-4 padd_right">
                    <div class="form-group">
                      <label>Sales tax (PKR)</label>
                      <input type="text" name="pft_amount" value="0" class="form-control allownumericwithdecimal rate_pft_amount" readonly="true">
                    </div>
                  </div> -->
                  <!-- <div class="col-sm-4 padd_right">
                    <div class="form-group">
                      <label>Total service charges (PKR)</label>
                      <input type="text" value="0" name="inc_amount" class="form-control allownumericwithdecimal rate_inc_amount" readonly="true">
                    </div>
                  </div> -->
            </div>
  			</form>
		  </div>
		</div>
	</div>

  	</div>
  	<script type="text/javascript">
  		 function isNumberKey(txt, evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
          return true;
        } else {
          return false;
        }
      } else {
        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
      }
      return true;
    }

    $(function(){
      $('body').on('change','.rate_service',function(e){

        e.preventDefault();
        var val = $(this).val();
          $.ajax({
              url: 'ajax.php',
              type: 'POST',
              data: {cod_id:val},
              success: function (data) {
                console.log(data)
              }
          });



          $('body').on('click','#rate_calculate',function(e){

            e.preventDefault();
            origin = $(".origin").val();
            destination = $(".destination").val();
            weight = $(".weight").val();
            order_type = $(".order_type").val();
            if(order_type ==='' || weight ==="" || destination==="" || origin==="")
            {
              alert("Please fill all values");
            }else{
              $.ajax({
                  url: 'ajax.php',
                  type: 'POST',
                  data: {origin:origin,destination:destination,weight:weight,order_type:order_type,price_calculate:1},
                  success: function (data) {
                    console.log(data)
                  }
              });

            }

      })


    })
  	</script>
<?php include "includes/footer.php"; ?>
