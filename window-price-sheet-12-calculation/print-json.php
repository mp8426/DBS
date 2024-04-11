<?php

include __DIR__ . './../cPanel/connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($no === 2) {
    include __DIR__ . '/../scripts/get-price-from-csv-price-sheet-function.php';
}

$query_1 = "SELECT code, name, locations, materials_and_colours, accessories, per_meters, fitting_charges,qty FROM window_price_sheet_12_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($window_price_sheet_12_calculation_code, $window_price_sheet_12_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select, $qty_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    $query_1x = "SELECT location, width_x, drop_x, type, material, colour FROM window_price_sheet_12_calculation_fixed_fields_visibility WHERE window_price_sheet_12_calculation_code = '$window_price_sheet_12_calculation_code' ";
    $query_1x_result = mysqli_query($mysqli, $query_1x);

    $columns_1x = array();
    $row_1x = mysqli_fetch_assoc($query_1x_result);
    foreach ($row_1x as $column_1x => $value_1x) {
        $columns_1x[$column_1x] = explode(',', $value_1x)[1];
    }
    asort($columns_1x); // sorting foreach by position

    $query_2x = "SELECT location, width_x, drop_x, type, material, colour, price, qty, total, note, group_discount, accessory, per_meter, fitting_charge FROM window_price_sheet_12_calculation_fixed_fields_visibility WHERE window_price_sheet_12_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $window_price_sheet_12_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $width_x_print, $drop_x_print, $type_print, $material_print, $colour_print, $price_print, $qty_print, $total_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $field_th_left = [];
    $field_th_right = [];

    $query_2 = "SELECT code, name, side FROM window_price_sheet_12_calculation_fields WHERE " . $print_type . " = 1 AND window_price_sheet_12_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $window_price_sheet_12_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($window_price_sheet_12_calculation_field_code, $window_price_sheet_12_calculation_field_name, $window_price_sheet_12_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {

        if ($window_price_sheet_12_calculation_field_side === 0) { // Right Side
            $field_th_right['_' . $window_price_sheet_12_calculation_field_code] = $window_price_sheet_12_calculation_field_name;
        }
        if ($window_price_sheet_12_calculation_field_side === 1) { // Left Side
            $field_th_left['_' . $window_price_sheet_12_calculation_field_code] = $window_price_sheet_12_calculation_field_name;
        }
    }
    $stmt_2->close();

    $no_th_arr["no"] = '#';

    $fixed_th_arr = [
        "location" => $locations_select === 1 && $location_print[$no] === '1' ? 'Location' : [],
        "width_x" => $width_x_print[$no] === '1' ? 'Width' : [],
        "drop_x" => $drop_x_print[$no] === '1' ? 'Height' : [],
        "type" => $type_print[$no] === '1' ? 'Type' : [],
        "material" => $materials_and_colours_select === 1 && $material_print[$no] === '1' ? 'Material' : [],
        "colour" => $materials_and_colours_select === 1 && $colour_print[$no] === '1' ? 'Colour' : [],
    ];

    $fixed_th_sorted_arr = [];

    // sort the above $fixed_th_arr according to the the positions set from cPanel
    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
        $fixed_th_sorted_arr[$variable_1x] = $fixed_th_arr[$variable_1x];
    }

    $price_qty_tot_arr = [
        "price" => $price_print[$no] === '1' ? 'Price' : [],
        "qty" => $qty_select === 1 && $qty_print[$no] === '1' ? 'Qty' : [],
        "total" =>  $qty_select === 1 && $total_print[$no] === '1' ? 'Total' : []
    ];

    $body = [];

    // $row_no = 1;
    $window_price_sheet_12_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, row_no, location, width_x, drop_x, type, material, colour, notes, price, qty, total, discount FROM window_price_sheet_12_calculation_quote_items WHERE window_price_sheet_12_calculation_code = ? AND cid = ? ORDER BY -row_position DESC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_price_sheet_12_calculation_code, $quote_no);
    $stmt_3->execute();
    $stmt_3->bind_result($window_price_sheet_12_calculation_quote_item_code, $row_no, $window_price_sheet_12_calculation_quote_item_location, $window_price_sheet_12_calculation_quote_item_width, $window_price_sheet_12_calculation_quote_item_drop, $window_price_sheet_12_calculation_quote_item_type, $window_price_sheet_12_calculation_quote_item_material, $window_price_sheet_12_calculation_quote_item_colour, $window_price_sheet_12_calculation_quote_item_notes, $window_price_sheet_12_calculation_quote_item_price, $window_price_sheet_12_calculation_quote_item_qty, $window_price_sheet_12_calculation_quote_item_total, $window_price_sheet_12_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            if ($no === 2) {

                $csv_price_sheet = __DIR__ . '/../cPanel/price-sheets/' . explode('<->', $window_price_sheet_12_calculation_quote_item_type)[1] . '-' . $window_price_sheet_12_calculation_code . '.csv';

                $window_price_sheet_12_calculation_quote_item_price = get_price_from_csv($csv_price_sheet, $window_price_sheet_12_calculation_quote_item_width, $window_price_sheet_12_calculation_quote_item_drop);
            }

            $window_price_sheet_12_calculation_quote_item_price_sub_total += $window_price_sheet_12_calculation_quote_item_total;

            $field_td_right = [];
            $field_td_left = [];

            $query_3_1 = "SELECT window_price_sheet_12_calculation_quote_item_fields.name, "
                . "window_price_sheet_12_calculation_quote_item_fields.price, "
                . "window_price_sheet_12_calculation_quote_item_fields.sub_options, "
                . "window_price_sheet_12_calculation_fields.code, "
                . "window_price_sheet_12_calculation_fields.side "
                . "FROM window_price_sheet_12_calculation_fields "
                . "LEFT JOIN window_price_sheet_12_calculation_quote_item_fields ON "
                . "window_price_sheet_12_calculation_quote_item_fields.window_price_sheet_12_calculation_quote_item_code = ? AND "
                . "window_price_sheet_12_calculation_quote_item_fields.window_price_sheet_12_calculation_code = ? AND "
                . "window_price_sheet_12_calculation_quote_item_fields.cid = ? AND "
                . "window_price_sheet_12_calculation_fields.code = window_price_sheet_12_calculation_quote_item_fields.window_price_sheet_12_calculation_field_code "
                . "WHERE "
                . "window_price_sheet_12_calculation_fields." . $print_type . " = 1 AND "
                . "window_price_sheet_12_calculation_fields.window_price_sheet_12_calculation_code = ? "
                . "ORDER BY window_price_sheet_12_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('ssss', $window_price_sheet_12_calculation_quote_item_code, $window_price_sheet_12_calculation_code, $quote_no, $window_price_sheet_12_calculation_code);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_price_sheet_12_calculation_quote_item_field_name, $window_price_sheet_12_calculation_quote_item_field_price, $sub_options, $window_price_sheet_12_calculation_field_code, $window_price_sheet_12_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                $field_text = get_sub_option_text(json_decode($sub_options, true));

                if ($window_price_sheet_12_calculation_quote_item_field_price > 0) {
                    $window_price_sheet_12_calculation_quote_item_field_price_x = ' | ' . number_format($window_price_sheet_12_calculation_quote_item_field_price, 2);
                } else {
                    $window_price_sheet_12_calculation_quote_item_field_price_x = '';
                }

                if (empty($field_text)) {
                    $field_text = $window_price_sheet_12_calculation_quote_item_field_name . $window_price_sheet_12_calculation_quote_item_field_price_x;
                } else {
                    $field_text = $window_price_sheet_12_calculation_quote_item_field_name . $window_price_sheet_12_calculation_quote_item_field_price_x . '<br>>> ' . $field_text;
                }

                if ($window_price_sheet_12_calculation_field_side === 0) { // Right Side
                    $field_td_right['_' . $window_price_sheet_12_calculation_field_code] = $field_text;
                }
                if ($window_price_sheet_12_calculation_field_side === 1) { // Left Side
                    $field_td_left['_' . $window_price_sheet_12_calculation_field_code] = $field_text;
                }
            }
            $stmt_3_1->close();

            $accessories = [];

            $quote_item_accessory_no = 1;
            $window_price_sheet_12_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM window_price_sheet_12_calculation_quote_item_accessories WHERE window_price_sheet_12_calculation_quote_item_code = ? AND window_price_sheet_12_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_price_sheet_12_calculation_quote_item_code, $window_price_sheet_12_calculation_code, $quote_no);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_price_sheet_12_calculation_quote_item_accessory_code, $window_price_sheet_12_calculation_quote_item_accessory_name, $window_price_sheet_12_calculation_quote_item_accessory_price, $window_price_sheet_12_calculation_quote_item_accessory_qty, $window_price_sheet_12_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $accessories[] = [
                    "no" => $quote_item_accessory_no,
                    "name" => $window_price_sheet_12_calculation_quote_item_accessory_name,
                    "price" =>  number_format($window_price_sheet_12_calculation_quote_item_accessory_price, 2),
                    "qty" => $window_price_sheet_12_calculation_quote_item_accessory_qty,
                    "total" => number_format($window_price_sheet_12_calculation_quote_item_accessory_total, 2),
                ];

                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();

            $per_meters = [];

            $quote_item_per_meter_no = 1;
            $window_price_sheet_12_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM window_price_sheet_12_calculation_quote_item_per_meters WHERE window_price_sheet_12_calculation_quote_item_code = ? AND window_price_sheet_12_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $window_price_sheet_12_calculation_quote_item_code, $window_price_sheet_12_calculation_code, $quote_no);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($window_price_sheet_12_calculation_quote_item_per_meter_code, $window_price_sheet_12_calculation_quote_item_per_meter_name, $window_price_sheet_12_calculation_quote_item_per_meter_price, $window_price_sheet_12_calculation_quote_item_per_meter_width, $window_price_sheet_12_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $per_meters[] = [
                    "no" => $quote_item_per_meter_no,
                    "name" => $window_price_sheet_12_calculation_quote_item_per_meter_name,
                    "price" => number_format($window_price_sheet_12_calculation_quote_item_per_meter_price, 2),
                    "width" => $window_price_sheet_12_calculation_quote_item_per_meter_width,
                    "total" => number_format($window_price_sheet_12_calculation_quote_item_per_meter_total, 2),
                ];

                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();

            $fitting_charges = [];

            $quote_item_fitting_charge_no = 1;
            $window_price_sheet_12_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM window_price_sheet_12_calculation_quote_item_fitting_charges WHERE window_price_sheet_12_calculation_quote_item_code = ? AND window_price_sheet_12_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $window_price_sheet_12_calculation_quote_item_code, $window_price_sheet_12_calculation_code, $quote_no);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($window_price_sheet_12_calculation_quote_item_fitting_charge_code, $window_price_sheet_12_calculation_quote_item_fitting_charge_name, $window_price_sheet_12_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $fitting_charges[] = [
                    "no" => $quote_item_fitting_charge_no,
                    "name" => $window_price_sheet_12_calculation_quote_item_fitting_charge_name,
                    "price" => number_format($window_price_sheet_12_calculation_quote_item_fitting_charge_price, 2)
                ];

                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $fixed_td_arr = [
                "location" => $locations_select === 1 && $location_print[$no] === '1' ? explode('<->', $window_price_sheet_12_calculation_quote_item_location)[0] : '',
                "width_x" => $width_x_print[$no] === '1' ? $window_price_sheet_12_calculation_quote_item_width : '',
                "drop_x" => $drop_x_print[$no] === '1' ? $window_price_sheet_12_calculation_quote_item_drop : '',
                "type" => $type_print[$no] === '1' ? explode('<->', $window_price_sheet_12_calculation_quote_item_type)[0] : '',
                "material" => $materials_and_colours_select === 1 && $material_print[$no] === '1' ? explode('<->', $window_price_sheet_12_calculation_quote_item_material)[0] : '',
                "colour" => $materials_and_colours_select === 1 && $colour_print[$no] === '1' ? explode('<->', $window_price_sheet_12_calculation_quote_item_colour)[0] : '',
            ];

            $fixed_td_sorted_arr = [];

            // sort the above $fixed_td_arr according to the the positions set from cPanel
            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
                $fixed_td_sorted_arr[$variable_1x] = $fixed_td_arr[$variable_1x];
            }

            $price_qty_tot_td_arr = [
                "price" => $price_print[$no] === '1' ?  number_format($window_price_sheet_12_calculation_quote_item_price, 2) : '',
                "qty" => $qty_select === 1 && $qty_print[$no] === '1' ? $window_price_sheet_12_calculation_quote_item_qty : '',
                "total" => $qty_select === 1 && $total_print[$no] === '1' ?  number_format($window_price_sheet_12_calculation_quote_item_total, 2) : ''
            ];

            $extra_arr = [
                "notes" => $window_price_sheet_12_calculation_quote_item_notes && $note_print[$no] === '1' ? $window_price_sheet_12_calculation_quote_item_notes : '',
                "acc" => $accessories && $accessories_select === 1 && $accessory_print[$no] === '1' ? $accessories : [],
                "pm" =>  $per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1' ? $per_meters : [],
                "fc" => $fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' ? $fitting_charges : [],
            ];

            $body[] = (array_filter(array_merge(["no" => $row_no], $field_td_left, $fixed_td_sorted_arr, $field_td_right, $price_qty_tot_td_arr, $extra_arr)));

            //$row_no++;

            $total_arr = [];

            if ($group_discount_print[$no] === '1') {

                $window_price_sheet_12_calculation_quote_item_discount_value = $window_price_sheet_12_calculation_quote_item_price_sub_total * $window_price_sheet_12_calculation_quote_item_discount / 100;
                $window_price_sheet_12_calculation_quote_item_price_total = $window_price_sheet_12_calculation_quote_item_price_sub_total - $window_price_sheet_12_calculation_quote_item_discount_value;

                $total_arr = [
                    "sub_total" => number_format($window_price_sheet_12_calculation_quote_item_price_sub_total, 2),
                    "discount_p" => $window_price_sheet_12_calculation_quote_item_discount,
                    "discount_v" => number_format($window_price_sheet_12_calculation_quote_item_discount_value, 2),
                    "total" => number_format($window_price_sheet_12_calculation_quote_item_price_total, 2),
                ];
            }
        }
    }

    $stmt_3->close();

    if (!empty($body)) {

        $header = (array_filter(array_merge($no_th_arr, $field_th_left, $fixed_th_sorted_arr, $field_th_right, $price_qty_tot_arr)));

        $calc_result[] = [
            'name' => $window_price_sheet_12_calculation_name,
            "header" => $header,
            "body" => $body,
            "footer" => $total_arr,
        ];
    }
}
$stmt_1->close();

$mysqli->close();
