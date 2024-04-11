<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$window_shutter_4_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges FROM window_shutter_4_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($window_shutter_4_calculation_code, $window_shutter_4_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    $query_1x = "SELECT location, width_x, drop_x, type FROM window_shutter_4_calculation_fixed_fields_visibility WHERE window_shutter_4_calculation_code = '$window_shutter_4_calculation_code' ";
    $query_1x_result = mysqli_query($mysqli, $query_1x);

    $columns_1x = array();
    $row_1x = mysqli_fetch_assoc($query_1x_result);
    foreach ($row_1x as $column_1x => $value_1x) {
        $columns_1x[$column_1x] = explode(',', $value_1x)[1];
    }
    asort($columns_1x); // sorting foreach by position

    $query_2x = "SELECT location, width_x, drop_x, type, price, note, group_discount, accessory, per_meter, fitting_charge FROM window_shutter_4_calculation_fixed_fields_visibility WHERE window_shutter_4_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $window_shutter_4_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $width_x_print, $drop_x_print, $type_print, $price_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $location_th = $locations_select === 1 && $location_print[1] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $width_x_th = $width_x_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">Width</td>' : '';
    $drop_x_th = $drop_x_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">Height</td>' : '';
    $type_th = $type_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">Type</td>' : '';
    $field_th_right = '';
    $field_th_left = '';
    $price_th = $price_print[1] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';

    $price_td_num_rows = $price_print[1] === '1' ? 1 : 0;

    // Print sorted th to dynamic vatiables...
    $table_th = "";
    $table_th_num_rows = 0;
    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
        $table_th .= ${$variable_1x . '_th'};

        if (!empty(${$variable_1x . '_th'})) {
            $table_th_num_rows++;
        }
    }

    $query_2 = "SELECT code, name, side FROM window_shutter_4_calculation_fields WHERE customer_copy = 1 AND window_shutter_4_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $window_shutter_4_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($window_shutter_4_calculation_field_code, $window_shutter_4_calculation_field_name, $window_shutter_4_calculation_field_side);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {
        if ($window_shutter_4_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $window_shutter_4_calculation_field_name . '</th>';
        }
        if ($window_shutter_4_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $window_shutter_4_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $window_shutter_4_calculation_table_header = '<span nobr="true">'
            . '<h3>' . $window_shutter_4_calculation_name . '</h3>'
            . '<table cellpadding="4" cellspacing="0" style="text-align: center; background-color: #f1f1f1;">'
            . '<tr style="font-size: 0.9em; font-weight: bold;">'
            . '<th style="border: 0.5px solid #000000;">#</th>'
            . $field_th_left
            . $table_th
            . $field_th_right
            . $price_th
            . '</tr>'
            . '</table>'
            . '</span>';


    $quote_item_no = 1;
    $total_table_colspan = 0;
    $quote_items = "";
    $table_more = "";
    $price_sub_total = 0;

    $quote_item_accessories = "";
    $quote_item_per_meters = "";
    $quote_item_fitting_charges = "";

    $query_3 = "SELECT code, location, width_x, drop_x, type, notes, price, discount FROM window_shutter_4_calculation_quote_items WHERE window_shutter_4_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_shutter_4_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_shutter_4_calculation_quote_item_code, $window_shutter_4_calculation_quote_item_location, $window_shutter_4_calculation_quote_item_width, $window_shutter_4_calculation_quote_item_drop, $window_shutter_4_calculation_quote_item_type, $window_shutter_4_calculation_quote_item_notes, $window_shutter_4_calculation_quote_item_price, $window_shutter_4_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $price_sub_total = $price_sub_total + $window_shutter_4_calculation_quote_item_price;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT window_shutter_4_calculation_quote_item_fields.name, "
                    . "window_shutter_4_calculation_quote_item_fields.price, "
                    . "window_shutter_4_calculation_fields.side "
                    . "FROM window_shutter_4_calculation_quote_item_fields "
                    . "JOIN window_shutter_4_calculation_fields ON "
                    . "window_shutter_4_calculation_fields.code = window_shutter_4_calculation_quote_item_fields.window_shutter_4_calculation_field_code "
                    . "WHERE "
                    . "window_shutter_4_calculation_fields.customer_copy = 1 AND "
                    . "window_shutter_4_calculation_quote_item_fields.window_shutter_4_calculation_quote_item_code = ? AND "
                    . "window_shutter_4_calculation_quote_item_fields.window_shutter_4_calculation_code = ? AND "
                    . "window_shutter_4_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY window_shutter_4_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_shutter_4_calculation_quote_item_code, $window_shutter_4_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_shutter_4_calculation_quote_item_field_name, $window_shutter_4_calculation_quote_item_field_price, $window_shutter_4_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            $table_colspan = $table_th_num_rows + $field_td_num_rows;
            $total_table_colspan = $table_colspan + $price_td_num_rows;

            while ($stmt_3_1->fetch()) {

//                if ($window_shutter_4_calculation_quote_item_field_price > 0) {
//                    $window_shutter_4_calculation_quote_item_field_price_x = ' | ' . number_format($window_shutter_4_calculation_quote_item_field_price, 2);
//                } else {
//                    $window_shutter_4_calculation_quote_item_field_price_x = '';
//                }

                if ($window_shutter_4_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $window_shutter_4_calculation_quote_item_field_name . '</td>';
                }
                if ($window_shutter_4_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $window_shutter_4_calculation_quote_item_field_name . '</td>';
                }
            }
            $stmt_3_1->close();

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM window_shutter_4_calculation_quote_item_accessories WHERE window_shutter_4_calculation_quote_item_code = ? AND window_shutter_4_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_shutter_4_calculation_quote_item_code, $window_shutter_4_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_shutter_4_calculation_quote_item_accessory_code, $window_shutter_4_calculation_quote_item_accessory_name, $window_shutter_4_calculation_quote_item_accessory_price, $window_shutter_4_calculation_quote_item_accessory_qty, $window_shutter_4_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $quote_item_accessories_price_td = $price_td_num_rows ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($window_shutter_4_calculation_quote_item_accessory_total, 2) . '</td>' : '';

                $quote_item_accessories .= '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $quote_item_no . '</td>'
                        . '<td style="border: 0.5px solid #000000;" colspan="' . $table_colspan . '">' . $window_shutter_4_calculation_quote_item_accessory_name . ' ($' . $window_shutter_4_calculation_quote_item_accessory_price . ' x ' . $window_shutter_4_calculation_quote_item_accessory_qty . ')</td>'
                        . $quote_item_accessories_price_td
                        . '</tr>';
            }
            $stmt_3_2->close();

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM window_shutter_4_calculation_quote_item_per_meters WHERE window_shutter_4_calculation_quote_item_code = ? AND window_shutter_4_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $window_shutter_4_calculation_quote_item_code, $window_shutter_4_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($window_shutter_4_calculation_quote_item_per_meter_code, $window_shutter_4_calculation_quote_item_per_meter_name, $window_shutter_4_calculation_quote_item_per_meter_price, $window_shutter_4_calculation_quote_item_per_meter_width, $window_shutter_4_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $quote_item_per_meters_price_td = $price_td_num_rows ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($window_shutter_4_calculation_quote_item_per_meter_total, 2) . '</td>' : '';

                $quote_item_per_meters .= '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $quote_item_no . '</td>'
                        . '<td style="border: 0.5px solid #000000;" colspan="' . $table_colspan . '">' . $window_shutter_4_calculation_quote_item_per_meter_name . ' ($' . $window_shutter_4_calculation_quote_item_per_meter_price . ' x ' . $window_shutter_4_calculation_quote_item_per_meter_width . ')</td>'
                        . $quote_item_per_meters_price_td
                        . '</tr>';
            }
            $stmt_3_3->close();

            $query_3_4 = "SELECT code, name, price FROM window_shutter_4_calculation_quote_item_fitting_charges WHERE window_shutter_4_calculation_quote_item_code = ? AND window_shutter_4_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $window_shutter_4_calculation_quote_item_code, $window_shutter_4_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($window_shutter_4_calculation_quote_item_fitting_charge_code, $window_shutter_4_calculation_quote_item_fitting_charge_name, $window_shutter_4_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $quote_item_fitting_charges_price_td = $price_td_num_rows ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($window_shutter_4_calculation_quote_item_fitting_charge_price, 2) . '</td>' : '';

                $quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $quote_item_no . '</td>'
                        . '<td style="border: 0.5px solid #000000;" colspan="' . $table_colspan . '">' . $window_shutter_4_calculation_quote_item_fitting_charge_name . '</td>'
                        . $quote_item_fitting_charges_price_td
                        . '</tr>';
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_shutter_4_calculation_quote_item_location)[0] . '</td>' : '';
            $width_x_td = $width_x_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_shutter_4_calculation_quote_item_width . '</td>' : '';
            $drop_x_td = $drop_x_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_shutter_4_calculation_quote_item_drop . '</td>' : '';
            $type_td = $type_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_shutter_4_calculation_quote_item_type)[0] . '</td>' : '';
            $price_td = $price_print[1] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($window_shutter_4_calculation_quote_item_price, 2) . '</td>' : '';

            // Print sorted td to dynamic vatiables...
            $table_td = "";
            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
                $table_td .= ${$variable_1x . '_td'};
            }

            $window_shutter_4_calculation_quote_item_notes_table = "";
            if ($window_shutter_4_calculation_quote_item_notes && $note_print[1] === '1') {

                $window_shutter_4_calculation_quote_item_notes_table = '<table cellpadding="4" cellspacing="0" style="text-align: left;" nobr="true">'
                        . '<tr>'
                        . '<td style="border: 0.5px solid #000000;">'
                        . nl2br($window_shutter_4_calculation_quote_item_notes)
                        . '</td>'
                        . '</tr>'
                        . '</table>';
            }

            $quote_items .= '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                    . '<tr style="font-size: 0.9em;">'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $table_td
                    . $field_td_right
                    . $price_td
                    . '</tr>'
                    . '</table>'
                    . $window_shutter_4_calculation_quote_item_notes_table;
            $quote_item_no++;

            if ($group_discount_print[1] === '1') {

                $window_shutter_4_calculation_quote_item_discount_value = $price_sub_total * $window_shutter_4_calculation_quote_item_discount / 100;
                $window_shutter_4_calculation_quote_item_price_total = $price_sub_total - $window_shutter_4_calculation_quote_item_discount_value;

                $window_shutter_4_calculation_total = '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $total_table_colspan . '">Discount (' . $window_shutter_4_calculation_quote_item_discount . '%) </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">-' . number_format($window_shutter_4_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $total_table_colspan . '">Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($window_shutter_4_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>'
                        . '</table>';
            } else {
                $window_shutter_4_calculation_total = '';
            }
        }

        $quote_item_accessories_table = "";
        if ($quote_item_accessories && $accessories_select === 1 && $accessory_print[1] === '1') {

            $quote_item_accessories_table = '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                    . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                    . '<th style="border: 0.5px solid #000000; text-align: center;">#</th>'
                    . '<th style="border: 0.5px solid #000000;" colspan="' . $total_table_colspan . '">Accessory</th>'
                    . '</tr>'
                    . $quote_item_accessories
                    . '</table>';
        }

        $quote_item_per_meters_table = "";
        if ($quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[1] === '1') {

            $quote_item_per_meters_table = '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                    . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                    . '<th style="border: 0.5px solid #000000; text-align: center;">#</th>'
                    . '<th style="border: 0.5px solid #000000;" colspan="' . $total_table_colspan . '">Per Meter</th>'
                    . '</tr>'
                    . $quote_item_per_meters
                    . '</table>';
        }

        $quote_item_fitting_charges_table = "";
        if ($quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[1] === '1') {

            $quote_item_fitting_charges_table = '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                    . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                    . '<th style="border: 0.5px solid #000000; text-align: center;">#</th>'
                    . '<th style="border: 0.5px solid #000000;" colspan="' . $total_table_colspan . '">Fitting Charge</th>'
                    . '</tr>'
                    . $quote_item_fitting_charges
                    . '</table>';
        }

        $table_more = $quote_item_accessories_table .
                $quote_item_per_meters_table .
                $quote_item_fitting_charges_table;

        $window_shutter_4_calculation_quote_tables .= $window_shutter_4_calculation_table_header . $quote_items . $table_more . $window_shutter_4_calculation_total . "<div></div>";
    } else {
        $window_shutter_4_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
