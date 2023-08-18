<?php
session_start();
require 'includes/conn.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (isset($_SESSION['users_id'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 65, 'view_only', $comment = null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";

?>

<body data-ng-app>
    <style type="text/css">
    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 100%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        float: left;
        margin: 2px;
        width: 100%;
    }

    .city_dropdown {
        max-height: 186px;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: auto;
    }
    .has-error{
            border-color: #a94442;
    }
    .with-error {
        color: #a94442;
    }
    #unique_order_datatable_info{
    	display: none;
    }
    .order_list_view tr:hover{
    	background-image: none;
    }
    .order_list_view tr {
    border-bottom: 2px solid #e0e3ed;
}

.order_list_view ,.order_list_view:hover {
     border-radius: 0px; 
}
.order_list_view tr:nth-child(even){
	background-color: #f9f9f9;
}
.html5buttons{
	float: right;
}
    </style>

    <?php

        include "includes/sidebar.php";

        ?>
    <!-- Aside Ends-->

    <section class="content">

        <?php
            include "includes/header2.php";
            ?>

        <!-- Header Ends -->
<?php

    $query ="SELECT * FROM customers WHERE tariff_type= 'custom'";
    $customers = mysqli_query($con, $query);
    $sq =  "SELECT * FROM products ORDER BY id DESC";
    $products = mysqli_query($con,$sq);
    // var_dump($sq);

