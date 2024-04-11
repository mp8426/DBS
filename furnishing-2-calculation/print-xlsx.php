<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$print_type = $_GET['type']; // " . $print_type . ", customer_copy, order_sheet
$no = +$_GET['no']; // 0 = " . $print_type . ", 1 = customer_copy, 2 = order_sheet

$furnishing_2_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges FROM furnishing_2_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($furnishing_2_calculation_code, $furnishing_2_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    /* $query_1x = "SELECT location, cost, markup FROM furnishing_2_calculation_fixed_fields_visibility WHERE furnishing_2_calculation_code = '$furnishing_2_calculation_code' ";
      $query_1x_result = mysqli_query($mysqli, $query_1x);

      $columns_1x = array();
      $row_1x = mysqli_fetch_assoc($query_1x_result);
      foreach ($row_1x as $column_1x => $value_1x) {
      $columns_1x[$column_1x] = explode(',', $value_1x)[1];
      }
      asort($columns_1x); // sorting foreach by position */

    $query_2x = "SELECT location, cost, markup, price, note, group_discount, accessory, per_meter, fitting_charge FROM furnishing_2_calculation_fixed_fields_visibility WHERE furnishing_2_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $furnishing_2_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $cost_print, $markup_print, $price_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $field_th_right = '';
    $location_th = $locations_select === 1 && $location_print[$no] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $field_th_left = '';
    $cost_th = $cost_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Cost</td>' : '';
    $markup_th = $markup_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Markup</td>' : '';
    $price_th = $price_print[$no] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';

    $extra_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 1px solid #000000;"></td>' : ''; // for acc, fitting charge and permeter last td

    $location_th_num_rows = $locations_select === 1 && $location_print[$no] === '1' ? 1 : 0;
    $cost_th_num_rows = $cost_print[$no] === '1' ? 1 : 0;
    $markup_th_num_rows = $markup_print[$no] === '1' ? 1 : 0;
    $price_th_num_rows = $price_print[$no] === '1' ? 1 : 0;
    $extra_td_num_rows = $price_print[$no] === '1' ? 1 : 0;

    // Print sorted th to dynamic vatiables...
//    $table_th = "";
//    $table_th_num_rows = 0;
//    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
//        $table_th .= ${$variable_1x . '_th'};
//        if (!empty(${$variable_1x . '_th'})) {
//            $table_th_num_rows++;
//        }
//    }

    $query_2 = "SELECT code, name, side FROM furnishing_2_calculation_fields WHERE " . $print_type . " = 1 AND furnishing_2_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $furnishing_2_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($furnishing_2_calculation_field_code, $furnishing_2_calculation_field_name, $furnishing_2_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {
        if ($furnishing_2_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $furnishing_2_calculation_field_name . '</th>';
        }
        if ($furnishing_2_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $furnishing_2_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $tot_th_colspan_x = $location_th_num_rows + $field_th_num_rows + $price_th_num_rows + $cost_th_num_rows + $markup_th_num_rows + 1; // with # column
    $tot_th_colspan = $location_th_num_rows + $field_th_num_rows + $price_th_num_rows + $cost_th_num_rows + $markup_th_num_rows; // without # column

    $furnishing_2_calculation_table_header = '<table><thead>'
            . '<tr><th colspan="' . $tot_th_colspan_x . '"><strong>' . $furnishing_2_calculation_name . '</strong></th></tr>'
            . '<tr style="font-weight: bold;">'
            . '<th style="border: 1px solid #000000;">#</th>'
            . $field_th_left
            . $location_th
            . $field_th_right
            . $cost_th
            . $markup_th
            . $price_th
            . '</tr>'
            . '</thead><tbody>';


    $quote_item_no = 1;
    $furnishing_2_calculation_quote_items = "";
    $furnishing_2_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, location, cost, markup, notes, price, discount FROM furnishing_2_calculation_quote_items WHERE furnishing_2_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $furnishing_2_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_quote_item_location, $furnishing_2_calculation_quote_item_cost, $furnishing_2_calculation_quote_item_markup, $furnishing_2_calculation_quote_item_notes, $furnishing_2_calculation_quote_item_price, $furnishing_2_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $furnishing_2_calculation_quote_item_price_sub_total += $furnishing_2_calculation_quote_item_price;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT furnishing_2_calculation_quote_item_fields.name, "
                    . "furnishing_2_calculation_quote_item_fields.price, "
                    . "furnishing_2_calculation_fields.side "
                    . "FROM furnishing_2_calculation_quote_item_fields "
                    . "JOIN furnishing_2_calculation_fields ON "
                    . "furnishing_2_calculation_fields.code = furnishing_2_calculation_quote_item_fields.furnishing_2_calculation_field_code "
                    . "WHERE "
                    . "furnishing_2_calculation_fields." . $print_type . " = 1 AND "
                    . "furnishing_2_calculation_quote_item_fields.furnishing_2_calculation_quote_item_code = ? AND "
                    . "furnishing_2_calculation_quote_item_fields.furnishing_2_calculation_code = ? AND "
                    . "furnishing_2_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY furnishing_2_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($furnishing_2_calculation_quote_item_field_name, $furnishing_2_calculation_quote_item_field_price, $furnishing_2_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                if ($furnishing_2_calculation_quote_item_field_price > 0) {
                    $furnishing_2_calculation_quote_item_field_price_x = ' | ' . number_format($furnishing_2_calculation_quote_item_field_price, 2);
                } else {
                    $furnishing_2_calculation_quote_item_field_price_x = '';
                }

                if ($furnishing_2_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $furnishing_2_calculation_quote_item_field_name . $furnishing_2_calculation_quote_item_field_price_x . '</td>';
                }
                if ($furnishing_2_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $furnishing_2_calculation_quote_item_field_name . $furnishing_2_calculation_quote_item_field_price_x . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $furnishing_2_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM furnishing_2_calculation_quote_item_accessories WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($furnishing_2_calculation_quote_item_accessory_code, $furnishing_2_calculation_quote_item_accessory_name, $furnishing_2_calculation_quote_item_accessory_price, $furnishing_2_calculation_quote_item_accessory_qty, $furnishing_2_calculation_quote_item_accessory_total);

            $tot_th_colspan_for_acc = +$tot_th_colspan - (3 + $extra_td_num_rows);

            while ($stmt_3_2->fetch()) {

                $furnishing_2_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_accessory_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Accessory - ' . $furnishing_2_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $furnishing_2_calculation_quote_item_accessory_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $furnishing_2_calculation_quote_item_accessory_qty . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($furnishing_2_calculation_quote_item_accessory_total, 2) . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $furnishing_2_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM furnishing_2_calculation_quote_item_per_meters WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($furnishing_2_calculation_quote_item_per_meter_code, $furnishing_2_calculation_quote_item_per_meter_name, $furnishing_2_calculation_quote_item_per_meter_price, $furnishing_2_calculation_quote_item_per_meter_width, $furnishing_2_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $furnishing_2_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_per_meter_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter - ' . $furnishing_2_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $furnishing_2_calculation_quote_item_per_meter_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $furnishing_2_calculation_quote_item_per_meter_width . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($furnishing_2_calculation_quote_item_per_meter_total, 2) . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $furnishing_2_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM furnishing_2_calculation_quote_item_fitting_charges WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($furnishing_2_calculation_quote_item_fitting_charge_code, $furnishing_2_calculation_quote_item_fitting_charge_name, $furnishing_2_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $furnishing_2_calculation_quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_fitting_charge_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge - ' . $furnishing_2_calculation_quote_item_fitting_charge_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $furnishing_2_calculation_quote_item_fitting_charge_price . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $furnishing_2_calculation_quote_item_location)[0] . '</td>' : '';
            $cost_td = $cost_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $furnishing_2_calculation_quote_item_cost . '</td>' : '';
            $markup_td = $markup_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $furnishing_2_calculation_quote_item_markup . '</td>' : '';
            $price_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($furnishing_2_calculation_quote_item_price, 2) . '</td>' : '';

            $location_td_num_rows = $locations_select === 1 && $location_print[$no] === '1' ? 1 : 0;
            $cost_td_num_rows = $cost_print[$no] === '1' ? 1 : 0;
            $markup_td_num_rows = $markup_print[$no] === '1' ? 1 : 0;
            $price_td_num_rows = $price_print[$no] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
//            $table_td = "";
//            $table_td_num_rows = 0;
//            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
//                $table_td .= ${$variable_1x . '_td'};
//
//                if (!empty(${$variable_1x . '_td'})) {
//                    $table_td_num_rows++;
//                }
//            }

            $tot_td_colspan = $location_td_num_rows + $field_td_num_rows + $price_td_num_rows + $cost_td_num_rows + $markup_td_num_rows + 1; // with # field

            if ($furnishing_2_calculation_quote_item_notes || $furnishing_2_calculation_quote_item_accessories || $furnishing_2_calculation_quote_item_per_meters || $furnishing_2_calculation_quote_item_fitting_charges) {

                if ($furnishing_2_calculation_quote_item_notes && $note_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 1px solid #000000;" colspan="' . $tot_td_colspan . '">'
                            . nl2br($furnishing_2_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                } else {
                    $furnishing_2_calculation_quote_item_notes_table = "";
                }

                if ($furnishing_2_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_accessories_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Accessory</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $furnishing_2_calculation_quote_item_accessories;
                } else {
                    $furnishing_2_calculation_quote_item_accessories_table = "";
                }

                if ($furnishing_2_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_per_meters_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $furnishing_2_calculation_quote_item_per_meters;
                } else {
                    $furnishing_2_calculation_quote_item_per_meters_table = "";
                }

                if ($furnishing_2_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_fitting_charges_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge</th>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . $extra_td
                            . '</tr>'
                            . $furnishing_2_calculation_quote_item_fitting_charges;
                } else {
                    $furnishing_2_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $furnishing_2_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[$no] === '1' && $furnishing_2_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[$no] === '1' && $furnishing_2_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' && $furnishing_2_calculation_quote_item_fitting_charges
                ) {


                    $table_more .= $furnishing_2_calculation_quote_item_accessories_table
                            . $furnishing_2_calculation_quote_item_per_meters_table
                            . $furnishing_2_calculation_quote_item_fitting_charges_table;
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            if ($furnishing_2_calculation_quote_item_notes || $furnishing_2_calculation_quote_item_accessories || $furnishing_2_calculation_quote_item_per_meters || $furnishing_2_calculation_quote_item_fitting_charges) {

                if ($furnishing_2_calculation_quote_item_notes && $note_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_notes_table = '<table cellpadding="4" cellspacing="0" style="text-align: left;" nobr="true">'
                            . '<tr>'
                            . '<td style="border: 0.5px solid #000000;">'
                            . nl2br($furnishing_2_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>'
                            . '</table>';
                } else {
                    $furnishing_2_calculation_quote_item_notes_table = "";
                }

                if ($furnishing_2_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_accessories_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 60%; border: 0.5px solid #000000;">#. Accessory</th>'
                            . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                            . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Qty</th>'
                            . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                            . '</tr>'
                            . $furnishing_2_calculation_quote_item_accessories
                            . '</table>'
                            . '</td>';
                } else {
                    $furnishing_2_calculation_quote_item_accessories_table = "";
                }

                if ($furnishing_2_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_per_meters_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 60%; border: 0.5px solid #000000;">#. Per Meter</th>'
                            . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                            . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Width</th>'
                            . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                            . '</tr>'
                            . $furnishing_2_calculation_quote_item_per_meters
                            . '</table>'
                            . '</td>';
                } else {
                    $furnishing_2_calculation_quote_item_per_meters_table = "";
                }

                if ($furnishing_2_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1') {

                    $furnishing_2_calculation_quote_item_fitting_charges_table = '<td style="border: 0.5px solid #000000;">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2; border: 0.5px solid #000000;">'
                            . '<th style="border: 0.5px solid #000000;">#. Fitting Charge</th>'
                            . '<th style="text-align: center; border: 0.5px solid #000000;">Price</th>'
                            . '</tr>'
                            . $furnishing_2_calculation_quote_item_fitting_charges
                            . '</table>'
                            . '</td>';
                } else {
                    $furnishing_2_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $furnishing_2_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[$no] === '1' && $furnishing_2_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[$no] === '1' && $furnishing_2_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' && $furnishing_2_calculation_quote_item_fitting_charges
                ) {

                    $table_more .= '<table cellpadding="0" cellspacing="0" style="text-align: left;" nobr="true">'
                            . '<tr style="font-size: 0.9em;">'
                            . $furnishing_2_calculation_quote_item_accessories_table
                            . $furnishing_2_calculation_quote_item_per_meters_table
                            . $furnishing_2_calculation_quote_item_fitting_charges_table
                            . '</tr>'
                            . '</table>';
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $furnishing_2_calculation_quote_items .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $location_td
                    . $field_td_right
                    . $cost_td
                    . $markup_td
                    . $price_td
                    . '</tr>'
                    . $table_more;
            $quote_item_no++;

            if ($group_discount_print[$no] === '1') {

                $furnishing_2_calculation_quote_item_discount_value = $furnishing_2_calculation_quote_item_price_sub_total * $furnishing_2_calculation_quote_item_discount / 100;
                $furnishing_2_calculation_quote_item_price_total = $furnishing_2_calculation_quote_item_price_sub_total - $furnishing_2_calculation_quote_item_discount_value;

                $furnishing_2_calculation_total_table_colspan = $location_td_num_rows + $field_td_num_rows + $price_td_num_rows + $cost_td_num_rows + $markup_td_num_rows;
                $furnishing_2_calculation_total = '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $furnishing_2_calculation_total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($furnishing_2_calculation_quote_item_price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $furnishing_2_calculation_total_table_colspan . '">Discount (' . $furnishing_2_calculation_quote_item_discount . ' % ) </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">-' . number_format($furnishing_2_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $furnishing_2_calculation_total_table_colspan . '">Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($furnishing_2_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>';
            } else {
                $furnishing_2_calculation_total = '';
            }
        }

        $furnishing_2_calculation_quote_tables .= $furnishing_2_calculation_table_header . $furnishing_2_calculation_quote_items . $furnishing_2_calculation_total . "</tbody></table>";
        $furnishing_2_calculation_quote_tables_2 .= "";
    } else {
        $furnishing_2_calculation_quote_tables .= "";
        $furnishing_2_calculation_quote_tables_2 .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
