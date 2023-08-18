<?php

        if(isset($_POST['addcompany'])){



            $mode_id=mysqli_real_escape_string($con,$_POST['mode_id']);

            $company_name=mysqli_real_escape_string($con,$_POST['company_name']);

            $query2=mysqli_query($con,"INSERT into `transport_company`(name,mode_id)values('$company_name',$mode_id)") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);
            if($query2){
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a Transport company successfully</div>';
            }
            else{
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a Transport company unsuccessfully.</div>';
            }

        }

        $sql= "SELECT * From modes ";

        $allmodes = mysqli_query($con,$sql);


?>

<div class="panel panel-default">

    <div class="panel-heading">Add Transport Company</div>

    <div class="panel-body">



        <form role="form" class="" data-toggle="validator" action="" method="post">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label">Company Name</label>
                        <input type="text"  class="form-control" name="company_name" value="" placeholder="Enter City name" required>

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

         <button type="submit" name="addcompany" class="btn btn-purple" >Add</button>

        </form>



    </div>

</div>

<?php



?>
