<?php
  session_start();
  require 'includes/conn.php';
  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
     require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],18,'edit_only',$comment =null)){
        header("location:access_denied.php");
    }
  include "includes/header.php";
  $origincitydata=mysqli_query($con,"Select * from cities order by city_name");
  $destcitydata=mysqli_query($con,"Select * from cities order by city_name");
  $riderdata=mysqli_query($con,"Select * from users WHERE type='driver' ");
  $servicetypes=mysqli_query($con,"Select * from services WHERE 1 ");
  if(isset($_GET['zone_id'])){
    $zone_id = $_GET['zone_id'];
    $zone_q = mysqli_query($con," SELECT * FROM zone WHERE id ='".$zone_id."' ");
    $zone_rec = mysqli_fetch_array($zone_q);
  }
   $sql = "SELECT * FROM zone_cities WHERE zone= ".$zone_rec['id']."  ";
  $cities_get = mysqli_query($con,$sql);
  $count = mysqli_num_rows($cities_get);
   $products=mysqli_query($con,"SELECT * FROM products ORDER BY id DESC ");
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
        <div class="warper container-fluid">
            <div class="page-header"><h1><?php echo getLange('dashboard') ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>
            <?php
  $msg="";
  if(isset($_POST['addcities'])){
    for($i=0;$i<count($_POST['city']);$i++){
    $query1=mysqli_query($con,"INSERT INTO `cities`(`city_name`) VALUES ('".$_POST['city'][$i]."')") or die(mysqli_error($con));
    $rowscount=mysqli_affected_rows($con);
    if($rowscount > 0){
        $msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new City successfully</div>';
        }else{
        $msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new City unsuccessfully.</div>';
      }
    }
  }
