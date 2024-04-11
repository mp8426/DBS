<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . './../cPanel/connect.php';

$print_type = $_GET['type']; // " . $print_type . ", customer_copy, order_sheet
$no = +$_GET['no']; // 0 = " . $print_type . ", 1 = customer_copy, 2 = order_sheet

$window_curtain_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, accessories,per_meters, fitting_charges FROM window_curtain_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($window_curtain_calculation_code, $window_curtain_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
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

    $location_th = $locations_select === 1 && $location_print[$no] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $width_th = $width_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Width</td>' : '';
    $right_return_th = $right_return_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Right Return</td>' : '';
    $left_return_th = $left_return_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Left Return</td>' : '';
    $overlap_th = $overlap_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Overlap</td>' : '';
    $fullness_th = $fullness_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Fullness</td>' : '';
    $supplier_th = $supplier_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Supplier</td>' : '';
    $fabric_th = $fabric_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Fabric</td>' : '';
    $fabric_colour_th = $fabric_colour_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Colour</td>' : '';
    $qty_drop_th = $qty_drop_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Qty Drop</td>' : '';
    $curtain_type_1_th = $curtain_type_1_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Curtain Type 1</td>' : '';
    $cont_meter_th = $cont_meter_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Cont. Meter</td>' : '';
    $curtain_type_2_th = $curtain_type_2_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Curtain Type 2</td>' : '';
    $drop_x_th = $drop_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Drop</td>' : '';
    $hem_heading_th = $hem_heading_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Hem Heading</td>' : '';
    $pattern_repeate_th = $pattern_repeate_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Pattern Repeate</td>' : '';
    $fabric_cut_length_th = $fabric_cut_length_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Fabric Cut Length</td>' : '';
    $fabric_qty_th = $fabric_qty_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Fabric Qty</td>' : '';
    $field_th_right = '';
    $field_th_left = '';
    $price_th = $price_print[$no] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';

    $extra_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 1px solid #000000;"></td>' : ''; // for acc, fitting charge and permeter last td

    $price_th_num_rows = $price_print[$no] === '1' ? 1 : 0;
    $extra_td_num_rows = $price_print[$no] === '1' ? 1 : 0;

    // Print sorted th to dynamic vatiables...
    $table_th = "";
    $table_th_num_rows = 0;
    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
        $table_th .= ${$variable_1x . '_th'};
        if (!empty(${$variable_1x . '_th'})) {
            $table_th_num_rows++;
        }
    }

    $query_2 = "SELECT code, name, side FROM window_curtain_calculation_fields WHERE " . $print_type . " = 1 AND window_curtain_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $window_curtain_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($window_curtain_calculation_field_code, $window_curtain_calculation_field_name, $window_curtain_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {
        if ($window_curtain_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $window_curtain_calculation_field_name . '</th>';
        }
        if ($window_curtain_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $window_curtain_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $tot_th_colspan_x = $table_th_num_rows + $field_th_num_rows + $price_th_num_rows + 1; // with # column
    $tot_th_colspan = $table_th_num_rows + $field_th_num_rows + $price_th_num_rows; // without # column

    $window_curtain_calculation_table_header = '<table><thead>'
            . '<tr><th colspan="' . $tot_th_colspan_x . '"><strong>' . $window_curtain_calculation_name . '</strong></th></tr>'
            . '<tr style="font-weight: bold;">'
            . '<th style="border: 1px solid #000000;">#</th>'
            . $field_th_left
            . $table_th
            . $field_th_right
            . $price_th
            . '</tr>'
            . '</thead><tbody>';

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
                    . "window_curtain_calculation_quote_item_fields.price, "
                    . "window_curtain_calculation_fields.side "
                    . "FROM window_curtain_calculation_quote_item_fields "
                    . "JOIN window_curtain_calculation_fields ON "
                    . "window_curtain_calculation_fields.code = window_curtain_calculation_quote_item_fields.window_curtain_calculation_field_code "
                    . "WHERE "
                    . "window_curtain_calculation_fields." . $print_type . " = 1 AND "
                    . "window_curtain_calculation_quote_item_fields.window_curtain_calculation_quote_item_code = ? AND "
                    . "window_curtain_calculation_quote_item_fields.window_curtain_calculation_code = ? AND "
                    . "window_curtain_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY window_curtain_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_curtain_calculation_quote_item_field_name, $window_curtain_calculation_quote_item_field_price, $window_curtain_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                if ($window_curtain_calculation_quote_item_field_price > 0) {
                    $window_curtain_calculation_quote_item_field_price_x = ' | ' . number_format($window_curtain_calculation_quote_item_field_price, 2);
                } else {
                    $window_curtain_calculation_quote_item_field_price_x = '';
                }

                if ($window_curtain_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_field_name . $window_curtain_calculation_quote_item_field_price_x . '</td>';
                }
                if ($window_curtain_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_field_name . $window_curtain_calculation_quote_item_field_price_x . '</td>';
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

            $tot_th_colspan_for_acc = +$tot_th_colspan - (3 + $extra_td_num_rows);

            while ($stmt_3_2->fetch()) {

                $window_curtain_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_accessory_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Accessory - ' . $window_curtain_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $window_curtain_calculation_quote_item_accessory_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $window_curtain_calculation_quote_item_accessory_qty . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($window_curtain_calculation_quote_item_accessory_total, 2) . '</td>'
                        . $extra_td
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
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_per_meter_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter - ' . $window_curtain_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $window_curtain_calculation_quote_item_per_meter_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $window_curtain_calculation_quote_item_per_meter_width . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($window_curtain_calculation_quote_item_per_meter_total, 2) . '</td>'
                        . $extra_td
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
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_fitting_charge_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge - ' . $window_curtain_calculation_quote_item_fitting_charge_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $window_curtain_calculation_quote_item_fitting_charge_price . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_location)[0] . '</td>' : '';
            $width_td = $width_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_width . '</td>' : '';
            $right_return_td = $right_return_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_right_return . '</td>' : '';
            $left_return_td = $right_return_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_left_return . '</td>' : '';
            $overlap_td = $overlap_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_overlap . '</td>' : '';
            $fullness_td = $fullness_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fullness . '</td>' : '';
            $supplier_td = $supplier_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_supplier)[0] . '</td>' : '';
            $fabric_td = $fabric_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_fabric)[0] . '</td>' : '';
            $fabric_colour_td = $fabric_colour_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_fabric_colour)[0] . '</td>' : '';
            $qty_drop_td = $qty_drop_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_qty_drop . '</td>' : '';
            $curtain_type_1_td = $curtain_type_1_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_curtain_type_1)[0] . '</td>' : '';
            $cont_meter_td = $cont_meter_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_continuous_meter . '</td>' : '';
            $curtain_type_2_td = $curtain_type_2_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $window_curtain_calculation_quote_item_curtain_type_2)[0] . '</td>' : '';
            $drop_x_td = $drop_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_drop . '</td>' : '';
            $hem_heading_td = $hem_heading_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_hem_heading . '</td>' : '';
            $pattern_repeate_td = $pattern_repeate_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_pattern_repeate . '</td>' : '';
            $fabric_cut_length_td = $fabric_cut_length_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fabric_cut_length . '</td>' : '';
            $fabric_qty_td = $fabric_qty_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $window_curtain_calculation_quote_item_fabric_qty . '</td>' : '';
            $price_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($window_curtain_calculation_quote_item_price, 2) . '</td>' : '';

            $price_td_num_rows = $price_print[$no] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
            $table_td = "";
            $table_td_num_rows = 0;
            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
                $table_td .= ${$variable_1x . '_td'};

                if (!empty(${$variable_1x . '_td'})) {
                    $table_td_num_rows++;
                }
            }

            $tot_td_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows + 1; // with # field

            if ($window_curtain_calculation_quote_item_notes || $window_curtain_calculation_quote_item_accessories || $window_curtain_calculation_quote_item_per_meters || $window_curtain_calculation_quote_item_fitting_charges) {

                if ($window_curtain_calculation_quote_item_notes && $note_print[$no] === '1') {

                    $window_curtain_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 1px solid #000000;" colspan="' . $tot_td_colspan . '">'
                            . nl2br($window_curtain_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                } else {
                    $window_curtain_calculation_quote_item_notes_table = "";
                }

                if ($window_curtain_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[$no] === '1') {

                    $window_curtain_calculation_quote_item_accessories_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Accessory</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $window_curtain_calculation_quote_item_accessories;
                } else {
                    $window_curtain_calculation_quote_item_accessories_table = "";
                }

                if ($window_curtain_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1') {

                    $window_curtain_calculation_quote_item_per_meters_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $window_curtain_calculation_quote_item_per_meters;
                } else {
                    $window_curtain_calculation_quote_item_per_meters_table = "";
                }

                if ($window_curtain_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1') {

                    $window_curtain_calculation_quote_item_fitting_charges_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge</th>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . $extra_td
                            . '</tr>'
                            . $window_curtain_calculation_quote_item_fitting_charges;
                } else {
                    $window_curtain_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $window_curtain_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[$no] === '1' && $window_curtain_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[$no] === '1' && $window_curtain_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' && $window_curtain_calculation_quote_item_fitting_charges
                ) {


                    $table_more .= $window_curtain_calculation_quote_item_accessories_table
                            . $window_curtain_calculation_quote_item_per_meters_table
                            . $window_curtain_calculation_quote_item_fitting_charges_table;
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $window_curtain_calculation_quote_items .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $table_td
                    . $field_td_right
                    . $price_td
                    . '</tr>'
                    . $table_more;
            $quote_item_no++;

            if ($group_discount_print[$no] === '1') {

                $window_curtain_calculation_quote_item_discount_value = $window_curtain_calculation_quote_item_price_sub_total * $window_curtain_calculation_quote_item_discount / 100;
                $window_curtain_calculation_quote_item_price_total = $window_curtain_calculation_quote_item_price_sub_total - $window_curtain_calculation_quote_item_discount_value;

                $window_curtain_calculation_total_table_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows;
                $window_curtain_calculation_total = '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $window_curtain_calculation_total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($window_curtain_calculation_quote_item_price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $window_curtain_calculation_total_table_colspan . '">Discount (' . $window_curtain_calculation_quote_item_discount . ' % ) </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">-' . number_format($window_curtain_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $window_curtain_calculation_total_table_colspan . '">Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($window_curtain_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>';
            } else {
                $window_curtain_calculation_total = '';
            }
        }

        $window_curtain_calculation_quote_tables .= $window_curtain_calculation_table_header . $window_curtain_calculation_quote_items . $window_curtain_calculation_total . "</tbody></table>";
        $window_curtain_calculation_quote_tables_2 .= "";
    } else {
        $window_curtain_calculation_quote_tables .= "";
        $window_curtain_calculation_quote_tables_2 .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
