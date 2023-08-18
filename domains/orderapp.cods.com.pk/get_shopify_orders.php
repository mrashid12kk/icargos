<?php
	session_start();
	$access_token = $_SESSION['access_token'];
	$shop_url = $_SESSION['shop_url'];
	include_once("inc/conn.php");
	require_once("inc/constants.php");
	require_once("inc/functions.php");
	$shop_1 = SHOP_NAME;
	$token = $access_token;
	$shop = SHOP_NAME;
	$array = '';
	if(isset($_POST['is_request']))
	{
	  function recursiveFunctionGetOrder($url=null)
	  {
	    if($url!=null)
	    {
	      $shop_1 = SHOP_NAME;
	      $access_token = $_SESSION['access_token'];
	      $explode_url = explode('?', $url);
	      $explode_again_url = explode('&', $explode_url[1]);
	      $explode_again1_url = explode('=', $explode_again_url[1]);
	      $page_info = $explode_again1_url[1];
	      $collects_r = shopify_call_get_orders($access_token,$shop_1 ,"/admin/api/".API_DATE."/orders.json", array('page_info'=>$page_info,'limit'=>100), 'GET');
	      return $collects_r;
	    }
	  }
	  function orderList($collects_data = array())
	  {
	    $ordersList = json_decode($collects_data['response'], true);
	    $headers_parameters = $collects_data['headers'];
	    if(isset($ordersList['orders']) && !empty($ordersList['orders']))
	    {
	      foreach($ordersList['orders'] as $key=>$order)
	      {
	          $row_data = [];
	          $order_id = $order['id'];
	          $name = $order['name'];
	          $customer = $order['customer'];
	          $customer_name  = isset($customer['first_name']) ? $customer['first_name']:'';
	          if(isset($customer['last_name']))
	          {
	            $customer_name.= isset($customer['last_name']) ? ' '.$customer['last_name']:'';
	          }
	          $row_data['order_id'] = $order_id;
	          $row_data['name'] = $name;
	          $row_data['customer_name'] = $customer_name;
	          $row_data['total_price'] = $order['total_price'];
	          $row_data['financial_status'] = $order['financial_status'];
	          $row_data['date'] = isset($order['created_at']) ? date('Y-m-d',strtotime($order['created_at'])) : '';
	          $line_items = $order['line_items'];
	          $title = '';
	          if(isset($line_items) && !empty($line_items))
	          { 
	            foreach($line_items as $key1=> $item)
	            {
	              if($key1 == 0)
	              {
	                $title.=isset($item['title'])  ? $item['title']:'';
	              }
	              else
	              {
	                $title.=isset($item['title'])  ? ' '.$item['title']:'';
	              }
	            }
	          }
	          $row_data['title'] = $title;
	          if (!isset($_SESSION['return_data'])) {
	              $_SESSION['return_data'] = array();
	              $_SESSION['set_order_id'] = array();
	          }
	          if(!in_array($order_id,$_SESSION['set_order_id']))
	          {
	            array_push($_SESSION['set_order_id'], $order_id);
	            array_push($_SESSION['return_data'], $row_data);
	          }
	      }
	    }
	    $headers_link = '';
	    if(isset($headers_parameters['link']) && $headers_parameters['link'])
	    {
	      $headers_link=$headers_parameters['link'];
	    }
	    return array('headers_link'=>$headers_link,'orders_data'=>$_SESSION['return_data']);
	  }
	  $shop_1 = SHOP_NAME;
	  $access_token = $_SESSION['access_token'];
	  $next_page_url = isset($_POST['param1']) ? $_POST['param1']:'';
	  if($next_page_url)
	  {
	    $collects = shopify_call_page_info($access_token,$next_page_url,array(),'GET');
	  }
	  else
	  {
	    $collects = shopify_call_get_orders($access_token,$shop_1 ,"/admin/api/".API_DATE."/orders.json", array('limit'=>250,'fulfillment_status'=>'unfulfilled'), 'GET');
	  }
	  $order_data['data'] = orderList($collects);
	  $return_array_data['headers_link'] = isset($order_data['data']['headers_link']) ? $order_data['data']['headers_link']:'';
	  $table = '';
	  if(!empty($_SESSION['return_data']))
	  {
	    $table = '<table id="example" class="table table-striped table-bordered orders_tbl" style="width:100%">';
	    $table.= '<thead>';
	      $table.= '<tr>';
	        $table.= '<th><input type="checkbox" name="" class="main_select"></th>';
	        $table.= '<th>Order</th>';
	        $table.= '<th>Date</th>';
	        $table.= '<th>Customer</th>';
	        $table.= '<th>City</th>';
	        $table.= '<th>Total</th>';
	        $table.= '<th>Items</th>';
	        $table.= '<th>Shopify status</th>';
	      $table.= '</tr>';
	    $table.= '</thead>';
	    $table.= '<tbody>';
	    foreach($_SESSION['return_data'] as $key=>$row)
	    {
			$order_id = $row['order_id'];
			$collects = shopify_call($token, $shop, "/admin/api/" . API_DATE . "/orders/" . $order_id . ".json", $array, 'GET');
			$orderInfo = json_decode($collects['response'], JSON_PRETTY_PRINT);
			$orderInfo = $orderInfo['order'];		
			$shipping_detail = $orderInfo['shipping_address'];			
	      $table.= '<tr>';
	        $table.= '<td><input type="checkbox" name="" class="order_check" value="'.$row['order_id'].'"></td>';
	        $table.= '<td>'.$row['name'].'</td>';
	        $table.= '<td>'.$row['date'].'</td>';
	        $table.= '<td>'.$row['customer_name'].'</td>';
	        $table.= '<td>'.$shipping_detail['city'].'</td>';
	        $table.= '<td>'.$row['total_price'].'</td>';
	        $table.= '<td>'.$row['title'].'</td>';
	        $table.= '<td>'.$row['financial_status'].'</td>';
	      $table.= '</tr>';
	    }
	    $table.= '</tbody>';
	    $table.='</table>';
	  }
	  $return_array_data['table_data'] = $table;
	  echo json_encode($return_array_data);exit(); 
	}
