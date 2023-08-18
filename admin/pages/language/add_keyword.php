<?php
// echo $_SESSION['branch_id'];
// die();
    if(isset($_POST['addphrase'])){
        $keyword=mysqli_real_escape_string($con,$_POST['keyword']);
        $language_id=mysqli_real_escape_string($con,$_POST['language_id']);
        $translation=mysqli_real_escape_string($con,$_POST['translation']);

        $query = "INSERT INTO `language_translator`(`keyword`,`language_id`,`translation`) VALUES ('$keyword',$language_id,'$translation')";
        // echo $query;
        // die();
        $query1=mysqli_query($con,$query) or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Phrase successfully</div>';
        /*  $query=mysqli_query($con,"select * from admin");
            $fetch=mysqli_fetch_array($query);
            $reciever=$fetch['email'];
            $subject = "Signup Request";
            $txt = "$user_name send a signup request to you please check the the details from admin panel";
            $headers = "From: $email" . "\r\n";
            mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/
            }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Phrase unsuccessfully.</div>';
        }
    }


    $user_roles_list = mysqli_query($con,"SELECT * FROM user_role order by id desc ");
    $languages = mysqli_query($con,"SELECT * FROM portal_language");
?>
<div class="panel panel-default">
    <div class="panel-heading">Add Keyword Phrase</div>
    <div class="panel-body">

        <form role="form"  action="" method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                <label  class="control-label">Keyword</label>
                <input type="text" class="form-control" name="keyword" placeholder="Enter Keyword" required>
                <div class="help-block with-errors "></div>
            </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Language</label>
                    <select name="language_id" class="form-control">
                        <option value="">Select</option>
                        <?php while ($row = mysqli_fetch_array($languages)) { ?>
                            <option value="<?php echo $row['id'] ?>"> <?php echo $row['language'] ?> </option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors  "></div>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Translation</label>
                    <input type="text" class="form-control emaill" name="translation" placeholder="Enter Translation" >
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>


        </div>



        <button style="margin: 0 0 8px 15px;" type="submit" name="addphrase" class="btn btn-purple editp" >Submit</button>
        </form>

    </div>
</div>
