<?php
include_once('conn.php');

if (!function_exists('checkRolePermission')){

	function checkRolePermission($module_id,$condition,$comment =null)
	{

		global $con;
		if (isset($_SESSION['user_customer_id']) && $_SESSION['user_customer_id']!='') {
			
			$user_role_id=$_SESSION['customers'];

			$query = mysqli_query($con,"SELECT * FROM customer_permissions where role_id=".$user_role_id."  AND module_id=".$module_id);
			// echo "SELECT * FROM customer_permissions where role_id=".$user_role_id."  AND module_id=".$module_id;
			// die;
			$fetch = (object) mysqli_fetch_assoc($query);

			$output = false;

			if ($condition == 'crud')
			{
				if (isset($fetch->view_id) AND $fetch->view_id == 1 AND  $fetch->add_id == 1  AND $fetch->edit_id == 1 AND $fetch->delete_id == 1 )
				{
					$output = true;
				}
			}else if ($condition == 'view_only')
			{
				if (isset($fetch->view_id) AND $fetch->view_id == 1 )
				{
					$output = true;
				}
			}else if ($condition == 'add_only')
			{
				if (isset($fetch->add_id) AND $fetch->add_id == 1 )
				{
					$output = true;
				}
			}else if ($condition == 'edit_only')
			{
				if (isset($fetch->edit_id) AND $fetch->edit_id == 1 )
				{
					$output = true;
				}
			}else if ($condition == 'delete_only')
			{
				if (isset($fetch->delete_id) AND $fetch->delete_id == 1 )
				{
					$output = true;
				}
			}
			return $output;
		}
		else{
			$output = true;
			return $output;
		}
	}
}


?>
