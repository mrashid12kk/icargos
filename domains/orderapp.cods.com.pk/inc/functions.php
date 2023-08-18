<?php
function shopify_call_page_info($token, $url,$query = array(),$method='GET') {
	// $url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
	// if (!is_null($query) && in_array($method, array('GET', 	'DELETE'))) $url = $url . "?" . http_build_query($query);
	$url = str_replace('https//', 'https://', $url);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
	curl_setopt($curl, CURLOPT_SSLVERSION, 3);
	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	// Setup headers
	$request_headers[] = "";
	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

	// if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
	// 	if (is_array($query)) $query = http_build_query($query);
	// 	curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	// }
	$response = curl_exec($curl);
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);
	curl_close($curl);
	if ($error_number) {
		return $error_message;
	} else {
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
		array_shift($header_data); // Remove status, we've already set it above
		foreach($header_data as $part) {
			$h = explode(":", $part);
			if($h[0] == 'link')
			{
				$s_=trim($h[1]).trim($h[2]);
				$res = preg_split('/\s*<([^>]*)>/', $s_, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
				$headers[trim($h[0])] = $res[0];
			}
			else
			{
				$headers[trim($h[0])] = trim($h[1]);
			}
		}
		return array('headers' => $headers, 'response' => $response[1]);
	}
}
function shopify_call_get_orders($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) {
	$url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
	if (!is_null($query) && in_array($method, array('GET', 	'DELETE'))) $url = $url . "?" . http_build_query($query);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
	curl_setopt($curl, CURLOPT_SSLVERSION, 3);
	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	// Setup headers
	$request_headers[] = "";
	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
		if (is_array($query)) $query = http_build_query($query);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	}
	$response = curl_exec($curl);
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);
	curl_close($curl);
	// $header_size_response = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	// $header_response = substr($response, 0, $header_size_response);
	// $body_response = substr($response, $header_size_response);
	// echo '<pre>',print_r($body_response),'</pre>';exit();
	// exit();
	// Return an error is cURL has a problem
	if ($error_number) {
		return $error_message;
	} else {

		// No error, return Shopify's response by parsing out the body and the headers
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
		// Convert headers into an array
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
		array_shift($header_data); // Remove status, we've already set it above
		foreach($header_data as $part) {
			$h = explode(":", $part);
			if($h[0] == 'link')
			{
				$s_=trim($h[1]).trim($h[2]);
				$res = preg_split('/\s*<([^>]*)>/', $s_, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
				$headers[trim($h[0])] = $res[0];
			}
			else
			{
				$headers[trim($h[0])] = trim($h[1]);
			}
		}
		// Return headers and Shopify's response
		return array('headers' => $headers, 'response' => $response[1]);

	}
    
}
function shopify_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) 
{
	// Build URL
	$url = "https://" . $shop .'.myshopify.com'. $api_endpoint;
	// echo $url;
	// die;
	if (!is_null($query) && in_array($method, array('GET', 	'DELETE')))
	{
		$url = $url . "?" . http_build_query($query);
	} 
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
	// curl_setopt($curl, CURLOPT_SSLVERSION, 3);
	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

	// Setup headers
	$request_headers[] = "";
	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
		if (is_array($query)) $query = http_build_query($query);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	}
    
	// Send request to Shopify and capture any errors
	$response = curl_exec($curl);
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);
	 
	// Close cURL to be nice
	curl_close($curl);
	if ($error_number) {
		return $error_message;
	} else {

		// No error, return Shopify's response by parsing out the body and the headers
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

		// Convert headers into an array
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
		array_shift($header_data); // Remove status, we've already set it above
		foreach($header_data as $part) {
			$h = explode(":", $part);
			$headers[trim($h[0])] = trim($h[1]);
		}
		// Return headers and Shopify's response
		return array('headers' => $headers, 'response' => $response[1]);

	}
}
function shopify_call_update_status($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) 
{
	// Build URL
	$url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
	if (!is_null($query) && in_array($method, array('GET', 	'DELETE')))
	{
		$url = $url . "?" . http_build_query($query);
	}
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
	// curl_setopt($curl, CURLOPT_SSLVERSION, 3);
	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

	// Setup headers
	$request_headers[] = "";
	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
	{	
		$request_headers[] = "Content-Type:application/json";
		$request_headers[] = "Accept:*/*";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
	}

	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
		if (is_array($query)) $query = http_build_query($query);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	}
    
	// Send request to Shopify and capture any errors
	$response = curl_exec($curl);
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);

	// Close cURL to be nice
	curl_close($curl);
	if ($error_number) {
		return $error_message;
	} else {

		// No error, return Shopify's response by parsing out the body and the headers
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

		// Convert headers into an array
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
		array_shift($header_data); // Remove status, we've already set it above
		foreach($header_data as $part) {
			$h = explode(":", $part);
			$headers[trim($h[0])] = trim($h[1]);
		}
		// Return headers and Shopify's response
		return array('headers' => $headers, 'response' => $response[1]);

	}
}