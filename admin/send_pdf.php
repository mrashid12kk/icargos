<?php
session_start();
require 'includes/conn.php';

if (isset($_POST['data']) && !empty($_POST['data'])) {
    include('assets/pdf/new/pdf.php');
    $file_name = 'assets/pdf/'.md5(rand()) . '.pdf';
    $html_code = $_POST['data'];
    $pdf = new Pdf();
    $pdf->load_html($html_code);
    $pdf->render();
    $file = $pdf->output();
    file_put_contents($file_name, $file);
    $data['email'] = $_POST['customer_email'];
    $customer_name = $_POST['fname'];
    $message['subject'] = 'Invoice Created';
    $message['attachment'] = $file_name;
    $message['body'] = '<p>Your Invoice is Created From '.getConfig('companyname').' Please Downlaod It</p>';
    require_once 'includes/functions.php';
    if (sendEmail_pdf($data, $message)) {
        echo "true";
    }else{
        echo "false";
    }
}
?>