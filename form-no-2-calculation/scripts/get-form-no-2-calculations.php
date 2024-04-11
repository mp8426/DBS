<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');

$form_no_2_calculations = array();

$query_1 = "SELECT code, name, locations, cost, markup, dynamic_field_qty, accessories, per_meters, fitting_charges, qty, status FROM form_no_2_calculations ORDER BY position DESC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($form_no_2_calculation_code, $form_no_2_calculation_name, $locations_select, $cost_select, $markup_select, $dynamic_field_qty_select, $accessories_select, $per_meters_select, $fitting_charges_select, $qty_select, $form_no_2_calculation_status);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $form_no_2_calculation_fields = array();

    $query_3 = "SELECT code, name, side, field_type FROM form_no_2_calculation_fields WHERE form_no_2_calculation_code = ? ORDER BY position ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('s', $form_no_2_calculation_code);
    $stmt_3->execute();
    $stmt_3->bind_result($form_no_2_calculation_field_code, $form_no_2_calculation_field_name, $form_no_2_calculation_field_side, $form_no_2_calculation_field_type);
    $stmt_3->store_result();

    while ($stmt_3->fetch()) {

        $form_no_2_calculation_field_options = array();

        $query_3_1 = "SELECT code, name, price, price_type, sub_options FROM form_no_2_calculation_field_options WHERE form_no_2_calculation_field_code = ? AND form_no_2_calculation_code = ? ORDER BY position ASC";

        $stmt_3_1 = $mysqli->prepare($query_3_1);
        $stmt_3_1->bind_param('ss', $form_no_2_calculation_field_code, $form_no_2_calculation_code);
        $stmt_3_1->execute();
        $stmt_3_1->bind_result($form_no_2_calculation_field_option_code, $form_no_2_calculation_field_option_name, $form_no_2_calculation_field_option_price, $form_no_2_calculation_field_option_type, $filed_sub_options);

        while ($stmt_3_1->fetch()) {
            $form_no_2_calculation_field_options[] = array(
                "code" => $form_no_2_calculation_field_option_code,
                "name" => $form_no_2_calculation_field_option_name,
                "name" => $form_no_2_calculation_field_option_name,
                "price" => $form_no_2_calculation_field_option_price,
                "price_type" => $form_no_2_calculation_field_option_type,
                "sub_options" => $filed_sub_options
            );
        }
        $stmt_3_1->close();

        $form_no_2_calculation_fields[] = array(
            "code" => $form_no_2_calculation_field_code,
            "name" => $form_no_2_calculation_field_name,
            "side" => $form_no_2_calculation_field_side,
            "field_type" => $form_no_2_calculation_field_type,
            "options" => $form_no_2_calculation_field_options
        );
    }
    $stmt_3->close();



    $form_no_2_calculation_accessory_options = array();

    $query_2x = "SELECT code, name, price FROM form_no_2_calculation_accessory_options WHERE form_no_2_calculation_code = ? ORDER BY position ASC";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $form_no_2_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($form_no_2_calculation_accessory_option_code, $form_no_2_calculation_accessory_option_name, $form_no_2_calculation_accessory_option_price);

    while ($stmt_2x->fetch()) {
        $form_no_2_calculation_accessory_options[] = array(
            "code" => $form_no_2_calculation_accessory_option_code,
            "name" => $form_no_2_calculation_accessory_option_name,
            "price" => $form_no_2_calculation_accessory_option_price
        );
    }
    $stmt_2x->close();



    $form_no_2_calculation_per_meter_options = array();

    $query_3x = "SELECT code, name, price FROM form_no_2_calculation_per_meter_options WHERE form_no_2_calculation_code = ? ORDER BY position ASC";

    $stmt_3x = $mysqli->prepare($query_3x);
    $stmt_3x->bind_param('s', $form_no_2_calculation_code);
    $stmt_3x->execute();
    $stmt_3x->bind_result($form_no_2_calculation_per_meter_option_code, $form_no_2_calculation_per_meter_option_name, $form_no_2_calculation_per_meter_option_price);

    while ($stmt_3x->fetch()) {
        $form_no_2_calculation_per_meter_options[] = array(
            "code" => $form_no_2_calculation_per_meter_option_code,
            "name" => $form_no_2_calculation_per_meter_option_name,
            "price" => $form_no_2_calculation_per_meter_option_price
        );
    }
    $stmt_3x->close();



    $form_no_2_calculation_fitting_charge_options = array();

    $query_4x = "SELECT code, name, price FROM form_no_2_calculation_fitting_charge_options WHERE form_no_2_calculation_code = ? ORDER BY position ASC";

    $stmt_4x = $mysqli->prepare($query_4x);
    $stmt_4x->bind_param('s', $form_no_2_calculation_code);
    $stmt_4x->execute();
    $stmt_4x->bind_result($form_no_2_calculation_fitting_charge_option_code, $form_no_2_calculation_fitting_charge_option_name, $form_no_2_calculation_fitting_charge_option_price);

    while ($stmt_4x->fetch()) {
        $form_no_2_calculation_fitting_charge_options[] = array(
            "code" => $form_no_2_calculation_fitting_charge_option_code,
            "name" => $form_no_2_calculation_fitting_charge_option_name,
            "price" => $form_no_2_calculation_fitting_charge_option_price
        );
    }
    $stmt_4x->close();



    $form_no_2_calculation_quote_items = array();

    $query_4 = "SELECT code, row_no, location, cost, markup, dynamic_field_qty, notes, price, qty, total, discount FROM form_no_2_calculation_quote_items WHERE form_no_2_calculation_code = ? AND cid = ? ORDER BY -row_position DESC";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('ss', $form_no_2_calculation_code, $cid);
    $stmt_4->execute();
    $stmt_4->bind_result($form_no_2_calculation_quote_item_code, $row_no, $form_no_2_calculation_quote_item_location, $form_no_2_calculation_quote_item_cost, $form_no_2_calculation_quote_item_markup, $form_no_2_calculation_quote_item_dynamic_field_qty, $form_no_2_calculation_quote_item_notes, $form_no_2_calculation_quote_item_price, $form_no_2_calculation_quote_item_qty, $form_no_2_calculation_quote_item_total, $form_no_2_calculation_quote_item_discount);
    $stmt_4->store_result();

    while ($stmt_4->fetch()) {

        $form_no_2_calculation_quote_item_fields = array();

        $query_4_1 = "SELECT code, name, price, sub_options, form_no_2_calculation_field_code FROM form_no_2_calculation_quote_item_fields WHERE form_no_2_calculation_quote_item_code = ? AND form_no_2_calculation_code = ? AND cid = ?";

        $stmt_4_1 = $mysqli->prepare($query_4_1);
        $stmt_4_1->bind_param('sss', $form_no_2_calculation_quote_item_code, $form_no_2_calculation_code, $cid);
        $stmt_4_1->execute();
        $stmt_4_1->bind_result($form_no_2_calculation_quote_item_field_code, $form_no_2_calculation_quote_item_field_name, $form_no_2_calculation_quote_item_field_price, $filed_sub_options, $form_no_2_calculation_field_code);

        while ($stmt_4_1->fetch()) {

            $form_no_2_calculation_quote_item_fields[] = array(
                "code" => $form_no_2_calculation_quote_item_field_code,
                "name" => $form_no_2_calculation_quote_item_field_name,
                "price" => $form_no_2_calculation_quote_item_field_price,
                "sub_options" => $filed_sub_options,
                "form_no_2_calculation_field_code" => $form_no_2_calculation_field_code
            );
        }
        $stmt_4_1->close();

        $form_no_2_calculation_quote_item_accessories = array();

        $query_4_2 = "SELECT code, name, price, qty FROM form_no_2_calculation_quote_item_accessories WHERE form_no_2_calculation_quote_item_code = ? AND form_no_2_calculation_code = ? AND cid = ?";

        $stmt_4_2 = $mysqli->prepare($query_4_2);
        $stmt_4_2->bind_param('sss', $form_no_2_calculation_quote_item_code, $form_no_2_calculation_code, $cid);
        $stmt_4_2->execute();
        $stmt_4_2->bind_result($form_no_2_calculation_quote_item_accessory_code, $form_no_2_calculation_quote_item_accessory_name, $form_no_2_calculation_quote_item_accessory_price, $form_no_2_calculation_quote_item_accessory_qty);

        while ($stmt_4_2->fetch()) {

            $form_no_2_calculation_quote_item_accessories[] = array(
                "code" => $form_no_2_calculation_quote_item_accessory_code,
                "name" => $form_no_2_calculation_quote_item_accessory_name,
                "price" => $form_no_2_calculation_quote_item_accessory_price,
                "qty" => $form_no_2_calculation_quote_item_accessory_qty
            );
        }
        $stmt_4_2->close();

        $form_no_2_calculation_quote_item_per_meters = array();

        $query_4_3 = "SELECT code, name, price, width FROM form_no_2_calculation_quote_item_per_meters WHERE form_no_2_calculation_quote_item_code = ? AND form_no_2_calculation_code = ? AND cid = ?";

        $stmt_4_3 = $mysqli->prepare($query_4_3);
        $stmt_4_3->bind_param('sss', $form_no_2_calculation_quote_item_code, $form_no_2_calculation_code, $cid);
        $stmt_4_3->execute();
        $stmt_4_3->bind_result($form_no_2_calculation_quote_item_per_meter_code, $form_no_2_calculation_quote_item_per_meter_name, $form_no_2_calculation_quote_item_per_meter_price, $form_no_2_calculation_quote_item_per_meter_width);

        while ($stmt_4_3->fetch()) {

            $form_no_2_calculation_quote_item_per_meters[] = array(
                "code" => $form_no_2_calculation_quote_item_per_meter_code,
                "name" => $form_no_2_calculation_quote_item_per_meter_name,
                "price" => $form_no_2_calculation_quote_item_per_meter_price,
                "width" => $form_no_2_calculation_quote_item_per_meter_width
            );
        }
        $stmt_4_3->close();


        $form_no_2_calculation_quote_item_fitting_charges = array();

        $query_4_4 = "SELECT code, name, price FROM form_no_2_calculation_quote_item_fitting_charges WHERE form_no_2_calculation_quote_item_code = ? AND form_no_2_calculation_code = ? AND cid = ?";

        $stmt_4_4 = $mysqli->prepare($query_4_4);
        $stmt_4_4->bind_param('sss', $form_no_2_calculation_quote_item_code, $form_no_2_calculation_code, $cid);
        $stmt_4_4->execute();
        $stmt_4_4->bind_result($form_no_2_calculation_quote_item_fitting_charge_code, $form_no_2_calculation_quote_item_fitting_charge_name, $form_no_2_calculation_quote_item_fitting_charge_price);

        while ($stmt_4_4->fetch()) {

            $form_no_2_calculation_quote_item_fitting_charges[] = array(
                "code" => $form_no_2_calculation_quote_item_fitting_charge_code,
                "name" => $form_no_2_calculation_quote_item_fitting_charge_name,
                "price" => $form_no_2_calculation_quote_item_fitting_charge_price
            );
        }
        $stmt_4_4->close();

        $form_no_2_calculation_quote_items[] = array(
            "code" => $form_no_2_calculation_quote_item_code,
            "row_no" => $row_no,
            "location" => $form_no_2_calculation_quote_item_location,
            "cost" => $form_no_2_calculation_quote_item_cost,
            "markup" => $form_no_2_calculation_quote_item_markup,
            "dynamic_field_qty" => $form_no_2_calculation_quote_item_dynamic_field_qty,
            "fields" => $form_no_2_calculation_quote_item_fields,
            "notes" => $form_no_2_calculation_quote_item_notes,
            "accessories" => $form_no_2_calculation_quote_item_accessories,
            "per_meters" => $form_no_2_calculation_quote_item_per_meters,
            "fitting_charges" => $form_no_2_calculation_quote_item_fitting_charges,
            "price" => $form_no_2_calculation_quote_item_price,
            "qty" => $form_no_2_calculation_quote_item_qty,
            "total" => $form_no_2_calculation_quote_item_total,
            "discount" => $form_no_2_calculation_quote_item_discount
        );
    }
    $stmt_4->close();

    $form_no_2_calculations[] = array(
        "code" => $form_no_2_calculation_code,
        "name" => $form_no_2_calculation_name,
        "location_select" => $locations_select,
        "cost_select" => $cost_select,
        "markup_select" => $markup_select,
        "dynamic_field_qty_select" => $dynamic_field_qty_select,
        "fields" => $form_no_2_calculation_fields,
        "accessories_select" => $accessories_select,
        "accessory_options" => $form_no_2_calculation_accessory_options,
        "per_meters_select" => $per_meters_select,
        "per_meter_options" => $form_no_2_calculation_per_meter_options,
        "fitting_charges_select" => $fitting_charges_select,
        "qty_select" => $qty_select,
        "fitting_charge_options" => $form_no_2_calculation_fitting_charge_options,
        "quote_items" => $form_no_2_calculation_quote_items,
        "status" => $form_no_2_calculation_status
    );
}
$stmt_1->close();



$locations = array();

$query_5 = "SELECT code, name FROM locations ORDER BY name ASC";

$stmt_5 = $mysqli->prepare($query_5);
$stmt_5->execute();
$stmt_5->bind_result($location_code, $location_name);

while ($stmt_5->fetch()) {
    $locations[] = array(
        "code" => $location_code,
        "name" => $location_name
    );
}
$stmt_5->close();


print json_encode(array(1, $form_no_2_calculations, $locations)); // Success

$mysqli->close();
