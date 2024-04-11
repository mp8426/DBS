<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$code = uniqid();
$window_curtain_calculation_code = filter_input(INPUT_POST, 'window-curtain-calculation-code');
$cid = filter_input(INPUT_POST, 'cid');

$location = filter_input(INPUT_POST, 'location') ? filter_input(INPUT_POST, 'location') : "-"; // If location not selected at backend
$width = filter_input(INPUT_POST, 'width');
$right_return = filter_input(INPUT_POST, 'right_return');
$left_return = filter_input(INPUT_POST, 'left_return');
$overlap = filter_input(INPUT_POST, 'overlap');
$fullness = filter_input(INPUT_POST, 'fullness');
$supplier = filter_input(INPUT_POST, 'supplier');
$fabric = filter_input(INPUT_POST, 'fabric');
$fabric_colour = filter_input(INPUT_POST, 'fabric_colour');
$qty_drop = filter_input(INPUT_POST, 'qty_drop');
$curtain_type_1 = filter_input(INPUT_POST, 'curtain_type_1');
$continuous_meter = filter_input(INPUT_POST, 'continuous_meter');
$curtain_type_2 = filter_input(INPUT_POST, 'curtain_type_2');
$drop = filter_input(INPUT_POST, 'drop');
$hem_heading = filter_input(INPUT_POST, 'hem_heading');
$pattern_repeate = filter_input(INPUT_POST, 'pattern_repeate');
$fabric_cut_length = filter_input(INPUT_POST, 'fabric_cut_length');
$fabric_qty = filter_input(INPUT_POST, 'fabric_qty');
$field = filter_input(INPUT_POST, 'field', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$price = filter_input(INPUT_POST, 'price') ? str_replace(",", "", filter_input(INPUT_POST, 'price')) : "0.00";

$qty = filter_input(INPUT_POST, 'qty');

$window_curtain_calculation_quote_item_codes = array();


$query_1 = "INSERT INTO window_curtain_calculation_quote_items (  code, location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, continuous_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty, price, window_curtain_calculation_code, cid ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('ssssssssssssssssssssss', $code, $location, $width, $right_return, $left_return, $overlap, $fullness, $supplier, $fabric, $fabric_colour, $qty_drop, $curtain_type_1, $continuous_meter, $curtain_type_2, $drop, $hem_heading, $pattern_repeate, $fabric_cut_length, $fabric_qty, $price, $window_curtain_calculation_code, $cid);

for ($i = 0; $i < $qty; $i++) {

    $stmt_1->execute();

    if ($field) {

        foreach ($field as $window_curtain_calculation_field_code => $window_curtain_calculation_field_value) {

            if ($window_curtain_calculation_field_value) {

                if (strpos($window_curtain_calculation_field_value, '<->') !== false) {

                    $field_code = explode("<->", $window_curtain_calculation_field_value)[1];
                    $field_name = explode("<->", $window_curtain_calculation_field_value)[0];
                    $field_price = explode("<->", $window_curtain_calculation_field_value)[2];
                } else {
                    $field_code = 'text_field';
                    $field_name = ucwords(strtolower(trim(preg_replace("/[[:blank:]]+/", " ", $window_curtain_calculation_field_value))));
                    $field_price = 0;
                }
            } else {

                $field_code = "";
                $field_name = "NA";
                $field_price = 0;
            }

            $query_2 = "INSERT INTO window_curtain_calculation_quote_item_fields ( code, name, price, window_curtain_calculation_field_code, window_curtain_calculation_quote_item_code, window_curtain_calculation_code, cid ) VALUES ( ?, ?, ?, ?, ?, ?, ? )";

            $stmt_2 = $mysqli->prepare($query_2);
            $stmt_2->bind_param('ssdssss', $field_code, $field_name, $field_price, $window_curtain_calculation_field_code, $code, $window_curtain_calculation_code, $cid);
            $stmt_2->execute();
            $stmt_2->close();
        }
    }

    $window_curtain_calculation_quote_item_codes[] = array($code);

    $code = uniqid(); // Refresh code for next INSERT
}
$stmt_1->close();

print json_encode(array(1, $window_curtain_calculation_quote_item_codes));

$mysqli->close();
