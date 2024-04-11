<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$window_price_sheet_11_calculation_code = filter_input(INPUT_POST, 'code');
$window_price_sheet_11_calculation_type_option_code = filter_input(INPUT_POST, 'type_code');

$width = trim(preg_replace("/[[:blank:]]+/", " ", filter_input(INPUT_POST, 'width')));
$drop = trim(preg_replace("/[[:blank:]]+/", " ", filter_input(INPUT_POST, 'drop')));

$query_1 = "SELECT price FROM window_price_sheet_11_calculation_type_option_price_sheet WHERE width_x >= ? AND drop_x >= ? AND window_price_sheet_11_calculation_type_option_code = ? AND window_price_sheet_11_calculation_code = ? ";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('iiss', $width, $drop, $window_price_sheet_11_calculation_type_option_code, $window_price_sheet_11_calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($price);
$stmt_1->fetch();
$stmt_1->close();

print json_encode($price);

$mysqli->close();