echo $msg;
if(isset($_SESSION['zone_msg'])){
  echo $_SESSION['zone_msg'];
  unset($_SESSION['zone_msg']);
}
?>
<?php
            //require_once "setup-sidebar.php";
          ?>

          <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
            <div class="panel panel-default">
  <div class="panel-heading"><?php echo getLange('edit').' '.getLange('') ?></div>
  <div class="panel-body" id="same_form_layout">
    <form role="form" data-toggle="validator" action="updatezone.php" method="post">
      <div id="cities">
         <input type="hidden" name="zone_id" value="<?php echo $zone_rec['id']; ?>">
          <div class="row">
              <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label"><?php echo getLange('zone'); ?></label>
                <input type="text" class="form-control" name="zone" value="<?php echo $zone_rec['zone'] ?>" placeholder="Zone Name" required readonly="true">
                <div class="help-block with-errors "></div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label">Product</label>
                <select class="form-control select2" name="product_id">
                  <?php if(isset($products) && !empty($products)){
                    foreach($products as $row){ ?>
                  <option <?php if($zone_rec['product_id'] == $row['id']){ echo "selected"; } ?> value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"><?php echo isset($row['name']) ? $row['name'] : ''; ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label"><?php echo getLange('servicetype'); ?></label>
                <select class="form-control select2" name="service_type">
                  <?php if(isset($servicetypes) && !empty($servicetypes)){
                    foreach($servicetypes as $row){ ?>
                  <option <?php if($zone_rec['service_type'] == $row['id']){ echo "selected"; } ?> value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"><?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label"><?php echo getLange('0.5kg'); ?></label>
                <input type="text" class="form-control allownumericwithdecimal" name="point_5_kg" value="<?php echo isset($zone_rec['point_5_kg']) ? $zone_rec['point_5_kg'] :0; ?>"  required>
                <div class="help-block with-errors "></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label"><?php echo getLange('upto1kg'); ?></label>
                <input type="text" class="form-control allownumericwithdecimal" name="upto_1_kg" value="<?php echo isset($zone_rec['upto_1_kg']) ? $zone_rec['upto_1_kg']:0; ?>"  required>
                <div class="help-block with-errors "></div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label"><?php echo getLange('upto3kg'); ?></label>
                <input type="text" class="form-control allownumericwithdecimal" name="upto_3_kg" value="<?php echo isset($zone_rec['upto_3_kg']) ? $zone_rec['upto_3_kg']:0; ?>" >
                <!-- <div class="help-block with-errors "></div> -->
              </div>
            </div>
             <div class="col-md-3">
              <div class="form-group">
                <label  class="control-label"><?php echo getLange('upto10kg'); ?></label>
                <input type="text" class="form-control allownumericwithdecimal" name="upto_10_kg" value="<?php echo isset($zone_rec['upto_10_kg']) ? $zone_rec['upto_10_kg']:0; ?>">
                <!-- <div class="help-block with-errors "></div> -->
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo getLange('additionalkg'); ?></label>
                <select name="addition_kg_type" class="form-control addition_kg_type" required="">
                  <option <?php echo (isset($zone_rec['addition_kg_type']) && $zone_rec['addition_kg_type'] == 'Additional Weight 0.5 kg') ? 'selected':''; ?> value="Additional Weight 0.5 kg">Additional Weight 0.5 kg</option>
                  <option <?php echo (isset($zone_rec['addition_kg_type']) && $zone_rec['addition_kg_type'] == 'Additional Weight 1 kg') ? 'selected':''; ?> value="Additional Weight 1 kg">Additional Weight 1 kg</option>
                </select>
                <!-- <label  class="control-label"><?php echo getLange('additionalkg'); ?> </label>
                <input type="text" class="form-control allownumericwithdecimal" name="other_kg">
                <div class="help-block with-errors "></div> -->
              </div>
            </div>
            <div class="col-md-3">
                <div class="form-group addition_kg_input">
                  <?php
                    if(isset($zone_rec['addition_kg_type']) && $zone_rec['addition_kg_type'] == 'Additional Weight 0.5 kg')
                    {
                      $name='additional_point_5_kg';
                      $value = isset($zone_rec['additional_point_5_kg']) ? $zone_rec['additional_point_5_kg']:'';
                    }
                    else
                    {
                      $name='other_kg';
                      $value = isset($zone_rec['other_kg']) ? $zone_rec['other_kg']:'';
                    }
                  ?>
                    <label  class="control-label"><?php echo isset($zone_rec['addition_kg_type']) ? $zone_rec['addition_kg_type']:''; ?></label>
                    <input type="text" class="form-control allownumericwithdecimal" value="<?php echo isset($value) ? $value:''; ?>" name="<?php echo isset($name) ? $name:''; ?>"  required>
                    <div class="help-block with-errors "></div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="panel panel-default">
            <div class="panel-heading"><?php echo getLange('add').' '.getLange('city'); ?></div>
                <div class="panel-body">
                   <table class="table add_cities" id="price_table">
                          <thead>
                            <tr>
                              <th><?php echo getLange('origin'); ?></th>
                              <th><?php echo getLange('destination'); ?></th>
                             <th></th>
                            </tr>
                          </thead>
                          <?php
                          $loop = 0;
                          if($count > 0){
                          while($record = mysqli_fetch_array($cities_get)){
                          mysqli_data_seek($origincitydata,0);
                          mysqli_data_seek($destcitydata,0);
                           ?>
                          <tr>
                            <td>
                              <div class="form-group">
                                <select  class="form-control city_form" name="pricing[<?php echo $loop; ?>][city_form]" >
                                  <?php while($row = mysqli_fetch_array($origincitydata)){ ?>
                                  <option <?php if($row['city_name'] == $record['origin']) { echo "selected"; } ?> ><?php echo $row['city_name']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <select  class="form-control city_to get_city_name " name="pricing[<?php echo $loop; ?>][city_to]">
                                 <?php while($row = mysqli_fetch_array($destcitydata)){ ?>
                                  <option <?php if(trim($record['destination']) == trim($row['city_name'])) { echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                            </td>
                              <td>
                            <?php if($loop == 0){ ?>
                              <a style="" href="#" class="add_row btn btn-info"><i class="fa fa-plus"></i></a>
                            <?php }else{ ?>
                              <a style="" href="#" class="remove_row btn btn-danger"><i class="fa fa-trash"></i></a>
                            <?php } ?>
                            </td>
                          </tr>
                        <?php
                         $loop ++;
                          }
                        }else{ ?>
                          <tr>
                            <td>
                              <div class="form-group">
                                <select  class="form-control city_form" name="pricing[<?php echo $loop; ?>][city_form]" >
                                  <?php while($row = mysqli_fetch_array($origincitydata)){ ?>
                                  <option <?php if($row['city_name'] == 'LAHORE') { echo "selected"; } ?> ><?php echo $row['city_name']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <select  class="form-control city_to get_city_name " name="pricing[<?php echo $loop; ?>][city_to]">
                                 <?php while($row = mysqli_fetch_array($destcitydata)){ ?>
                                  <option <?php if(trim($record['city_to']) == trim($row['city_name'])) { echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                            </td>
                              <td>
                            <?php if($loop == 0){ ?>
                              <a style="" href="#" class="add_row btn btn-info"><i class="fa fa-plus"></i></a>
                            <?php }else{ ?>
                              <a style="" href="#" class="remove_row btn btn-danger"><i class="fa fa-trash"></i></a>
                            <?php } ?>
                            </td>
                          </tr>
                        <?php } ?>
                         </table>
                         <div class="row">
                          <div class="col-md-4 submit_padd">
                            <button type="submit" name="updatedzone" class="add_form_btn" ><?php echo getLange('update'); ?></button>
                          </div>
                      </div>
                </div>
              </div>
          </div>
          
        </div>
      <br>
    </form>
  </div>
</div>
</div>
        </div>

        </div>
        </div>
        <!-- Warper Ends Here (working area) -->
      <?php
  include "includes/footer.php";
  }
  else{
    header("location:index.php");
  }
  ?>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.select2').select2();
      // $('body').on('change','.addition_kg_type',function(event){
      //   event.preventDefault();
      //   var addition_kg_type = $(this).find(':selected').val();
      //   if(addition_kg_type)
      //   {
      //     $('body').find('.addition_kg_input').show();
      //     $('body').find('.addition_kg_input').find('label').html(addition_kg_type);
      //     if(addition_kg_type == 'Additional Weight 1 kg')
      //     {
      //       $('body').find('.addition_kg_input').find('input').attr('name','other_kg'); 
      //     }
      //     else
      //     {
      //       $('body').find('.addition_kg_input').find('input').attr('name','additional_point_5_kg');  
      //     }
      //   }
      // });

      var counter =1;
      var selected_to_array = [];
      // updateSelectedCites();
      // $('body').on('change', '#price_table .city_to', function(e) {
      //   updateSelectedCites($(this).closest('tr'));
      // })
      $('body').on('click','.add_row',function(e){
        e.preventDefault();
        var counter = $('#price_table > tbody tr').length;
        var row = $('#price_table > tbody tr').first().clone();
        row.find('input,select').each(function(){
          var name = $(this).attr('name').split('[0]');
          $(this).attr('name',name[0]+'['+counter+']'+name[1]);
        })
        row.find('.add_row').addClass('remove_row');
        row.find('.add_row').addClass('btn btn-danger');
        row.find('.fa-plus').addClass('fa-trash');
        row.find('.fa-plus').removeClass('fa-plus');
        row.find('.add_row').removeClass('add_row');
        $('#price_table').append(row);
        // updateSelectedCites();
      })
      $('body').on('click','.remove_row',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        // updateSelectedCites();
      })
    })
  </script>
