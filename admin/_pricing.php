<style>
  .panel-primary > .panel-heading, .panel-primary > .panel-footer {
    border-color: #1e2c59 !important;
    background-color: #1e2c59 !important;
    box-shadow: none !important;
}
</style>
 

<?php
  session_start(); 
  require 'includes/conn.php';
  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
  include "includes/header.php";
  $cities_from = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
  $cod_pricing = mysqli_query($con,"SELECT * FROM cod_pricing WHERE 1 ");
  $overlong_pricing = mysqli_query($con,"SELECT * FROM overlong_pricing WHERE 1 ");
  $cities_to = mysqli_query($con,"SELECT * FROM cities WHERE 1  order by id desc ");
  $origin_city = mysqli_query($con,"SELECT * FROM cities WHERE city_name='Lahore' ");
  $origin_city_rec = mysqli_fetch_array($origin_city);
?>
<body data-ng-app>
  
    
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
          
            <div class="panel panel-primary" style="margin-bottom: 11px;">
              <div class="panel-heading">COD Pricing</div>
              <div class="panel-body" id="same_form_layout" style="padding: 10px;">
                <form method="POST" action="cod_pricing.php">
          <div class="table-responsive">
            <table class="table table-bordered ">
                  <thead>
                  <tr>
                    <th>City From</th>
                    <th>City To</th>
                    <th>0.5 KG</th>
                    <th>Upto 1 KG</th>
                    <th>Upto 2 KG</th>
                    <th>Other KG's</th>
                  </tr>
                </thead>
                  <tbody>
                  <?php while($row = mysqli_fetch_array($cod_pricing)){
                  $city_to = $row['city_to'];
                   ?>
                      <tr>
                      <td>
                        <b>Lahore</b>
                      </td>
                      <td>
                         <b><?php echo $row['city_to']; ?></b>
                         <input type="hidden"  name="prices[<?php echo $city_to; ?>][city_to]" value="<?php echo $row['city_to']; ?>" >
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" name="prices[<?php echo $city_to; ?>][point_5_kg]" value="<?php echo $row['point_5_kg']; ?>" class="form-control allownumericwithdecimal"  >
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" name="prices[<?php echo $city_to; ?>][upto_1_kg]" value="<?php echo $row['upto_1_kg']; ?>" class="form-control allownumericwithdecimal"  >
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" name="prices[<?php echo $city_to; ?>][upto_2_kg]" value="<?php echo $row['upto_2_kg']; ?>" class="form-control allownumericwithdecimal"  >
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" value="<?php echo $row['other_kg']; ?>" name="prices[<?php echo $city_to; ?>][other_kg]" class="form-control allownumericwithdecimal"  >
                        </div>
                      </td>
                    </tr>
                  <?php } ?>

                    
                  </tbody>
               </table>
          </div>
                  <input type="submit" name="submit_cod" class="btn btn-info" value="Save">                 
                </form>
              </div>
            </div>
            <div class="panel panel-primary">
              <div class="panel-heading">Overland Pricing</div>
              <div class="panel-body" id="same_form_layout" style="padding: 10px;">
               <div class="col-md-6 sidegapp-none" style="padding: 0;">
                 <form method="POST" action="cod_pricing.php">
                 <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th>City From</th>
                    <th>City To</th>
                    <th>Price Per KG</th>
                  </tr>
                </thead>
                  <tbody>
                     <?php while($row = mysqli_fetch_array($overlong_pricing)){
                  $city_to = $row['city_to'];
                   ?>
                      <tr>
                      <td>
                        <b>Lahore</b>
                      </td>
                      <td>
                         <b><?php echo $row['city_to']; ?></b>
                         <input type="hidden"  name="prices[<?php echo $city_to; ?>][city_to]" value="<?php echo $row['city_to']; ?>">
                      </td>
                     
                      <td>
                        <div class="form-group">
                          <input type="text" value="<?php echo $row['price_per_kg']; ?>" name="prices[<?php echo $city_to; ?>][price_per_kg]" class="form-control allownumericwithdecimal"  >
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                     
                  </tbody>
               </table>
                  <input type="submit" name="submit_overlong" class="btn btn-info" value="Save">
               </form>
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
      var counter =1;
      $('body').on('click','.add_row',function(e){
        e.preventDefault();
        var counter = $('#price_table tr').length;
        var row = $('#price_table tr').first().clone();
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
      })
      $('body').on('click','.remove_row',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
      })
    })
  </script>