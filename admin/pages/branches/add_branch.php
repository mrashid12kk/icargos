<?php
// echo $_SESSION['branch_id'];
// die();
    if(isset($_POST['addBranch'])){
        $querycount=mysqli_query($con,"SELECT COUNT(id) as branchid FROM branches");
        $mainnechawal=mysqli_fetch_assoc($querycount);
        $countbranch=$mainnechawal['branchid'];

        $branchlimit=getConfig('branch_limit');
      
        if($countbranch<$branchlimit){

            $name=mysqli_real_escape_string($con,$_POST['name']);
            $color=mysqli_real_escape_string($con,$_POST['color']);
            $code=mysqli_real_escape_string($con,$_POST['code']);
            $phone=mysqli_real_escape_string($con,$_POST['phone']);
            $branch_city=mysqli_real_escape_string($con,$_POST['branch_city']);
            $branch_origin=$_POST['branch_origin'];

            $branch_origin = '';
            foreach ($_POST['branch_origin'] as $key => $value) {
               $branch_origin .= $value.",";
            }
            $getids=rtrim($branch_origin, ',');

            $query = "INSERT INTO `branches`(`name`,`color`,`code`,`phone`,`branch_city`,`branch_origin`) VALUES ('$name','$color','$code','$phone','$branch_city','$getids')";
            $query1=mysqli_query($con,$query) or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);
            if($rowscount>0){
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new branch successfully</div>';
                }
            else{
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new branch unsuccessfully.</div>';
            }
        }else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have reached the maximum Branch limit . You can create maximum of ( '.$branchlimit.' ) Branches</div>';
        }

    }

    $city_q = mysqli_query($con,"SELECT * FROM `cities`");
    $origin_q = mysqli_query($con,"SELECT * FROM `cities`");




?>
<div class="panel panel-default">
<div class="panel-heading"><?php echo getLange('addnewbranch'); ?>       <a href="branch_list.php" class="add_form_btn" style="float: right;font-size: 11px;"><i class="fa fa-list"></i> &nbsp;&nbsp; <?php echo getLange('branchlist'); ?> </a>
    </div>

   <?php


    ?>


    <div class="panel-body">

        <form role="form"  action="" method="post">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                <label  class="control-label"><?php echo getLange('branchname'); ?></label>
                <input type="text" class="form-control" name="name" placeholder="<?php echo getLange('enter').' '.getLange('branchname'); ?>" required>
                <div class="help-block with-errors "></div>
            </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Branch Phone No</label>
                    <input type="text" class="form-control" name="phone" placeholder="<?php echo getLange('enter').' '.getLange('phone'); ?>" >
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('branchcode'); ?></label>
                    <input type="text" class="form-control" name="code" placeholder="<?php echo getLange('enter').' '.getLange('branchcode'); ?>" >
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>



            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('color'); ?></label>
                    <input type="color" class="form-control " name="color" value="#000000" style="width: 41px; padding: 0 !important;">
                    <div class="help-block with-errors  "></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('branchcity'); ?></label>
                    <select class="form-control js-example-basic-single" name="branch_city">
                            <?php while ($row = mysqli_fetch_assoc($city_q)) {  ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['city_name']; ?></option>
                            <?php   } ?>

                    </select>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>

            <div class="col-sm-4 ">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('origin'); ?></label>
                    <select class="form-control js-example-basic-multiple" name="branch_origin[]" multiple required>
                            <?php while ($row = mysqli_fetch_assoc($origin_q)) {  ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['city_name']; ?></option>
                            <?php   } ?>

                    </select>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>
        </div>




        <button style="margin: 0 0 8px 0;" type="submit" name="addBranch" class="add_form_btn" ><?php echo getLange('submit'); ?></button>
        </form>

    </div>
</div>
