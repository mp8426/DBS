<?php

include __DIR__ . './../cPanel/connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);

$form_no_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, cost, markup, dynamic_field_qty, accessories, per_meters, fitting_charges, qty FROM form_no_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($form_no_calculation_code, $form_no_calculation_name, $locations_select, $cost_select, $markup_select, $dynamic_field_qty_select, $accessories_select, $per_meters_select, $fitting_charges_select, $qty_select);
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

    $query_2x = "SELECT location, cost, markup, dynamic_field_qty, price, qty, total, note, group_discount, accessory, per_meter, fitting_charge FROM form_no_calculation_fixed_fields_visibility WHERE form_no_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $form_no_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $cost_print, $markup_print, $dynamic_field_qty_print, $price_print, $qty_print, $total_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $field_th_right = '';
    $location_th = $locations_select === 1 && $location_print[1] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $field_th_left = '';
    $cost_th = $cost_select === 1 && $cost_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">Cost</td>' : '';
    $markup_th = $markup_select === 1 && $markup_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">Markup</td>' : '';
    $dynamic_field_qty_th = $dynamic_field_qty_select === 1 && $dynamic_field_qty_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">Qty</td>' : '';
    $price_th = $price_print[1] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';
    $qty_th = $qty_select === 1 && $total_print[1] === '1' ? '<th style="text-align: center; border: 0.5px solid #000000;">Qty</th>' : '';
    $total_th = $qty_select === 1 && $total_print[1] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Total</th>' : '';

    // Print sorted th to dynamic vatiables...
    /* $table_th = "";
      foreach ($columns_1x as $variable_1x => $variable_1x_value) {
      $table_th .= ${$variable_1x . '_th'};
      } */

    $query_2 = "SELECT code, name, side FROM form_no_calculation_fields WHERE customer_copy = 1 AND form_no_calculation_code = ? ORDER BY position ASC";

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
        . $dynamic_field_qty_th
        . $price_th
        . $qty_th
        . $total_th
        . '</tr>'
        . '</table>'
        . '</span>';


    //$row_no = 1;
    $form_no_calculation_quote_items = "";
    $form_no_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, row_no, location, cost, markup, dynamic_field_qty, notes, price, qty, total, discount FROM form_no_calculation_quote_items WHERE form_no_calculation_code = ? AND cid = ? ORDER BY -row_position DESC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $form_no_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($form_no_calculation_quote_item_code, $row_no, $form_no_calculation_quote_item_location, $form_no_calculation_quote_item_cost, $form_no_calculation_quote_item_markup, $form_no_calculation_quote_item_dynamic_field_qty, $form_no_calculation_quote_item_notes, $form_no_calculation_quote_item_price, $form_no_calculation_quote_item_qty, $form_no_calculation_quote_item_total, $form_no_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            // $form_no_calculation_quote_item_price_sub_total += $form_no_calculation_quote_item_price;
            $form_no_calculation_quote_item_price_sub_total += $form_no_calculation_quote_item_total;

            $field_td_right = '';
            $field_td_left = '';

            // $query_3_1 = "SELECT form_no_calculation_quote_item_fields.name, "
            //     . "form_no_calculation_quote_item_fields.price, "
            //     . "form_no_calculation_fields.side "
            //     . "FROM form_no_calculation_quote_item_fields "
            //     . "JOIN form_no_calculation_fields ON "
            //     . "form_no_calculation_fields.code = form_no_calculation_quote_item_fields.form_no_calculation_field_code "
            //     . "WHERE "
            //     . "form_no_calculation_fields.customer_copy = 1 AND "
            //     . "form_no_calculation_quote_item_fields.form_no_calculation_quote_item_code = ? AND "
            //     . "form_no_calculation_quote_item_fields.form_no_calculation_code = ? AND "
            //     . "form_no_calculation_quote_item_fields.cid = ? "
            //     . "ORDER BY form_no_calculation_fields.position ASC";

            $query_3_1 = "SELECT form_no_calculation_quote_item_fields.name, "
                . "form_no_calculation_quote_item_fields.price, "
                . "form_no_calculation_quote_item_fields.sub_options, "
                . "form_no_calculation_fields.side "
                . "FROM form_no_calculation_quote_item_fields "
                . "JOIN form_no_calculation_fields ON "
                . "form_no_calculation_fields.code = form_no_calculation_quote_item_fields.form_no_calculation_field_code "
                . "WHERE "
                . "form_no_calculation_fields.customer_copy = 1 AND "
                . "form_no_calculation_quote_item_fields.form_no_calculation_quote_item_code = ? AND "
                . "form_no_calculation_quote_item_fields.form_no_calculation_code = ? AND "
                . "form_no_calculation_quote_item_fields.cid = ? "
                . "ORDER BY form_no_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($form_no_calculation_quote_item_field_name, $form_no_calculation_quote_item_field_price, $sub_options, $form_no_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                $field_text = get_sub_option_text(json_decode($sub_options, true));

                if ($form_no_calculation_quote_item_field_price > 0) {
                    $form_no_calculation_quote_item_field_price_x = ' | ' . number_format($form_no_calculation_quote_item_field_price, 2);
                } else {
                    $form_no_calculation_quote_item_field_price_x = '';
                }

                if (empty($field_text)) {
                    $field_text = $form_no_calculation_quote_item_field_name . $form_no_calculation_quote_item_field_price_x;
                } else {
                    $field_text = $form_no_calculation_quote_item_field_name . $form_no_calculation_quote_item_field_price_x . '<br>>> ' . $field_text;
                }

                if ($form_no_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $field_text . '</td>';
                }
                if ($form_no_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $field_text . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $form_no_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM form_no_calculation_quote_item_accessories WHERE form_no_calculation_quote_item_code = ? AND form_no_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($form_no_calculation_quote_item_accessory_code, $form_no_calculation_quote_item_accessory_name, $form_no_calculation_quote_item_accessory_price, $form_no_calculation_quote_item_accessory_qty, $form_no_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $form_no_calculation_quote_item_accessories .= '<tr>'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_accessory_no . '. ' . $form_no_calculation_quote_item_accessory_name . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: right;">' . $form_no_calculation_quote_item_accessory_price . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: center;">' . $form_no_calculation_quote_item_accessory_qty . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: center;">' . number_format($form_no_calculation_quote_item_accessory_total, 2) . '</td>'
                    . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $form_no_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM form_no_calculation_quote_item_per_meters WHERE form_no_calculation_quote_item_code = ? AND form_no_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($form_no_calculation_quote_item_per_meter_code, $form_no_calculation_quote_item_per_meter_name, $form_no_calculation_quote_item_per_meter_price, $form_no_calculation_quote_item_per_meter_width, $form_no_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $form_no_calculation_quote_item_per_meters .= '<tr>'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_per_meter_no . '. ' . $form_no_calculation_quote_item_per_meter_name . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: right;">' . $form_no_calculation_quote_item_per_meter_price . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: center;">' . $form_no_calculation_quote_item_per_meter_width . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: center;">' . number_format($form_no_calculation_quote_item_per_meter_total, 2) . '</td>'
                    . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $form_no_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM form_no_calculation_quote_item_fitting_charges WHERE form_no_calculation_quote_item_code = ? AND form_no_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $form_no_calculation_quote_item_code, $form_no_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($form_no_calculation_quote_item_fitting_charge_code, $form_no_calculation_quote_item_fitting_charge_name, $form_no_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $form_no_calculation_quote_item_fitting_charges .= '<tr>'
                    . '<td style="border: 0.5px solid #000000;">' . $quote_item_fitting_charge_no . '. ' . $form_no_calculation_quote_item_fitting_charge_name . '</td>'
                    . '<td style="border: 0.5px solid #000000; text-align: center;">' . $form_no_calculation_quote_item_fitting_charge_price . '</td>'
                    . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $form_no_calculation_quote_item_location)[0] . '</td>' : '';
            $cost_td = $cost_select === 1 && $cost_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_cost . '</td>' : '';
            $markup_td = $markup_select === 1 && $markup_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_markup . '</td>' : '';
            $dynamic_field_qty_td = $dynamic_field_qty_select === 1 && $dynamic_field_qty_print[1] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_dynamic_field_qty . '</td>' : '';
            $price_td = $price_print[1] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($form_no_calculation_quote_item_price, 2) . '</td>' : '';
            $qty_td = $qty_select === 1 && $qty_print[1] === '1' ? '<td style="text-align: center; border: 0.5px solid #000000;">' . $form_no_calculation_quote_item_qty . '</td>' : '';
            $total_td = $qty_select === 1 && $total_print[1] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($form_no_calculation_quote_item_total, 2) . '</td>' : '';

            $cost_td_num_rows = $cost_select === 1 && $cost_print[1] === '1' ? 1 : 0;
            $markup_td_num_rows = $markup_select === 1 && $markup_print[1] === '1' ? 1 : 0;
            $dynamic_field_qty_td_num_rows = $dynamic_field_qty_select === 1 && $dynamic_field_qty_print[1] === '1' ? 1 : 0;
            $price_td_num_rows = $price_print[1] === '1' ? 1 : 0;
            $qty_td_num_rows = $qty_select === 1 && $qty_print[1] === '1' ? 1 : 0;
            $total_td_num_rows = $qty_select === 1 && $total_print[1] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
            /* $table_td = "";
              $table_td_num_rows = 0;
              foreach ($columns_1x as $variable_1x => $variable_1x_value) {
              $table_td .= ${$variable_1x . '_td'};

              if (!empty(${$variable_1x . '_td'})) {
              $table_td_num_rows++;
              }
              } */

            if ($form_no_calculation_quote_item_notes || $form_no_calculation_quote_item_accessories || $form_no_calculation_quote_item_per_meters || $form_no_calculation_quote_item_fitting_charges) {

                if ($form_no_calculation_quote_item_notes && $note_print[1] === '1') {

                    $form_no_calculation_quote_item_notes_table = '<table cellpadding="4" cellspacing="0" style="text-align: left;" nobr="true">'
                        . '<tr>'
                        . '<td style="border: 0.5px solid #000000;">'
                        . nl2br($form_no_calculation_quote_item_notes)
                        . '</td>'
                        . '</tr>'
                        . '</table>';
                } else {
                    $form_no_calculation_quote_item_notes_table = "";
                }

                if ($form_no_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[1] === '1') {

                    $form_no_calculation_quote_item_accessories_table = '<td style="border: 0.5px solid #000000;">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="width: 60%; border: 0.5px solid #000000;">#. Accessory</th>'
                        . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                        . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Qty</th>'
                        . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                        . '</tr>'
                        . $form_no_calculation_quote_item_accessories
                        . '</table>'
                        . '</td>';
                } else {
                    $form_no_calculation_quote_item_accessories_table = "";
                }

                if ($form_no_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[1] === '1') {

                    $form_no_calculation_quote_item_per_meters_table = '<td style="border: 0.5px solid #000000;">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="width: 60%; border: 0.5px solid #000000;">#. Per Meter</th>'
                        . '<th style="width: 15%; text-align: right; border: 0.5px solid #000000;">Price</th>'
                        . '<th style="width: 10%; text-align: center; border: 0.5px solid #000000;">Width</th>'
                        . '<th style="width: 15%; text-align: center; border: 0.5px solid #000000;">Total</th>'
                        . '</tr>'
                        . $form_no_calculation_quote_item_per_meters
                        . '</table>'
                        . '</td>';
                } else {
                    $form_no_calculation_quote_item_per_meters_table = "";
                }

                if ($form_no_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[1] === '1') {

                    $form_no_calculation_quote_item_fitting_charges_table = '<td style="border: 0.5px solid #000000;">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2; border: 0.5px solid #000000;">'
                        . '<th style="border: 0.5px solid #000000;">#. Fitting Charge</th>'
                        . '<th style="text-align: center; border: 0.5px solid #000000;">Price</th>'
                        . '</tr>'
                        . $form_no_calculation_quote_item_fitting_charges
                        . '</table>'
                        . '</td>';
                } else {
                    $form_no_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $form_no_calculation_quote_item_notes_table;

                if (
                    $accessories_select === 1 && $accessory_print[1] === '1' && $form_no_calculation_quote_item_accessories ||
                    $per_meters_select === 1 && $per_meter_print[1] === '1' && $form_no_calculation_quote_item_per_meters ||
                    $fitting_charges_select === 1 && $fitting_charge_print[1] === '1' && $form_no_calculation_quote_item_fitting_charges
                ) {

                    $table_more .= '<table cellpadding="0" cellspacing="0" style="text-align: left;" nobr="true">'
                        . '<tr style="font-size: 0.9em;">'
                        . $form_no_calculation_quote_item_accessories_table
                        . $form_no_calculation_quote_item_per_meters_table
                        . $form_no_calculation_quote_item_fitting_charges_table
                        . '</tr>'
                        . '</table>';
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $form_no_calculation_quote_items .= '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                . '<tr style="font-size: 0.9em;">'
                . '<td style="border: 0.5px solid #000000;">' . $row_no . '</td>'
                . $field_td_left
                . $location_td
                . $field_td_right
                . $cost_td
                . $markup_td
                . $dynamic_field_qty_td
                . $price_td
                . $qty_td
                . $total_td
                . '</tr>'
                . '</table>'
                . $table_more;
            //$row_no++;

            if ($group_discount_print[1] === '1') {

                $form_no_calculation_quote_item_discount_value = $form_no_calculation_quote_item_price_sub_total * $form_no_calculation_quote_item_discount / 100;
                $form_no_calculation_quote_item_price_total = $form_no_calculation_quote_item_price_sub_total - $form_no_calculation_quote_item_discount_value;

                $form_no_calculation_total_table_colspan = $field_td_num_rows + $price_td_num_rows + $cost_td_num_rows + $markup_td_num_rows + $dynamic_field_qty_td_num_rows + $qty_td_num_rows + $total_td_num_rows + 1;

                $form_no_calculation_total = '<table cellpadding="4" cellspacing="0" style="text-align: center;" nobr="true">'
                    . '<tr style="font-size: 0.9em;">'
                    . '<th style="width:800px; border-top: 0.5px solid #616060; border-left: 0.5px solid #616060; border-bottom: 0.5px solid #616060; color:#303030; font-size: 1.9em; text-align: center; font-weight: 14px; vertical-align: middle;" rowspan="3">'.$form_no_calculation_name.'</th>'
                    . '<th style="width:100px;  border-top: 0.5px solid #616060; border-right: 0.5px solid #616060; color:#303030; text-align: right; font-weight: bold;" colspan="' . $form_no_calculation_total_table_colspan . '">Sub Total </th>'
                    . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($form_no_calculation_quote_item_price_sub_total, 2) . '</th>'
                    . '</tr>'
                    . '<tr style="font-size: 0.9em;">'
                    . '<th style="border-top: 0.5px solid #616060; border-right: 0.5px solid #616060; border-bottom: 0.5px solid #616060; color:#277cbe; text-align: right; font-weight: bold;" colspan="' . $form_no_calculation_total_table_colspan . '">Discount (' . $form_no_calculation_quote_item_discount . '%) </th>'
                    . '<th style="border: 0.5px solid #000000; color:#277cbe; text-align: right; font-weight: bold;">-' . number_format($form_no_calculation_quote_item_discount_value, 2) . '</th>'
                    . '</tr>'
                    // . '<tr style="font-size: 0.9em;">'
                    // . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;" colspan="' . $form_no_calculation_total_table_colspan . '">Total </th>'
                    // . '<th style="border: 0.5px solid #000000; text-align: right; font-weight: bold;">' . number_format($form_no_calculation_quote_item_price_total, 2) . '</th>'
                    // . '</tr>'
                    . '</table>';
            } else {
                $form_no_calculation_total = '';
            }
        }

        $form_no_calculation_quote_tables .= $form_no_calculation_table_header . $form_no_calculation_quote_items . $form_no_calculation_total . "<div></div>";
        // Add the item to the $price_list array
        $price_list[] = array(
            'p_name' => $form_no_calculation_name,
            'quantity' => $row_no,
            'price' => number_format($form_no_calculation_quote_item_price_sub_total, 2)
        ); 
    
    } else {
        $form_no_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();