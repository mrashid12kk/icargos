<?php
session_start();
require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 17, 'view_only', $comment = null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";

?>

<body data-ng-app>
    <style type="text/css">
    	#width_44{
    		width: 55% !important;
    	}
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

            <div class="page-header">
                <h1><?php echo getLange('servicelist'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
            </div>
            <div class="row">
               
                <?php include "pages/location/location_sidebar.php"; ?>
                <div class="col-sm-10 table-responsive" id="setting_box">
                    <?php
                        $msg = "";
                        if (isset($_GET['delete_id'])) {
                            $id = $_GET['delete_id'];
                            $query1 = mysqli_query($con, "DELETE from state where id=$id") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong>You delete a State Successfully</div>';
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong>Ypu cannot delete State unsuccessfully.</div>';
                            }
                        }
                        if(isset($_POST['add_city'])){
                            $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

                            $state_id=mysqli_real_escape_string($con,$_POST['state_id']);

                            $city_name=mysqli_real_escape_string($con,$_POST['city_name']);

                            $title=mysqli_real_escape_string($con,$_POST['title']);

                            $description=mysqli_real_escape_string($con,$_POST['description']);

                            $keyword=mysqli_real_escape_string($con,$_POST['keyword']);
                            
                            $query2=mysqli_query($con,"INSERT into `city`(country_id,state_id,city_name,title,description,keyword,created_on)values('$country_id','$state_id','$city_name','$title','$description','$keyword','$date')") or die(mysqli_error($con));
                            $rowscount=mysqli_affected_rows($con);
                            if($query2){
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Add a New Country successfully</div>';
                            }
                            else{
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Country unsuccessfully.</div>';
                            }
                        }
                       if(isset($_POST['update_city'])){
                               $id=$_GET['edit_id'];

                               $country_id=mysqli_real_escape_string($con,$_POST['country_id']);

                               $state_id=mysqli_real_escape_string($con,$_POST['state_id']);

                               $city_name=mysqli_real_escape_string($con,$_POST['city_name']);

                               $title=mysqli_real_escape_string($con,$_POST['title']);

                               $description=mysqli_real_escape_string($con,$_POST['description']);

                               $keyword=mysqli_real_escape_string($con,$_POST['keyword']);
                            
                                  $query2=mysqli_query($con,"UPDATE `city` set country_id='$country_id',state_id='$state_id',city_name= '$city_name',title= '$title',description= '$description',keyword= '$keyword' where id=$id") or die(mysqli_error($con));
                                  $rowscount=mysqli_affected_rows($con);
                                  if($query2){
                                      echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Updated  City Successfully</div>';
                                  }
                                  else{
                                      echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated City UnSuccessfully.</div>';
                                  }
                              }
                          $country=mysqli_query($con,"SELECT * from country order by id desc");
                          $state=mysqli_query($con,"SELECT * from state order by id desc");

                         if (isset($_GET['edit_id']) && $_GET['edit_id']!='') {
                             $edit=mysqli_fetch_assoc(mysqli_query($con,"SELECT * from city WHERE id=".$_GET['edit_id']));
                          }
                        ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">City</div>
                        <div class="panel-body" id="same_form_layout">
                            
                            <form role="form" data-toggle="validator" action="state.php<?php echo isset($_GET['edit_id']) ? '?edit_id='.$_GET['edit_id'] : ''; ?>" method="post"  enctype="multipart/form-data">
                                <div id="cities">

                                    <div class="row" id="select_citites">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Country Name</label>
                                                <select type="text" class="select2 country" required name="country_id">
                                                     <option value="" disabled selected>Select Country</option>
                                                     <?php 
                                                     while ($row=mysqli_fetch_array($country)) {
                                                        $selected=isset($edit['country_id']) && $edit['country_id']==$row['id'] ? 'selected' : '';
                                                        echo "<option value='".$row['id']."' ".$selected.">".$row['country_name']."</option>";
                                                     }
                                                      ?>
                                                  </select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">State Name</label>
                                               <div class="get_state">
                                                 <select type="text" class="select2 state" required name="state_id" autocomplete="off" >
                                                    <option value="" disabled selected>Select State</option>
                                                     <?php 
                                                 while ($row=mysqli_fetch_array($state)) {

                                                    $selected=isset($edit['state_id']) && $edit['state_id']==$row['id'] ? 'selected' : '';
                                                    echo "<option value='".$row['id']."' ".$selected.">".$row['state_name']."</option>";
                                                 }
                                                  ?>
                                                 </select> 
                                              </div>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">City name</label>
                                                <input type="text" class="form-control" name="city_name" value="<?php echo isset($edit['city_name']) ? $edit['city_name'] : ''; ?>" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Title</label>
                                                <input type="text" class="form-control" name="title" value="<?php echo isset($edit['title']) ? $edit['title'] : ''; ?>" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 rtl_full">
                                            <button type="submit" name="<?php if (isset($edit)) {echo 'update_city';} else {echo 'add_city';} ?>" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">City

                        </div>
                        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                            <div id="basic-datatable_wrapper"
                                class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                                <table cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped table-bordered dataTable no-footer" id="basic-datatable"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 2%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;"><?php echo getLange('srno'); ?></th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Country Name</th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">State Name </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">City </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Title </th>


                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                                colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                                style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query1 = mysqli_query($con, "SELECT country.country_name,cities.city_name,country.image,state.state_name,cities.title,cities.id FROM cities LEFT JOIN country on cities.country_id=country.id LEFT JOIN state on cities.state_id=state.id ORDER BY id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['state_name']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['country_name']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['city_name']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['title']; ?></td>
                                            <td class="center">
                                                <a href="city.php?edit_id=<?php echo $fetch1['id']; ?>">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                <a href="city.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this State ?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                                $sr++;
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
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
        $('.select2').select2();
        </script>
        <script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
$('body').on('change','.country',function(e){
    e.preventDefault();
    var country_id=$(this).val();
          $.ajax({
          type:'POST',
          data:{country_id:country_id,get_country:1},
          url:'ajax.php',
          success:function(response){
          $('.get_state').html('');
          $('.get_state').html(response);
          $('.js-example-basic-single').select2();
          }
          });
     })
}, false);
</script>