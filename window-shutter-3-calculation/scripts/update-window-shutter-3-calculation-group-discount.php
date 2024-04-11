<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');
$discount = trim(filter_input(INPUT_POST, 'discount'));
$window_shutter_3_calculation_code = filter_input(INPUT_POST, 'window_shutter_3_calculation_code');

if(empty($discount)){
    $discount = 0;
}

$query_1 = "UPDATE window_shutter_3_calculation_quote_items SET discount = ? WHERE window_shutter_3_calculation_code = ? AND cid = ?";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('sss', $discount, $window_shutter_3_calculation_code, $cid);
if($stmt_1->execute()){
    print json_encode(array(1));
}else{
    print json_encode(array(2, "Something went wrong!"));
}
$stmt_1->close();

$mysqli->close();
