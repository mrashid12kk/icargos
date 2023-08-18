<?php

    if(isset($_POST['delete'])){

        $id=mysqli_real_escape_string($con,$_POST['id']);


        $query1=mysqli_query($con,"Delete from language_translator where id=$id") or die(mysqli_error($con));

        $rowscount=mysqli_affected_rows($con);

        if($rowscount>0){

            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a language keyword successfully</div>';

        }

        else{

            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete language keyword unsuccessfully.</div>';

        }

    }

    function getLangName($id)
 {
    global $con;
    $branchQ = mysqli_query($con, "SELECT language from portal_language where id = $id");

    $res = mysqli_fetch_array($branchQ);

    return $res['language'];
 }
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
    if(isset($_POST['updatedata'])){
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
     if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
            $id=$_GET['edit_id'];
            $query1=mysqli_query($con,"SELECT DISTINCT(translation), keyword, language_id FROM language_translator where id=$id");
                $edit=mysqli_fetch_assoc($query1);
              
        }

    $user_roles_list = mysqli_query($con,"SELECT * FROM user_role order by id desc ");
    $languages = mysqli_query($con,"SELECT * FROM portal_language");
?>


<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('addkeywordphrase'); ?></div>
    <div class="panel-body">

        <form role="form"  action="" method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                <label  class="control-label"><?php echo getLange('Keyword'); ?></label>
                <input type="text" class="form-control" name="keyword" value="<?php if(isset($edit)){echo $edit['keyword']; } ?>" placeholder="<?php echo getLange('enter').' '.getLange('Keyword'); ?>" required>
                <div class="help-block with-errors "></div>
            </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('language'); ?></label>
                    <select name="language_id" class="form-control">
                        <option value=""><?php echo getLange('select'); ?></option>
                        <?php while ($row = mysqli_fetch_array($languages)) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php if(isset($edit) && $edit['language_id']==$row['id']){echo 'Selected';} ?>> <?php echo $row['language'] ?> </option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors  "></div>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange('Translation'); ?></label>
                    <input type="text" class="form-control emaill" name="translation" placeholder="<?php echo getLange('enter').' '.getLange('Translation'); ?>" value="<?php if(isset($edit)){echo $edit['translation']; } ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
            </div>


        </div>
        <input type="hidden" name="<?php if(isset($edit)){echo 'edit_id'; } ?>" value="<?php if(isset($edit)){echo $_GET['edit_id']; } ?>">


        <button style="margin: 0 0 8px 3px;" type="submit" name="<?php if(isset($edit)){echo 'updatedata'; }else{ echo 'addphrase';} ?>" class="add_form_btn" ><?php echo getLange('submit'); ?></button>
        </form>

    </div>
</div>

<div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange('allkeyword'); ?>
        
    </div>



        <div class="panel-body" id="same_form_layout" style="padding: 11px;">

            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

               

                    

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">

                            <thead>

                                <tr >
                                   <th><?php echo getLange('srno'); ?></th>
                                   <th><?php echo getLange('language'); ?></th>
                                   <th><?php echo getLange('Keyword'); ?></th>
                                   <th><?php echo getLange('Translation'); ?></th>
                                   <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php
                                $query1=mysqli_query($con,"SELECT DISTINCT(translation), keyword, id, language_id FROM language_translator ");


                                $sr=1;
                                while($fetch1=mysqli_fetch_array($query1)){


                            ?>



                                <tr>

                                    <td><?php echo $sr; ?></td>
                                    <td><?php echo getLangName($fetch1['language_id']); ?></td>

                                    <td>  <?php echo $fetch1['keyword']; ?> </td>
                                    <td><?php echo $fetch1['translation']; ?></td>

                                    <td class="center">

                                        <a href="language_keyword.php?edit_id=<?php echo $fetch1['id']; ?>"   >
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </a>



                                        <form action="" method="post" style="display: inline-block;">

                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                            <button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are You Sure Delete this keyword')" >

                                              <span class="glyphicon glyphicon-trash"></span>

                                            </button>

                                        </form>

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
