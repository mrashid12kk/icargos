<?php
	session_start();
	include_once "includes/conn.php";
 function decrypt($string) {
  $key="usmannnn";
    $result = '';
    $string = base64_decode($string);
    for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
    }
    return $result;
  }



    if(isset($_GET['template']))
    {
        mysqli_query($con,"DELETE FROM sms_templates WHERE id=".$_GET['template']." ");
        if(mysqli_affected_rows($con)){
         $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Template Is Deleted Sucessfully.</div>';
        }else{
             $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Template Is Not Deleted Unsucessfully.</div>';
        }
        header('Location: templates.php');
    
    }
    if(isset($_POST['delete_email_all']))
    {
      
       $invoice_ids = explode(',',$_POST['all_email']);
       foreach($invoice_ids as $key => $id_delete)
        {
            mysqli_query($con,"DELETE FROM email_detail WHERE id=".$id_delete." ");
          if(mysqli_affected_rows($con)){
          $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Emails Is Deleted Sucessfully.</div>';
          }else{
               $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Emails Is Not Deleted Unsucessfully.</div>';
          }
        }
        header('Location: sent_list_email.php');
    }

    if(isset($_GET['sms_id']))
    {
        mysqli_query($con,"DELETE FROM sms_detail WHERE id=".$_GET['sms_id']." ");
        if(mysqli_affected_rows($con)){
         $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> SMS Is Deleted Sucessfully.</div>';
        }else{
             $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> SMS Is Not Deleted Unsucessfully.</div>';
        }
        header('Location: sent_list.php');
    
    }
    if(isset($_GET['email_id']))
    {
        mysqli_query($con,"DELETE FROM email_detail WHERE id=".$_GET['email_id']." ");
        if(mysqli_affected_rows($con)){
         $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> EMAIL Is Deleted Sucessfully.</div>';
        }else{
             $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> EMAIL Is Not Deleted Unsucessfully.</div>';
        }
        header('Location: sent_list_email.php');
    
    }
    


    if(isset($_GET['template_email']))
    {
        mysqli_query($con,"DELETE FROM email_templates WHERE id=".$_GET['template_email']." ");
        if(mysqli_affected_rows($con)){
         $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Template Is Deleted Sucessfully.</div>';
        }else{
             $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Template Is Not Deleted Unsucessfully.</div>';
        }
        header('Location: templates_email.php');
    
    }
    
  ?>