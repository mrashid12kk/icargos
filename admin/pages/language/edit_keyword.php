<?php
// echo $_SESSION['branch_id'];
// die();
    if(isset($_POST['addphrase'])){
        $keyword=mysqli_real_escape_string($con,$_POST['keyword']);
        $language_id=mysqli_real_escape_string($con,$_POST['language_id']);
        $translation=mysqli_real_escape_string($con,$_POST['translation']);

        $edit_id = mysqli_real_escape_string($con,$_POST['edit_id']);

        $query = "UPDATE `language_translator` set `keyword`='$keyword',`language_id`='$language_id',`translation`='$translation' WHERE id=$edit_id";
        // echo $query;
        // die();
        $query1=mysqli_query($con,$query) or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you update Phrase successfully</div>';
        /*  $query=mysqli_query($con,"select * from admin");
            $fetch=mysqli_fetch_array($query);
            $reciever=$fetch['email'];
            $subject = "Signup Request";
            $txt = "$user_name send a signup request to you please check the the details from admin panel";
            $headers = "From: $email" . "\r\n";
            mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/
            }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not update Phrase unsuccessfully.</div>';
        }
    }


    $user_roles_list = mysqli_query($con,"SELECT * FROM user_role order by id desc ");

        $id=$_GET['id'];
            $query1=mysqli_query($con,"SELECT DISTINCT(translation), keyword, language_id FROM language_translator where id=$id");
                $fetch1=mysqli_fetch_assoc($query1);

 $languages = mysqli_query($con,"SELECT * FROM portal_language");

?>

<div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
<div class="panel panel-default">
    <div class="panel-heading">Edit Keyword Phrase</div>
    <div class="panel-body">

        <form role="form"  action="" method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                <label  class="control-label">Keyword</label>
                <input type="text" class="form-control" name="keyword" placeholder="Enter Keyword" value="<?php echo $fetch1['keyword']; ?>">
                <div class="help-block with-errors "></div>
            </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Language</label>
                    <select name="language_id" class="form-control">
                        <option value="">Select</option>
                        <?php while ($row = mysqli_fetch_array($languages)) { ?>
                            <option value="<?php echo $row['id']; ?>" <?php if($fetch1['language_id']==$row['id']){echo 'Selected';} ?>> <?php echo $row['language'] ?> </option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors  "></div>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">Translation</label>
                    <input type="text" class="form-control emaill" name="translation" placeholder="Enter Translation" value="<?php echo $fetch1['translation']; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>


        </div>


        <input type="hidden" name="edit_id" value="<?php echo $_GET['id']; ?>">
        <button style="margin: 0 0 8px 0;" type="submit" name="addphrase" class="btn btn-purple editp add_form_btn" >Submit</button>
        </form>

    </div>
</div>
</div>
</div>
