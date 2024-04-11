<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$flooring_acc_calculation_quote_item_code = filter_input(INPUT_POST, 'flooring_acc_calculation_quote_item_code');
$flooring_acc_calculation_code = filter_input(INPUT_POST, 'flooring_acc_calculation_code');
$cid = filter_input(INPUT_POST, 'cid');

$per_meter = filter_input(INPUT_POST, 'per_meter');
$per_meter_width = filter_input(INPUT_POST, 'per_meter_width');

$per_meter_code = explode("<->", $per_meter)[0];
$per_meter_name = explode("<->", $per_meter)[1];
$per_meter_price = str_replace(",", "", explode("<->", $per_meter)[2]);

$query_1 = "INSERT INTO flooring_acc_calculation_quote_item_per_meters ( code, name, price, width, flooring_acc_calculation_quote_item_code, flooring_acc_calculation_code, cid ) VALUES( ?, ?, ?, ?, ?, ?, ? )";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('sssssss', $per_meter_code, $per_meter_name, $per_meter_price, $per_meter_width, $flooring_acc_calculation_quote_item_code, $flooring_acc_calculation_code, $cid);

if ($stmt_1->execute()) {
    print json_encode(array(1)); // Success
} else {
    print json_encode(array(2, "Oops! Something went wrong.")); // Error
}
$stmt_1->close();

$mysqli->close();
