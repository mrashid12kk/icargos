<?php

        if(isset($_POST['updatecompany'])){



            $id=mysqli_real_escape_string($con,$_POST['id']);
            $mode_id=mysqli_real_escape_string($con,$_POST['mode_id']);

            $company_name=mysqli_real_escape_string($con,$_POST['company_name']);

            $query2=mysqli_query($con,"update transport_company set name='$company_name', mode_id = $mode_id where id=$id") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);



            if($query2){

                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Transport company successfully</div>';

            }

            else{

                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Transport company unsuccessfully.</div>';

            }

        }

        $sql= "SELECT * From modes ";

        $allmodes = mysqli_query($con,$sql);

    if(isset($_POST['id'])){

        $id=mysqli_real_escape_string($con,$_POST['id']);

        $query1=mysqli_query($con,"select * from transport_company where id=$id") or die(mysqli_error($con));

        $fetch1=mysqli_fetch_array($query1);

    }

?>


<div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
            
<div class="panel panel-default">

    <div class="panel-heading">Edit Transport Company</div>

    <div class="panel-body">



        <form role="form" class="" data-toggle="validator" action="" method="post">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label">Company Name</label>
                        <input type="text"  class="form-control" name="company_name" value="<?php echo isset($fetch1['name'])?$fetch1['name']:""; ?>" placeholder="Enter City name" required>

                        <div class="help-block with-errors "></div>

                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label">Mode</label>

                        <select name="mode_id" class="form-control">
                            <?php while ($row = mysqli_fetch_array($allmodes)) { ?>

                                <option <?php if ($row['id']==$fetch1['mode_id']): ?>
                                    selected
                                <?php endif ?> value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
                            <?php } ?>

                        </select>

                        <div class="help-block with-errors "></div>

                    </div>
                </div>
            </div>



                <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

         <button type="submit" name="updatecompany" class="btn btn-purple add_form_btn" >Update</button>

        </form>



    </div>

</div>
</div>
</div>

<?php



?>
