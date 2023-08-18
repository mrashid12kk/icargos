<?php
    session_start();
    require 'includes/conn.php';
      
    // require 'includes/setting_helper.php';
    if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver'){
        echo test;
       require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],50,'view_only',$comment =null)){
        header("location:access_denied.php");
    }

    include "includes/header.php";
    
?>
 
    <?php
    
    // include "includes/sidebar.php";
    
    ?>
<?php
    // session_start(); 
    // require 'includes/conn.php';
    // require 'includes/setting_helper.php';
    // if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin')
    // if(isset($_SESSION['users_id']) && $_SESSION['type']=='driver')
    // {   
       
        if(isset($_POST['save_key']))
        { 
            $title          = $_POST['title'];
            $api_key        = $_POST['api_key'];
            $api_secret_key = $_POST['api_secret_key'];

            mysqli_query($con,"INSERT into third_party_apis (`title`,`api_key`,`api_secret_key`) VALUES ('$title', '$api_key','$api_secret_key' ) ");

            header('location: thirdparty_setting.php'); 
        }




        if(isset($_POST['save_city']))
        { 
            // echo "<pre>";
            // print_r($_POST);
            // die();
            $city_origin   = $_POST['city_origin'];
            $api_id        = $_POST['api_id']; 
          
            $api_city_id   = $_POST['api_city_id'];  
            $api_city_name = $_POST['api_city_name']; 
            if( !empty(mysqli_fetch_array(mysqli_query($con,"SELECT * FROM city_mapping WHERE city_id = '".$city_origin."'  AND api_id = '".$api_id."'    "))) )
            {
                $_SESSION['error_msg'] = 'Same data already exist.';
            }else{
                if ( mysqli_query($con,"INSERT into city_mapping (`city_id`, `api_id`, `api_city_id` , `api_city_name` ) VALUES ('$city_origin', '$api_id' , '$api_city_id', '$api_city_name') "))
                {
                    $_SESSION['success_msg'] = 'New data has been inserted successfully.';
                } else{
                    $_SESSION['error_msg'] = 'Please try again latter.';
                }
                // echo "<pre>";
                // print_r($_SESSION);
                // die();
                header('location: thirdparty_setting.php'); 
            }
           
        }


        if(isset($_POST['save_status']))
        { 
       
            $title          = $_POST['title'];
            $api_key        = $_POST['api_key'];
            $api_secret_key = $_POST['api_secret_key'];

            mysqli_query($con,"INSERT into third_party_apis (`title`,`api_key`,`api_secret_key`) VALUES ('$title', '$api_key','$api_secret_key' ) ");

            header('location: thirdparty_setting.php'); 
        }


        if(isset($_POST['save_service_map']))
        { 
              
            $services = $_POST['services'];
            $api_id   = $_POST['api_id'];
            $mode_id  = $_POST['mode_id'];
            if($mode_id == 5 ){
               $mode_name = 'COD'; 
            }
            elseif($mode_id == 6){
               $mode_name = 'Overland COD';  
            }else{
                  $mode_name   = getSonicModeName($mode_id);
            }
            
           
            $serviceData = getDataById('services', ' WHERE id = "'.$services.'" '); 

            $service_name = '';
            if (isset($serviceData['service_type'])) 
            {
                $service_name = $serviceData['service_type'];
            }

            if( !empty(mysqli_fetch_array(mysqli_query($con,"SELECT * FROM third_party_api_service_mapping WHERE service_id = '".$services."'  AND api_provider_id = '".$api_id."'  AND api_service_id = '".$mode_id."'   "))) )
            {
                $_SESSION['error_msg'] = ' Same data already exist.';
            }else{
                if(
              mysqli_query($con,"INSERT into third_party_api_service_mapping (`service_id`,`service_name`,`api_service_id`,`api_provider_id`,`api_service_name`) VALUES ('$services','$service_name', '$mode_id', '$api_id', '$mode_name' ) "))
                {
                    $_SESSION['success_msg'] = ' New data has been inserted successfully.';
                } else{
                    $_SESSION['error_msg'] = ' Please try again latter.';
                }
            }
            // echo "<pre>";
            // print_r($_SESSION);
            // die();

           header('location: thirdparty_setting.php');

        }
        if(isset($_POST['save_status_map']))
        { 

              $status_id = $_POST['status_id'];
             $api_provider_id   = $_POST['api_provider_id'];
            $api_status  = $_POST['api_status'];

    

            if( !empty(mysqli_fetch_array(mysqli_query($con,"SELECT * FROM third_party_api_status_mapping WHERE status_id = '".$status_id."'  AND api_provider_id = '".$api_provider_id."'  AND api_status = '".$api_status."'   "))) )
            {
                $_SESSION['error_msg'] = ' Same data already exist.';
            }else{
                if( mysqli_query($con,"INSERT into third_party_api_status_mapping (`status_id`,`api_provider_id`,`api_status`) VALUES ('".$status_id."','".$api_provider_id."', '".$api_status."') "))
                {
                   $_SESSION['success_msg'] = ' New data has been inserted successfully.';
                } else{
                    $_SESSION['error_msg'] = ' Please try again latter.';
                }
            }
       

             header('location: thirdparty_setting.php'); 
        }

        if(isset($_POST['edit_status_map']))
        { 

        

            $edittable_id = $_POST['edit_post_id'];
            $editstatus_id = $_POST['edit_status_id'];
            $editapi_provider_id   = $_POST['edit_api_provider_id'];
            $editapi_status  = $_POST['edit_api_status'];

                if( mysqli_query($con,"UPDATE third_party_api_status_mapping SET `status_id` = '".$editapi_provider_id."',`api_provider_id`='".$editstatus_id."' , `api_status`='".$editapi_status."' WHERE id ='".$edittable_id."'  "))
                {
                   $_SESSION['success_msg'] = 'data has been Updated successfully.';
            
                   
                } else{
                    $_SESSION['error_msg'] = ' Please try again latter.';
                }

    

             header('location: thirdparty_setting.php'); 
        }


        if(isset($_POST['delete_status_map']))
        { 

            
            $table_id = $_POST['delete_post_id'];

                if( mysqli_query($con,"DELETE FROM third_party_api_status_mapping  where id ='".$table_id."'  "))
                {
                   $_SESSION['success_msg'] = 'data has been Deleted successfully.';
                } else{
                    $_SESSION['error_msg'] = ' Please try again latter.';
                }
            
            // echo "<pre>";
            // print_r($_SESSION);
            // die();

             header('location: thirdparty_setting.php'); 
        }


        
        if(isset($_GET['delete_mapped']))
        { 
            $id  = $_GET['delete_mapped']; 
            mysqli_query($con,"DELETE  FROM city_mapping where id =".$id." "); 
            header('location: thirdparty_setting.php'); 
        }

        if(isset($_GET['delete_apis']))
        { 
            $id  = $_GET['delete_apis']; 
            mysqli_query($con,"DELETE  FROM third_party_apis where id =".$id." "); 
            header('location: thirdparty_setting.php'); 
        }




        include "includes/header.php";
        



        $cites        = mysqli_query($con,"SELECT * FROM cities  ");
        $order_status = mysqli_query($con,"SELECT * FROM order_status  "); 
        

        $cityapi_mapped = mysqli_query($con,"SELECT cities.city_name,third_party_apis.title,city_mapping.id,city_mapping.api_city_name FROM city_mapping LEFT JOIN cities on city_mapping.city_id = cities.id LEFT JOIN third_party_apis on city_mapping.api_id = third_party_apis.id");
        

        
 

        
        $return_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='return_fee'  ");
        $total_return = mysqli_fetch_array($return_query);

        $cash_handling_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='cash_handling'  ");
        $total_cash_handling = mysqli_fetch_array($cash_handling_query);
        $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
        $total_gst = mysqli_fetch_array($gst_query);


        /**
         * Service Mapping with shipping mode for SONIC API: 
        */

        $services       = mysqli_query($con,"SELECT * FROM services");
        $serviceapi_mapped = mysqli_query($con,"SELECT * FROM third_party_api_service_mapping "); 

        $thirdparties = mysqli_query($con,"SELECT * FROM  third_party_apis  ");

     

        $statuses       = mysqli_query($con,"SELECT * FROM order_status  ");

        $editstatuses       = mysqli_query($con,"SELECT * FROM order_status  ");
        
        $statusapi_mapped = mysqli_query($con,"SELECT *, third_party_api_status_mapping.id as post_id FROM third_party_api_status_mapping  
        join order_status on
        third_party_api_status_mapping.status_id =  order_status.sts_id
        join third_party_apis on 
        third_party_api_status_mapping.api_provider_id =third_party_apis.id
         ");

// $api_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM third_party_apis WHERE id  = 1 "));

// // $total_arraysab= mysqli_fetch_array($thirdparties);
// echo "<pre>";
//     print_r($api_data);
// echo "</pre>";
// die();



// $total_arraysz= mysqli_fetch_array($statuses);
//         echo "<pre>";
//             print_r($total_arraysz);
//         echo "</pre>";


    
        // $total_arrays= mysqli_fetch_array($statusapi_mapped);
        // echo "<pre>";
        //     print_r($total_arrays);
        // echo "</pre>";

        // exit();


        ?>

<style type="text/css">
.city_to option.hide {
    /*display: none;*/
}

.form-group {
    margin-bottom: 0px !important;
}

.tabs-left {
    border-bottom: none;
}

.tabs-left>li {
    float: none;
}

.tabs-left>li.active>a, .tabs-left>li.active>a:hover, .tabs-left>li.active>a:focus{
    background: #0e688c;
    color: #fff;
}

.tabs-left>li>a {
    margin-right: 0;
    border-radius: 0;
    display: block;
    font-weight: 600;
    padding: 15px 10px;
    border: 1px solid #3333 !important;
}

.panel-body .container {
    width: 100%;
    padding: 0;
}

.panel-body .col-xs-3 {
    padding-left: 0;
}

.panel-body .col-xs-9 {
    padding: 10px 0;
}

.btn_style {
    margin: 9px 0px;
}
</style>
<!-- Header Ends -->

<body data-ng-app>


    <?php include "includes/sidebar.php"; ?>
    <!-- Aside Ends-->

    <section class="content">

        <?php  include "includes/header2.php"; ?>

        <div class="warper container-fluid">
            <div class="page-header"><h1>
            <?php echo getLange('thirdpasrysetting') ?> <small><?php echo getLange('letsgetquick'); ?></small>
                       
                        </h1></div>
            <form method="POST" action="">
                <div class=" ">
                    <!-- <div class="panel-heading">Third Party Setting</div> -->
                    <div class=" ">


                        <div class="container_">
                            <?php
                                    if(isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])){
                                        $msg = $_SESSION['success_msg'];
                                        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>'.$msg.'</div>';
                                        
                                    } 
                                    ?>

                            <?php
                                        if(isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])){
                                            $msg = $_SESSION['error_msg'];
                                            echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error!</strong>'.$msg.'</div>';
                                            
                                        } 
                                    ?>
                            <div class="col-xs-3 third_party_sidebar">
                                <ul class="nav nav-tabs tabs-left">
                                    <li class="active"><a href="#ClientInfo" data-toggle="tab"><?php echo getLange('api'); ?></a></li>
                                    <li><a href="#tab2" data-toggle="tab"><?php echo getLange('general'); ?> </a></li>
                                    <li><a href="#tab3" data-toggle="tab"><?php echo getLange('servicemaping'); ?></a></li>
                                    <li><a href="#tab4" data-toggle="tab"><?php echo getLange('statusmapping'); ?></a></li>
                                    <!-- <li><a href="#tab4" data-toggle="tab">Tab 4</a></li> -->
                                </ul>
                            </div>
                            <div class="col-xs-9">
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="ClientInfo">
                                        
                                       
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?php echo getLange('add').' '.getLange('api'); ?> </div>
                                            <div class="panel-body">

                                                <form method="post" action="">
                                                    <div class="row">

                                                        <div class="col-md-3 padd_none form-group">
                                                            <div class="form-group">
                                                                <label><?php echo getLange('title'); ?></label>
                                                                <input required type="text" name="title"
                                                                    class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-3 padd_none form-group">
                                                            <div class="form-group">
                                                                <label><?php echo getLange('apikey'); ?></label>
                                                                <input required type="text" name="api_key"
                                                                    class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-3 padd_none form-group">
                                                            <div class="form-group">
                                                                <label><?php echo getLange('apisecretkey'); ?></label>
                                                                <input type="text" name="api_secret_key"
                                                                    class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1 padd_none">
                                                            <div class="form-group">
                                                                <input  type="submit"
                                                                    name="save_key" value="<?php echo getLange('save'); ?>"
                                                                    class="add_apibtn btn btn-primary pull-right">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="panel panel-primary" style="margin-top: 5px">
                                            <div class="panel-heading"><?php echo getLange('apilist') ?> </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('title') ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th style="width: 5%;"><?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <?php
                                                                        $i = 1;
                                                                        while($row=mysqli_fetch_array($thirdparties))
                                                                        {
                                                                            $row = (object) $row;  
                                            if($row->id == 10){
                                             ?>
                                        <tr>
                                        <td><?php  echo $i++; ?></td>
                                                                    <td><?php echo $row->title ?></td>
                                                                    <td><?php echo $row->api_key ?></td>
                                                                    <td><a onclick="return confirm('Are you sure you want to delete?');"
                                                                            href="thirdparty_setting.php?delete_apis=<?php echo $row->id ?>"><i
                                                                                class="fa fa-trash"></i></a></td>
                                        </tr>
                                        <?php
                                              } 
                                                                            
                                                                        } 
                                           ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?php echo getLange('citymaping'); ?> </div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('city'); ?></label>
                                                            <select class="form-control" name="city_origin" required>
                                                                <option value="">Select</option>
                                                                <?php
                                                                while($row=mysqli_fetch_array($cites))
                                                                {
                                                                    $row = (object) $row;  ?>
                                                                <option value="<?php echo $row->id; ?>">
                                                                    <?php echo $row->city_name; ?></option>
                                                                <?php
                                                                }
                                                                
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                        <label><?php echo getLange('api'); ?></label>
                                        <select class="form-control onclickapi" name="api_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?></option>
                                                                <?php
                                                                mysqli_data_seek($thirdparties,0);
                                                                while($row=mysqli_fetch_array($thirdparties))
                                                                {
                                                                    $row = (object) $row;  ?>
                                                                <option value="<?php echo $row->id; ?>">
                                                                    <?php echo $row->title; ?></option>
                                                                <?php
                                                                }
                                                                
                                                                ?>
                                                            </select>
                                                        </div>


                                        <div class="col-md-4">
                                        <label><?php echo getLange('api').' '.getLange('cities'); ?></label>
                                                           
                                              
                                              
                                             <!--forrun Api Cities-->
                                              <select class="form-control api_city_id" name="api_city_id"
                                                                required="" >
                                        <option value=""><?php echo getLange('select'); ?></option>
                                        <?php
                                        $sonice_cities = getSonicCities();
                                                                foreach ($sonice_cities->cities as $citykey => $cityvalue) 
                                                                {
                                                                    ?>
                                        <option data-cityname="<?php echo $cityvalue->name; ?>" value="<?php echo $cityvalue->id; ?>" style="display:none;" class="transoCities" >
                                        <?php echo $cityvalue->name; ?></option>
                                        <?php
                                            } 
                                            ?>
                                                  
                        <?php 
                        $ch = curl_init();
                        
                        curl_setopt($ch, CURLOPT_URL,"https://forrun.co/api/v1/getCities");
                        curl_setopt($ch, CURLOPT_GET, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                          
                           
                                        $result1 = curl_exec ($ch);
                                        curl_close($ch);
                                        /* echo "<pre>";
                                        print_r($result1);*/
                                        $data1 =  json_decode($result1);  
                                        
                                       foreach ( $data1 as $key1 => $value1) {
                                           
                                        foreach ( $value1 as $val) {  
                        
                                                  ?>
                                                               
                                        <option data-cityname="<?php echo $val; ?>"
                                         value="0" 
                                         style="display:none;" class="forrunCities"><?php echo $val; ?>
                                        </option>
                                        <?php } } ?>
                                                                
                                        </select>
                                                        

                                                            <input type="hidden" class="api_city_name"
                                                                name="api_city_name" value="">
                                                        </div>


                                                        <div class="col-md-12">
                                                            <input type="submit"
                                                                class="btn btn-info btn_style pull-right"
                                                                name="save_city" value="<?php echo getLange('save'); ?>" />
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('city'); ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th><?php echo getLange('api').' '.getLange('city'); ?></th>
                                                                    <th style="width: 5%;"><?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                        $i = 1;
                                                                        while($row=mysqli_fetch_array($cityapi_mapped))
                                                                        {
                                                                            $row = (object) $row;  
                                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $row->city_name ?></td>
                                                                    <td><?php echo $row->title ?></td>
                                                                    <td><?php echo $row->api_city_name ?></td>
                                                                    <td><a onclick="return confirm('Are you sure you want to delete?');"
                                                                            href="thirdparty_setting.php?delete_mapped=<?php echo $row->id ?>"><i
                                                                                class="fa fa-trash"></i></a></td>
                                                                </tr>
                                                                <?php
                                                                        } 
                                                                    ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?php echo getLange('statusmapping'); ?> </div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <select class="form-control" name="status_origin">
                                                                <option value=""><?php echo getLange('select'); ?></option>
                                                                <?php
                                                                while($row=mysqli_fetch_array($order_status))
                                                                {
                                                                    $row = (object) $row;  ?>
                                                                <option value="<?php echo $row->id; ?>">
                                                                    <?php echo $row->status; ?></option>
                                                                <?php
                                                                }
                                                                
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <input class="form-control" name="status_api" />
                                                        </div>


                                                        <div class="col-md-12">
                                                            <input type="submit"
                                                                class="btn btn-info btn_style pull-right"
                                                                name="save_status" value="<?php echo getLange('save'); ?>" />
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('status'); ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th style="width: 5%;"><?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab3">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?php echo getLange('servicemaping'); ?></div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('Services'); ?></label>
                                                            <select class="form-control" name="services" required>
                                                                <option value=""><?php echo getLange('select'); ?></option>
                                                                <?php
                                                                while($row=mysqli_fetch_array($services))
                                                                {
                                                                    $row = (object) $row;  ?>
                                                                <option value="<?php echo $row->id; ?>">
                                                                    <?php echo $row->service_type; ?>
                                                                    (<?php echo $row->service_code ?>) </option>
                                                                <?php
                                                                } 
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('api'); ?></label>
                                                            <select class="form-control apiName" name="api_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?></option>
                                                                <?php
                                                                mysqli_data_seek($thirdparties,0);
                                                                while($row=mysqli_fetch_array($thirdparties))
                                                                {
                                                                    $row = (object) $row;  ?>
                                                                <option value="<?php echo $row->id; ?>">
                                                                    <?php echo $row->title; ?></option>
                                                                <?php
                                                                }
                                                                
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('mode'); ?></label>
                                                            <select class="form-control" name="mode_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?></option>
                                                                <option value="1" class="transoServiceType">Overnight</option>
                                                                <option value="2" class="transoServiceType">Overland</option>
                                                                <option value="3" class="transoServiceType">Detain</option>
                                                                <option value="4" class="transoServiceType">Same-day</option> 
                                                                                <option value="5" class="forrunServiceType">COD</option>
                                                                <option value="6" class="forrunServiceType">OVL</option>
                                                            </select>
                                                            
                                                            
                                                        </div>


                                                        <div class="col-md-12">
                                                            <input type="submit"
                                                                class="btn btn-info btn_style pull-right"
                                                                name="save_service_map" value="<?php echo getLange('save'); ?>" />
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('Services') ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th><?php echo getLange('mode'); ?></th>
                                                                    <th style="width: 5%;"><?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                        $i = 1;
                                                                        while($row=mysqli_fetch_array($serviceapi_mapped))
                                                                        {
                                                                            $row = (object) $row;  

                                                                            $serviceData = (object) getDataById('services', ' WHERE id = "'.$row->service_id.'" ');
                                                                            $apiProviderData = (object) getDataById('third_party_apis', ' WHERE id = "'.$row->api_provider_id.'" ');

                                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $serviceData->service_type; ?> (
                                                                        <?php echo $serviceData->service_code ?> )</td>
                                                                    <td><?php echo $apiProviderData->title ?></td>
                                                                    <td><?php
                                   $res = getSonicModeName($row->api_service_id);
                                            if($res){
                                                echo getSonicModeName($row->api_service_id);
                                            }else{
                                                
                                               echo $row->api_service_name;
                                            }
                                                                   ?>
                                                                    </td>
                                                                    <td><a onclick="return confirm('Are you sure you want to delete?');"
                                                                            href="thirdparty_service.php?delete_service_mapped=<?php echo $row->id ?>"><i
                                                                                class="fa fa-trash"></i></a></td>
                                                                </tr>
                                                                <?php
                                                                        } 
                                                                    ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab4">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?php echo getLange('statusmapping'); ?></div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('status'); ?></label>
                                                            <select class="form-control" name="status_id" required>
                                                                <option value=""><?php echo getLange('select'); ?></option>
                                                                <?php

                                                                while($row=mysqli_fetch_array($statuses))
                                                                {
                                                                    $row = (object) $row;  ?>

                                                                <option value="<?php echo $row->sts_id; ?>">
                                                                    <?php echo $row->status; ?></option>

                                                                
                                                                <?php
                                                                   echo  $our_status_id =   $row->sts_id; 
                                                                }
                                                                
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('api'); ?></label>
                                                            <select class="form-control" name="api_provider_id"
                                                                required="" id="api_providerr">
                                                                <option><?php echo getLange('select'); ?></option>
                                                                <?php
                                                                mysqli_data_seek($thirdparties,0);
                                                                while($row=mysqli_fetch_array($thirdparties))
                                                                {
                                                                    $row = (object) $row;  ?>
                                                                 <option value="<?php echo $row->id; ?>">
                                                                    <?php echo $row->title; ?>
                                                                 </option> 

                                                                
                                                                <?php
                                                                }
                                                                  
                                                                ?>
                                                            </select>
                                                        </div>
                                                               

                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('api').' '.getLange('status'); ?></label>
                                                            <select class="form-control" name="api_status" id="api_status_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?></option>

                                                            </select>
                                                        </div>

                                                    
                                                        <div class="col-md-12">
                                                            <input type="submit"
                                                                class="btn btn-info btn_style pull-right"
                                                                name="save_status_map" value="<?php echo getLange('save'); ?>" />
                                                        </div>
                                                    </div>
                                                </form>
                                                    <?PHP 

                                                    if (isset($_POST['save_status_map'])) {

                                                       echo  $status_id = $_POST['status_id'];
                                                       echo  $api_provider_id = $_POST['api_provider_id'];
                                                       echo  $api_status = $_POST['api_status'];
                                                
                                                        $query=mysqli_query($con,"INSERT INTO third_party_api_status_mapping (status_id, api_provider_id, api_status)
                                                                VALUES ($status_id, $api_provider_id, $api_status");
                                                      

                                                    }
                                                         
                                                    ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('status'); ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th><?php echo getLange('api').' '.getLange('status'); ?></th>
                                                                    <th style="width: 5%;"><?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <?php
                                                                mysqli_data_seek($statusapi_mapped,0);
                                                                while($row=mysqli_fetch_array($statusapi_mapped))
                                                                {
                                                                    $row = (object) $row; 
                                                                   
                                                                        ?>

                                                                <tr>
                                                                    <td><?php echo $row->post_id ?></td>
                                                                    <td><?php echo $row->status ?></td>
                                                                    <td><?php echo $row->title ?></td>
                                                                    <td><?php echo $row->api_status;

                                                                    // if($row->active == 1){
                                                                    //     echo  'Active';
                                                                    // }else{
                                                                    //     echo "In Active";
                                                                    // }
                                                                    ?></td>
                                                                    <td style="width: 10%;">
                                                                    <input type="hidden" value="<?php echo $row->title ?>" id="postTitle<?php echo $row->post_id ?>">
                                                                    <input type="hidden" value="<?php echo $row->status ?>" id="postStatus<?php echo $row->post_id ?>">

                                                                        <a><i
                                                                                class="fa fa-trash text-danger"
                                                                                style="margin-left:6px;" onclick="showDeleteModel(this.id)" id="<?php echo $row->post_id ?>" ></i></a>

                                                                        <a onclick="showEditModel(this.id)" id="<?php echo $row->post_id ?>"><i
                                                                                class="fa fa-edit text-success"
                                                                                style="margin-left:6px;cursor:pointer;"></i></a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                }
                                                                
                                                                ?>


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div id="editModel" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo getLange('edit').' '.getLange('thirdpasrysetting'); ?></h4>
                    </div>
                    <div class="modal-body">


                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-4">
                                    <label><?php echo getLange('status'); ?></label>

                                    <select class="form-control" name="edit_api_provider_id" required="">
                                        <option value="">Select</option>
                                        <?php
                                             while($row=mysqli_fetch_array($editstatuses))
                                             {
                                                 $row = (object) $row;  ?>

                                             <option value="<?php echo $row->sts_id; ?>">
                                                 <?php echo $row->status; ?></option>
                                             <?php
                                             }
                                                                
                                                                ?>
                                    </select>

                                </div>


                                <div class="col-md-4">
                                    <label><?php echo getLange('api'); ?></label>
                                    <select class="form-control" name="edit_status_id" id="api_providerr_edit" required="">
                                        <option value=""><?php echo getLange('select'); ?></option>
                                        <?php
                                                                mysqli_data_seek($thirdparties,0);
                                                                while($row=mysqli_fetch_array($thirdparties))
                                                                {
                                                                    $row = (object) $row;  ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->title; ?></option>
                                        <?php
                                                                }
                                                                
                                                                ?>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label><?php echo getLange('api').' '.getLange('status'); ?></label>
                                    <select class="form-control" name="edit_api_status" id="api_status_id_edit" required="">
                                        <option value=""><?php echo getLange('select'); ?></option>

                                        <?php 

                                                             
                                        /*$query=mysqli_query($con,"select * from api_statues where api_id = 1; ");
                                                              
                                        if ($query) {                                                                                                                              
                                        while($fetch=mysqli_fetch_array($query)){
                                            $fetch = (object) $fetch; 
                                          echo  $api_status = $fetch->status;*/
                                        ?>
                                            <!-- <option value="<?php //echo $fetch->status; ?>"><?php // echo $fetch->status; ?></option> -->

                                        <?php     
                                       /* }
                                        }*/
                                        ?>

                                    </select>
                                </div>

                                <input type="hidden" value="" name="edit_post_id" id="edit_post_id">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-info btn_style pull-right"
                                        name="edit_status_map" value="Edit">
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getLange('close'); ?></button>
                    </div>
                </div>

            </div>
        </div>



        <div id="deleteModel" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo getLange('delete').' '.getLange('thirdpasrysetting'); ?></h4>
                    </div>
                    <div class="modal-body">


                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-12" id="deleteModelBody">
                                                                

                                   
                                </div>

                                <input type="hidden" value="" name="delete_post_id" id="delete_post_id">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-danger btn_style pull-right " 
                                        name="delete_status_map" value="delete">
                                </div>
                            </div>
                        </form>


                    </div>
         
                </div>

            </div>
        </div>

        <?php include "includes/footer.php"; 
    } else{
    header("location:index.php");
    }
?>
        <?php 

if (isset($_SESSION['success_msg'])) {
    unset($_SESSION['success_msg']);
}

if (isset($_SESSION['error_msg'])) {
    unset($_SESSION['error_msg']);
} 

?>
        <script type="text/javascript">
        function showEditModel(id) {

            $("#edit_post_id").val(id);
            jQuery.noConflict();
            $('#editModel').modal('show');

        }

function showDeleteModel(id) {

    var status = $("#postStatus"+id).val();
    var title = $("#postTitle"+id).val();
    
    $("#deleteModelBody").append("<h5>Are You sure want to delete setting with title"+title +" and status  "+status+"</h5>")
    $("#delete_post_id").val(id); 
    jQuery.noConflict();
    $('#deleteModel').modal('show');

}

        $(document).on('change', '.api_city_id', function() {
            $('.api_city_name').val($(this).find(':selected').attr('data-cityname'));
        });
        </script>


 
 <script type="text/javascript">
        $('.transoServiceType').hide(); 
        $('.forrunServiceType').hide(); 
         $('.apiName').on('change',function() {
       
          var  apiId =  $(this).find(':selected').val();
    //   alert(apiId);
          if(apiId == 10) {
              
               
              $('.forrunServiceType').show(); 
              $('.transoServiceType').hide(); 
          }
          if(apiId == 1) {
             
            
               $('.forrunServiceType').hide();
               $('.transoServiceType').show();
          }
        });
 </script>
 <script type="text/javascript">
 
//   alert('ok');
      $('.onclickapi').on('change',function() {
       
          var  apiId =  $(this).find(':selected').val();
       
          if(apiId == 10) {
              
              $('.forrunCities').css("display", "block"); 
              $('.transoCities').css("display", "none"); 
              $('.transoCities').hide(); 
          }
          if(apiId == 1) {
             
              $('.transoCities').css("display", "block"); 
              $('.forrunCities').css("display", "none"); 
               $('.forrunCities').hide();
          }
        });
        
        
            $('#api_providerr').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
              // console.log(stateID);
              // alert(stateID);
                $.ajax({
                    // url: "{{url('testapi11.php')}}"+'/'+stateID,
                    // url: 'testapi11.php'+'/'+stateID,
                    url: 'api_ajax_file.php?api_id='+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        // console.log(data);
                        $('#api_status_id').empty();
                        $.each(data, function(key, value) {
          

                            $('#api_status_id').append('<option value="'+ value.status +'">'+ value.status +'</option>');
                        });


                    }
                });
            }
            else{
                alert('Please Select a Valid API');
            }
        });
    

         </script>

          <script type="text/javascript">
      
            $('#api_providerr_edit').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
              // console.log(stateID);
              // alert(stateID);
                $.ajax({
                    // url: "{{url('testapi11.php')}}"+'/'+stateID,
                    url: 'api_ajax_file.php?api_id='+stateID,
                    // url: 'testapi11.php?api_id='+'/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        // console.log(data);
                        $('#api_status_id_edit').empty();
                        $.each(data, function(key, value) {
          

                            $('#api_status_id_edit').append('<option value="'+ value.status +'">'+ value.status +'</option>');
                        });


                    }
                });
            }
            else{
                alert('Please Select a Valid API');
            }
        });
    

         </script>