<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

include __DIR__ . './../cPanel/connect.php';

$print_type = $_GET['type']; // " . $print_type . ", customer_copy, order_sheet
$no = +$_GET['no']; // 0 = " . $print_type . ", 1 = customer_copy, 2 = order_sheet

$flooring_lab_2_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges FROM flooring_lab_2_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($flooring_lab_2_calculation_code, $flooring_lab_2_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    $query_1x = "SELECT location, unit, installation_type FROM flooring_lab_2_calculation_fixed_fields_visibility WHERE flooring_lab_2_calculation_code = '$flooring_lab_2_calculation_code' ";
    $query_1x_result = mysqli_query($mysqli, $query_1x);

    $columns_1x = array();
    $row_1x = mysqli_fetch_assoc($query_1x_result);
    foreach ($row_1x as $column_1x => $value_1x) {
        $columns_1x[$column_1x] = explode(',', $value_1x)[1];
    }
    asort($columns_1x); // sorting foreach by position

    $query_2x = "SELECT location, unit, installation_type, price, total, note, group_discount, accessory, per_meter, fitting_charge FROM flooring_lab_2_calculation_fixed_fields_visibility WHERE flooring_lab_2_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $flooring_lab_2_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $unit_print, $installation_type_print, $price_print, $total_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $location_th = $locations_select === 1 && $location_print[$no] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $unit_th = $unit_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Unit</td>' : '';
    $installation_type_th = $installation_type_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Installation Type</td>' : '';
    $field_th_right = '';
    $field_th_left = '';
    $price_th = $price_print[$no] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';
    $total_th = $total_print[$no] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Total</th>' : '';

    $extra_td = $total_print[$no] === '1' ? '<td style="text-align: right; border: 1px solid #000000;"></td>' : ''; // for acc, fitting charge and permeter last td

    $price_th_num_rows = $price_print[$no] === '1' ? 1 : 0;
    $total_th_num_rows = $total_print[$no] === '1' ? 1 : 0;
    $extra_td_num_rows = $total_print[$no] === '1' ? 1 : 0;

    // Print sorted th to dynamic vatiables...
    $table_th = "";
    $table_th_num_rows = 0;
    foreach ($columns_1x as $variable_1x => $variable_1x_value) {
        $table_th .= ${$variable_1x . '_th'};
        if (!empty(${$variable_1x . '_th'})) {
            $table_th_num_rows++;
        }
    }

    $query_2 = "SELECT code, name, side FROM flooring_lab_2_calculation_fields WHERE " . $print_type . " = 1 AND flooring_lab_2_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $flooring_lab_2_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($flooring_lab_2_calculation_field_code, $flooring_lab_2_calculation_field_name, $flooring_lab_2_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {
        if ($flooring_lab_2_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $flooring_lab_2_calculation_field_name . '</th>';
        }
        if ($flooring_lab_2_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $flooring_lab_2_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $tot_th_colspan_x = $table_th_num_rows + $field_th_num_rows + $price_th_num_rows + $total_th_num_rows + 1; // with # column
    $tot_th_colspan = $table_th_num_rows + $field_th_num_rows + $price_th_num_rows + $total_th_num_rows; // without # column

    $flooring_lab_2_calculation_table_header = '<table><thead>'
            . '<tr><th colspan="' . $tot_th_colspan_x . '"><strong>' . $flooring_lab_2_calculation_name . '</strong></th></tr>'
            . '<tr style="font-weight: bold;">'
            . '<th style="border: 1px solid #000000;">#</th>'
            . $field_th_left
            . $table_th
            . $field_th_right
            . $price_th
            . $total_th
            . '</tr>'
            . '</thead><tbody>';


    $quote_item_no = 1;
    $flooring_lab_2_calculation_quote_items = "";
    $flooring_lab_2_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, location, unit, installation_type, notes, price, total, discount FROM flooring_lab_2_calculation_quote_items WHERE flooring_lab_2_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $flooring_lab_2_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($flooring_lab_2_calculation_quote_item_code, $flooring_lab_2_calculation_quote_item_location, $flooring_lab_2_calculation_quote_item_unit, $flooring_lab_2_calculation_quote_item_installation_type, $flooring_lab_2_calculation_quote_item_notes, $flooring_lab_2_calculation_quote_item_price, $flooring_lab_2_calculation_quote_item_total, $flooring_lab_2_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $flooring_lab_2_calculation_quote_item_price_sub_total = $flooring_lab_2_calculation_quote_item_price_sub_total + $flooring_lab_2_calculation_quote_item_total;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT flooring_lab_2_calculation_quote_item_fields.name, "
                    . "flooring_lab_2_calculation_quote_item_fields.price, "
                    . "flooring_lab_2_calculation_fields.side "
                    . "FROM flooring_lab_2_calculation_quote_item_fields "
                    . "JOIN flooring_lab_2_calculation_fields ON "
                    . "flooring_lab_2_calculation_fields.code = flooring_lab_2_calculation_quote_item_fields.flooring_lab_2_calculation_field_code "
                    . "WHERE "
                    . "flooring_lab_2_calculation_fields." . $print_type . " = 1 AND "
                    . "flooring_lab_2_calculation_quote_item_fields.flooring_lab_2_calculation_quote_item_code = ? AND "
                    . "flooring_lab_2_calculation_quote_item_fields.flooring_lab_2_calculation_code = ? AND "
                    . "flooring_lab_2_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY flooring_lab_2_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $flooring_lab_2_calculation_quote_item_code, $flooring_lab_2_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($flooring_lab_2_calculation_quote_item_field_name, $flooring_lab_2_calculation_quote_item_field_price, $flooring_lab_2_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                if ($flooring_lab_2_calculation_quote_item_field_price > 0) {
                    $flooring_lab_2_calculation_quote_item_field_price_x = ' | ' . number_format($flooring_lab_2_calculation_quote_item_field_price, 2);
                } else {
                    $flooring_lab_2_calculation_quote_item_field_price_x = '';
                }

                if ($flooring_lab_2_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $flooring_lab_2_calculation_quote_item_field_name . $flooring_lab_2_calculation_quote_item_field_price_x . '</td>';
                }
                if ($flooring_lab_2_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $flooring_lab_2_calculation_quote_item_field_name . $flooring_lab_2_calculation_quote_item_field_price_x . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $flooring_lab_2_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM flooring_lab_2_calculation_quote_item_accessories WHERE flooring_lab_2_calculation_quote_item_code = ? AND flooring_lab_2_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $flooring_lab_2_calculation_quote_item_code, $flooring_lab_2_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($flooring_lab_2_calculation_quote_item_accessory_code, $flooring_lab_2_calculation_quote_item_accessory_name, $flooring_lab_2_calculation_quote_item_accessory_price, $flooring_lab_2_calculation_quote_item_accessory_qty, $flooring_lab_2_calculation_quote_item_accessory_total);

            $tot_th_colspan_for_acc = +$tot_th_colspan - (3 + $extra_td_num_rows);

            while ($stmt_3_2->fetch()) {

                $flooring_lab_2_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_accessory_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Accessory - ' . $flooring_lab_2_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $flooring_lab_2_calculation_quote_item_accessory_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $flooring_lab_2_calculation_quote_item_accessory_qty . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($flooring_lab_2_calculation_quote_item_accessory_total, 2) . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $flooring_lab_2_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM flooring_lab_2_calculation_quote_item_per_meters WHERE flooring_lab_2_calculation_quote_item_code = ? AND flooring_lab_2_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $flooring_lab_2_calculation_quote_item_code, $flooring_lab_2_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($flooring_lab_2_calculation_quote_item_per_meter_code, $flooring_lab_2_calculation_quote_item_per_meter_name, $flooring_lab_2_calculation_quote_item_per_meter_price, $flooring_lab_2_calculation_quote_item_per_meter_width, $flooring_lab_2_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $flooring_lab_2_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_per_meter_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter - ' . $flooring_lab_2_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $flooring_lab_2_calculation_quote_item_per_meter_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $flooring_lab_2_calculation_quote_item_per_meter_width . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($flooring_lab_2_calculation_quote_item_per_meter_total, 2) . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $flooring_lab_2_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM flooring_lab_2_calculation_quote_item_fitting_charges WHERE flooring_lab_2_calculation_quote_item_code = ? AND flooring_lab_2_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $flooring_lab_2_calculation_quote_item_code, $flooring_lab_2_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($flooring_lab_2_calculation_quote_item_fitting_charge_code, $flooring_lab_2_calculation_quote_item_fitting_charge_name, $flooring_lab_2_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $flooring_lab_2_calculation_quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_fitting_charge_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge - ' . $flooring_lab_2_calculation_quote_item_fitting_charge_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $flooring_lab_2_calculation_quote_item_fitting_charge_price . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $flooring_lab_2_calculation_quote_item_location)[0] . '</td>' : '';
            $unit_td = $unit_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $flooring_lab_2_calculation_quote_item_unit . '</td>' : '';
            $installation_type_td = $installation_type_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $flooring_lab_2_calculation_quote_item_installation_type)[0] . '</td>' : '';
            $price_td = $price_print[$no] === '1' ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($flooring_lab_2_calculation_quote_item_price, 2) . '</td>' : '';
            $total_td = $total_print[$no] === '1' ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($flooring_lab_2_calculation_quote_item_total, 2) . '</td>' : '';

            $price_td_num_rows = $price_print[$no] === '1' ? 1 : 0;
            $total_td_num_rows = $total_print[$no] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
            $table_td = "";
            $table_td_num_rows = 0;
            foreach ($columns_1x as $variable_1x => $variable_1x_value) {
                $table_td .= ${$variable_1x . '_td'};

                if (!empty(${$variable_1x . '_td'})) {
                    $table_td_num_rows++;
                }
            }

            $tot_td_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows + $total_td_num_rows + 1; // with # field

            if ($flooring_lab_2_calculation_quote_item_notes || $flooring_lab_2_calculation_quote_item_accessories || $flooring_lab_2_calculation_quote_item_per_meters || $flooring_lab_2_calculation_quote_item_fitting_charges) {

                if ($flooring_lab_2_calculation_quote_item_notes && $note_print[$no] === '1') {

                    $flooring_lab_2_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 1px solid #000000;" colspan="' . $tot_td_colspan . '">'
                            . nl2br($flooring_lab_2_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                } else {
                    $flooring_lab_2_calculation_quote_item_notes_table = "";
                }

                if ($flooring_lab_2_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[$no] === '1') {

                    $flooring_lab_2_calculation_quote_item_accessories_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Accessory</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $flooring_lab_2_calculation_quote_item_accessories;
                } else {
                    $flooring_lab_2_calculation_quote_item_accessories_table = "";
                }

                if ($flooring_lab_2_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1') {

                    $flooring_lab_2_calculation_quote_item_per_meters_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $flooring_lab_2_calculation_quote_item_per_meters;
                } else {
                    $flooring_lab_2_calculation_quote_item_per_meters_table = "";
                }

                if ($flooring_lab_2_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1') {

                    $flooring_lab_2_calculation_quote_item_fitting_charges_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge</th>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . $extra_td
                            . '</tr>'
                            . $flooring_lab_2_calculation_quote_item_fitting_charges;
                } else {
                    $flooring_lab_2_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $flooring_lab_2_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[$no] === '1' && $flooring_lab_2_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[$no] === '1' && $flooring_lab_2_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' && $flooring_lab_2_calculation_quote_item_fitting_charges
                ) {


                    $table_more .= $flooring_lab_2_calculation_quote_item_accessories_table
                            . $flooring_lab_2_calculation_quote_item_per_meters_table
                            . $flooring_lab_2_calculation_quote_item_fitting_charges_table;
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $flooring_lab_2_calculation_quote_items .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $table_td
                    . $field_td_right
                    . $price_td
                    . $total_td
                    . '</tr>'
                    . $table_more;
            $quote_item_no++;

            if ($group_discount_print[$no] === '1') {

                $flooring_lab_2_calculation_quote_item_discount_value = $flooring_lab_2_calculation_quote_item_price_sub_total * $flooring_lab_2_calculation_quote_item_discount / 100;
                $flooring_lab_2_calculation_quote_item_price_total = $flooring_lab_2_calculation_quote_item_price_sub_total - $flooring_lab_2_calculation_quote_item_discount_value;

                $flooring_lab_2_calculation_total_table_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows + $total_td_num_rows;
                $flooring_lab_2_calculation_total = '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $flooring_lab_2_calculation_total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($flooring_lab_2_calculation_quote_item_price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $flooring_lab_2_calculation_total_table_colspan . '">Discount (' . $flooring_lab_2_calculation_quote_item_discount . ' % ) </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">-' . number_format($flooring_lab_2_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $flooring_lab_2_calculation_total_table_colspan . '">Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($flooring_lab_2_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>';
            } else {
                $flooring_lab_2_calculation_total = '';
            }
        }

        $flooring_lab_2_calculation_quote_tables .= $flooring_lab_2_calculation_table_header . $flooring_lab_2_calculation_quote_items . $flooring_lab_2_calculation_total . "</tbody></table>";
    } else {
        $flooring_lab_2_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
