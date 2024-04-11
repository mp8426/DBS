<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$form_no_1_calculation_quote_items_array = filter_input(INPUT_POST, 'quote_items_array', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$query_1 = "UPDATE form_no_1_calculation_quote_items SET row_no = ?, location = ?, cost = ?, markup = ?, notes = ?, price = ?, qty = ?, total = ? WHERE code = ? AND form_no_1_calculation_code = ? AND cid = ?";

$stmt_1 = $mysqli->prepare($query_1);

foreach ($form_no_1_calculation_quote_items_array as $form_no_1_calculation_quote_items) {

    parse_str($form_no_1_calculation_quote_items, $quote_item);

    $form_no_1_calculation_quote_item_code = $quote_item['form-no-1-calculation-quote-item-code'];
    $form_no_1_calculation_code = $quote_item['form-no-1-calculation-code'];
    $cid = $quote_item['cid'];

    $row_no = (int) $quote_item['quote-item-row-no'][0];
    $location = isset($quote_item['location']) && !empty($quote_item['location']) ? $quote_item['location'] : "NA";
    $cost = isset($quote_item['cost']) && !empty($quote_item['cost']) ? $quote_item['cost'] : 0;
    $markup = isset($quote_item['markup']) && !empty($quote_item['markup']) ? $quote_item['markup'] : 1;
    $notes = $quote_item['notes'];
    $price = $quote_item['price'] ? str_replace(",", "", $quote_item['price']) : "0.00";
    $qty = $quote_item['qty'] ? $quote_item['qty'] : 1;

    $qty_field = $quote_item['qty_field'] ? $quote_item['qty_field'] : 'off';

    if ($qty_field === 'off') {

        $qty_x = 1;
        $total = $price;
    } else {

        $qty_x = $qty;
        $total = $quote_item['total'] ? str_replace(",", "", $quote_item['total']) : "0.00";
    }

    $stmt_1->bind_param('isssssddsss', $row_no, $location, $cost, $markup, $notes, $price, $qty_x, $total, $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid);
    $stmt_1->execute();

    if (isset($quote_item['field'])) {

        $fields = $quote_item['field'];

        // $query_2 = 'UPDATE form_no_1_calculation_quote_item_fields SET code = ?, name = ?, price = ? WHERE form_no_1_calculation_field_code = ? AND form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?';

        $query_2 = 'INSERT INTO form_no_1_calculation_quote_item_fields (code, name, price, form_no_1_calculation_field_code, form_no_1_calculation_quote_item_code, form_no_1_calculation_code, cid) VALUES(?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE code = ?, name = ?, price = ?';

        $stmt_2 = $mysqli->prepare($query_2);

        foreach ($fields as $form_no_1_calculation_field_code => $field_value) {

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

            // $stmt_2->bind_param('ssdssss', $field_code, $field_name, $field_price, $form_no_1_calculation_field_code, $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid);
            $stmt_2->bind_param('ssdssssssd', $field_code, $field_name, $field_price, $form_no_1_calculation_field_code, $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid, $field_code, $field_name, $field_price);
            $stmt_2->execute();
        }
        $stmt_2->close();
    }
}
$stmt_1->close();

print json_encode(array(1));
$mysqli->close();
