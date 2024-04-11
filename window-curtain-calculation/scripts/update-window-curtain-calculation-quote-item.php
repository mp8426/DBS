<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$window_curtain_calculation_quote_items_array = filter_input(INPUT_POST, 'quote_items_array', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$query_1 = "UPDATE window_curtain_calculation_quote_items SET location = ?, width = ?, right_return = ?, left_return = ?, overlap = ?, fullness = ?, supplier = ?, fabric = ?, fabric_colour = ?, qty_drop = ?, curtain_type_1 = ?, continuous_meter = ?, curtain_type_2 = ?, drop_x = ?, hem_heading = ?, pattern_repeate = ?, fabric_cut_length = ?, fabric_qty = ?, notes = ?, price = ? WHERE code = ? AND window_curtain_calculation_code = ? AND cid = ?";

$stmt_1 = $mysqli->prepare($query_1);

foreach ($window_curtain_calculation_quote_items_array as $window_curtain_calculation_quote_items) {

    parse_str($window_curtain_calculation_quote_items, $quote_item);

    $window_curtain_calculation_quote_item_code = $quote_item['window-curtain-calculation-quote-item-code'];
    $window_curtain_calculation_code = $quote_item['window-curtain-calculation-code'];
    $cid = $quote_item['cid'];

    $location = isset($quote_item['location']) && !empty($quote_item['location']) ? $quote_item['location'] : "NA";
    $width = $quote_item['width'];
    $right_return = $quote_item['right_return'];
    $left_return = $quote_item['left_return'];
    $overlap = $quote_item['overlap'];
    $fullness = $quote_item['fullness'];
    $supplier = $quote_item['supplier'];
    $fabric = $quote_item['fabric'];
    $fabric_colour = $quote_item['fabric_colour'];
    $qty_drop = $quote_item['qty_drop'];
    $curtain_type_1 = $quote_item['curtain_type_1'];
    $continuous_meter = $quote_item['continuous_meter'];
    $curtain_type_2 = $quote_item['curtain_type_2'];
    $drop = $quote_item['drop'];
    $hem_heading = $quote_item['hem_heading'];
    $pattern_repeate = $quote_item['pattern_repeate'];
    $fabric_cut_length = $quote_item['fabric_cut_length'];
    $fabric_qty = $quote_item['fabric_qty'];
    $notes = $quote_item['notes'];
    $price = str_replace(",", "", $quote_item['price']);

    $stmt_1->bind_param('sssssssssssssssssssssss', $location, $width, $right_return, $left_return, $overlap, $fullness, $supplier, $fabric, $fabric_colour, $qty_drop, $curtain_type_1, $continuous_meter, $curtain_type_2, $drop, $hem_heading, $pattern_repeate, $fabric_cut_length, $fabric_qty, $notes, $price, $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
    $stmt_1->execute();



    if (isset($quote_item['field'])) {

        $fields = $quote_item['field'];

        $query_2 = 'UPDATE window_curtain_calculation_quote_item_fields SET code = ?, name = ?, price = ? WHERE window_curtain_calculation_field_code = ? AND window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?';

        $stmt_2 = $mysqli->prepare($query_2);

        foreach ($fields as $window_curtain_calculation_field_code => $field_value) {

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

            $stmt_2->bind_param('ssdssss', $field_code, $field_name, $field_price, $window_curtain_calculation_field_code, $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
            $stmt_2->execute();
        }
        $stmt_2->close();
    }
}
$stmt_1->close();

print json_encode(array(1));
$mysqli->close();
