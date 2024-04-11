<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');

$furnishing_2_calculations = array();

$query_1 = "SELECT code, name, locations, accessories, per_meters, fitting_charges, status FROM furnishing_2_calculations ORDER BY position DESC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($furnishing_2_calculation_code, $furnishing_2_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select, $furnishing_2_calculation_status);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $furnishing_2_calculation_fields = array();

    $query_3 = "SELECT code, name, side, field_type FROM furnishing_2_calculation_fields WHERE furnishing_2_calculation_code = ? ORDER BY position ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('s', $furnishing_2_calculation_code);
    $stmt_3->execute();
    $stmt_3->bind_result($furnishing_2_calculation_field_code, $furnishing_2_calculation_field_name, $furnishing_2_calculation_field_side, $furnishing_2_calculation_field_type);
    $stmt_3->store_result();

    while ($stmt_3->fetch()) {

        $furnishing_2_calculation_field_options = array();

        $query_3_1 = "SELECT code, name, price FROM furnishing_2_calculation_field_options WHERE furnishing_2_calculation_field_code = ? AND furnishing_2_calculation_code = ? ORDER BY id ASC";

        $stmt_3_1 = $mysqli->prepare($query_3_1);
        $stmt_3_1->bind_param('ss', $furnishing_2_calculation_field_code, $furnishing_2_calculation_code);
        $stmt_3_1->execute();
        $stmt_3_1->bind_result($furnishing_2_calculation_field_option_code, $furnishing_2_calculation_field_option_name, $furnishing_2_calculation_field_option_price);

        while ($stmt_3_1->fetch()) {
            $furnishing_2_calculation_field_options[] = array(
                "code" => $furnishing_2_calculation_field_option_code,
                "name" => $furnishing_2_calculation_field_option_name,
                "name" => $furnishing_2_calculation_field_option_name,
                "price" => $furnishing_2_calculation_field_option_price
            );
        }
        $stmt_3_1->close();

        $furnishing_2_calculation_fields[] = array(
            "code" => $furnishing_2_calculation_field_code,
            "name" => $furnishing_2_calculation_field_name,
            "side" => $furnishing_2_calculation_field_side,
            "field_type" => $furnishing_2_calculation_field_type,
            "options" => $furnishing_2_calculation_field_options
        );
    }
    $stmt_3->close();



    $furnishing_2_calculation_accessory_options = array();

    $query_2x = "SELECT code, name, price FROM furnishing_2_calculation_accessory_options WHERE furnishing_2_calculation_code = ? ORDER BY id ASC";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $furnishing_2_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($furnishing_2_calculation_accessory_option_code, $furnishing_2_calculation_accessory_option_name, $furnishing_2_calculation_accessory_option_price);

    while ($stmt_2x->fetch()) {
        $furnishing_2_calculation_accessory_options[] = array(
            "code" => $furnishing_2_calculation_accessory_option_code,
            "name" => $furnishing_2_calculation_accessory_option_name,
            "price" => $furnishing_2_calculation_accessory_option_price
        );
    }
    $stmt_2x->close();



    $furnishing_2_calculation_per_meter_options = array();

    $query_3x = "SELECT code, name, price FROM furnishing_2_calculation_per_meter_options WHERE furnishing_2_calculation_code = ? ORDER BY id ASC";

    $stmt_3x = $mysqli->prepare($query_3x);
    $stmt_3x->bind_param('s', $furnishing_2_calculation_code);
    $stmt_3x->execute();
    $stmt_3x->bind_result($furnishing_2_calculation_per_meter_option_code, $furnishing_2_calculation_per_meter_option_name, $furnishing_2_calculation_per_meter_option_price);

    while ($stmt_3x->fetch()) {
        $furnishing_2_calculation_per_meter_options[] = array(
            "code" => $furnishing_2_calculation_per_meter_option_code,
            "name" => $furnishing_2_calculation_per_meter_option_name,
            "price" => $furnishing_2_calculation_per_meter_option_price
        );
    }
    $stmt_3x->close();



    $furnishing_2_calculation_fitting_charge_options = array();

    $query_4x = "SELECT code, name, price FROM furnishing_2_calculation_fitting_charge_options WHERE furnishing_2_calculation_code = ? ORDER BY id ASC";

    $stmt_4x = $mysqli->prepare($query_4x);
    $stmt_4x->bind_param('s', $furnishing_2_calculation_code);
    $stmt_4x->execute();
    $stmt_4x->bind_result($furnishing_2_calculation_fitting_charge_option_code, $furnishing_2_calculation_fitting_charge_option_name, $furnishing_2_calculation_fitting_charge_option_price);

    while ($stmt_4x->fetch()) {
        $furnishing_2_calculation_fitting_charge_options[] = array(
            "code" => $furnishing_2_calculation_fitting_charge_option_code,
            "name" => $furnishing_2_calculation_fitting_charge_option_name,
            "price" => $furnishing_2_calculation_fitting_charge_option_price
        );
    }
    $stmt_4x->close();



    $furnishing_2_calculation_quote_items = array();

    $query_4 = "SELECT code, row_no, location, cost, markup, notes, price, discount FROM furnishing_2_calculation_quote_items WHERE furnishing_2_calculation_code = ? AND cid = ? ORDER BY -row_position DESC";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('ss', $furnishing_2_calculation_code, $cid);
    $stmt_4->execute();
    $stmt_4->bind_result($furnishing_2_calculation_quote_item_code, $row_no, $furnishing_2_calculation_quote_item_location, $furnishing_2_calculation_quote_item_cost, $furnishing_2_calculation_quote_item_markup, $furnishing_2_calculation_quote_item_notes, $furnishing_2_calculation_quote_item_price, $furnishing_2_calculation_quote_item_discount);
    $stmt_4->store_result();

    while ($stmt_4->fetch()) {

        $furnishing_2_calculation_quote_item_fields = array();

        $query_4_1 = "SELECT code, name, price, furnishing_2_calculation_field_code FROM furnishing_2_calculation_quote_item_fields WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

        $stmt_4_1 = $mysqli->prepare($query_4_1);
        $stmt_4_1->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
        $stmt_4_1->execute();
        $stmt_4_1->bind_result($furnishing_2_calculation_quote_item_field_code, $furnishing_2_calculation_quote_item_field_name, $furnishing_2_calculation_quote_item_field_price, $furnishing_2_calculation_field_code);

        while ($stmt_4_1->fetch()) {

            $furnishing_2_calculation_quote_item_fields[] = array(
                "code" => $furnishing_2_calculation_quote_item_field_code,
                "name" => $furnishing_2_calculation_quote_item_field_name,
                "price" => $furnishing_2_calculation_quote_item_field_price,
                "furnishing_2_calculation_field_code" => $furnishing_2_calculation_field_code
            );
        }
        $stmt_4_1->close();

        $furnishing_2_calculation_quote_item_accessories = array();

        $query_4_2 = "SELECT code, name, price, qty FROM furnishing_2_calculation_quote_item_accessories WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

        $stmt_4_2 = $mysqli->prepare($query_4_2);
        $stmt_4_2->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
        $stmt_4_2->execute();
        $stmt_4_2->bind_result($furnishing_2_calculation_quote_item_accessory_code, $furnishing_2_calculation_quote_item_accessory_name, $furnishing_2_calculation_quote_item_accessory_price, $furnishing_2_calculation_quote_item_accessory_qty);

        while ($stmt_4_2->fetch()) {

            $furnishing_2_calculation_quote_item_accessories[] = array(
                "code" => $furnishing_2_calculation_quote_item_accessory_code,
                "name" => $furnishing_2_calculation_quote_item_accessory_name,
                "price" => $furnishing_2_calculation_quote_item_accessory_price,
                "qty" => $furnishing_2_calculation_quote_item_accessory_qty
            );
        }
        $stmt_4_2->close();

        $furnishing_2_calculation_quote_item_per_meters = array();

        $query_4_3 = "SELECT code, name, price, width FROM furnishing_2_calculation_quote_item_per_meters WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

        $stmt_4_3 = $mysqli->prepare($query_4_3);
        $stmt_4_3->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
        $stmt_4_3->execute();
        $stmt_4_3->bind_result($furnishing_2_calculation_quote_item_per_meter_code, $furnishing_2_calculation_quote_item_per_meter_name, $furnishing_2_calculation_quote_item_per_meter_price, $furnishing_2_calculation_quote_item_per_meter_width);

        while ($stmt_4_3->fetch()) {

            $furnishing_2_calculation_quote_item_per_meters[] = array(
                "code" => $furnishing_2_calculation_quote_item_per_meter_code,
                "name" => $furnishing_2_calculation_quote_item_per_meter_name,
                "price" => $furnishing_2_calculation_quote_item_per_meter_price,
                "width" => $furnishing_2_calculation_quote_item_per_meter_width
            );
        }
        $stmt_4_3->close();


        $furnishing_2_calculation_quote_item_fitting_charges = array();

        $query_4_4 = "SELECT code, name, price FROM furnishing_2_calculation_quote_item_fitting_charges WHERE furnishing_2_calculation_quote_item_code = ? AND furnishing_2_calculation_code = ? AND cid = ?";

        $stmt_4_4 = $mysqli->prepare($query_4_4);
        $stmt_4_4->bind_param('sss', $furnishing_2_calculation_quote_item_code, $furnishing_2_calculation_code, $cid);
        $stmt_4_4->execute();
        $stmt_4_4->bind_result($furnishing_2_calculation_quote_item_fitting_charge_code, $furnishing_2_calculation_quote_item_fitting_charge_name, $furnishing_2_calculation_quote_item_fitting_charge_price);

        while ($stmt_4_4->fetch()) {

            $furnishing_2_calculation_quote_item_fitting_charges[] = array(
                "code" => $furnishing_2_calculation_quote_item_fitting_charge_code,
                "name" => $furnishing_2_calculation_quote_item_fitting_charge_name,
                "price" => $furnishing_2_calculation_quote_item_fitting_charge_price
            );
        }
        $stmt_4_4->close();

        $furnishing_2_calculation_quote_items[] = array(
            "code" => $furnishing_2_calculation_quote_item_code,
            "row_no" => $row_no,
            "location" => $furnishing_2_calculation_quote_item_location,
            "cost" => $furnishing_2_calculation_quote_item_cost,
            "markup" => $furnishing_2_calculation_quote_item_markup,
            "fields" => $furnishing_2_calculation_quote_item_fields,
            "notes" => $furnishing_2_calculation_quote_item_notes,
            "accessories" => $furnishing_2_calculation_quote_item_accessories,
            "per_meters" => $furnishing_2_calculation_quote_item_per_meters,
            "fitting_charges" => $furnishing_2_calculation_quote_item_fitting_charges,
            "price" => $furnishing_2_calculation_quote_item_price,
            "discount" => $furnishing_2_calculation_quote_item_discount
        );
    }
    $stmt_4->close();

    $query_4_5 = "SELECT DATE_FORMAT(install_date, '%d-%m-%Y') AS install_date , DATE_FORMAT(production_date, '%d-%m-%Y') AS production_date  FROM product_eta_dates WHERE calc_code = ? AND quote_no = ?";

    $stmt_4_5 = $mysqli->prepare($query_4_5);
    $stmt_4_5->bind_param('ss', $furnishing_2_calculation_code, $cid);
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

    $furnishing_2_calculations[] = array(
        "code" => $furnishing_2_calculation_code,
        "name" => $furnishing_2_calculation_name,
        "location_select" => $locations_select,
        "fields" => $furnishing_2_calculation_fields,
        "accessories_select" => $accessories_select,
        "accessory_options" => $furnishing_2_calculation_accessory_options,
        "per_meters_select" => $per_meters_select,
        "per_meter_options" => $furnishing_2_calculation_per_meter_options,
        "fitting_charges_select" => $fitting_charges_select,
        "fitting_charge_options" => $furnishing_2_calculation_fitting_charge_options,
        "quote_items" => $furnishing_2_calculation_quote_items,
        "production_date" => $production_date,
        "install_date" => $install_date,
        "status" => $furnishing_2_calculation_status
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


print json_encode(array(1, $furnishing_2_calculations, $locations)); // Success

$mysqli->close();
