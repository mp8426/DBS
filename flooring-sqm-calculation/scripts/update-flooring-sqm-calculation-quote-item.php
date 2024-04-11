<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$flooring_sqm_calculation_quote_items_array = filter_input(INPUT_POST, 'quote_items_array', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$query_1 = "UPDATE flooring_sqm_calculation_quote_items SET location = ?, unit = ?, manufacturer = ?, collection = ?, colour = ?, required_unit = ?, notes = ?, price = ?, total = ? WHERE code = ? AND flooring_sqm_calculation_code = ? AND cid = ?";

$stmt_1 = $mysqli->prepare($query_1);

foreach ($flooring_sqm_calculation_quote_items_array as $flooring_sqm_calculation_quote_items) {

    parse_str($flooring_sqm_calculation_quote_items, $quote_item);

    $flooring_sqm_calculation_quote_item_code = $quote_item['flooring-sqm-calculation-quote-item-code'];
    $flooring_sqm_calculation_code = $quote_item['flooring-sqm-calculation-code'];
    $cid = $quote_item['cid'];

    $location = isset($quote_item['location']) && !empty($quote_item['location']) ? $quote_item['location'] : "NA";
    $unit = $quote_item['unit'];
    $manufacturer = $quote_item['manufacturer'];
    $collection = $quote_item['collection'];
    $colour = $quote_item['colour'];
    $required_unit = $quote_item['required-unit'];
    $notes = $quote_item['notes'];
    $price = str_replace(",", "", $quote_item['price']);
    $total = $quote_item['total'];

    $stmt_1->bind_param('ssssssssssss', $location, $unit, $manufacturer, $collection, $colour, $required_unit, $notes, $price, $total, $flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_code, $cid);
    $stmt_1->execute();

    if (isset($quote_item['field'])) {

        $fields = $quote_item['field'];

        $query_2 = 'UPDATE flooring_sqm_calculation_quote_item_fields SET code = ?, name = ?, price = ? WHERE flooring_sqm_calculation_field_code = ? AND flooring_sqm_calculation_quote_item_code = ? AND flooring_sqm_calculation_code = ? AND cid = ?';

        $stmt_2 = $mysqli->prepare($query_2);

        foreach ($fields as $flooring_sqm_calculation_field_code => $field_value) {

            if ($field_value !== "") {
                if (strpos($field_value, '<->') !== false) {

                    $field_code = explode("<->", $field_value)[1];
                    $field_name = explode("<->", $field_value)[0];
                    $field_price = explode("<->", $field_value)[2];
                } else {

                    $field_code = 'text_field';
                    $field_name = trim(preg_replace("/[[:blank:]]+/", " ", $field_value));
                    $field_price = 0;
                }
            } else {
                $field_code = "";
                $field_name = "NA";
                $field_price = 0;
            }

            $stmt_2->bind_param('ssdssss', $field_code, $field_name, $field_price, $flooring_sqm_calculation_field_code, $flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_code, $cid);
            $stmt_2->execute();
        }
        $stmt_2->close();
    }
}
$stmt_1->close();

print json_encode(array(1));
$mysqli->close();
