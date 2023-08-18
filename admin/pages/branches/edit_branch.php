<?php

        if(isset($_POST['editBranch'])){

            $id=$_GET['id'];

            $name=mysqli_real_escape_string($con,$_POST['name']);

            $color=mysqli_real_escape_string($con,$_POST['color']);

            $code=mysqli_real_escape_string($con,$_POST['code']);

            $phone=mysqli_real_escape_string($con,$_POST['phone']);
              $branch_city=mysqli_real_escape_string($con,$_POST['branch_city']);

                $branch_origin = '';
                foreach ($_POST['branch_origin'] as $key => $value) {
                   $branch_origin .= $value.",";
                }
            $getids=rtrim($branch_origin, ',');
            $query2=mysqli_query($con,"update branches set name='$name',color='$color',code='$code',phone='$phone',branch_city='$branch_city',branch_origin='$getids' where id=$id") or die(mysqli_error($con));

            $rowscount=mysqli_affected_rows($con);

            if($rowscount>0){

                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a branch successfully</div>';

                echo "<script>document.location.href='branch_list.php';</script>";

            }

            else{

                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a barnch unsuccessfully.</div>';

            }

        }

    $user_roles_list = mysqli_query($con,"SELECT * FROM user_role order by id desc ");
    $branches = mysqli_query($con,"SELECT * FROM branches");

    if(isset($_GET['id'])){
        $id= $_GET['id'];

        $query1=mysqli_query($con,"select * from branches where id=$id") or die(mysqli_error($con));

        $fetch1=mysqli_fetch_array($query1);
    }
   $city_q = mysqli_query($con,"SELECT * FROM `cities`");
    $origin_q = mysqli_query($con,"SELECT * FROM `cities`");

?>

<div class="panel panel-default">


    <div class="panel-heading"><?php echo getLange('updatebranch'); ?>
        <a href="branch_list.php" class="btn btn-info btn-sm pull-right" style="margin-top:-5px;background-color: #2b86e4 !important;" ><i class="fa fa-list"></i> &nbsp;&nbsp; <?php echo getLange('branchlist'); ?></a>
    </div>


    <div class="panel-body">

        <form role="form"  action="" method="post">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                <label  class="control-label"><?php echo getLange('branchname'); ?></label>
                <input type="text" class="form-control" name="name" placeholder="Enter Branch Name" required value="<?php echo $fetch1['name'] ?>">
                <div class="help-block with-errors "></div>
            </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Branch Phone</label>
                    <input type="text" class="form-control" name="phone" placeholder="Enter Phone No" value="<?php echo $fetch1['phone'] ?>" >
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('branchcode'); ?></label>
                    <input type="text" class="form-control" name="code" placeholder="Enter Branch Code" value="<?php echo $fetch1['code'] ?>" >
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('color'); ?></label>
                    <input type="color" class="form-control " name="color" value="<?php echo $fetch1['color'] ?>"  style="width: 41px; padding: 0 !important;">
                    <div class="help-block with-errors  "></div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-6">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('branchcity'); ?></label>
                    <select class="form-control js-example-basic-single" name="branch_city">
                            <?php while ($row = mysqli_fetch_assoc($city_q)) {  ?>
                                <option value="<?php echo $row['id']; ?>" <?php if($fetch1['branch_city']==$row['id']){echo 'Selected';} ?>><?php echo $row['city_name']; ?></option>
                            <?php   } ?>

                    </select>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('origin'); ?></label>
                    <select class="form-control js-example-basic-single" name="branch_origin[]" multiple required>
                             <?php

                                                $fetch=explode(",", $fetch1['branch_origin']);
                                                while($fetch1=mysqli_fetch_array($origin_q)){

                                                  if(in_array($fetch1['id'] , $fetch)){
                                                    $selected="Selected";
                                                  }
                                                  else{
                                                     $selected="";
                                                  }
                                                  ?>

                                                  <option <?php echo $selected; ?> value="<?php echo $fetch1['id'] ?>"><?php echo $fetch1['city_name']; ?></option>
                                            <?php } ?>

                    </select>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>
            </div>
        <hr />



        <button style="margin: 0 0 8px 15px;" type="submit" name="editBranch" class="btn btn-purple editp" ><?php echo getLange('submit'); ?></button>
        </form>

    </div>
</div>

<?php



?>
