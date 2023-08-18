<?php

require 'vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;


if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name']))
{
	

	$allowed_extensions = array("xls","xlsx","csv");

	$filename = $_FILES['file']['name'];
	$file_array = explode(".",$filename);
	$extension = end($file_array);

	if(in_array($extension,$allowed_extensions))
	{
		$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['file']['name']);
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
		$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$data = $spreadsheet->getActiveSheet()->toArray();

		echo "<pre>";
		print_r($data);
		exit;
	}
	else
	{
		$message = '<div class="alert alert-danger">only .xls and xlsx files are allowed</div>';

	}


}
else{
	$message = '<div class="alert alert-danger">Please upload file</div>';
}

echo $message;