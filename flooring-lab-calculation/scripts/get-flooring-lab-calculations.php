<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');

$flooring_lab_calculations = array();

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges, status FROM flooring_lab_calculations ORDER BY position DESC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($flooring_lab_calculation_code, $flooring_lab_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select, $flooring_lab_calculation_status);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $flooring_lab_calculation_installation_type_options = array();

    $query_2 = "SELECT code, name, price FROM flooring_lab_calculation_installation_type_options WHERE flooring_lab_calculation_code = ? ORDER BY name ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $flooring_lab_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($flooring_lab_calculation_installation_type_option_code, $flooring_lab_calculation_installation_type_option_name, $flooring_lab_calculation_installation_type_option_price);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {
        $flooring_lab_calculation_installation_type_options[] = array(
            "code" => $flooring_lab_calculation_installation_type_option_code,
            "name" => $flooring_lab_calculation_installation_type_option_name,
            "price" => $flooring_lab_calculation_installation_type_option_price
        );
    }
    $stmt_2->close();

    $flooring_lab_calculation_fields = array();

    $query_3 = "SELECT code, name, side, field_type FROM flooring_lab_calculation_fields WHERE flooring_lab_calculation_code = ? ORDER BY position ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('s', $flooring_lab_calculation_code);
    $stmt_3->execute();
    $stmt_3->bind_result($flooring_lab_calculation_field_code, $flooring_lab_calculation_field_name, $flooring_lab_calculation_field_side, $flooring_lab_calculation_field_type);
    $stmt_3->store_result();

    while ($stmt_3->fetch()) {

        $flooring_lab_calculation_field_options = array();

        $query_3_1 = "SELECT code, name, price FROM flooring_lab_calculation_field_options WHERE flooring_lab_calculation_field_code = ? AND flooring_lab_calculation_code = ? ORDER BY id ASC";

        $stmt_3_1 = $mysqli->prepare($query_3_1);
        $stmt_3_1->bind_param('ss', $flooring_lab_calculation_field_code, $flooring_lab_calculation_code);
        $stmt_3_1->execute();
        $stmt_3_1->bind_result($flooring_lab_calculation_field_option_code, $flooring_lab_calculation_field_option_name, $flooring_lab_calculation_field_option_price);

        while ($stmt_3_1->fetch()) {
            $flooring_lab_calculation_field_options[] = array(
                "code" => $flooring_lab_calculation_field_option_code,
                "name" => $flooring_lab_calculation_field_option_name,
                "price" => $flooring_lab_calculation_field_option_price
            );
        }
        $stmt_3_1->close();

        $flooring_lab_calculation_fields[] = array(
            "code" => $flooring_lab_calculation_field_code,
            "name" => $flooring_lab_calculation_field_name,
            "side" => $flooring_lab_calculation_field_side,
            "field_type" => $flooring_lab_calculation_field_type,
            "options" => $flooring_lab_calculation_field_options
        );
    }
    $stmt_3->close();



    $flooring_lab_calculation_accessory_options = array();

    $query_2x = "SELECT code, name, price FROM flooring_lab_calculation_accessory_options WHERE flooring_lab_calculation_code = ? ORDER BY id ASC";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $flooring_lab_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($flooring_lab_calculation_accessory_option_code, $flooring_lab_calculation_accessory_option_name, $flooring_lab_calculation_accessory_option_price);

    while ($stmt_2x->fetch()) {
        $flooring_lab_calculation_accessory_options[] = array(
            "code" => $flooring_lab_calculation_accessory_option_code,
            "name" => $flooring_lab_calculation_accessory_option_name,
            "price" => $flooring_lab_calculation_accessory_option_price
        );
    }
    $stmt_2x->close();



    $flooring_lab_calculation_per_meter_options = array();

    $query_3x = "SELECT code, name, price FROM flooring_lab_calculation_per_meter_options WHERE flooring_lab_calculation_code = ? ORDER BY id ASC";

    $stmt_3x = $mysqli->prepare($query_3x);
    $stmt_3x->bind_param('s', $flooring_lab_calculation_code);
    $stmt_3x->execute();
    $stmt_3x->bind_result($flooring_lab_calculation_per_meter_option_code, $flooring_lab_calculation_per_meter_option_name, $flooring_lab_calculation_per_meter_option_price);

    while ($stmt_3x->fetch()) {
        $flooring_lab_calculation_per_meter_options[] = array(
            "code" => $flooring_lab_calculation_per_meter_option_code,
            "name" => $flooring_lab_calculation_per_meter_option_name,
            "price" => $flooring_lab_calculation_per_meter_option_price
        );
    }
    $stmt_3x->close();



    $flooring_lab_calculation_fitting_charge_options = array();

    $query_4x = "SELECT code, name, price FROM flooring_lab_calculation_fitting_charge_options WHERE flooring_lab_calculation_code = ? ORDER BY id ASC";

    $stmt_4x = $mysqli->prepare($query_4x);
    $stmt_4x->bind_param('s', $flooring_lab_calculation_code);
    $stmt_4x->execute();
    $stmt_4x->bind_result($flooring_lab_calculation_fitting_charge_option_code, $flooring_lab_calculation_fitting_charge_option_name, $flooring_lab_calculation_fitting_charge_option_price);

    while ($stmt_4x->fetch()) {
        $flooring_lab_calculation_fitting_charge_options[] = array(
            "code" => $flooring_lab_calculation_fitting_charge_option_code,
            "name" => $flooring_lab_calculation_fitting_charge_option_name,
            "price" => $flooring_lab_calculation_fitting_charge_option_price
        );
    }
    $stmt_4x->close();



    $flooring_lab_calculation_quote_items = array();

    $query_4 = "SELECT code, location, unit, installation_type, notes, price, total, discount FROM flooring_lab_calculation_quote_items WHERE flooring_lab_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('ss', $flooring_lab_calculation_code, $cid);
    $stmt_4->execute();
    $stmt_4->bind_result($flooring_lab_calculation_quote_item_code, $flooring_lab_calculation_quote_item_location, $flooring_lab_calculation_quote_item_unit, $flooring_lab_calculation_quote_item_installation_type, $flooring_lab_calculation_quote_item_notes, $flooring_lab_calculation_quote_item_price, $flooring_lab_calculation_quote_item_total, $flooring_lab_calculation_quote_item_discount);
    $stmt_4->store_result();

    while ($stmt_4->fetch()) {

        $flooring_lab_calculation_quote_item_fields = array();

        $query_4_1 = "SELECT code, name, price, flooring_lab_calculation_field_code FROM flooring_lab_calculation_quote_item_fields WHERE flooring_lab_calculation_quote_item_code = ? AND flooring_lab_calculation_code = ? AND cid = ?";

        $stmt_4_1 = $mysqli->prepare($query_4_1);
        $stmt_4_1->bind_param('sss', $flooring_lab_calculation_quote_item_code, $flooring_lab_calculation_code, $cid);
        $stmt_4_1->execute();
        $stmt_4_1->bind_result($flooring_lab_calculation_quote_item_field_code, $flooring_lab_calculation_quote_item_field_name, $flooring_lab_calculation_quote_item_field_price, $flooring_lab_calculation_field_code);

        while ($stmt_4_1->fetch()) {

            $flooring_lab_calculation_quote_item_fields[] = array(
                "code" => $flooring_lab_calculation_quote_item_field_code,
                "name" => $flooring_lab_calculation_quote_item_field_name,
                "price" => $flooring_lab_calculation_quote_item_field_price,
                "flooring_lab_calculation_field_code" => $flooring_lab_calculation_field_code
            );
        }
        $stmt_4_1->close();

        $flooring_lab_calculation_quote_item_accessories = array();

        $query_4_2 = "SELECT code, name, price, qty FROM flooring_lab_calculation_quote_item_accessories WHERE flooring_lab_calculation_quote_item_code = ? AND flooring_lab_calculation_code = ? AND cid = ?";

        $stmt_4_2 = $mysqli->prepare($query_4_2);
        $stmt_4_2->bind_param('sss', $flooring_lab_calculation_quote_item_code, $flooring_lab_calculation_code, $cid);
        $stmt_4_2->execute();
        $stmt_4_2->bind_result($flooring_lab_calculation_quote_item_accessory_code, $flooring_lab_calculation_quote_item_accessory_name, $flooring_lab_calculation_quote_item_accessory_price, $flooring_lab_calculation_quote_item_accessory_qty);

        while ($stmt_4_2->fetch()) {

            $flooring_lab_calculation_quote_item_accessories[] = array(
                "code" => $flooring_lab_calculation_quote_item_accessory_code,
                "name" => $flooring_lab_calculation_quote_item_accessory_name,
                "price" => $flooring_lab_calculation_quote_item_accessory_price,
                "qty" => $flooring_lab_calculation_quote_item_accessory_qty
            );
        }
        $stmt_4_2->close();

        $flooring_lab_calculation_quote_item_per_meters = array();

        $query_4_3 = "SELECT code, name, price, width FROM flooring_lab_calculation_quote_item_per_meters WHERE flooring_lab_calculation_quote_item_code = ? AND flooring_lab_calculation_code = ? AND cid = ?";

        $stmt_4_3 = $mysqli->prepare($query_4_3);
        $stmt_4_3->bind_param('sss', $flooring_lab_calculation_quote_item_code, $flooring_lab_calculation_code, $cid);
        $stmt_4_3->execute();
        $stmt_4_3->bind_result($flooring_lab_calculation_quote_item_per_meter_code, $flooring_lab_calculation_quote_item_per_meter_name, $flooring_lab_calculation_quote_item_per_meter_price, $flooring_lab_calculation_quote_item_per_meter_width);

        while ($stmt_4_3->fetch()) {

            $flooring_lab_calculation_quote_item_per_meters[] = array(
                "code" => $flooring_lab_calculation_quote_item_per_meter_code,
                "name" => $flooring_lab_calculation_quote_item_per_meter_name,
                "price" => $flooring_lab_calculation_quote_item_per_meter_price,
                "width" => $flooring_lab_calculation_quote_item_per_meter_width
            );
        }
        $stmt_4_3->close();


        $flooring_lab_calculation_quote_item_fitting_charges = array();

        $query_4_4 = "SELECT code, name, price FROM flooring_lab_calculation_quote_item_fitting_charges WHERE flooring_lab_calculation_quote_item_code = ? AND flooring_lab_calculation_code = ? AND cid = ?";

        $stmt_4_4 = $mysqli->prepare($query_4_4);
        $stmt_4_4->bind_param('sss', $flooring_lab_calculation_quote_item_code, $flooring_lab_calculation_code, $cid);
        $stmt_4_4->execute();
        $stmt_4_4->bind_result($flooring_lab_calculation_quote_item_fitting_charge_code, $flooring_lab_calculation_quote_item_fitting_charge_name, $flooring_lab_calculation_quote_item_fitting_charge_price);

        while ($stmt_4_4->fetch()) {

            $flooring_lab_calculation_quote_item_fitting_charges[] = array(
                "code" => $flooring_lab_calculation_quote_item_fitting_charge_code,
                "name" => $flooring_lab_calculation_quote_item_fitting_charge_name,
                "price" => $flooring_lab_calculation_quote_item_fitting_charge_price
            );
        }
        $stmt_4_4->close();

        $flooring_lab_calculation_quote_items[] = array(
            "code" => $flooring_lab_calculation_quote_item_code,
            "location" => $flooring_lab_calculation_quote_item_location,
            "unit" => $flooring_lab_calculation_quote_item_unit,
            "installation_type" => $flooring_lab_calculation_quote_item_installation_type,
            "fields" => $flooring_lab_calculation_quote_item_fields,
            "notes" => $flooring_lab_calculation_quote_item_notes,
            "accessories" => $flooring_lab_calculation_quote_item_accessories,
            "per_meters" => $flooring_lab_calculation_quote_item_per_meters,
            "fitting_charges" => $flooring_lab_calculation_quote_item_fitting_charges,
            "price" => $flooring_lab_calculation_quote_item_price,
            "total" => $flooring_lab_calculation_quote_item_total,
            "discount" => $flooring_lab_calculation_quote_item_discount
        );
    }
    $stmt_4->close();

    $query_4_5 = "SELECT DATE_FORMAT(install_date, '%d-%m-%Y') AS install_date , DATE_FORMAT(production_date, '%d-%m-%Y') AS production_date  FROM product_eta_dates WHERE calc_code = ? AND quote_no = ?";

    $stmt_4_5 = $mysqli->prepare($query_4_5);
    $stmt_4_5->bind_param('ss', $flooring_lab_calculation_code, $cid);
    $stmt_4_5->execute();
    $stmt_4_5->bind_result($install_date, $production_date);
    $stmt_4_5->fetch();
    $stmt_4_5->close();
    
    if(!$production_date){
        $production_date = 'Production Date';
    }
    if(!$install_date){
        $install_date = 'Install Date';
    }

    $flooring_lab_calculations[] = array(
        "code" => $flooring_lab_calculation_code,
        "name" => $flooring_lab_calculation_name,
        "location_select" => $locations_select,
        "installation_type_options" => $flooring_lab_calculation_installation_type_options,
        "fields" => $flooring_lab_calculation_fields,
        "accessories_select" => $accessories_select,
        "accessory_options" => $flooring_lab_calculation_accessory_options,
        "per_meters_select" => $per_meters_select,
        "per_meter_options" => $flooring_lab_calculation_per_meter_options,
        "fitting_charges_select" => $fitting_charges_select,
        "fitting_charge_options" => $flooring_lab_calculation_fitting_charge_options,
        "quote_items" => $flooring_lab_calculation_quote_items,
        "production_date" => $production_date,
        "install_date" => $install_date,
        "status" => $flooring_lab_calculation_status
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

print json_encode(array(1, $flooring_lab_calculations, $locations)); // Success

$mysqli->close();
