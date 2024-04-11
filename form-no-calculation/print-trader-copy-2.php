<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$form_no_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges FROM form_no_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($form_no_calculation_code, $form_no_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    /* $query_1x = "SELECT location, cost, markup FROM form_no_calculation_fixed_fields_visibility WHERE form_no_calculation_code = '$form_no_calculation_code' ";
      $query_1x_result = mysqli_query($mysqli, $query_1x);

      $columns_1x = array();
      $row_1x = mysqli_fetch_assoc($query_1x_result);
      foreach ($row_1x as $column_1x => $value_1x) {
      $columns_1x[$column_1x] = explode(',', $value_1x)[1];
      }
      asort($columns_1x); // sorting foreach by position */

    $query_2x = "SELECT location, cost, markup, price, note, group_discount, accessory, per_meter, fitting_charge FROM form_no_calculation_fixed_fields_visibility WHERE form_no_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $form_no_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $cost_print, $markup_print, $price_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $field_th_right = '';
    $location_th = $locations_select === 1 && $location_print[0] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $field_th_left = '';
    $cost_th = $cost_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Cost</td>' : '';
    $markup_th = $markup_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">Markup</td>' : '';
    $price_th = $price_print[0] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';

    $cost_td_num_rows = $cost_print[0] === '1' ? 1 : 0;
    $markup_td_num_rows = $markup_print[0] === '1' ? 1 : 0;
    $price_td_num_rows = $price_print[0] === '1' ? 1 : 0;

    // Print sorted th to dynamic vatiables...
    /* $table_th = "";
      foreach ($columns_1x as $variable_1x => $variable_1x_value) {
      $table_th .= ${$variable_1x . '_th'};
      } */

    $query_2 = "SELECT code, name, side FROM form_no_calculation_fields WHERE office_copy = 1 AND form_no_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $form_no_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($form_no_calculation_field_code, $form_no_calculation_field_name, $form_no_calculation_field_side);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {
        if ($form_no_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $form_no_calculation_field_name . '</th>';
        }
        if ($form_no_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $form_no_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $form_no_calculation_table_header = '<span nobr="true">'
            . '<h3>' . $form_no_calculation_name . '</h3>'
            . '<table cellpadding="4" cellspacing="0" style="text-align: center; background-color: #f1f1f1;">'
            . '<tr style="font-size: 0.9em; font-weight: bold;">'
            . '<th style="border: 0.5px solid #000000;">#</th>'
            . $field_th_left
            . $location_th
            . $field_th_right
            . $cost_th
            . $markup_th
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

    $query_3 = "SELECT code, location, cost, markup, notes, price, discount FROM form_no_calculation_quote_items WHERE form_no_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $form_no_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($form_no_calculation_quote_item_code, $form_no_calculation_quote_item_location, $form_no_calculation_quote_item_cost, $form_no_calculation_quote_item_markup, $form_no_calculation_quote_item_notes, $form_no_calculation_quote_item_price, $form_no_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $price_sub_total += $form_no_calculation_quote_item_price;

            $field_td_right = '';
            $field_td_left = '';

            $query_3_1 = "SELECT form_no_calculation_quote_item_fields.name, "
                    . "form_no_calculation_quote_item_fields.price, "
                    . "form_no_calculation_fields.side "
                    . "FROM form_no_calculation_quote_item_fields "
                    . "JOIN form_no_calculation_fields ON "
                    . "form_no_calculation_fields.code = form_no_calculation_quote_item_fields.form_no_calculation_field_code "
                    . "WHERE "
                    . "form_no_calculation_fields.office_copy = 1 AND "
                    . "form_no_calculation_quote_item_fields.form_no_calculation_quote_item_code = ? AND "
                    . "form_no_calculation_quote_item_fields.form_no_calculation_code = ? AND "
                    . "form_no_calculation_quote_item_fields.cid = ? "
                    . "ORDER BY form_no_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($form_no_calculation_quote_item_field_name, $form_no_calculation_quote_item_field_price, $form_no_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            $table_colspan = $field_td_num_rows + $cost_td_num_rows + $markup_td_num_rows + 1;
            $total_table_colspan = $table_colspan + $price_td_num_rows;

            while ($stmt_3_1->fetch()) {

//                if ($form_no_calculation_quote_item_field_price > 0) {
//                    $form_no_calculation_quote_item_field_price_x = ' | ' . number_format($form_no_calculation_quote_item_field_price, 2);
//                } else {
//                    $form_no_calculation_quote_item_field_price_x = '';
//                }

                if ($form_no_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_field_name . '</td>';
                }
                if ($form_no_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_field_name . '</td>';
                }
            }
            $stmt_3_1->close();

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM form_no_calculation_quote_item_accessories WHERE form_no_calculation_quote_item_code = ? AND form_no_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($form_no_calculation_quote_item_accessory_code, $form_no_calculation_quote_item_accessory_name, $form_no_calculation_quote_item_accessory_price, $form_no_calculation_quote_item_accessory_qty, $form_no_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $quote_item_accessories_price_td = $price_td_num_rows ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($form_no_calculation_quote_item_accessory_total, 2) . '</td>' : '';

                $quote_item_accessories .= '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $quote_item_no . '</td>'
                        . '<td style="border: 0.5px solid #000000;" colspan="' . $table_colspan . '">' . $form_no_calculation_quote_item_accessory_name . ' ($' . $form_no_calculation_quote_item_accessory_price . ' x ' . $form_no_calculation_quote_item_accessory_qty . ')</td>'
                        . $quote_item_accessories_price_td
                        . '</tr>';
            }
            $stmt_3_2->close();

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM form_no_calculation_quote_item_per_meters WHERE form_no_calculation_quote_item_code = ? AND form_no_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($form_no_calculation_quote_item_per_meter_code, $form_no_calculation_quote_item_per_meter_name, $form_no_calculation_quote_item_per_meter_price, $form_no_calculation_quote_item_per_meter_width, $form_no_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $quote_item_per_meters_price_td = $price_td_num_rows ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($form_no_calculation_quote_item_per_meter_total, 2) . '</td>' : '';

                $quote_item_per_meters .= '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $quote_item_no . '</td>'
                        . '<td style="border: 0.5px solid #000000;" colspan="' . $table_colspan . '">' . $form_no_calculation_quote_item_per_meter_name . ' ($' . $form_no_calculation_quote_item_per_meter_price . ' x ' . $form_no_calculation_quote_item_per_meter_width . ')</td>'
                        . $quote_item_per_meters_price_td
                        . '</tr>';
            }
            $stmt_3_3->close();

            $query_3_4 = "SELECT code, name, price FROM form_no_calculation_quote_item_fitting_charges WHERE form_no_calculation_quote_item_code = ? AND form_no_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($form_no_calculation_quote_item_fitting_charge_code, $form_no_calculation_quote_item_fitting_charge_name, $form_no_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $quote_item_fitting_charges_price_td = $price_td_num_rows ? '<td style="border: 0.5px solid #000000; text-align: right;">' . number_format($form_no_calculation_quote_item_fitting_charge_price, 2) . '</td>' : '';

                $quote_item_fitting_charges .= '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align: center;">' . $quote_item_no . '</td>'
                        . '<td style="border: 0.5px solid #000000;" colspan="' . $table_colspan . '">' . $form_no_calculation_quote_item_fitting_charge_name . '</td>'
                        . $quote_item_fitting_charges_price_td
                        . '</tr>';
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $form_no_calculation_quote_item_location)[0] . '</td>' : '';
            $cost_td = $cost_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_cost . '</td>' : '';
            $markup_td = $markup_print[0] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_markup . '</td>' : '';
            $price_td = $price_print[0] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($form_no_calculation_quote_item_price, 2) . '</td>' : '';

            // Print sorted td to dynamic vatiables...
            /* $table_td = "";
              $table_td_num_rows = 0;
              foreach ($columns_1x as $variable_1x => $variable_1x_value) {
              $table_td .= ${$variable_1x . '_td'};

              if (!empty(${$variable_1x . '_td'})) {
              $table_td_num_rows++;
              }
              } */

            $form_no_calculation_quote_item_notes_table = "";
            if ($form_no_calculation_quote_item_notes && $note_print[0] === '1') {

                $form_no_calculation_quote_item_notes_table = '<table cellpadding="4" cellspacing="0" style="text-align: left;" nobr="true">'
                        . '<tr>'
                        . '<td style="border: 0.5px solid #000000;">'
                        . nl2br($form_no_calculation_quote_item_notes)
                        . '</td>'
                        . '</tr>'
                        . '</table>';
            }

            $quote_items .= '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                    . '<tr style="font-size: 0.9em;">'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_no . '</td>'
                    . $field_td_left
                    . $location_td
                    . $field_td_right
                    . $cost_td
                    . $markup_td
                    . $price_td
                    . '</tr>'
                    . '</table>'
                    . $form_no_calculation_quote_item_notes_table;
            $quote_item_no++;

            if ($group_discount_print[0] === '1') {

                $form_no_calculation_quote_item_discount_value = $price_sub_total * $form_no_calculation_quote_item_discount / 100;
                $form_no_calculation_quote_item_price_total = $price_sub_total - $form_no_calculation_quote_item_discount_value;

                $form_no_calculation_total = '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $total_table_colspan . '">Sub Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($price_sub_total, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $total_table_colspan . '">Discount (' . $form_no_calculation_quote_item_discount . '%) </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">-' . number_format($form_no_calculation_quote_item_discount_value, 2) . '</th>'
                        . '</tr>'
                        . '<tr style="font-size: 0.9em;">'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $total_table_colspan . '">Total </th>'
                        . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($form_no_calculation_quote_item_price_total, 2) . '</th>'
                        . '</tr>'
                        . '</table>';
            } else {
                $form_no_calculation_total = '';
            }
        }

        $quote_item_accessories_table = "";
        if ($quote_item_accessories && $accessories_select === 1 && $accessory_print[0] === '1') {

            $quote_item_accessories_table = '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                    . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                    . '<th style="border: 0.5px solid #000000; text-align: center;">#</th>'
                    . '<th style="border: 0.5px solid #000000;" colspan="' . $total_table_colspan . '">Accessory</th>'
                    . '</tr>'
                    . $quote_item_accessories
                    . '</table>';
        }

        $quote_item_per_meters_table = "";
        if ($quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[0] === '1') {

            $quote_item_per_meters_table = '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                    . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                    . '<th style="border: 0.5px solid #000000; text-align: center;">#</th>'
                    . '<th style="border: 0.5px solid #000000;" colspan="' . $total_table_colspan . '">Per Meter</th>'
                    . '</tr>'
                    . $quote_item_per_meters
                    . '</table>';
        }

        $quote_item_fitting_charges_table = "";
        if ($quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[0] === '1') {

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

        $form_no_calculation_quote_tables .= $form_no_calculation_table_header . $quote_items . $table_more . $form_no_calculation_total . "<div></div>";
    } else {
        $form_no_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
