<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$window_curtain_calculation_quote_tables = "";

$query_1 = "SELECT name, locations, accessories,per_meters, fitting_charges FROM window_curtain_calculations WHERE code = ? ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $window_curtain_calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($window_curtain_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    $query_1x = "SELECT location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, cont_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty FROM window_curtain_calculation_fixed_fields_visibility WHERE window_curtain_calculation_code = '$window_curtain_calculation_code' ";
    $query_1x_result = mysqli_query($mysqli, $query_1x);

    $columns_1x = array();
    $row_1x = mysqli_fetch_assoc($query_1x_result);
    foreach ($row_1x as $column_1x => $value_1x) {
        $columns_1x[$column_1x] = explode(',', $value_1x)[1];
    }
    asort($columns_1x); // sorting foreach by position

    $query_2x = "SELECT location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, cont_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty, price, note, group_discount, accessory, per_meter, fitting_charge FROM window_curtain_calculation_fixed_fields_visibility WHERE window_curtain_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $window_curtain_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $width_print, $right_return_print, $left_return_print, $overlap_print, $fullness_print, $supplier_print, $fabric_print, $fabric_colour_print, $qty_drop_print, $curtain_type_1_print, $cont_meter_print, $curtain_type_2_print, $drop_print, $hem_heading_print, $pattern_repeate_print, $fabric_cut_length_print, $fabric_qty_print, $price_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $location_th = $locations_select === 1 && $location_print[2] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $width_th = $width_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Width</td>' : '';
    $right_return_th = $right_return_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Right Return</td>' : '';
    $left_return_th = $left_return_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Left Return</td>' : '';
    $overlap_th = $overlap_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Overlap</td>' : '';
    $fullness_th = $fullness_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Fullness</td>' : '';
    $supplier_th = $supplier_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Supplier</td>' : '';
    $fabric_th = $fabric_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Fabric</td>' : '';
    $fabric_colour_th = $fabric_colour_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Colour</td>' : '';
    $qty_drop_th = $qty_drop_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Qty Drop</td>' : '';
    $curtain_type_1_th = $curtain_type_1_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Curtain Type 1</td>' : '';
    $cont_meter_th = $cont_meter_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Cont. Meter</td>' : '';
    $curtain_type_2_th = $curtain_type_2_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Curtain Type 2</td>' : '';
    $drop_x_th = $drop_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Drop</td>' : '';
    $hem_heading_th = $hem_heading_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Hem Heading</td>' : '';
    $pattern_repeate_th = $pattern_repeate_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Pattern Repeate</td>' : '';
    $fabric_cut_length_th = $fabric_cut_length_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Fabric Cut Length</td>' : '';
    $fabric_qty_th = $fabric_qty_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">Fabric Qty</td>' : '';
    $field_th_right = '';
    $field_th_left = '';
    $price_th = $price_print[2] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';

    // Print sorted th to dynamic vatiables...
    $table_th = "";
    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
        $table_th .= ${$variable_1x . '_th'};
    }

    $query_2 = "SELECT code, name, side FROM window_curtain_calculation_fields WHERE order_sheet = 1 AND window_curtain_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $window_curtain_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($window_curtain_calculation_field_code, $window_curtain_calculation_field_name, $window_curtain_calculation_field_side);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {
        if ($window_curtain_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $window_curtain_calculation_field_name . '</th>';
        }
        if ($window_curtain_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $window_curtain_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $window_curtain_calculation_table_header = '<span nobr="true">'
            . '<h3>' . $window_curtain_calculation_name . '</h3>'
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
    $window_curtain_calculation_quote_items = "";
    $window_curtain_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, continuous_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty, notes, price, discount FROM window_curtain_calculation_quote_items WHERE window_curtain_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_curtain_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_curtain_calculation_quote_item_code, $window_curtain_calculation_quote_item_location, $window_curtain_calculation_quote_item_width, $window_curtain_calculation_quote_item_right_return, $window_curtain_calculation_quote_item_left_return, $window_curtain_calculation_quote_item_overlap, $window_curtain_calculation_quote_item_fullness, $window_curtain_calculation_quote_item_supplier, $window_curtain_calculation_quote_item_fabric, $window_curtain_calculation_quote_item_fabric_colour, $window_curtain_calculation_quote_item_qty_drop, $window_curtain_calculation_quote_item_curtain_type_1, $window_curtain_calculation_quote_item_continuous_meter, $window_curtain_calculation_quote_item_curtain_type_2, $window_curtain_calculation_quote_item_drop, $window_curtain_calculation_quote_item_hem_heading, $window_curtain_calculation_quote_item_pattern_repeate, $window_curtain_calculation_quote_item_fabric_cut_length, $window_curtain_calculation_quote_item_fabric_qty, $window_curtain_calculation_quote_item_notes, $window_curtain_calculation_quote_item_price, $window_curtain_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $window_curtain_calculation_quote_item_price_sub_total = $window_curtain_calculation_quote_item_price_sub_total + $window_curtain_calculation_quote_item_price;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT window_curtain_calculation_quote_item_fields.name, "
                    . "window_curtain_calculation_fields.side "
                    . "FROM window_curtain_calculation_quote_item_fields "
                    . "JOIN window_curtain_calculation_fields ON "
                    . "window_curtain_calculation_fields.code = window_curtain_calculation_quote_item_fields.window_curtain_calculation_field_code "
                    . "WHERE "
                    . "window_curtain_calculation_fields.order_sheet = 1 AND "
                    . "window_curtain_calculation_quote_item_fields.window_curtain_calculation_quote_item_code = ? AND "
                    . "window_curtain_calculation_quote_item_fields.window_curtain_calculation_code = ? AND "
                    . "window_curtain_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY window_curtain_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_curtain_calculation_quote_item_field_name, $window_curtain_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {
                if ($window_curtain_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_field_name . '</td>';
                }
                if ($window_curtain_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_field_name . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $window_curtain_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM window_curtain_calculation_quote_item_accessories WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_curtain_calculation_quote_item_accessory_code, $window_curtain_calculation_quote_item_accessory_name, $window_curtain_calculation_quote_item_accessory_price, $window_curtain_calculation_quote_item_accessory_qty, $window_curtain_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $window_curtain_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="border: 0.5px solid #000000;">' . $quote_item_accessory_no . '. ' . $window_curtain_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="text-align: right; border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_accessory_price . '</td>'
                        . '<td style="text-align: center; border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_accessory_qty . '</td>'
                        . '<td style="text-align: center; border: 0.5px solid #000000;">' . number_format($window_curtain_calculation_quote_item_accessory_total, 2) . '</td>'
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $window_curtain_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM window_curtain_calculation_quote_item_per_meters WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($window_curtain_calculation_quote_item_per_meter_code, $window_curtain_calculation_quote_item_per_meter_name, $window_curtain_calculation_quote_item_per_meter_price, $window_curtain_calculation_quote_item_per_meter_width, $window_curtain_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $window_curtain_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style="border: 0.5px solid #000000;">' . $quote_item_per_meter_no . '. ' . $window_curtain_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="text-align: right; border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_per_meter_price . '</td>'
                        . '<td style="text-align: center; border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_per_meter_width . '</td>'
                        . '<td style="text-align: center; border: 0.5px solid #000000;">' . number_format($window_curtain_calculation_quote_item_per_meter_total, 2) . '</td>'
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $window_curtain_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM window_curtain_calculation_quote_item_fitting_charges WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($window_curtain_calculation_quote_item_fitting_charge_code, $window_curtain_calculation_quote_item_fitting_charge_name, $window_curtain_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $window_curtain_calculation_quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 0.5px solid #000000;">' . $quote_item_fitting_charge_no . '. ' . $window_curtain_calculation_quote_item_fitting_charge_name . '</td>'
                        . '<td style="text-align: center; border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fitting_charge_price . '</td>'
                        . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_location)[0] . '</td>' : '';
            $width_td = $width_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_width . '</td>' : '';
            $right_return_td = $right_return_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_right_return . '</td>' : '';
            $left_return_td = $right_return_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_left_return . '</td>' : '';
            $overlap_td = $overlap_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_overlap . '</td>' : '';
            $fullness_td = $fullness_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fullness . '</td>' : '';
            $supplier_td = $supplier_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_supplier)[0] . '</td>' : '';
            $fabric_td = $fabric_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_fabric)[0] . '</td>' : '';
            $fabric_colour_td = $fabric_colour_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_fabric_colour)[0] . '</td>' : '';
            $qty_drop_td = $qty_drop_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_qty_drop . '</td>' : '';
            $curtain_type_1_td = $curtain_type_1_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_curtain_type_1)[0] . '</td>' : '';
            $cont_meter_td = $cont_meter_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_continuous_meter . '</td>' : '';
            $curtain_type_2_td = $curtain_type_2_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_curtain_type_2)[0] . '</td>' : '';
            $drop_x_td = $drop_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_drop . '</td>' : '';
            $hem_heading_td = $hem_heading_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_hem_heading . '</td>' : '';
            $pattern_repeate_td = $pattern_repeate_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_pattern_repeate . '</td>' : '';
            $fabric_cut_length_td = $fabric_cut_length_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fabric_cut_length . '</td>' : '';
            $fabric_qty_td = $fabric_qty_print[2] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fabric_qty . '</td>' : '';
            $price_td = $price_print[2] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($window_curtain_calculation_quote_item_price, 2) . '</td>' : '';

            $price_td_num_rows = $price_print[2] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
            $table_td = "";
            $table_td_num_rows = 0;
            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
                $table_td .= ${$variable_1x . '_td'};

                if (!empty(${$variable_1x . '_td'})) {
                    $table_td_num_rows++;
                }
            }

            if ($window_curtain_calculation_quote_item_notes || $window_curtain_calculation_quote_item_accessories || $window_curtain_calculation_quote_item_per_meters || $window_curtain_calculation_quote_item_fitting_charges) {

                if ($window_curtain_calculation_quote_item_notes && $note_print[2] === '1') {

                    $window_curtain_calculation_quote_item_notes_table = '<table cellpadding="4" cellspacing="0" style="text-align: left;" nobr="true">'
                            . '<tr>'
                            . '<td style="border: 0.5px solid #000000;">'
                            . nl2br($window_curtain_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>'
                            . '</table>';
                } else {
                    $window_curtain_calculation_quote_item_notes_table = "";
                }

                if ($window_curtain_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[2] === '1') {

                    $window_curtain_calculation_quote_item_accessories_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 60%; border: 0.5px solid #000000;">#. Accessory</th>'
                            . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                            . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Qty</th>'
                            . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                            . '</tr>'
                            . $window_curtain_calculation_quote_item_accessories
                            . '</table>'
                            . '</td>';
                } else {
                    $window_curtain_calculation_quote_item_accessories_table = "";
                }

                if ($window_curtain_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[2] === '1') {

                    $window_curtain_calculation_quote_item_per_meters_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 60%; border: 0.5px solid #000000;">#. Per Meter</th>'
                            . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                            . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Width</th>'
                            . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                            . '</tr>'
                            . $window_curtain_calculation_quote_item_per_meters
                            . '</table>'
                            . '</td>';
                } else {
                    $window_curtain_calculation_quote_item_per_meters_table = "";
                }

                if ($window_curtain_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[2] === '1') {

                    $window_curtain_calculation_quote_item_fitting_charges_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2; border: 0.5px solid #000000;">'
                            . '<th style="border: 0.5px solid #000000;">#. Fitting Charge</th>'
                            . '<th style="text-align: center; border: 0.5px solid #000000;">Price</th>'
                            . '</tr>'
                            . $window_curtain_calculation_quote_item_fitting_charges
                            . '</table>'
                            . '</td>';
                } else {
                    $window_curtain_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $window_curtain_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[2] === '1' && $accessory_print[2] === '1' && $window_curtain_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[2] === '1' && $window_curtain_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[2] === '1' && $window_curtain_calculation_quote_item_fitting_charges
                ) {

                    $table_more .= '<table cellpadding="0" cellspacing="0" style="text-align: left;" nobr="true">'
                            . '<tr style="font-size: 0.9em;">'
                            . $window_curtain_calculation_quote_item_accessories_table
                            . $window_curtain_calculation_quote_item_per_meters_table
                            . $window_curtain_calculation_quote_item_fitting_charges_table
                            . '</tr>'
                            . '</table>';
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $window_curtain_calculation_quote_items .= '<table cellpadding="4" cellspacing="0"  style="text-align: center;" nobr="true">'
                    . '<tr style="font-size: 0.9em;">'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $table_td
                    . $field_td_right
                    . $price_td
                    . '</tr>'
                    . '</table>'
                    . $table_more;
            $quote_item_no++;

            if ($group_discount_print[2] === '1') {

                $window_curtain_calculation_quote_item_discount_value = $window_curtain_calculation_quote_item_price_sub_total * $window_curtain_calculation_quote_item_discount / 100;
                $window_curtain_calculation_quote_item_price_total = $window_curtain_calculation_quote_item_price_sub_total - $window_curtain_calculation_quote_item_discount_value;

                $window_curtain_calculation_total_table_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows;
                $window_curtain_calculation_total = '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $window_curtain_calculation_total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($window_curtain_calculation_quote_item_price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $window_curtain_calculation_total_table_colspan . '">Discount (' . $window_curtain_calculation_quote_item_discount . '%) </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">-' . number_format($window_curtain_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $window_curtain_calculation_total_table_colspan . '">Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($window_curtain_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>'
                        . '</table>';
            } else {
                $window_curtain_calculation_total = '';
            }
        }

        $window_curtain_calculation_quote_tables .= $window_curtain_calculation_table_header . $window_curtain_calculation_quote_items . $window_curtain_calculation_total . "<div></div>";
    } else {
        $window_curtain_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
