<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$window_shutter_calculation_quote_item_code = filter_input(INPUT_POST, 'window_shutter_calculation_quote_item_code');
$window_shutter_calculation_code = filter_input(INPUT_POST, 'window_shutter_calculation_code');
$cid = filter_input(INPUT_POST, 'cid');

$accessory_code = filter_input(INPUT_POST, 'accessory_code');

$query_1 = "DELETE FROM window_shutter_calculation_quote_item_accessories WHERE code = ? AND window_shutter_calculation_quote_item_code = ? AND window_shutter_calculation_code = ? AND cid = ?";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('ssss', $accessory_code, $window_shutter_calculation_quote_item_code, $window_shutter_calculation_code, $cid);

if ($stmt_1->execute()) {
    print json_encode(array(1)); // Success
} else {
    print json_encode(array(2, "Oops! Something went wrong.")); // Error
}
$stmt_1->close();

$mysqli->close();
