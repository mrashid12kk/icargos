<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name']))
{
	

	$allowed_extensions = array("xls","xlsx","csv");

	$filename = $_FILES['file']['name'];
	$file_array = explode(".",$filename);
	$extension = end($file_array);

	if(in_array($extension,$allowed_extensions))
	{
		$reader = IOFactory::createReader("Xlsx");
		$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$writer = IOFactory::createWriter($spreadsheet,"Html");
		$message = $writer->save("php://output");
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