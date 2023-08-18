<?php
function encrypt($string){
	$key="usmannnn";
	  $result = '';
	  for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	  }
	  return base64_encode($result);
	}
	$message = '';
	session_start();
	require 'includes/conn.php';
	$currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
	if(isset($_SESSION['users_id'])){
	include "includes/header.php";
	if(isset($_POST['update_order']) && isset($_GET['id'])) {
		$id = (int)$_GET['id'];
		$data = $_POST;
		unset($data['update_order']);
		$sql = "UPDATE orders SET";
		$index = 0;
		foreach ($data as $key => $value) {
			$sql .= " $key = '$value'";
			$index++;
			if($index != count($data))
				$sql .= ",";
		}
		$sql .= " WHERE id = $id";
		if(mysqli_query($con, $sql))
			{
				$collection_amount = (int)trim($data['collection_amount']);
				$price = (int)trim($data['price']);
				$order_data = mysqli_query($con,"SELECT track_no FROM orders WHERE id =".$id." ");
				$order_number_data = mysqli_fetch_array($order_data);
				$order_no = $order_number_data['track_no'];

				mysqli_query($con,"UPDATE ledger SET delivery_charges ='".$price."', collected_amount='".$collection_amount."' WHERE order_no=".$order_no." ");
				$message = '<div class="alert alert-success">Order is updated successfully!</div>';
				
			}else{
				$message = '<div class="alert alert-warning">Order is not updated!</div>';
			}
	}
if(isset($_POST['branch_id']) && $_POST['branch_id'] != '') {
	$branch_id = $_POST['branch_id'];
	$order_id = $_GET['id'];
	mysqli_query($con, "UPDATE orders SET branch_id = '".$branch_id."' WHERE id = '".$order_id."' ");
}
$id =(int)$_GET['id'];
$message_query = mysqli_query($con,"SELECT * FROM order_comments WHERE order_id =".$id." order by id   ");
$total_comments = mysqli_num_rows($message_query);



	$cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
?>
<body data-ng-app>
 	<style type="text/css">
 		#same_form_layout table tr th:last-child {
    width: auto !important;
}
 	@media(max-width: 767px ){
 		.container{
 			width: auto;
 		}
 		.content>.container-fluid {
		    padding-left: 5px;
		    padding-right: 6px;
		}
		table.detail a, table.detail select, table.detail input {
		    margin-bottom: 7px;
		    margin-right: 10px;
		}
		#same_form_layout{
			    padding: 0 !important;
		}
		.table-bordered {
		    border: 1px solid #ddd;
		    border-right: none;
		}
		.panel-body {
		    padding: 10px 8px;
		}
 	}
 	table tr th, table tr td {
    font-size: 18px !important;
    font-weight: 500 !important; 
    color: #6e6e71;
}
.form-input strong{
	width: 100px !important;
	    float: left;

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
        <style type="text/css">
        	table.detail a, table.detail select, table.detail input {
        		margin-right: 10px;
        	}
        </style>
        
        <div class="warper container-fluid">
        	 <div class="col-lg-12 col-md-12 col-sm-12 password">
      <table class="table table-bordered business_tbl" id="order_list_page">
   		<form action="" method="POST" enctype=""></form>
		   <tbody>
		      <tr>
		         <td><b>Order View-</b> 2010961591</td>
		         <td><b>Status:</b> Received at destination</td>
		         <td><b>BDM:</b> Noman Iqbal</td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b>Hub:</b> Hyderabad</td>
		         <td><b>Reference # :</b> Mustafa Traders</td>
		         <td><b>Created Date:</b> 03/10/2021 8:10 PM</td>
		         <td><a href="#">Print Order</a></td>
		      </tr>
		      <tr>
		         <td><b class="font-18">Business Name:</b> 247 Express</td>
		         <td></td>
		         <td></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b class="font-18">Pickup Address</b></td>
		         <td></td>
		         <td><b class="font-18">Delivery Address</b></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b>Name</b></td>
		         <td>247 Express</td>
		         <td><b>Name</b></td>
		         <td>Humair Ali</td>
		      </tr>
		      <tr>
		         <td><b>Address</b></td>
		         <td>s-96 amma Tower Saddar Mobile Market Karachi</td>
		         <td><b>Address</b></td>
		         <td>s-96 amma Tower Saddar Mobile Market Karachi</td>
		      </tr>
		      <tr>
		         <td><b>City</b></td>
		         <td>Karachi</td>
		         <td><b>City</b></td>
		         <td>Karachi</td>
		      </tr>
		      <tr>
		         <td><b>Email</b></td>
		         <td>zafir@gamil.com</td>
		         <td><b>Email</b></td>
		         <td>zafir@gamil.com</td>
		      </tr>
		      <tr>
		         <td><b>Phone</b></td>
		         <td>03215636985</td>
		         <td><b>Phone</b></td>
		         <td>03215636985</td>
		      </tr>
		      <tr>
		         <td><b class="font-18">Order Details</b></td>
		         <td></td>
		         <td></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td><b>Service Type</b></td>
		         <td>COD</td>
		         <td><b>No.of Pieces</b></td>
		         <td>1</td>
		      </tr>
		      <tr>
		         <td><b>Amount (COD)</b></td>
		         <td>2200.00</td>
		         <td><b>Weight(Kg)</b></td>
		         <td>0.4</td>
		      </tr>
		      <tr>
		         <td><b>Declared Value</b></td>
		         <td>2200</td>
		         <td><b>Length(In)</b></td>
		         <td>0.00</td>
		      </tr>
		      <tr>
		         <td><b>Item Detail</b></td>
		         <td>T500</td>
		         <td><b>Height(In)</b></td>
		         <td>0.00</td>
		      </tr>
		      <tr>
		         <td><b>Fragile</b></td>
		         <td>NO</td>
		         <td><b>Width(In)</b></td>
		         <td>0.00</td>
		      </tr>
		      <tr>
		         <td><b>Special Instructions</b></td>
		         <td></td>
		         <td><b>Delivery Charges (Incl. Tax)</b></td>
		         <td>101.70</td>
		      </tr>

		      <tr>
		         <td><b>Status</b></td>
		         <td><input type="text" class="form-control" name="" placeholder="Please enter comments..."></td>
		         <td></td>
		         <td></td>
		      </tr>

		      <tr>
		         <td><b class="font-18">Logs</b></td>
		         <td></td>
		         <td></td>
		         <td></td>
		      </tr>
		      <tr>
		         <td colspan="2"><b>Status changed to Received at destination</b></td>
		         <td  colspan="2">07 January 2021 10:29:45 AM</td>
		      </tr>
		      <tr>
		         <td colspan="2"><b>Status changed to In Transit to destination</b></td>
		         <td  colspan="2">07 January 2021 10:29:45 AM</td>
		      </tr>
		      <tr>
		         <td colspan="2"><b>Status changed to Received at office</b></td>
		         <td  colspan="2">07 January 2021 10:29:45 AM</td>
		      </tr>
		      

		      
		   </tbody>
</table>
  </div>
        </div>
      

        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>