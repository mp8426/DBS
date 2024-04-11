<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$print_type = $_GET['type']; // " . $print_type . ", customer_copy, order_sheet
$no = +$_GET['no']; // 0 = " . $print_type . ", 1 = customer_copy, 2 = order_sheet

$twc_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, materials_and_colours, accessories, per_meters, fitting_charges FROM twc_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($twc_calculation_code, $twc_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    $query_1x = "SELECT location, width_x, drop_x, type, material, colour FROM twc_calculation_fixed_fields_visibility WHERE twc_calculation_code = '$twc_calculation_code' ";
    $query_1x_result = mysqli_query($mysqli, $query_1x);

    $columns_1x = array();
    $row_1x = mysqli_fetch_assoc($query_1x_result);
    foreach ($row_1x as $column_1x => $value_1x) {
        $columns_1x[$column_1x] = explode(',', $value_1x)[1];
    }
    asort($columns_1x); // sorting foreach by position

    $query_2x = "SELECT location, width_x, drop_x, type, material, colour, price, note, group_discount, accessory, per_meter, fitting_charge FROM twc_calculation_fixed_fields_visibility WHERE twc_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $twc_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $width_x_print, $drop_x_print, $type_print, $material_print, $colour_print, $price_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $location_th = $locations_select === 1 && $location_print[$no] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $width_x_th = $width_x_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Width</td>' : '';
    $drop_x_th = $drop_x_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Height</td>' : '';
    $type_th = $type_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Type</td>' : '';
    $material_th = $materials_and_colours_select === 1 && $material_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Material</td>' : '';
    $colour_th = $materials_and_colours_select === 1 && $colour_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Colour</td>' : '';
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

    $query_2 = "SELECT code, name, side FROM twc_calculation_fields WHERE " . $print_type . " = 1 AND twc_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $twc_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($twc_calculation_field_code, $twc_calculation_field_name, $twc_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {
        if ($twc_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $twc_calculation_field_name . '</th>';
        }
        if ($twc_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $twc_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $tot_th_colspan_x = $table_th_num_rows + $field_th_num_rows + $price_th_num_rows + 1; // with # column
    $tot_th_colspan = $table_th_num_rows + $field_th_num_rows + $price_th_num_rows; // without # column

    $twc_calculation_table_header = '<table><thead>'
            . '<tr><th colspan="' . $tot_th_colspan_x . '"><strong>' . $twc_calculation_name . '</strong></th></tr>'
            . '<tr style="font-weight: bold;">'
            . '<th style="border: 1px solid #000000;">#</th>'
            . $field_th_left
            . $table_th
            . $field_th_right
            . $price_th
            . '</tr>'
            . '</thead><tbody>';

    $quote_item_no = 1;
    $twc_calculation_quote_items = "";
    $twc_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, location, width_x, drop_x, type, material, colour, notes, price, discount FROM twc_calculation_quote_items WHERE twc_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $twc_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($twc_calculation_quote_item_code, $twc_calculation_quote_item_location, $twc_calculation_quote_item_width, $twc_calculation_quote_item_drop, $twc_calculation_quote_item_type, $twc_calculation_quote_item_material, $twc_calculation_quote_item_colour, $twc_calculation_quote_item_notes, $twc_calculation_quote_item_price, $twc_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $twc_calculation_quote_item_price_sub_total += $twc_calculation_quote_item_price;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT twc_calculation_quote_item_fields.name, "
                    . "twc_calculation_quote_item_fields.price, "
                    . "twc_calculation_fields.side "
                    . "FROM twc_calculation_quote_item_fields "
                    . "JOIN twc_calculation_fields ON "
                    . "twc_calculation_fields.code = twc_calculation_quote_item_fields.twc_calculation_field_code "
                    . "WHERE "
                    . "twc_calculation_fields." . $print_type . " = 1 AND "
                    . "twc_calculation_quote_item_fields.twc_calculation_quote_item_code = ? AND "
                    . "twc_calculation_quote_item_fields.twc_calculation_code = ? AND "
                    . "twc_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY twc_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($twc_calculation_quote_item_field_name, $twc_calculation_quote_item_field_price, $twc_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                if ($twc_calculation_quote_item_field_price > 0) {
                    $twc_calculation_quote_item_field_price_x = ' | ' . number_format($twc_calculation_quote_item_field_price, 2);
                } else {
                    $twc_calculation_quote_item_field_price_x = '';
                }

                if ($twc_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $twc_calculation_quote_item_field_name . $twc_calculation_quote_item_field_price_x . '</td>';
                }
                if ($twc_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $twc_calculation_quote_item_field_name . $twc_calculation_quote_item_field_price_x . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $twc_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM twc_calculation_quote_item_accessories WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($twc_calculation_quote_item_accessory_code, $twc_calculation_quote_item_accessory_name, $twc_calculation_quote_item_accessory_price, $twc_calculation_quote_item_accessory_qty, $twc_calculation_quote_item_accessory_total);

            $tot_th_colspan_for_acc = +$tot_th_colspan - (3 + $extra_td_num_rows);

            while ($stmt_3_2->fetch()) {

                $twc_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_accessory_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Accessory - ' . $twc_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $twc_calculation_quote_item_accessory_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $twc_calculation_quote_item_accessory_qty . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($twc_calculation_quote_item_accessory_total, 2) . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $twc_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM twc_calculation_quote_item_per_meters WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($twc_calculation_quote_item_per_meter_code, $twc_calculation_quote_item_per_meter_name, $twc_calculation_quote_item_per_meter_price, $twc_calculation_quote_item_per_meter_width, $twc_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $twc_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_per_meter_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter - ' . $twc_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $twc_calculation_quote_item_per_meter_price . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: center;">' . $twc_calculation_quote_item_per_meter_width . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($twc_calculation_quote_item_per_meter_total, 2) . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $twc_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM twc_calculation_quote_item_fitting_charges WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($twc_calculation_quote_item_fitting_charge_code, $twc_calculation_quote_item_fitting_charge_name, $twc_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $twc_calculation_quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '. ' . $quote_item_fitting_charge_no . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge - ' . $twc_calculation_quote_item_fitting_charge_name . '</td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;">' . $twc_calculation_quote_item_fitting_charge_price . '</td>'
                        . $extra_td
                        . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $twc_calculation_quote_item_location)[0] . '</td>' : '';
            $width_x_td = $width_x_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $twc_calculation_quote_item_width . '</td>' : '';
            $drop_x_td = $drop_x_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $twc_calculation_quote_item_drop . '</td>' : '';
            $type_td = $type_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $twc_calculation_quote_item_type)[0] . '</td>' : '';
            $material_td = $materials_and_colours_select === 1 && $material_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $twc_calculation_quote_item_material)[0] . '</td>' : '';
            $colour_td = $materials_and_colours_select === 1 && $colour_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $twc_calculation_quote_item_colour)[0] . '</td>' : '';
            $price_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($twc_calculation_quote_item_price, 2) . '</td>' : '';

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

            if ($twc_calculation_quote_item_notes || $twc_calculation_quote_item_accessories || $twc_calculation_quote_item_per_meters || $twc_calculation_quote_item_fitting_charges) {

                if ($twc_calculation_quote_item_notes && $note_print[$no] === '1') {

                    $twc_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 1px solid #000000;" colspan="' . $tot_td_colspan . '">'
                            . nl2br($twc_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                } else {
                    $twc_calculation_quote_item_notes_table = "";
                }

                if ($twc_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[$no] === '1') {

                    $twc_calculation_quote_item_accessories_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Accessory</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $twc_calculation_quote_item_accessories;
                } else {
                    $twc_calculation_quote_item_accessories_table = "";
                }

                if ($twc_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1') {

                    $twc_calculation_quote_item_per_meters_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                            . $extra_td
                            . '</tr>'
                            . $twc_calculation_quote_item_per_meters;
                } else {
                    $twc_calculation_quote_item_per_meters_table = "";
                }

                if ($twc_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1') {

                    $twc_calculation_quote_item_fitting_charges_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                            . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge</th>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                            . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                            . $extra_td
                            . '</tr>'
                            . $twc_calculation_quote_item_fitting_charges;
                } else {
                    $twc_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $twc_calculation_quote_item_notes_table;

                if ($accessories_select === 1 && $accessory_print[$no] === '1' && $twc_calculation_quote_item_accessories ||
                        $per_meters_select === 1 && $per_meter_print[$no] === '1' && $twc_calculation_quote_item_per_meters ||
                        $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' && $twc_calculation_quote_item_fitting_charges
                ) {


                    $table_more .= $twc_calculation_quote_item_accessories_table
                            . $twc_calculation_quote_item_per_meters_table
                            . $twc_calculation_quote_item_fitting_charges_table;
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $twc_calculation_quote_items .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $table_td
                    . $field_td_right
                    . $price_td
                    . '</tr>'
                    . $table_more;
            $quote_item_no++;

            if ($group_discount_print[$no] === '1') {

                $twc_calculation_quote_item_discount_value = $twc_calculation_quote_item_price_sub_total * $twc_calculation_quote_item_discount / 100;
                $twc_calculation_quote_item_price_total = $twc_calculation_quote_item_price_sub_total - $twc_calculation_quote_item_discount_value;

                $twc_calculation_total_table_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows;
                $twc_calculation_total = '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $twc_calculation_total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($twc_calculation_quote_item_price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="border: 1px solid #000000;">'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $twc_calculation_total_table_colspan . '">Discount (' . $twc_calculation_quote_item_discount . ' % ) </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">-' . number_format($twc_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $twc_calculation_total_table_colspan . '">Total </th>'
                        . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($twc_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>';
            } else {
                $twc_calculation_total = '';
            }
        }

        $twc_calculation_quote_tables .= $twc_calculation_table_header . $twc_calculation_quote_items . $twc_calculation_total . "</tbody></table>";
        $twc_calculation_quote_tables_2 .= "";
    } else {
        $twc_calculation_quote_tables .= "";
        $twc_calculation_quote_tables_2 .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
