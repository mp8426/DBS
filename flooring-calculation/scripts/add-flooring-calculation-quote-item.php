<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$code = uniqid();
$flooring_calculation_code = filter_input(INPUT_POST, 'flooring-calculation-code');
$cid = filter_input(INPUT_POST, 'cid');

$location = filter_input(INPUT_POST, 'location') ? filter_input(INPUT_POST, 'location') : "-"; // If location not selected at backend
$unit = filter_input(INPUT_POST, 'unit');
$manufacturer = filter_input(INPUT_POST, 'manufacturer');
$collection = filter_input(INPUT_POST, 'collection');
$colour = filter_input(INPUT_POST, 'colour');
$field = filter_input(INPUT_POST, 'field', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$price = filter_input(INPUT_POST, 'price') ? str_replace(",", "", filter_input(INPUT_POST, 'price')) : "0.00";
$total = filter_input(INPUT_POST, 'total') ? str_replace(",", "", filter_input(INPUT_POST, 'total')) : "0.00";

$qty = filter_input(INPUT_POST, 'qty');

$flooring_calculation_quote_item_codes = array();


$query_1 = "INSERT INTO flooring_calculation_quote_items ( code, location, unit, manufacturer, collection, colour, price, total, flooring_calculation_code, cid ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('ssssssssss', $code, $location, $unit, $manufacturer, $collection, $colour, $price, $total, $flooring_calculation_code, $cid);

for ($i = 0; $i < $qty; $i++) {

    $stmt_1->execute();

    if ($field) {

        foreach ($field as $flooring_calculation_field_code => $flooring_calculation_field_value) {

            if ($flooring_calculation_field_value) {

                if (strpos($flooring_calculation_field_value, '<->') !== false) {

                    $field_code = explode("<->", $flooring_calculation_field_value)[1];
                    $field_name = explode("<->", $flooring_calculation_field_value)[0];
                    $field_price = explode("<->", $flooring_calculation_field_value)[2];
                } else {
                    $field_code = 'text_field';
                    $field_name = ucwords(strtolower(trim(preg_replace("/[[:blank:]]+/", " ", $flooring_calculation_field_value))));
                    $field_price = 0;
                }
            } else {

                $field_code = "";
                $field_name = "NA";
                $field_price = 0;
            }

            $query_2 = "INSERT INTO flooring_calculation_quote_item_fields ( code, name, price, flooring_calculation_field_code, flooring_calculation_quote_item_code, flooring_calculation_code, cid ) VALUES ( ?, ?, ?, ?, ?, ?, ? )";

            $stmt_2 = $mysqli->prepare($query_2);
            $stmt_2->bind_param('ssdssss', $field_code, $field_name, $field_price, $flooring_calculation_field_code, $code, $flooring_calculation_code, $cid);
            $stmt_2->execute();
            $stmt_2->close();
        }
    }

    $flooring_calculation_quote_item_codes[] = array($code);

    $code = uniqid(); // Refresh code for next INSERT
}
$stmt_1->close();

print json_encode(array(1, $flooring_calculation_quote_item_codes));

$mysqli->close();
