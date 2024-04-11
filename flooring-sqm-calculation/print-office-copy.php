<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

include __DIR__ . './../cPanel/connect.php';

$flooring_sqm_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges FROM flooring_sqm_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($flooring_sqm_calculation_code, $flooring_sqm_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    $query_1x = "SELECT location, unit, manufacturer, collection, colour, required_unit, square_meter, cost, markup FROM flooring_sqm_calculation_fixed_fields_visibility WHERE flooring_sqm_calculation_code = '$flooring_sqm_calculation_code' ";
    $query_1x_result = mysqli_query($mysqli, $query_1x);

    $columns_1x = array();
    $row_1x = mysqli_fetch_assoc($query_1x_result);
    foreach ($row_1x as $column_1x => $value_1x) {
        $columns_1x[$column_1x] = explode(',', $value_1x)[1];
    }
    asort($columns_1x); // sorting foreach by position

    $query_2x = "SELECT location, unit, manufacturer, collection, colour, required_unit, square_meter, cost, markup, price, total, note, group_discount, accessory, per_meter, fitting_charge FROM flooring_sqm_calculation_fixed_fields_visibility WHERE flooring_sqm_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $flooring_sqm_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $unit_print, $manufacturer_print, $collection_print, $colour_print, $required_unit_print, $square_meter_print, $cost_print, $markup_print, $price_print, $total_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $location_th = $locations_select === 1 && $location_print[0] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $unit_th = $unit_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Unit</td>' : '';
    $manufacturer_th = $manufacturer_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Manufacturer</td>' : '';
    $collection_th = $collection_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Collection</td>' : '';
    $colour_th = $colour_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Colour</td>' : '';
    $required_unit_th = $required_unit_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Required Unit</td>' : '';
    $square_meter_th = $square_meter_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Square Meter</td>' : '';
    $cost_th = $cost_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Cost</td>' : '';
    $markup_th = $markup_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Markup</td>' : '';
    $field_th_right = '';
    $field_th_left = '';
    $price_th = $price_print[0] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';
    $total_th = $total_print[0] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Total</th>' : '';

    // Print sorted th to dynamic vatiables...
    $table_th = "";
    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
        $table_th .= ${$variable_1x . '_th'};
    }

    $query_2 = "SELECT code, name, side FROM flooring_sqm_calculation_fields WHERE office_copy = 1 AND flooring_sqm_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $flooring_sqm_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($flooring_sqm_calculation_field_code, $flooring_sqm_calculation_field_name, $flooring_sqm_calculation_field_side);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {
        if ($flooring_sqm_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_field_name . '</th>';
        }
        if ($flooring_sqm_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $flooring_sqm_calculation_table_header = '<span nobr="true">'
            . '<h3>' . $flooring_sqm_calculation_name . '</h3>'
            . '<table cellpadding="4" cellspacing="0" style="text-align: center; background-color: #f1f1f1;">'
            . '<tr style="font-size: 0.9em; font-weight: bold;">'
            . '<th style="border: 0.5px solid #000000;">#</th>'
            . $field_th_left
            . $table_th
            . $field_th_right
            . $price_th
            . $total_th
            . '</tr>'
            . '</table>'
            . '</span>';


    $quote_item_no = 1;
    $flooring_sqm_calculation_quote_items = "";
    $flooring_sqm_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, location, unit, manufacturer, collection, colour, required_unit, notes, price, total, discount FROM flooring_sqm_calculation_quote_items WHERE flooring_sqm_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $flooring_sqm_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_quote_item_location, $flooring_sqm_calculation_quote_item_unit, $flooring_sqm_calculation_quote_item_manufacturer, $flooring_sqm_calculation_quote_item_collection, $flooring_sqm_calculation_quote_item_colour, $flooring_sqm_calculation_quote_item_required_unit, $flooring_sqm_calculation_quote_item_notes, $flooring_sqm_calculation_quote_item_price, $flooring_sqm_calculation_quote_item_total, $flooring_sqm_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $flooring_sqm_calculation_quote_item_price_sub_total = $flooring_sqm_calculation_quote_item_price_sub_total + $flooring_sqm_calculation_quote_item_total;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT flooring_sqm_calculation_quote_item_fields.name, "
                    . "flooring_sqm_calculation_quote_item_fields.price, "
                    . "flooring_sqm_calculation_fields.side "
                    . "FROM flooring_sqm_calculation_quote_item_fields "
                    . "JOIN flooring_sqm_calculation_fields ON "
                    . "flooring_sqm_calculation_fields.code = flooring_sqm_calculation_quote_item_fields.flooring_sqm_calculation_field_code "
                    . "WHERE "
                    . "flooring_sqm_calculation_fields.office_copy = 1 AND "
                    . "flooring_sqm_calculation_quote_item_fields.flooring_sqm_calculation_quote_item_code = ? AND "
                    . "flooring_sqm_calculation_quote_item_fields.flooring_sqm_calculation_code = ? AND "
                    . "flooring_sqm_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY flooring_sqm_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($flooring_sqm_calculation_quote_item_field_name, $flooring_sqm_calculation_quote_item_field_price, $flooring_sqm_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                if ($flooring_sqm_calculation_quote_item_field_price > 0) {
                    $flooring_sqm_calculation_quote_item_field_price_x = ' | ' . number_format($flooring_sqm_calculation_quote_item_field_price, 2);
                } else {
                    $flooring_sqm_calculation_quote_item_field_price_x = '';
                }

                if ($flooring_sqm_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_quote_item_field_name . $flooring_sqm_calculation_quote_item_field_price_x . '</td>';
                }
                if ($flooring_sqm_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_quote_item_field_name . $flooring_sqm_calculation_quote_item_field_price_x . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $flooring_sqm_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM flooring_sqm_calculation_quote_item_accessories WHERE flooring_sqm_calculation_quote_item_code = ? AND flooring_sqm_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($flooring_sqm_calculation_quote_item_accessory_code, $flooring_sqm_calculation_quote_item_accessory_name, $flooring_sqm_calculation_quote_item_accessory_price, $flooring_sqm_calculation_quote_item_accessory_qty, $flooring_sqm_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $flooring_sqm_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="border: 0.5px solid #000000;">' . $quote_item_accessory_no . '. ' . $flooring_sqm_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: right;">' . $flooring_sqm_calculation_quote_item_accessory_price . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $flooring_sqm_calculation_quote_item_accessory_qty . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . number_format($flooring_sqm_calculation_quote_item_accessory_total, 2) . '</td>'
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $flooring_sqm_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM flooring_sqm_calculation_quote_item_per_meters WHERE flooring_sqm_calculation_quote_item_code = ? AND flooring_sqm_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($flooring_sqm_calculation_quote_item_per_meter_code, $flooring_sqm_calculation_quote_item_per_meter_name, $flooring_sqm_calculation_quote_item_per_meter_price, $flooring_sqm_calculation_quote_item_per_meter_width, $flooring_sqm_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $flooring_sqm_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style="border: 0.5px solid #000000;">' . $quote_item_per_meter_no . '. ' . $flooring_sqm_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: right;">' . $flooring_sqm_calculation_quote_item_per_meter_price . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $flooring_sqm_calculation_quote_item_per_meter_width . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . number_format($flooring_sqm_calculation_quote_item_per_meter_total, 2) . '</td>'
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $flooring_sqm_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM flooring_sqm_calculation_quote_item_fitting_charges WHERE flooring_sqm_calculation_quote_item_code = ? AND flooring_sqm_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $flooring_sqm_calculation_quote_item_code, $flooring_sqm_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($flooring_sqm_calculation_quote_item_fitting_charge_code, $flooring_sqm_calculation_quote_item_fitting_charge_name, $flooring_sqm_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $flooring_sqm_calculation_quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 0.5px solid #000000;">' . $quote_item_fitting_charge_no . '. ' . $flooring_sqm_calculation_quote_item_fitting_charge_name . '</td>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . number_format($flooring_sqm_calculation_quote_item_fitting_charge_price, 2) . '</td>'
                        . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            if ($flooring_sqm_calculation_quote_item_collection !== "") {

                $flooring_sqm_calculation_quote_item_collection_code = explode('<->', $flooring_sqm_calculation_quote_item_collection)[1];

                $query_3_5 = "SELECT sqm, cost, markup FROM flooring_sqm_calculation_manufacturer_collection_options WHERE code = ?";

                $stmt_3_5 = $mysqli->prepare($query_3_5);
                $stmt_3_5->bind_param('s', $flooring_sqm_calculation_quote_item_collection_code);
                $stmt_3_5->execute();
                $stmt_3_5->bind_result($flooring_sqm_calculation_quote_item_collection_sqm, $flooring_sqm_calculation_quote_item_collection_cost, $flooring_sqm_calculation_quote_item_collection_markup);
                $stmt_3_5->fetch();
                $stmt_3_5->close();
            } else {
                $flooring_sqm_calculation_quote_item_collection_sqm = 0;
                $flooring_sqm_calculation_quote_item_collection_cost = 0;
                $flooring_sqm_calculation_quote_item_collection_markup = 0;
            }

            $location_td = $locations_select === 1 && $location_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $flooring_sqm_calculation_quote_item_location)[0] . '</td>' : '';
            $unit_td = $unit_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_quote_item_unit . '</td>' : '';
            $manufacturer_td = $manufacturer_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $flooring_sqm_calculation_quote_item_manufacturer)[0] . '</td>' : '';
            $collection_td = $collection_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $flooring_sqm_calculation_quote_item_collection)[0] . '</td>' : '';
            $colour_td = $colour_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $flooring_sqm_calculation_quote_item_colour)[0] . '</td>' : '';
            $required_unit_td = $required_unit_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_quote_item_required_unit . '</td>' : '';
            $square_meter_td = $square_meter_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_quote_item_collection_sqm . '</td>' : '';
            $cost_td = $cost_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . number_format($flooring_sqm_calculation_quote_item_collection_cost, 2) . '</td>' : '';
            $markup_td = $markup_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . $flooring_sqm_calculation_quote_item_collection_markup . '%</td>' : '';
            $price_td = $price_print[0] === '1' ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($flooring_sqm_calculation_quote_item_price, 2) . '</td>' : '';
            $total_td = $total_print[0] === '1' ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($flooring_sqm_calculation_quote_item_total, 2) . '</td>' : '';

            $price_td_num_rows = $price_print[0] === '1' ? 1 : 0;
            $total_td_num_rows = $total_print[0] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
            $table_td = "";
            $table_td_num_rows = 0;
            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
                $table_td .= ${$variable_1x . '_td'};

                if (!empty(${$variable_1x . '_td'})) {
                    $table_td_num_rows++;
                }
            }

            if ($flooring_sqm_calculation_quote_item_notes || $flooring_sqm_calculation_quote_item_accessories || $flooring_sqm_calculation_quote_item_per_meters || $flooring_sqm_calculation_quote_item_fitting_charges) {

                if ($flooring_sqm_calculation_quote_item_notes && $note_print[0] === '1') {

                    $flooring_sqm_calculation_quote_item_notes_table = '<table cellpadding="4" cellspacing="0" style="text-align: left;" nobr="true">'
                            . '<tr>'
                            . '<td style="border: 0.5px solid #000000;">'
                            . nl2br($flooring_sqm_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>'
                            . '</table>';
                } else {
                    $flooring_sqm_calculation_quote_item_notes_table = "";
                }

                if ($flooring_sqm_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[0] === '1') {

                    $flooring_sqm_calculation_quote_item_accessories_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 60%; border: 0.5px solid #000000;">#. Accessory</th>'
                            . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                            . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Qty</th>'
                            . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                            . '</tr>'
                            . $flooring_sqm_calculation_quote_item_accessories
                            . '</table>'
                            . '</td>';
                } else {
                    $flooring_sqm_calculation_quote_item_accessories_table = "";
                }

                if ($flooring_sqm_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[0] === '1') {

                    $flooring_sqm_calculation_quote_item_fitting_charges_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2; border: 0.5px solid #000000;">'
                            . '<th style="border: 0.5px solid #000000;">#. Fitting Charge</th>'
                            . '<th style="text-align: center; border: 0.5px solid #000000;">Price</th>'
                            . '</tr>'
                            . $flooring_sqm_calculation_quote_item_fitting_charges
                            . '</table>'
                            . '</td>';
                } else {
                    $flooring_sqm_calculation_quote_item_fitting_charges_table = "";
                }

                if ($flooring_sqm_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[0] === '1') {

                    $flooring_sqm_calculation_quote_item_per_meters_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 60%; border: 0.5px solid #000000;">#. Per Meter</th>'
                            . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                            . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Width</th>'
                            . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                            . '</tr>'
                            . $flooring_sqm_calculation_quote_item_per_meters
                            . '</table>'
                            . '</td>';
                } else {
                    $flooring_sqm_calculation_quote_item_per_meters_table = "";
                }

                if ($flooring_sqm_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[0] === '1') {

                    $flooring_sqm_calculation_quote_item_fitting_charges_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2; border: 0.5px solid #000000;">'
                            . '<th style="border: 0.5px solid #000000;">#. Fitting Charge</th>'
                            . '<th style="text-align: center; border: 0.5px solid #000000;">Price</th>'
                            . '</tr>'
                            . $flooring_sqm_calculation_quote_item_fitting_charges
                            . '</table>'
                            . '</td>';
                } else {
                    $flooring_sqm_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $flooring_sqm_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[0] === '1' && $flooring_sqm_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[0] === '1' && $flooring_sqm_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[0] === '1' && $flooring_sqm_calculation_quote_item_fitting_charges
                ) {

                    $table_more .= '<table cellpadding="0" cellspacing="0" style="text-align: left;" nobr="true">'
                            . '<tr style="font-size: 0.9em;">'
                            . $flooring_sqm_calculation_quote_item_accessories_table
                            . $flooring_sqm_calculation_quote_item_per_meters_table
                            . $flooring_sqm_calculation_quote_item_fitting_charges_table
                            . '</tr>'
                            . '</table>';
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $flooring_sqm_calculation_quote_items .= '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                    . '<tr style="font-size: 0.9em;">'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $table_td
                    . $field_td_right
                    . $price_td
                    . $total_td
                    . '</tr>'
                    . '</table>'
                    . $table_more;
            $quote_item_no++;

            if ($group_discount_print[0] === '1') {

                $flooring_sqm_calculation_quote_item_discount_value = $flooring_sqm_calculation_quote_item_price_sub_total * $flooring_sqm_calculation_quote_item_discount / 100;
                $flooring_sqm_calculation_quote_item_price_total = $flooring_sqm_calculation_quote_item_price_sub_total - $flooring_sqm_calculation_quote_item_discount_value;

                $flooring_sqm_calculation_total_table_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows + $total_td_num_rows;
                $flooring_sqm_calculation_total = '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $flooring_sqm_calculation_total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($flooring_sqm_calculation_quote_item_price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $flooring_sqm_calculation_total_table_colspan . '">Discount (' . $flooring_sqm_calculation_quote_item_discount . '%) </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">-' . number_format($flooring_sqm_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $flooring_sqm_calculation_total_table_colspan . '">Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($flooring_sqm_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>'
                        . '</table>';
            } else {
                $flooring_sqm_calculation_total = '';
            }
        }

        $flooring_sqm_calculation_quote_tables .= $flooring_sqm_calculation_table_header . $flooring_sqm_calculation_quote_items . $flooring_sqm_calculation_total . "<div></div>";
    } else {
        $flooring_sqm_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
