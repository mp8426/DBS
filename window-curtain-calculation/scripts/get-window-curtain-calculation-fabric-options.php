<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$window_curtain_calculation_fabric_options = array();

$window_curtain_calculation_supplier_option_code = filter_input(INPUT_POST, 'code');

$query_2_1 = "SELECT code, name, width, price FROM window_curtain_calculation_fabric_options WHERE window_curtain_calculation_supplier_option_code = ? ORDER BY name ASC";

$stmt_2_1 = $mysqli->prepare($query_2_1);
$stmt_2_1->bind_param('s', $window_curtain_calculation_supplier_option_code);
$stmt_2_1->execute();
$stmt_2_1->bind_result($window_curtain_calculation_fabric_option_code, $window_curtain_calculation_fabric_option_name, $window_curtain_calculation_fabric_option_width, $window_curtain_calculation_fabric_option_price);
$stmt_2_1->store_result();

while ($stmt_2_1->fetch()) {

    $window_curtain_calculation_fabric_options[] = array(
        "code" => $window_curtain_calculation_fabric_option_code,
        "name" => $window_curtain_calculation_fabric_option_name,
        "width" => $window_curtain_calculation_fabric_option_width,
        "price" => $window_curtain_calculation_fabric_option_price,
        "supplier_code" => $window_curtain_calculation_supplier_option_code,
    );
}
$stmt_2_1->close();

print json_encode(array(1, $window_curtain_calculation_fabric_options)); // Success

$mysqli->close();
