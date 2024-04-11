<?php

include __DIR__ . './../cPanel/connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$query_1 = "SELECT code, name, locations, cost, markup, accessories, per_meters, fitting_charges, qty FROM form_no_1_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($form_no_1_calculation_code, $form_no_1_calculation_name, $locations_select, $cost_select, $markup_select, $accessories_select, $per_meters_select, $fitting_charges_select, $qty_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $query_2x = "SELECT location, cost, markup, price, qty, total, note, group_discount, accessory, per_meter, fitting_charge FROM form_no_1_calculation_fixed_fields_visibility WHERE form_no_1_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $form_no_1_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $cost_print, $markup_print, $price_print, $qty_print, $total_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $field_th_left = [];
    $field_th_right = [];

    $query_2 = "SELECT code, name, side FROM form_no_1_calculation_fields WHERE " . $print_type . " = 1 AND form_no_1_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $form_no_1_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($form_no_1_calculation_field_code, $form_no_1_calculation_field_name, $form_no_1_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {
        if ($form_no_1_calculation_field_side === 0) { // Right Side
            $field_th_right['_' . $form_no_1_calculation_field_code] =  $form_no_1_calculation_field_name;
        }
        if ($form_no_1_calculation_field_side === 1) { // Left Side
            $field_th_left['_' . $form_no_1_calculation_field_code] =  $form_no_1_calculation_field_name;
        }
    }
    $stmt_2->close();

    $no_th_arr["no"] = 'No';

    $locations_th_arr["location"] = $locations_select === 1 && $location_print[$no] === '1' ? 'Location' : [];

    $right_th_arr = [];

    $right_th_arr["cost"] = $cost_select === 1 && $cost_print[$no] === '1' ? 'Cost' : [];
    $right_th_arr["markup"] = $markup_select === 1 && $markup_print[$no] === '1' ? 'Markup' : [];
    $right_th_arr["price"] = $price_print[$no] === '1' ? 'Price' : [];
    $right_th_arr["qty"] = $qty_select === 1 && $qty_print[$no] === '1' ? 'Qty' : [];
    $right_th_arr["total"] =  $qty_select === 1 && $total_print[$no] === '1' ? 'Total' : [];

    $body = [];

    //$row_no = 1;
    $form_no_1_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, row_no, location, cost, markup, notes, price, qty, total, discount FROM form_no_1_calculation_quote_items WHERE form_no_1_calculation_code = ? AND cid = ? ORDER BY -row_position DESC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $form_no_1_calculation_code, $quote_no);
    $stmt_3->execute();
    $stmt_3->bind_result($form_no_1_calculation_quote_item_code, $row_no, $form_no_1_calculation_quote_item_location, $form_no_1_calculation_quote_item_cost, $form_no_1_calculation_quote_item_markup, $form_no_1_calculation_quote_item_notes, $form_no_1_calculation_quote_item_price, $form_no_1_calculation_quote_item_qty, $form_no_1_calculation_quote_item_total, $form_no_1_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $form_no_1_calculation_quote_item_price_sub_total += $form_no_1_calculation_quote_item_total;

            $field_td_right = [];
            $field_td_left = [];

            $query_3_1 = "SELECT form_no_1_calculation_quote_item_fields.name, "
                . "form_no_1_calculation_quote_item_fields.price, "
                . "form_no_1_calculation_quote_item_fields.sub_options, "
                . "form_no_1_calculation_fields.code, "
                . "form_no_1_calculation_fields.side "
                . "FROM form_no_1_calculation_quote_item_fields "
                . "JOIN form_no_1_calculation_fields ON "
                . "form_no_1_calculation_fields.code = form_no_1_calculation_quote_item_fields.form_no_1_calculation_field_code "
                . "WHERE "
                . "form_no_1_calculation_fields." . $print_type . " = 1 AND "
                . "form_no_1_calculation_quote_item_fields.form_no_1_calculation_quote_item_code = ? AND "
                . "form_no_1_calculation_quote_item_fields.form_no_1_calculation_code = ? AND "
                . "form_no_1_calculation_quote_item_fields.cid = ? "
                . "ORDER BY form_no_1_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $quote_no);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($form_no_1_calculation_quote_item_field_name, $form_no_1_calculation_quote_item_field_price, $sub_options, $form_no_1_calculation_field_code, $form_no_1_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                $field_text = get_sub_option_text(json_decode($sub_options, true));

                if ($form_no_1_calculation_quote_item_field_price > 0) {
                    $form_no_1_calculation_quote_item_field_price_x = ' | ' . number_format($form_no_1_calculation_quote_item_field_price, 2);
                } else {
                    $form_no_1_calculation_quote_item_field_price_x = '';
                }

                if (empty($field_text)) {
                    $field_text = $form_no_1_calculation_quote_item_field_name . $form_no_1_calculation_quote_item_field_price_x;
                } else {
                    $field_text = $form_no_1_calculation_quote_item_field_name . $form_no_1_calculation_quote_item_field_price_x . '<br>>> ' . $field_text;
                }

                if ($form_no_1_calculation_field_side === 0) { // Right Side
                    $field_td_right['_' . $form_no_1_calculation_field_code] = $field_text;
                }
                if ($form_no_1_calculation_field_side === 1) { // Left Side
                    $field_td_left['_' . $form_no_1_calculation_field_code] = $field_text;
                }
            }
            $stmt_3_1->close();

            $accessories = [];

            $quote_item_accessory_no = 1;

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM form_no_1_calculation_quote_item_accessories WHERE form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $quote_no);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($form_no_1_calculation_quote_item_accessory_code, $form_no_1_calculation_quote_item_accessory_name, $form_no_1_calculation_quote_item_accessory_price, $form_no_1_calculation_quote_item_accessory_qty, $form_no_1_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $accessories[] = [
                    "no" => $quote_item_accessory_no,
                    "name" => $form_no_1_calculation_quote_item_accessory_name,
                    "price" =>  number_format($form_no_1_calculation_quote_item_accessory_price, 2),
                    "qty" => $form_no_1_calculation_quote_item_accessory_qty,
                    "total" => number_format($form_no_1_calculation_quote_item_accessory_total, 2),
                ];
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();

            $per_meters = [];

            $quote_item_per_meter_no = 1;
            $form_no_1_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM form_no_1_calculation_quote_item_per_meters WHERE form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $quote_no);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($form_no_1_calculation_quote_item_per_meter_code, $form_no_1_calculation_quote_item_per_meter_name, $form_no_1_calculation_quote_item_per_meter_price, $form_no_1_calculation_quote_item_per_meter_width, $form_no_1_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $per_meters[] = [
                    "no" => $quote_item_per_meter_no,
                    "name" => $form_no_1_calculation_quote_item_per_meter_name,
                    "price" =>  number_format($form_no_1_calculation_quote_item_per_meter_price, 2),
                    "width" => $form_no_1_calculation_quote_item_per_meter_width,
                    "total" => number_format($form_no_1_calculation_quote_item_per_meter_total, 2),
                ];

                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();

            $fitting_charges = [];

            $quote_item_fitting_charge_no = 1;
            $form_no_1_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM form_no_1_calculation_quote_item_fitting_charges WHERE form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $quote_no);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($form_no_1_calculation_quote_item_fitting_charge_code, $form_no_1_calculation_quote_item_fitting_charge_name, $form_no_1_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $fitting_charges[] = [
                    "no" => $quote_item_fitting_charge_no,
                    "name" => $form_no_1_calculation_quote_item_fitting_charge_name,
                    "price" => number_format($form_no_1_calculation_quote_item_fitting_charge_price, 2)
                ];

                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[$no] === '1' ? explode('<->', $form_no_1_calculation_quote_item_location)[0] : '';

            $right_td_arr = [
                "cost" => $cost_select === 1 && $cost_print[$no] === '1' ? $form_no_1_calculation_quote_item_cost : '',
                "markup" => $markup_select === 1 && $markup_print[$no] === '1' ? $form_no_1_calculation_quote_item_markup : '',
                "price" => $price_print[$no] === '1' ?  number_format($form_no_1_calculation_quote_item_price, 2) : '',
                "qty" => $qty_select === 1 && $qty_print[$no] === '1' ? $form_no_1_calculation_quote_item_qty : '',
                "total" => $qty_select === 1 && $total_print[$no] === '1' ?  number_format($form_no_1_calculation_quote_item_total, 2) : '',
            ];

            $extra_arr = [
                "notes" => $form_no_1_calculation_quote_item_notes && $note_print[$no] === '1' ? $form_no_1_calculation_quote_item_notes : '',
                "acc" => $accessories && $accessories_select === 1 && $accessory_print[$no] === '1' ? $accessories : [],
                "pm" =>  $per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1' ? $per_meters : [],
                "fc" => $fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' ? $fitting_charges : [],
            ];

            $body[] = (array_filter(array_merge(["no" => $row_no], $field_td_left, ["location" => $location_td], $field_td_right, $right_td_arr, $extra_arr)));

            //$row_no++;

            $total_arr = [];

            if ($group_discount_print[$no] === '1') {

                $form_no_1_calculation_quote_item_discount_value = $form_no_1_calculation_quote_item_price_sub_total * $form_no_1_calculation_quote_item_discount / 100;
                $form_no_1_calculation_quote_item_price_total = $form_no_1_calculation_quote_item_price_sub_total - $form_no_1_calculation_quote_item_discount_value;

                $total_arr = [
                    "sub_total" => number_format($form_no_1_calculation_quote_item_price_sub_total, 2),
                    "discount_p" => $form_no_1_calculation_quote_item_discount,
                    "discount_v" => number_format($form_no_1_calculation_quote_item_discount_value, 2),
                    "total" => number_format($form_no_1_calculation_quote_item_price_total, 2),
                ];
            }
        }
    }

    $stmt_3->close();

    if (!empty($body)) {

        $header = (array_filter(array_merge($no_th_arr, $field_th_left, $locations_th_arr, $field_th_right, $right_th_arr)));

        $calc_result[] = [
            'name' => $form_no_1_calculation_name,
            "header" => $header,
            "body" => $body,
            "footer" => $total_arr,
        ];
    }
}
$stmt_1->close();

$mysqli->close();
