<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$twc_calculation_quote_item_code = filter_input(INPUT_POST, 'twc_calculation_quote_item_code');
$twc_calculation_code = filter_input(INPUT_POST, 'twc_calculation_code');
$cid = filter_input(INPUT_POST, 'cid');

$per_meter_code = filter_input(INPUT_POST, 'per_meter_code');

$query_1 = "DELETE FROM twc_calculation_quote_item_per_meters WHERE code = ? AND twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('ssss', $per_meter_code, $twc_calculation_quote_item_code, $twc_calculation_code, $cid);

if ($stmt_1->execute()) {
    print json_encode(array(1)); // Success
} else {
    print json_encode(array(2, "Oops! Something went wrong.")); // Error
}
$stmt_1->close();

$mysqli->close();
