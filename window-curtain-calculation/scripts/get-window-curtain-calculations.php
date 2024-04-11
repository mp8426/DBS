<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');

$window_curtain_calculations = array();

$query_2 = "SELECT code, name, locations, accessories, per_meters, fitting_charges, status FROM window_curtain_calculations ORDER BY position DESC";

$stmt_1 = $mysqli->prepare($query_2);
$stmt_1->execute();
$stmt_1->bind_result($window_curtain_calculation_code, $window_curtain_calculation_name, $locations_select, $accessories_select, $per_meters_select, $fitting_charges_select, $window_curtain_calculation_status);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $window_curtain_calculation_supplier_options = array();
    //$window_curtain_calculation_fabric_options = array();

    //Changed fabric_options as Supplier and fabric_options Fabric on 02.02.2018
    $query_2 = "SELECT code, name FROM window_curtain_calculation_supplier_options WHERE window_curtain_calculation_code = ? ORDER BY name ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $window_curtain_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($window_curtain_calculation_supplier_option_code, $window_curtain_calculation_supplier_option_name);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {

//        $query_2_1 = "SELECT code, name, width, price FROM window_curtain_calculation_fabric_options WHERE window_curtain_calculation_supplier_option_code = ? AND window_curtain_calculation_code = ? ORDER BY name ASC";
//
//        $stmt_2_1 = $mysqli->prepare($query_2_1);
//        $stmt_2_1->bind_param('ss', $window_curtain_calculation_supplier_option_code, $window_curtain_calculation_code);
//        $stmt_2_1->execute();
//        $stmt_2_1->bind_result($window_curtain_calculation_fabric_option_code, $window_curtain_calculation_fabric_option_name, $window_curtain_calculation_fabric_option_width, $window_curtain_calculation_fabric_option_price);
//        $stmt_2_1->store_result();
//
//        while ($stmt_2_1->fetch()) {
//
//            $window_curtain_calculation_fabric_options[] = array(
//                "code" => $window_curtain_calculation_fabric_option_code,
//                "name" => $window_curtain_calculation_fabric_option_name,
//                "width" => $window_curtain_calculation_fabric_option_width,
//                "price" => $window_curtain_calculation_fabric_option_price,
//                "supplier_code" => $window_curtain_calculation_supplier_option_code,
//            );
//        }
//        $stmt_2_1->close();

        $window_curtain_calculation_supplier_options[] = array(
            "code" => $window_curtain_calculation_supplier_option_code,
            "name" => $window_curtain_calculation_supplier_option_name,
        );
    }
    $stmt_2->close();


    $window_curtain_calculation_curtain_type_1_options = array();

    $query_3 = "SELECT code, name, price FROM window_curtain_calculation_curtain_type_1_options WHERE window_curtain_calculation_code = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('s', $window_curtain_calculation_code);
    $stmt_3->execute();
    $stmt_3->bind_result($window_curtain_calculation_curtain_type_1_option_code, $window_curtain_calculation_curtain_type_1_option_name, $window_curtain_calculation_curtain_type_1_option_price);

    while ($stmt_3->fetch()) {
        $window_curtain_calculation_curtain_type_1_options[] = array(
            "code" => $window_curtain_calculation_curtain_type_1_option_code,
            "name" => $window_curtain_calculation_curtain_type_1_option_name,
            "price" => $window_curtain_calculation_curtain_type_1_option_price
        );
    }
    $stmt_3->close();


    $window_curtain_calculation_curtain_type_2_options = array();

    $query_4 = "SELECT code, name, price FROM window_curtain_calculation_curtain_type_2_options WHERE window_curtain_calculation_code = ? ORDER BY id ASC";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('s', $window_curtain_calculation_code);
    $stmt_4->execute();
    $stmt_4->bind_result($window_curtain_calculation_curtain_type_2_option_code, $window_curtain_calculation_curtain_type_2_option_name, $window_curtain_calculation_curtain_type_2_option_price);

    while ($stmt_4->fetch()) {
        $window_curtain_calculation_curtain_type_2_options[] = array(
            "code" => $window_curtain_calculation_curtain_type_2_option_code,
            "name" => $window_curtain_calculation_curtain_type_2_option_name,
            "price" => $window_curtain_calculation_curtain_type_2_option_price
        );
    }
    $stmt_4->close();


    $window_curtain_calculation_fields = array();

    $query_5 = "SELECT code, name, side, field_type FROM window_curtain_calculation_fields WHERE window_curtain_calculation_code = ? ORDER BY position ASC";

    $stmt_5 = $mysqli->prepare($query_5);
    $stmt_5->bind_param('s', $window_curtain_calculation_code);
    $stmt_5->execute();
    $stmt_5->bind_result($window_curtain_calculation_field_code, $window_curtain_calculation_field_name, $window_curtain_calculation_field_side, $window_curtain_calculation_field_type);
    $stmt_5->store_result();

    while ($stmt_5->fetch()) {

        $window_curtain_calculation_field_options = array();

        $query_5_1 = "SELECT code, name, price FROM window_curtain_calculation_field_options WHERE window_curtain_calculation_field_code = ? AND window_curtain_calculation_code = ? ORDER BY id ASC";

        $stmt_5_1 = $mysqli->prepare($query_5_1);
        $stmt_5_1->bind_param('ss', $window_curtain_calculation_field_code, $window_curtain_calculation_code);
        $stmt_5_1->execute();
        $stmt_5_1->bind_result($window_curtain_calculation_field_option_code, $window_curtain_calculation_field_option_name, $window_curtain_calculation_field_option_price);

        while ($stmt_5_1->fetch()) {
            $window_curtain_calculation_field_options[] = array(
                "code" => $window_curtain_calculation_field_option_code,
                "name" => $window_curtain_calculation_field_option_name,
                "price" => $window_curtain_calculation_field_option_price
            );
        }
        $stmt_5_1->close();

        $window_curtain_calculation_fields[] = array(
            "code" => $window_curtain_calculation_field_code,
            "name" => $window_curtain_calculation_field_name,
            "side" => $window_curtain_calculation_field_side,
            "field_type" => $window_curtain_calculation_field_type,
            "options" => $window_curtain_calculation_field_options
        );
    }
    $stmt_5->close();



    $window_curtain_calculation_accessory_options = array();

    $query_6 = "SELECT code, name, price FROM window_curtain_calculation_accessory_options WHERE window_curtain_calculation_code = ? ORDER BY id ASC";

    $stmt_6 = $mysqli->prepare($query_6);
    $stmt_6->bind_param('s', $window_curtain_calculation_code);
    $stmt_6->execute();
    $stmt_6->bind_result($window_curtain_calculation_accessory_option_code, $window_curtain_calculation_accessory_option_name, $window_curtain_calculation_accessory_option_price);

    while ($stmt_6->fetch()) {
        $window_curtain_calculation_accessory_options[] = array(
            "code" => $window_curtain_calculation_accessory_option_code,
            "name" => $window_curtain_calculation_accessory_option_name,
            "price" => $window_curtain_calculation_accessory_option_price
        );
    }
    $stmt_6->close();



    $window_curtain_calculation_per_meter_options = array();

    $query_7 = "SELECT code, name, price FROM window_curtain_calculation_per_meter_options WHERE window_curtain_calculation_code = ? ORDER BY id ASC";

    $stmt_7 = $mysqli->prepare($query_7);
    $stmt_7->bind_param('s', $window_curtain_calculation_code);
    $stmt_7->execute();
    $stmt_7->bind_result($window_curtain_calculation_per_meter_option_code, $window_curtain_calculation_per_meter_option_name, $window_curtain_calculation_per_meter_option_price);

    while ($stmt_7->fetch()) {
        $window_curtain_calculation_per_meter_options[] = array(
            "code" => $window_curtain_calculation_per_meter_option_code,
            "name" => $window_curtain_calculation_per_meter_option_name,
            "price" => $window_curtain_calculation_per_meter_option_price
        );
    }
    $stmt_7->close();



    $window_curtain_calculation_fitting_charge_options = array();

    $query_8 = "SELECT code, name, price FROM window_curtain_calculation_fitting_charge_options WHERE window_curtain_calculation_code = ? ORDER BY id ASC";

    $stmt_8 = $mysqli->prepare($query_8);
    $stmt_8->bind_param('s', $window_curtain_calculation_code);
    $stmt_8->execute();
    $stmt_8->bind_result($window_curtain_calculation_fitting_charge_option_code, $window_curtain_calculation_fitting_charge_option_name, $window_curtain_calculation_fitting_charge_option_price);

    while ($stmt_8->fetch()) {
        $window_curtain_calculation_fitting_charge_options[] = array(
            "code" => $window_curtain_calculation_fitting_charge_option_code,
            "name" => $window_curtain_calculation_fitting_charge_option_name,
            "price" => $window_curtain_calculation_fitting_charge_option_price
        );
    }
    $stmt_8->close();



    $window_curtain_calculation_quote_items = array();

    $query_9 = "SELECT code, location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, continuous_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty, notes, price, discount FROM window_curtain_calculation_quote_items WHERE window_curtain_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_9 = $mysqli->prepare($query_9);
    $stmt_9->bind_param('ss', $window_curtain_calculation_code, $cid);
    $stmt_9->execute();
    $stmt_9->bind_result($window_curtain_calculation_quote_item_code, $window_curtain_calculation_quote_item_location, $window_curtain_calculation_quote_item_width, $window_curtain_calculation_quote_item_right_return, $window_curtain_calculation_quote_item_left_return, $window_curtain_calculation_quote_item_overlap, $window_curtain_calculation_quote_item_fullness, $window_curtain_calculation_quote_item_supplier, $window_curtain_calculation_quote_item_fabric, $window_curtain_calculation_quote_item_fabric_colour, $window_curtain_calculation_quote_item_qty_drop, $window_curtain_calculation_quote_item_curtain_type_1, $window_curtain_calculation_quote_item_continuous_meter, $window_curtain_calculation_quote_item_curtain_type_2, $window_curtain_calculation_quote_item_drop, $window_curtain_calculation_quote_item_hem_heading, $window_curtain_calculation_quote_item_pattern_repeate, $window_curtain_calculation_quote_item_fabric_cut_length, $window_curtain_calculation_quote_item_fabric_qty, $window_curtain_calculation_quote_item_notes, $window_curtain_calculation_quote_item_price, $window_curtain_calculation_quote_item_discount);
    $stmt_9->store_result();

    while ($stmt_9->fetch()) {

        $window_curtain_calculation_quote_item_fields = array();

        $query_9_1 = "SELECT code, name, price, window_curtain_calculation_field_code FROM window_curtain_calculation_quote_item_fields WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

        $stmt_9_1 = $mysqli->prepare($query_9_1);
        $stmt_9_1->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
        $stmt_9_1->execute();
        $stmt_9_1->bind_result($window_curtain_calculation_quote_item_field_code, $window_curtain_calculation_quote_item_field_name, $window_curtain_calculation_quote_item_field_price, $window_curtain_calculation_field_code);

        while ($stmt_9_1->fetch()) {

            $window_curtain_calculation_quote_item_fields[] = array(
                "code" => $window_curtain_calculation_quote_item_field_code,
                "name" => $window_curtain_calculation_quote_item_field_name,
                "price" => $window_curtain_calculation_quote_item_field_price,
                "window_curtain_calculation_field_code" => $window_curtain_calculation_field_code
            );
        }
        $stmt_9_1->close();

        $window_curtain_calculation_quote_item_accessories = array();

        $query_9_2 = "SELECT code, name, price, qty FROM window_curtain_calculation_quote_item_accessories WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

        $stmt_9_2 = $mysqli->prepare($query_9_2);
        $stmt_9_2->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
        $stmt_9_2->execute();
        $stmt_9_2->bind_result($window_curtain_calculation_quote_item_accessory_code, $window_curtain_calculation_quote_item_accessory_name, $window_curtain_calculation_quote_item_accessory_price, $window_curtain_calculation_quote_item_accessory_qty);

        while ($stmt_9_2->fetch()) {

            $window_curtain_calculation_quote_item_accessories[] = array(
                "code" => $window_curtain_calculation_quote_item_accessory_code,
                "name" => $window_curtain_calculation_quote_item_accessory_name,
                "price" => $window_curtain_calculation_quote_item_accessory_price,
                "qty" => $window_curtain_calculation_quote_item_accessory_qty
            );
        }
        $stmt_9_2->close();

        $window_curtain_calculation_quote_item_per_meters = array();

        $query_9_3 = "SELECT code, name, price, width FROM window_curtain_calculation_quote_item_per_meters WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

        $stmt_9_3 = $mysqli->prepare($query_9_3);
        $stmt_9_3->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
        $stmt_9_3->execute();
        $stmt_9_3->bind_result($window_curtain_calculation_quote_item_per_meter_code, $window_curtain_calculation_quote_item_per_meter_name, $window_curtain_calculation_quote_item_per_meter_price, $window_curtain_calculation_quote_item_per_meter_width);

        while ($stmt_9_3->fetch()) {

            $window_curtain_calculation_quote_item_per_meters[] = array(
                "code" => $window_curtain_calculation_quote_item_per_meter_code,
                "name" => $window_curtain_calculation_quote_item_per_meter_name,
                "price" => $window_curtain_calculation_quote_item_per_meter_price,
                "width" => $window_curtain_calculation_quote_item_per_meter_width
            );
        }
        $stmt_9_3->close();


        $window_curtain_calculation_quote_item_fitting_charges = array();

        $query_9_4 = "SELECT code, name, price FROM window_curtain_calculation_quote_item_fitting_charges WHERE window_curtain_calculation_quote_item_code = ? AND window_curtain_calculation_code = ? AND cid = ?";

        $stmt_9_4 = $mysqli->prepare($query_9_4);
        $stmt_9_4->bind_param('sss', $window_curtain_calculation_quote_item_code, $window_curtain_calculation_code, $cid);
        $stmt_9_4->execute();
        $stmt_9_4->bind_result($window_curtain_calculation_quote_item_fitting_charge_code, $window_curtain_calculation_quote_item_fitting_charge_name, $window_curtain_calculation_quote_item_fitting_charge_price);

        while ($stmt_9_4->fetch()) {

            $window_curtain_calculation_quote_item_fitting_charges[] = array(
                "code" => $window_curtain_calculation_quote_item_fitting_charge_code,
                "name" => $window_curtain_calculation_quote_item_fitting_charge_name,
                "price" => $window_curtain_calculation_quote_item_fitting_charge_price
            );
        }
        $stmt_9_4->close();

        $window_curtain_calculation_quote_items[] = array(
            "code" => $window_curtain_calculation_quote_item_code,
            "location" => $window_curtain_calculation_quote_item_location,
            "width" => $window_curtain_calculation_quote_item_width,
            "right_return" => $window_curtain_calculation_quote_item_right_return,
            "left_return" => $window_curtain_calculation_quote_item_left_return,
            "overlap" => $window_curtain_calculation_quote_item_overlap,
            "fullness" => $window_curtain_calculation_quote_item_fullness,
            "supplier" => $window_curtain_calculation_quote_item_supplier,
            "fabric" => $window_curtain_calculation_quote_item_fabric,
            "fabric_colour" => $window_curtain_calculation_quote_item_fabric_colour,
            "qty_drop" => $window_curtain_calculation_quote_item_qty_drop,
            "curtain_type_1" => $window_curtain_calculation_quote_item_curtain_type_1,
            "continuous_meter" => $window_curtain_calculation_quote_item_continuous_meter,
            "curtain_type_2" => $window_curtain_calculation_quote_item_curtain_type_2,
            "drop" => $window_curtain_calculation_quote_item_drop,
            "hem_heading" => $window_curtain_calculation_quote_item_hem_heading,
            "pattern_repeate" => $window_curtain_calculation_quote_item_pattern_repeate,
            "fabric_cut_length" => $window_curtain_calculation_quote_item_fabric_cut_length,
            "fabric_qty" => $window_curtain_calculation_quote_item_fabric_qty,
            "fields" => $window_curtain_calculation_quote_item_fields,
            "notes" => $window_curtain_calculation_quote_item_notes,
            "accessories" => $window_curtain_calculation_quote_item_accessories,
            "per_meters" => $window_curtain_calculation_quote_item_per_meters,
            "fitting_charges" => $window_curtain_calculation_quote_item_fitting_charges,
            "price" => $window_curtain_calculation_quote_item_price,
            "discount" => $window_curtain_calculation_quote_item_discount
        );
    }
    $stmt_9->close();

    $window_curtain_calculations[] = array(
        "code" => $window_curtain_calculation_code,
        "name" => $window_curtain_calculation_name,
        "location_select" => $locations_select,
        "supplier_options" => $window_curtain_calculation_supplier_options,
        //"fabric_options" => $window_curtain_calculation_fabric_options,
        "curtain_type_1_options" => $window_curtain_calculation_curtain_type_1_options,
        "curtain_type_2_options" => $window_curtain_calculation_curtain_type_2_options,
        "fields" => $window_curtain_calculation_fields,
        "accessories_select" => $accessories_select,
        "accessory_options" => $window_curtain_calculation_accessory_options,
        "per_meters_select" => $per_meters_select,
        "per_meter_options" => $window_curtain_calculation_per_meter_options,
        "fitting_charges_select" => $fitting_charges_select,
        "fitting_charge_options" => $window_curtain_calculation_fitting_charge_options,
        "quote_items" => $window_curtain_calculation_quote_items,
        "status" => $window_curtain_calculation_status
    );
}
$stmt_1->close();



$locations = array();

$query_10 = "SELECT code, name FROM locations ORDER BY name ASC";

$stmt_10 = $mysqli->prepare($query_10);
$stmt_10->execute();
$stmt_10->bind_result($location_code, $location_name);

while ($stmt_10->fetch()) {
    $locations[] = array(
        "code" => $location_code,
        "name" => $location_name
    );
}
$stmt_10->close();


print json_encode(array(1, $window_curtain_calculations, $locations)); // Success

$mysqli->close();