?>

        <div class="warper container-fluid">

            <div class="page-header">
                <h1><?php echo getLange('servicelist'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
            </div>
            <div class="row">
                <?php
                    require_once "setup-sidebar.php";
                    ?>
                <div class="col-sm-10 table-responsive" id="setting_box">
                    <?php
                        $msg = "";
                        if (isset($_GET['delete_id'])) {
                            $id = $_GET['delete_id'];
                            $query1 = mysqli_query($con, "DELETE from custom_tariff_pricing where id=$id") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong>You delete a Custom 
                                Tariff Successfully</div>';
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong>You cannot delete Custom Tariff.</div>';
                            }
                        }
                        if (isset($_POST['addsettlement_period'])) {
                            // var_dump($_POST);

                            $customer = isset($_POST['customer']) ? $_POST['customer'] : '';
                            $origin = isset($_POST['origin']) ? $_POST['origin'] : '';
                            $destination = isset($_POST['destination']) ? $_POST['destination'] : '';
                            $product = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
                            $service = isset($_POST['order_type']) ? $_POST['order_type'] : '0';
                            $minweight = isset($_POST['minweight']) ? $_POST['minweight'] : '';
                            $wprice = isset($_POST['wprice']) ? $_POST['wprice'] : '';
                            $add_kg = isset($_POST['add_kgs']) ? $_POST['add_kgs'] : '';
                            $add_kg_price = isset($_POST['add_kgs_price']) ? $_POST['add_kgs_price'] : '';

                            $select_query = mysqli_query($con , "SELECT id FROM custom_tariff_pricing where `origin` = '".$origin."' AND  `destination` = '".$destination."'  AND  `customer_id` = '".$customer."' AND  `product_id` = '".$product."' AND  `service_id` = '".$service."' AND  `min_weight` = '".$minweight."' ");
                   			$result = mysqli_affected_rows($con);
                   			// var_dump($result);
                   			// die();
                   			 if ($result > 0) {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Duplicate entries</div>';
                            } else {
                              
                             $insert_qry = "INSERT INTO `custom_tariff_pricing`(`customer_id`,`origin`,`destination`, `product_id`, `service_id`, `min_weight`, `min_weight_price`, `additional_kg`, `additional_kg_price`) VALUES ('" . $customer . "','" . $origin . "','" . $destination. "','" . $product . "','" . $service . "','" . $minweight . "','" . $wprice . "','" . $add_kg . "','" . $add_kg_price . "') ";
                              // $insert_qry = "ALTER table custom_tariff_pricing change column service_id service_id int(10) default 0";
                            $query = mysqli_query($con, $insert_qry);
                            // if(!$query){
                            // 	echo "string".mysqli_error($con);
                            // }
                            // else {
                            // 	var_dump($query);
                            // }
                            // die();
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Custom Tariff successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a Custom Tariff.</div>';
                            }
                        }
                        }
                        if (isset($_POST['updatesettlement_period'])) {
                           $customer = isset($_POST['customer']) ? $_POST['customer'] : '';
                            $origin = isset($_POST['origin']) ? $_POST['origin'] : '';
                            $destination = isset($_POST['destination']) ? $_POST['destination'] : '';
                            $product = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
                            $service = isset($_POST['order_type']) ? $_POST['order_type'] : '';
                            $minweight = isset($_POST['minweight']) ? $_POST['minweight'] : '';
                            $wprice = isset($_POST['wprice']) ? $_POST['wprice'] : '';
                            $add_kg = isset($_POST['add_kgs']) ? $_POST['add_kgs'] : '';
                            $add_kg_price = isset($_POST['add_kgs_price']) ? $_POST['add_kgs_price'] : '';

                               $select_query = mysqli_query($con , "SELECT id FROM custom_tariff_pricing where `customer_id` = '".$customer."' AND `origin` = '".$origin."' AND  `destination` = '".$destination."'  AND  `product_id` = '".$product."' AND  `service_id` = '".$service."' AND  `min_weight` = '".$minweight."' ");
                   			$result = mysqli_affected_rows($con);
                   			// var_dump($result);
                   			// die();
                   			 if ($result > 0) {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Duplicate entries</div>';
                            } else {

                            $query =  " UPDATE custom_tariff_pricing SET customer_id='" . $customer . "',origin='" . $origin . "',destination='" . $destination . "' ,product_id='" . $product . "',service_id='" . $service . "',min_weight='" . $minweight . "', min_weight_price='" . $wprice . "',additional_kg='" . $add_kg . "',additional_kg_price='" . $add_kg_price . "' WHERE id='" . $_GET['edit_id'] . "' ";
                            // var_dump($query);
                            $query1 = mysqli_query($con,$query) or die(mysqli_error($con));

                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Custom Tariff updated successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Custom Tariff have not updated.</div>';
                            }
                        }
                    }
                        echo $msg;
                        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                        	// die('okk');
                            $edit_id = $_GET['edit_id'];
                            $custom_tariff_pricing = mysqli_query($con, "SELECT * FROM custom_tariff_pricing WHERE id='" . $edit_id . "' ");
                            $edit = mysqli_fetch_array($custom_tariff_pricing);
                            // var_dump($edit);
                        }
                        // $account_types = mysqli_query($con, "SELECT * FROM account_types ORDER BY id DESC");
                        ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">Custom Tariff </div>
                        <div class="panel-body" id="same_form_layout">
                        <form role="form" data-toggle="validator" action="custom_tariff_pricing.php<?php echo isset($_GET['edit_id']) ? '?edit_id='.$_GET['edit_id'] : ''; ?>" method="post">
                      
                            <div class="row" >
                            
                            <div class="col-sm-2 padd_none" style="margin-left: 20px">
                                <div class="form-group">

                                   <label class="control-label"><span style="color: red;">*</span>Select Customer</label>
                                    <select class="form-control active_customer_detail select2 " name="customer" required="">
                                    <option selected><?php echo getLange('selectcustomer'); ?> </option>
                                    <?php 
                                    foreach ($customers as $customer) {
                                    // var_dump($customer);
                                     ?>
                                    <option value="<?php echo $customer['id']; ?>" <?php echo isset($edit) && $edit['customer_id'] == $customer['id']?'selected':'';?>>
                                    <?php echo $customer['fname']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <div class="help-block with-errors "></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span>Product</label>
                                                    <select class="form-control select2 product_type_id" name="product_type_id" required>
                                                        <option value="">Select Product</option>
                                                        <?php while ($row = mysqli_fetch_array($products)) {
                                                        ?>
                                                            <option value="<?php echo $row['id']; ?>" <?php echo isset($edit) && $edit['product_id'] == $row['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $row['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                            <div class="col-sm-2 sidegap">
                                <div class="form-group">
                                <label><span style="color: red;"></span><span style="color: red;">*</span><?php echo getLange('servicetype'); ?></label>
                                    <select class="form-control order_type  service select2" name="order_type">
                                    <option value="0">Select Service</option>
                                      <?php /*
                                    if(isset($edit)){
                                        $q = "SELECT * FROM services where id = '".$edit['service_id']."'";
                                        $conection = mysqli_query($con, $q);
                                       while($row = mysqli_fetch_array($conection)){
                                        // var_dump($row);
                                        ?>
                                        <option selected><?php echo $row['service_type'];?></option>
                                        <?php
                                       }
                                    } */
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 sidegap">
                                <div class="form-group">
                                <label class="control-label"><span style="color: red;">*</span><?php echo getLange('origin'); ?></label>
                                <select class="form-control origin origin_cal origin_branch_id select2" name="origin" required>
                                	<option>Select Origin</option>
                                    <?php
                                    $origin_q = mysqli_query($con, " SELECT * FROM cities");
                                    while ($row = mysqli_fetch_array($origin_q)) { ?>
                                    <option value="<?php echo $row['city_name']; ?>"  <?php echo isset($edit) && $edit['origin'] == $row['city_name']?'selected':''; ?>>
                                    <?php echo $row['city_name']; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>

                            <div class="col-sm-2 sidegap">
                                <div class="form-group">
                                <label><span style="color: red;">*</span><?php echo getLange('destination'); ?></label>
                                <select class="form-control origin select2" name="destination">
                               	<option>Select Destination</option>

                                    <?php
                                    $origin_q = mysqli_query($con, " SELECT * FROM cities");
                                    while ($row = mysqli_fetch_array($origin_q)) { ?>
                                    <option value="<?php echo $row['city_name']; ?>" <?php echo isset($edit) && $edit['destination'] == $row['city_name']?'selected':''; ?>>
                                    <?php echo $row['city_name']; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div></div>
                            <div class="row">
                                <div class="col-sm-2 sidegap" style="margin-left: 20px">
                                    <div class="form-group">
                                        <label class="control-label"><span style="color: red;">*</span><?php echo getLange('Min Weight'); ?></label>
                                        <input type="number" value="<?php if (isset($edit)) {echo $edit['min_weight'];} ?>" class="form-control " name="minweight"  id="minweight" min="1" required>
                                         <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2 sidegap" >
                                    <div class="form-group">
                                        <label class="control-label"><span style="color: red;">*</span><?php echo getLange('Min Weight Price'); ?></label>
                                        <input type="text" class="form-control" name="wprice" value="<?php if (isset($edit)) {echo $edit['min_weight_price'];} ?>" id="wprice" required>
                                            <div class="help-block with-errors "></div>
                                    </div>
                                </div>
                                <div class="col-sm-2 sidegap" >
                                    <div class="form-group">
                                        <label class="control-label"><span style="color: red;">*</span><?php echo getLange('Additional KGs'); ?> </label>
                                         <input type="tet" class="form-control" name="add_kgs" value="<?php if (isset($edit)) {echo $edit['additional_kg'];} ?>" id="add_kgs" min="1" required>
                                         <div class="help-block with-errors  with-error "></div>
                                    </div>
                                </div>
                                <div class="col-sm-2 sidegap" >
                                    <div class="form-group">
                                        <label class="control-label"><span style="color: red;">*</span><?php echo getLange('Price With Additional KGs'); ?> </label>
                                      <input type="tet" class="form-control" name="add_kgs_price" value="<?php if (isset($edit)) {echo $edit['additional_kg_price'];} ?>" id="add_kgs_price" required>
                                      <div class="help-block with-errors "></div>
                                    </div>
                                </div>
                              
                                <div class="col-md-2 rtl_full" style="    margin-top: 30px;
">
                                    <button type="submit" name="<?php if (isset($edit)) {echo 'updatesettlement_period';} else {echo 'addsettlement_period';} ?>" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                </div>

                            </div>

                        </form>

                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Custom Tariff</div>
                        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                            <div id="basic-datatable_wrapper"
                                class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                      <!--           <table cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped table-bordered dataTable no-footer" id="unique_order_datatable"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 2%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;"><?php echo getLange('srno'); ?></th>
                                                 <th style="width: 2%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;"><?php echo getLange('customername'); ?></th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Origin </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Destination </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Product Type </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Service Type </th>
                                             <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Min Weight </th>
                                             <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Min Weight Price</th>
                                             <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Additional Kg</th>
                                           <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Additional Kg Price</th>

                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                                colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                                style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <?php
                                    /*
                                    <tbody>
                                        <?php
                                            $query1 = mysqli_query($con, "SELECT * from custom_tariff_pricing ORDER BY id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                             // var_dump($fetch1);
                                                $product_query = mysqli_query($con, "SELECT * FROM products WHERE id=" .$fetch1['product_id']);
                                                $product_fetch = mysqli_fetch_array($product_query);
                                                $product_name = $product_fetch['name']; 

                                                 $service_query = mysqli_query($con, "SELECT * FROM services WHERE id='" . $fetch1['service_id'] . "' ");
                                                $services_fetch = mysqli_fetch_array($service_query);
                                                $service_type = $services_fetch['service_type'];

                                                $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id='" . $fetch1['customer_id'] . "' ");
                                                $customers_fetch = mysqli_fetch_array($customer_query);
                                                // var_dump($customers_fetch)
                                                $customers = $customers_fetch['bname'];
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $customers; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['origin']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['destination']; ?></td>
                                            <td class="sorting_1"><?php echo $product_name; ?></td>
                                            <td class="sorting_1"><?php echo $service_type; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['min_weight']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['min_weight_price']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['additional_kg']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['additional_kg_price']; ?></td>
                                            
                                            <td class="center">
                                                <a href="custom_tariff_pricing.php?edit_id=<?php echo $fetch1['id']; ?>">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                <a href="custom_tariff_pricing.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Settlement Period?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                                $sr++;
                                            }

                                            ?>
                                    </tbody> */ ?>
                                </table> -->
   <table class="order_list_view" id="unique_order_datatable" >
              <!-- <table class="" > -->
                <thead>
                  <th><?php echo getLange('srno'); ?></th>
                  <th>Customer Name</th>
                  <th>Product Type</th>
                  <th>Service Type</th>
                  <th>Origin</th>
                  <th>Destination</th>
                  <th>Min Weight</th>
                  <th>Min Weight Price</th>
                  <th>Additional KGs</th>
                  <th>Additional KGs Price</th>
                  <th>Action</th>
                </thead>

                            </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
 
    <script>
        $('.select2').select2();
    </script>
    <script type="text/javascript">
        function getServiceType() {  
            var product_type_id = $('.product_type_id').find(':selected').val();
            var customer_id = $('.active_customer_detail').val(); 
            var prodId = getParameterByName('edit_id');
            if (product_type_id) {
                $.ajax({
                    url: '<?php echo BASE_URL ?>getServices.php',
                    type: 'POST',
                    data: {
                        is_product: 1,
                        product_type_id: product_type_id,
                        customer_id: customer_id,
                        updatesettlement_period:prodId
                    },
                    success: function(response) {
                        // alert(response);
                     $('.order_type ').html(response);
                    }
                })
            }
        }</script>
        <script>
        $('.product_type_id').on('change',function(){
            getServiceType();
        });
            </script>
                 
          
             <script>
                $(document).ready(function() {
                	getServiceType();
                });
            </script>
      		<script>
        	
	        	function getParameterByName(name) {  
				    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
				    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex.exec(location.search);
				    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
				}
        	</script>
            <script>

                   $('#minweight').on('focusout',function(){
                    $('add_kgs').removeClass('has-error');

                    var min_weight = $(this).val();

                    var additional = $('#add_kgs').val();
                    if(additional == '' || min_weight == '')
                    {
                        return 0 ;
                    }
                    if(additional < min_weight){
                        // alert(1);
                        $('.add_form_btn'). attr('disabled', false);
                        $('#add_kgs').addClass('has-error');
                        $('.with-error').show();
                        $('.with-error').html('Additional KGs should be greater than min weight');
                        $('.add_form_btn'). attr('disabled', true);
                    }
                     else{
                         $('#add_kgs').removeClass('has-error');
                         $('.with-error').hide();

                     }
                })
                $('#add_kgs').on('focusout',function(){
                    $('.add_form_btn'). attr('disabled', false);
                    $('#add_kgs').removeClass('has-error');
                    var min_weight = $('#minweight').val();
                    var additional = $(this).val();
                    if(additional == '' || min_weight == '')
                    {
                        return 0 ;
                    }
                    if(additional < min_weight){
                        // alert(2);
                        $('#add_kgs').addClass('has-error');
                        $('.with-error').show();
                        $('.with-error').html('Additional KGs should be greater than min weight');
                        $('.add_form_btn'). attr('disabled', true);
                    }
                    else{
                         $('#add_kgs').removeClass('has-error');
                         $('.with-error').hide();
                    }
                });

            </script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var dataTable = $('#unique_order_datatable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    // 'scrollCollapse': true,
        // 'ordering': false,
        // pageLength: 5,
        'responsive': true,
        'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       dom: '<"html5buttons"B>lTfgitp',
         'buttons': [
                  {extend: 'copy'},
                  {extend: 'csv'},
                  {extend: 'excel', title: 'ExampleFile'},
                  {extend: 'pdf', title: 'ExampleFile'},
                  {extend: 'print',

                   customize: function (win){
                     $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                        );
                          $(win.document.body).addClass('white-bg');
                          $(win.document.body).css('font-size', '10px');
                          $(win.document.body).find('table')
                                  .addClass('compact')
                                  .css('font-size', 'inherit');
                  }
                  }
              ],
    'searching': false, // Remove default Search Control
    'ajax': {

       'url':'custom_tariff_ajax.php',
       'data': function(data){
       }
    },
    'columns': [
       { data: 'id' },
       { data: 'customername' },
       { data: 'ProductType' },
       { data: 'service_type' },
       { data: 'origin' },
       { data: 'destination' },
       { data: 'minweight' },
       { data: 'minweightprice' },
       { data: 'additionalkgs' },
       { data: 'additionalkgsprice' },
       { data: 'action' },
    ]
  });


$('#unique_order_datatable_filter').keyup(function (){
	  dataTable.draw();
});

}, false);
</script>