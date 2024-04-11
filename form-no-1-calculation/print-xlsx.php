<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$print_type = $_GET['type']; // " . $print_type . ", customer_copy, order_sheet
$no = +$_GET['no']; // 0 = " . $print_type . ", 1 = customer_copy, 2 = order_sheet

$form_no_1_calculation_quote_tables = "";

$query_1 = "SELECT code, name, locations, cost, markup, accessories, per_meters, fitting_charges, qty FROM form_no_1_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($form_no_1_calculation_code, $form_no_1_calculation_name, $locations_select, $cost_select, $markup_select, $accessories_select, $per_meters_select, $fitting_charges_select, $qty_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    // Getting Column names and postion value to sort table th and td
    /* $query_1x = "SELECT location, cost, markup FROM form_no_1_calculation_fixed_fields_visibility WHERE form_no_1_calculation_code = '$form_no_1_calculation_code' ";
      $query_1x_result = mysqli_query($mysqli, $query_1x);

      $columns_1x = array();
      $row_1x = mysqli_fetch_assoc($query_1x_result);
      foreach ($row_1x as $column_1x => $value_1x) {
      $columns_1x[$column_1x] = explode(',', $value_1x)[1];
      }
      asort($columns_1x); // sorting foreach by position */

    $query_2x = "SELECT location, cost, markup, price, qty, total, note, group_discount, accessory, per_meter, fitting_charge FROM form_no_1_calculation_fixed_fields_visibility WHERE form_no_1_calculation_code = ?";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $form_no_1_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($location_print, $cost_print, $markup_print, $price_print, $qty_print, $total_print, $note_print, $group_discount_print, $accessory_print, $per_meter_print, $fitting_charge_print);
    $stmt_2x->fetch();
    $stmt_2x->close();

    $field_th_right = '';
    $location_th = $locations_select === 1 && $location_print[$no] === '1' ? '<th style="border: 0.5px solid #000000;">Location</th>' : '';
    $field_th_left = '';
    $cost_th = $cost_select === 1 && $cost_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Cost</td>' : '';
    $markup_th = $markup_select === 1 && $markup_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">Markup</td>' : '';
    $price_th = $price_print[$no] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Price</th>' : '';
    $qty_th = $qty_select === 1 && $qty_print[$no] === '1' ? '<th style="text-align: center; border: 0.5px solid #000000;">Qty</th>' : '';
    $total_th = $qty_select === 1 && $total_print[$no] === '1' ? '<th style="text-align: right; border: 0.5px solid #000000;">Total</th>' : '';

    $extra_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 1px solid #000000;"></td>' : ''; // for acc, fitting charge and permeter last td

    $location_th_num_rows = $locations_select === 1 && $location_print[$no] === '1' ? 1 : 0;
    $cost_th_num_rows = $cost_print[$no] === '1' ? 1 : 0;
    $markup_th_num_rows = $markup_print[$no] === '1' ? 1 : 0;
    $price_th_num_rows = $price_print[$no] === '1' ? 1 : 0;
    $qty_th_num_rows =  $qty_select === 1 && $qty_print[$no] === '1' ? 1 : 0;
    $total_th_num_rows =  $qty_select === 1 && $total_print[$no] === '1' ? 1 : 0;
    $extra_td_num_rows = $price_print[$no] === '1' ? 1 : 0;

    // Print sorted th to dynamic vatiables...
    /* $table_th = "";
      foreach ($columns_1x as $variable_1x => $variable_1x_value) {
      $table_th .= ${$variable_1x . '_th'};
      } */

    $query_2 = "SELECT code, name, side FROM form_no_1_calculation_fields WHERE " . $print_type . " = 1 AND form_no_1_calculation_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $form_no_1_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($form_no_1_calculation_field_code, $form_no_1_calculation_field_name, $form_no_1_calculation_field_side);
    $stmt_2->store_result();
    $field_th_num_rows = $stmt_2->num_rows;

    while ($stmt_2->fetch()) {
        if ($form_no_1_calculation_field_side === 0) { // Right Side
            $field_th_right .= '<th style="border: 0.5px solid #000000;">' . $form_no_1_calculation_field_name . '</th>';
        }
        if ($form_no_1_calculation_field_side === 1) { // Left Side
            $field_th_left .= '<th style="border: 0.5px solid #000000;">' . $form_no_1_calculation_field_name . '</th>';
        }
    }
    $stmt_2->close();

    $tot_th_colspan_x = $location_th_num_rows + $field_th_num_rows + $price_th_num_rows + $cost_th_num_rows + $markup_th_num_rows + $qty_th_num_rows + $total_th_num_rows + 1; // with # column
    $tot_th_colspan = $location_th_num_rows + $field_th_num_rows + $price_th_num_rows + $cost_th_num_rows + $markup_th_num_rows + $qty_th_num_rows + $total_th_num_rows; // without # column

    $form_no_1_calculation_table_header = '<table><thead>'
        . '<tr><th colspan="' . $tot_th_colspan . '"><strong>' . $form_no_1_calculation_name . '</strong></th></tr>'
        . '<tr style="font-weight: bold;">'
        . '<th style="border: 1px solid #000000;">#</th>'
        . $field_th_left
        . $location_th
        . $field_th_right
        . $cost_th
        . $markup_th
        . $price_th
        . $qty_th
        . $total_th
        . '</tr>'
        . '</thead><tbody>';

    //$row_no = 1;
    $form_no_1_calculation_quote_items = "";
    $form_no_1_calculation_quote_item_price_sub_total = 0;

    $query_3 = "SELECT code, row_no, location, cost, markup, notes, price, qty, total, discount FROM form_no_1_calculation_quote_items WHERE form_no_1_calculation_code = ? AND cid = ? ORDER BY -row_position DESC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $form_no_1_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($form_no_1_calculation_quote_item_code, $row_no, $form_no_1_calculation_quote_item_location, $form_no_1_calculation_quote_item_cost, $form_no_1_calculation_quote_item_markup, $form_no_1_calculation_quote_item_notes, $form_no_1_calculation_quote_item_price, $form_no_1_calculation_quote_item_qty, $form_no_1_calculation_quote_item_total, $form_no_1_calculation_quote_item_discount);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            // $form_no_1_calculation_quote_item_price_sub_total += $form_no_1_calculation_quote_item_price;
            $form_no_1_calculation_quote_item_price_sub_total += $form_no_1_calculation_quote_item_total;

            $field_td_right = '';
            $field_td_left = '';

            // $query_3_1 = "SELECT form_no_1_calculation_quote_item_fields.name, "
            //         . "form_no_1_calculation_quote_item_fields.price, "
            //         . "form_no_1_calculation_fields.side "
            //         . "FROM form_no_1_calculation_quote_item_fields "
            //         . "JOIN form_no_1_calculation_fields ON "
            //         . "form_no_1_calculation_fields.code = form_no_1_calculation_quote_item_fields.form_no_1_calculation_field_code "
            //         . "WHERE "
            //         . "form_no_1_calculation_fields." . $print_type . " = 1 AND "
            //         . "form_no_1_calculation_quote_item_fields.form_no_1_calculation_quote_item_code = ? AND "
            //         . "form_no_1_calculation_quote_item_fields.form_no_1_calculation_code = ? AND "
            //         . "form_no_1_calculation_quote_item_fields.cid = ? "
            //         . "ORDER BY form_no_1_calculation_fields.position ASC";

            $query_3_1 = "SELECT form_no_1_calculation_quote_item_fields.name, "
                . "form_no_1_calculation_quote_item_fields.price, "
                . "form_no_1_calculation_quote_item_fields.sub_options, "
                . "form_no_1_calculation_fields.side "
                . "FROM form_no_1_calculation_quote_item_fields "
                . "JOIN form_no_1_calculation_fields ON "
                . "form_no_1_calculation_fields.code = form_no_1_calculation_quote_item_fields.form_no_1_calculation_field_code "
                . "WHERE "
                . "form_no_1_calculation_fields." . $print_type . " = 1 AND "
                . "form_no_1_calculation_quote_item_fields.form_no_1_calculation_quote_item_code = ? AND "
                . "form_no_1_calculation_quote_item_fields.form_no_1_calculation_code = ? AND "
                . "form_no_1_calculation_quote_item_fields.cid = ? "
                . "ORDER BY form_no_1_calculation_fields.position ASC";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($form_no_1_calculation_quote_item_field_name, $form_no_1_calculation_quote_item_field_price, $sub_options, $form_no_1_calculation_field_side);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            while ($stmt_3_1->fetch()) {

                $field_text = get_sub_option_text(json_decode($sub_options, true));

                if ($form_no_1_calculation_quote_item_field_price > 0) {
                    $form_no_1_calculation_quote_item_field_price_x = ' | ' . number_format($form_no_1_calculation_quote_item_field_price, 2);
                } else {
                    $form_no_1_calculation_quote_item_field_price_x = '';
                }

                if (empty($field_text)) {
                    $field_text = $form_no_1_calculation_quote_item_field_name . $form_no_1_calculation_quote_item_field_price_x;
                } else {
                    $field_text = $form_no_1_calculation_quote_item_field_name . $form_no_1_calculation_quote_item_field_price_x . '<br>>> ' . $field_text;
                }

                if ($form_no_1_calculation_field_side === 0) { // Right Side
                    $field_td_right .= '<td style="border: 0.5px solid #000000;">' . $field_text . '</td>';
                }
                if ($form_no_1_calculation_field_side === 1) { // Left Side
                    $field_td_left .= '<td style="border: 0.5px solid #000000;">' . $field_text . '</td>';
                }
            }
            $stmt_3_1->close();


            $quote_item_accessory_no = 1;
            $form_no_1_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM form_no_1_calculation_quote_item_accessories WHERE form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($form_no_1_calculation_quote_item_accessory_code, $form_no_1_calculation_quote_item_accessory_name, $form_no_1_calculation_quote_item_accessory_price, $form_no_1_calculation_quote_item_accessory_qty, $form_no_1_calculation_quote_item_accessory_total);

            $tot_th_colspan_for_acc = +$tot_th_colspan - (3 + $extra_td_num_rows);

            while ($stmt_3_2->fetch()) {

                $form_no_1_calculation_quote_item_accessories .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $row_no . '. ' . $quote_item_accessory_no . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Accessory - ' . $form_no_1_calculation_quote_item_accessory_name . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: right;">' . $form_no_1_calculation_quote_item_accessory_price . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: center;">' . $form_no_1_calculation_quote_item_accessory_qty . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($form_no_1_calculation_quote_item_accessory_total, 2) . '</td>'
                    . $extra_td
                    . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();


            $quote_item_per_meter_no = 1;
            $form_no_1_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM form_no_1_calculation_quote_item_per_meters WHERE form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($form_no_1_calculation_quote_item_per_meter_code, $form_no_1_calculation_quote_item_per_meter_name, $form_no_1_calculation_quote_item_per_meter_price, $form_no_1_calculation_quote_item_per_meter_width, $form_no_1_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $form_no_1_calculation_quote_item_per_meters .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $row_no . '. ' . $quote_item_per_meter_no . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter - ' . $form_no_1_calculation_quote_item_per_meter_name . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: right;">' . $form_no_1_calculation_quote_item_per_meter_price . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: center;">' . $form_no_1_calculation_quote_item_per_meter_width . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: right;">' . number_format($form_no_1_calculation_quote_item_per_meter_total, 2) . '</td>'
                    . $extra_td
                    . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();


            $quote_item_fitting_charge_no = 1;
            $form_no_1_calculation_quote_item_fitting_charges = "";

            $query_3_4 = "SELECT code, name, price FROM form_no_1_calculation_quote_item_fitting_charges WHERE form_no_1_calculation_quote_item_code = ? AND form_no_1_calculation_code = ? AND cid = ?";

            $stmt_3_4 = $mysqli->prepare($query_3_4);
            $stmt_3_4->bind_param('sss', $form_no_1_calculation_quote_item_code, $form_no_1_calculation_code, $cid);
            $stmt_3_4->execute();
            $stmt_3_4->bind_result($form_no_1_calculation_quote_item_fitting_charge_code, $form_no_1_calculation_quote_item_fitting_charge_name, $form_no_1_calculation_quote_item_fitting_charge_price);

            while ($stmt_3_4->fetch()) {

                $form_no_1_calculation_quote_item_fitting_charges .= '<tr>'
                    . '<td style="border: 1px solid #000000; text-align: left;">' . $row_no . '. ' . $quote_item_fitting_charge_no . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: left;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge - ' . $form_no_1_calculation_quote_item_fitting_charge_name . '</td>'
                    . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                    . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                    . '<td style="border: 1px solid #000000; text-align: right;">' . $form_no_1_calculation_quote_item_fitting_charge_price . '</td>'
                    . $extra_td
                    . '</tr>';
                $quote_item_fitting_charge_no++;
            }
            $stmt_3_4->close();

            $location_td = $locations_select === 1 && $location_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . explode('<->', $form_no_1_calculation_quote_item_location)[0] . '</td>' : '';
            $cost_td = $cost_select === 1 && $cost_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_1_calculation_quote_item_cost . '</td>' : '';
            $markup_td = $markup_select === 1 && $markup_print[$no] === '1' ? '<td style="border: 0.5px solid #000000;">' . $form_no_1_calculation_quote_item_markup . '</td>' : '';
            $price_td = $price_print[$no] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($form_no_1_calculation_quote_item_price, 2) . '</td>' : '';
            $qty_td = $qty_select === 1 && $qty_print[$no] === '1' ? '<td style="text-align: center; border: 0.5px solid #000000;">' . $form_no_1_calculation_quote_item_qty . '</td>' : '';
            $total_td = $qty_select === 1 && $total_print[$no] === '1' ? '<td style="text-align: right; border: 0.5px solid #000000;">' . number_format($form_no_1_calculation_quote_item_total, 2) . '</td>' : '';

            $location_td_num_rows = $locations_select === 1 && $location_print[$no] === '1' ? 1 : 0;
            $cost_td_num_rows = $cost_select === 1 && $cost_print[$no] === '1' ? 1 : 0;
            $markup_td_num_rows = $markup_select === 1 && $markup_print[$no] === '1' ? 1 : 0;
            $price_td_num_rows = $price_print[$no] === '1' ? 1 : 0;
            $qty_td_num_rows = $qty_select === 1 && $qty_print[$no] === '1' ? 1 : 0;
            $total_td_num_rows = $qty_select === 1 && $total_print[$no] === '1' ? 1 : 0;

            // Print sorted td to dynamic vatiables...
            /* $table_td = "";
              $table_td_num_rows = 0;
              foreach ($columns_1x as $variable_1x => $variable_1x_value) {
              $table_td .= ${$variable_1x . '_td'};

              if (!empty(${$variable_1x . '_td'})) {
              $table_td_num_rows++;
              }
              } */

            $tot_td_colspan = $location_td_num_rows + $field_td_num_rows + $price_td_num_rows + $cost_td_num_rows + $markup_td_num_rows + $qty_td_num_rows + $total_td_num_rows + 1; // with # field

            if ($form_no_1_calculation_quote_item_notes || $form_no_1_calculation_quote_item_accessories || $form_no_1_calculation_quote_item_per_meters || $form_no_1_calculation_quote_item_fitting_charges) {

                if ($form_no_1_calculation_quote_item_notes && $note_print[$no] === '1') {

                    $form_no_1_calculation_quote_item_notes_table = '<tr>'
                        . '<td style="border: 1px solid #000000;" colspan="' . $tot_td_colspan . '">'
                        . nl2br($form_no_1_calculation_quote_item_notes)
                        . '</td>'
                        . '</tr>';
                } else {
                    $form_no_1_calculation_quote_item_notes_table = "";
                }

                if ($form_no_1_calculation_quote_item_accessories && $accessories_select === 1 && $accessory_print[$no] === '1') {

                    $form_no_1_calculation_quote_item_accessories_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                        . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Accessory</th>'
                        . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                        . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                        . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                        . $extra_td
                        . '</tr>'
                        . $form_no_1_calculation_quote_item_accessories;
                } else {
                    $form_no_1_calculation_quote_item_accessories_table = "";
                }

                if ($form_no_1_calculation_quote_item_per_meters && $per_meters_select === 1 && $per_meter_print[$no] === '1') {

                    $form_no_1_calculation_quote_item_per_meters_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                        . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Per Meter</th>'
                        . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                        . '<th style="text-align: center; border: 1px solid #000000;">Qty</th>'
                        . '<th style="text-align: right; border: 1px solid #000000;">Total</th>'
                        . $extra_td
                        . '</tr>'
                        . $form_no_1_calculation_quote_item_per_meters;
                } else {
                    $form_no_1_calculation_quote_item_per_meters_table = "";
                }

                if ($form_no_1_calculation_quote_item_fitting_charges && $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1') {

                    $form_no_1_calculation_quote_item_fitting_charges_table = '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="border: 1px solid #000000; text-align: left;">#</th>'
                        . '<th style="text-align: left; border: 1px solid #000000;" colspan="' . $tot_th_colspan_for_acc . '">Fitting Charge</th>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<td style="border: 1px solid #000000; text-align: right;"></td>'
                        . '<th style="text-align: right; border: 1px solid #000000;">Price</th>'
                        . $extra_td
                        . '</tr>'
                        . $form_no_1_calculation_quote_item_fitting_charges;
                } else {
                    $form_no_1_calculation_quote_item_fitting_charges_table = "";
                }

                $table_more = $form_no_1_calculation_quote_item_notes_table;

                if (
                    $accessories_select === 1 && $accessory_print[$no] === '1' && $form_no_1_calculation_quote_item_accessories ||
                    $per_meters_select === 1 && $per_meter_print[$no] === '1' && $form_no_1_calculation_quote_item_per_meters ||
                    $fitting_charges_select === 1 && $fitting_charge_print[$no] === '1' && $form_no_1_calculation_quote_item_fitting_charges
                ) {


                    $table_more .= $form_no_1_calculation_quote_item_accessories_table
                        . $form_no_1_calculation_quote_item_per_meters_table
                        . $form_no_1_calculation_quote_item_fitting_charges_table;
                } else {
                    $table_more .= "";
                }
            } else {
                $table_more = "";
            }

            $form_no_1_calculation_quote_items .= '<tr>'
                . '<td style="border: 1px solid #000000; text-align: left;">' . $row_no . '</td>'
                . $field_td_left
                . $location_td
                . $field_td_right
                . $cost_td
                . $markup_td
                . $price_td
                . $qty_td
                . $total_td
                . '</tr>'
                . $table_more;
            //$row_no++;

            if ($group_discount_print[$no] === '1') {

                $form_no_1_calculation_quote_item_discount_value = $form_no_1_calculation_quote_item_price_sub_total * $form_no_1_calculation_quote_item_discount / 100;
                $form_no_1_calculation_quote_item_price_total = $form_no_1_calculation_quote_item_price_sub_total - $form_no_1_calculation_quote_item_discount_value;

                $form_no_1_calculation_total_table_colspan = $location_td_num_rows + $field_td_num_rows + $price_td_num_rows + $cost_td_num_rows + $markup_td_num_rows + $qty_td_num_rows + $total_td_num_rows;
                $form_no_1_calculation_total = '<tr style="border: 1px solid #000000;">'
                    . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $form_no_1_calculation_total_table_colspan . '">Sub Total </th>'
                    . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($form_no_1_calculation_quote_item_price_sub_total, 2) . '</th>'
                    . '</tr>'
                    . '<tr style="border: 1px solid #000000;">'
                    . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $form_no_1_calculation_total_table_colspan . '">Discount (' . $form_no_1_calculation_quote_item_discount . ' % ) </th>'
                    . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">-' . number_format($form_no_1_calculation_quote_item_discount_value, 2) . '</th>'
                    . '</tr>'
                    . '<tr>'
                    . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;" colspan="' . $form_no_1_calculation_total_table_colspan . '">Total </th>'
                    . '<th style="border: 1px solid #000000; text-align: right; font-weight: bold;">' . number_format($form_no_1_calculation_quote_item_price_total, 2) . '</th>'
                    . '</tr>';
            } else {
                $form_no_1_calculation_total = '';
            }
        }

        $form_no_1_calculation_quote_tables .= $form_no_1_calculation_table_header . $form_no_1_calculation_quote_items . $form_no_1_calculation_total . "</tbody></table>";
    } else {
        $form_no_1_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
