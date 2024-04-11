<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');

$twc_calculations = array();

$query_1 = "SELECT code, name, locations, materials_and_colours, accessories, per_meters, fitting_charges, status FROM twc_calculations ORDER BY position DESC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($twc_calculation_code, $twc_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select, $twc_calculation_status);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $twc_calculation_type_options = array();

    $query_2 = "SELECT code, name FROM twc_calculation_type_options WHERE twc_calculation_code = ? ORDER BY name ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $twc_calculation_code);
    $stmt_2->execute();
    $stmt_2->bind_result($twc_calculation_type_option_code, $twc_calculation_type_option_name);
    $stmt_2->store_result();

    while ($stmt_2->fetch()) {

        $query_2_0 = "SELECT MAX(CAST(width_x AS SIGNED)), MAX(CAST(drop_x AS SIGNED)) FROM twc_calculation_type_option_price_sheet WHERE twc_calculation_type_option_code = ? AND twc_calculation_code = ?";

        $stmt_2_0 = $mysqli->prepare($query_2_0);
        $stmt_2_0->bind_param('ss', $twc_calculation_type_option_code, $twc_calculation_code);
        $stmt_2_0->execute();
        $stmt_2_0->bind_result($maxx_width, $maxx_drop);
        $stmt_2_0->fetch();
        $stmt_2_0->close();

        $min_max_dimensions = array(
            "max_width" => (float) $maxx_width,
            "max_drop" => (float) $maxx_drop
        );

        $materials_and_colours = array();

        $query_2_1 = "SELECT material_codes FROM twc_calculation_type_option_materials WHERE twc_calculation_type_option_code = ? AND twc_calculation_code = ?";

        $stmt_2_1 = $mysqli->prepare($query_2_1);
        $stmt_2_1->bind_param('ss', $twc_calculation_type_option_code, $twc_calculation_code);
        $stmt_2_1->execute();
        $stmt_2_1->store_result();

        if ($stmt_2_1->num_rows !== 0) {

            $stmt_2_1->bind_result($material_codes);
            $stmt_2_1->fetch();
            $stmt_2_1->close();

            $material_codes_x = "'" . str_replace(',', "','", $material_codes) . "'";

            $query_2_2 = "SELECT code, name, max_width FROM materials WHERE code IN( $material_codes_x ) ORDER BY id ASC";

            $stmt_2_2 = $mysqli->prepare($query_2_2);
            $stmt_2_2->execute();
            $stmt_2_2->bind_result($material_code, $material_name, $max_width);
            $stmt_2_2->store_result();

            while ($stmt_2_2->fetch()) {

                $colours = array();

                $query_2_2_1 = "SELECT code, name FROM colours WHERE material_code = ? ORDER BY id ASC";

                $stmt_2_2_1 = $mysqli->prepare($query_2_2_1);
                $stmt_2_2_1->bind_param('s', $material_code);
                $stmt_2_2_1->execute();
                $stmt_2_2_1->bind_result($colour_code, $colour_name);

                while ($stmt_2_2_1->fetch()) {
                    $colours[] = array(
                        "code" => $colour_code,
                        "name" => $colour_name
                    );
                }
                $stmt_2_2_1->close();

                $materials_and_colours[] = array(
                    "code" => $material_code,
                    "name" => $material_name,
                    "max_width" => $max_width,
                    "colours" => $colours
                );
            }
            $stmt_2_2->close();
        }

        $twc_calculation_type_options[] = array(
            "code" => $twc_calculation_type_option_code,
            "name" => $twc_calculation_type_option_name,
            "min_max_dimensions" => $min_max_dimensions,
            "materials_and_colours" => $materials_and_colours
        );
    }
    $stmt_2->close();

    $twc_calculation_fields = array();

    $query_3 = "SELECT code, name, side, field_type FROM twc_calculation_fields WHERE twc_calculation_code = ? ORDER BY position ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('s', $twc_calculation_code);
    $stmt_3->execute();
    $stmt_3->bind_result($twc_calculation_field_code, $twc_calculation_field_name, $twc_calculation_field_side, $twc_calculation_field_type);
    $stmt_3->store_result();

    while ($stmt_3->fetch()) {

        $twc_calculation_field_options = array();

        $query_3_1 = "SELECT code, name, price FROM twc_calculation_field_options WHERE twc_calculation_field_code = ? AND twc_calculation_code = ? ORDER BY name ASC";

        $stmt_3_1 = $mysqli->prepare($query_3_1);
        $stmt_3_1->bind_param('ss', $twc_calculation_field_code, $twc_calculation_code);
        $stmt_3_1->execute();
        $stmt_3_1->bind_result($twc_calculation_field_option_code, $twc_calculation_field_option_name, $twc_calculation_field_option_price);

        while ($stmt_3_1->fetch()) {
            $twc_calculation_field_options[] = array(
                "code" => $twc_calculation_field_option_code,
                "name" => $twc_calculation_field_option_name,
                "price" => $twc_calculation_field_option_price
            );
        }
        $stmt_3_1->close();

        $twc_calculation_fields[] = array(
            "code" => $twc_calculation_field_code,
            "name" => $twc_calculation_field_name,
            "side" => $twc_calculation_field_side,
            "field_type" => $twc_calculation_field_type,
            "options" => $twc_calculation_field_options
        );
    }
    $stmt_3->close();



    $twc_calculation_accessory_options = array();

    $query_2x = "SELECT code, name, price FROM twc_calculation_accessory_options WHERE twc_calculation_code = ? ORDER BY id ASC";

    $stmt_2x = $mysqli->prepare($query_2x);
    $stmt_2x->bind_param('s', $twc_calculation_code);
    $stmt_2x->execute();
    $stmt_2x->bind_result($twc_calculation_accessory_option_code, $twc_calculation_accessory_option_name, $twc_calculation_accessory_option_price);

    while ($stmt_2x->fetch()) {
        $twc_calculation_accessory_options[] = array(
            "code" => $twc_calculation_accessory_option_code,
            "name" => $twc_calculation_accessory_option_name,
            "price" => $twc_calculation_accessory_option_price
        );
    }
    $stmt_2x->close();



    $twc_calculation_per_meter_options = array();

    $query_3x = "SELECT code, name, price FROM twc_calculation_per_meter_options WHERE twc_calculation_code = ? ORDER BY id ASC";

    $stmt_3x = $mysqli->prepare($query_3x);
    $stmt_3x->bind_param('s', $twc_calculation_code);
    $stmt_3x->execute();
    $stmt_3x->bind_result($twc_calculation_per_meter_option_code, $twc_calculation_per_meter_option_name, $twc_calculation_per_meter_option_price);

    while ($stmt_3x->fetch()) {
        $twc_calculation_per_meter_options[] = array(
            "code" => $twc_calculation_per_meter_option_code,
            "name" => $twc_calculation_per_meter_option_name,
            "price" => $twc_calculation_per_meter_option_price
        );
    }
    $stmt_3x->close();



    $twc_calculation_fitting_charge_options = array();

    $query_4x = "SELECT code, name, price FROM twc_calculation_fitting_charge_options WHERE twc_calculation_code = ? ORDER BY id ASC";

    $stmt_4x = $mysqli->prepare($query_4x);
    $stmt_4x->bind_param('s', $twc_calculation_code);
    $stmt_4x->execute();
    $stmt_4x->bind_result($twc_calculation_fitting_charge_option_code, $twc_calculation_fitting_charge_option_name, $twc_calculation_fitting_charge_option_price);

    while ($stmt_4x->fetch()) {
        $twc_calculation_fitting_charge_options[] = array(
            "code" => $twc_calculation_fitting_charge_option_code,
            "name" => $twc_calculation_fitting_charge_option_name,
            "price" => $twc_calculation_fitting_charge_option_price
        );
    }
    $stmt_4x->close();



    $twc_calculation_quote_items = array();

    $query_4 = "SELECT code, location, width_x, drop_x, type, material, colour, notes, price, discount FROM twc_calculation_quote_items WHERE twc_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('ss', $twc_calculation_code, $cid);
    $stmt_4->execute();
    $stmt_4->bind_result($twc_calculation_quote_item_code, $twc_calculation_quote_item_location, $twc_calculation_quote_item_width, $twc_calculation_quote_item_drop, $twc_calculation_quote_item_type, $twc_calculation_quote_item_material, $twc_calculation_quote_item_colour, $twc_calculation_quote_item_notes, $twc_calculation_quote_item_price, $twc_calculation_quote_item_discount);
    $stmt_4->store_result();

    while ($stmt_4->fetch()) {

        $twc_calculation_quote_item_fields = array();

        $query_4_1 = "SELECT code, name, price, twc_calculation_field_code FROM twc_calculation_quote_item_fields WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

        $stmt_4_1 = $mysqli->prepare($query_4_1);
        $stmt_4_1->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
        $stmt_4_1->execute();
        $stmt_4_1->bind_result($twc_calculation_quote_item_field_code, $twc_calculation_quote_item_field_name, $twc_calculation_quote_item_field_price, $twc_calculation_field_code);

        while ($stmt_4_1->fetch()) {

            $twc_calculation_quote_item_fields[] = array(
                "code" => $twc_calculation_quote_item_field_code,
                "name" => $twc_calculation_quote_item_field_name,
                "price" => $twc_calculation_quote_item_field_price,
                "twc_calculation_field_code" => $twc_calculation_field_code
            );
        }
        $stmt_4_1->close();

        $twc_calculation_quote_item_accessories = array();

        $query_4_2 = "SELECT code, name, price, qty FROM twc_calculation_quote_item_accessories WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

        $stmt_4_2 = $mysqli->prepare($query_4_2);
        $stmt_4_2->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
        $stmt_4_2->execute();
        $stmt_4_2->bind_result($twc_calculation_quote_item_accessory_code, $twc_calculation_quote_item_accessory_name, $twc_calculation_quote_item_accessory_price, $twc_calculation_quote_item_accessory_qty);

        while ($stmt_4_2->fetch()) {

            $twc_calculation_quote_item_accessories[] = array(
                "code" => $twc_calculation_quote_item_accessory_code,
                "name" => $twc_calculation_quote_item_accessory_name,
                "price" => $twc_calculation_quote_item_accessory_price,
                "qty" => $twc_calculation_quote_item_accessory_qty
            );
        }
        $stmt_4_2->close();

        $twc_calculation_quote_item_per_meters = array();

        $query_4_3 = "SELECT code, name, price, width FROM twc_calculation_quote_item_per_meters WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

        $stmt_4_3 = $mysqli->prepare($query_4_3);
        $stmt_4_3->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
        $stmt_4_3->execute();
        $stmt_4_3->bind_result($twc_calculation_quote_item_per_meter_code, $twc_calculation_quote_item_per_meter_name, $twc_calculation_quote_item_per_meter_price, $twc_calculation_quote_item_per_meter_width);

        while ($stmt_4_3->fetch()) {

            $twc_calculation_quote_item_per_meters[] = array(
                "code" => $twc_calculation_quote_item_per_meter_code,
                "name" => $twc_calculation_quote_item_per_meter_name,
                "price" => $twc_calculation_quote_item_per_meter_price,
                "width" => $twc_calculation_quote_item_per_meter_width
            );
        }
        $stmt_4_3->close();


        $twc_calculation_quote_item_fitting_charges = array();

        $query_4_4 = "SELECT code, name, price FROM twc_calculation_quote_item_fitting_charges WHERE twc_calculation_quote_item_code = ? AND twc_calculation_code = ? AND cid = ?";

        $stmt_4_4 = $mysqli->prepare($query_4_4);
        $stmt_4_4->bind_param('sss', $twc_calculation_quote_item_code, $twc_calculation_code, $cid);
        $stmt_4_4->execute();
        $stmt_4_4->bind_result($twc_calculation_quote_item_fitting_charge_code, $twc_calculation_quote_item_fitting_charge_name, $twc_calculation_quote_item_fitting_charge_price);

        while ($stmt_4_4->fetch()) {

            $twc_calculation_quote_item_fitting_charges[] = array(
                "code" => $twc_calculation_quote_item_fitting_charge_code,
                "name" => $twc_calculation_quote_item_fitting_charge_name,
                "price" => $twc_calculation_quote_item_fitting_charge_price
            );
        }
        $stmt_4_4->close();

        $twc_calculation_quote_items[] = array(
            "code" => $twc_calculation_quote_item_code,
            "location" => $twc_calculation_quote_item_location,
            "width" => $twc_calculation_quote_item_width,
            "drop" => $twc_calculation_quote_item_drop,
            "type" => $twc_calculation_quote_item_type,
            "material" => $twc_calculation_quote_item_material,
            "colour" => $twc_calculation_quote_item_colour,
            "fields" => $twc_calculation_quote_item_fields,
            "notes" => $twc_calculation_quote_item_notes,
            "accessories" => $twc_calculation_quote_item_accessories,
            "per_meters" => $twc_calculation_quote_item_per_meters,
            "fitting_charges" => $twc_calculation_quote_item_fitting_charges,
            "price" => $twc_calculation_quote_item_price,
            "discount" => $twc_calculation_quote_item_discount
        );
    }
    $stmt_4->close();

    $twc_calculations[] = array(
        "code" => $twc_calculation_code,
        "name" => $twc_calculation_name,
        "location_select" => $locations_select,
        "types" => $twc_calculation_type_options,
        "materials_and_colour_select" => $materials_and_colours_select,
        "fields" => $twc_calculation_fields,
        "accessories_select" => $accessories_select,
        "accessory_options" => $twc_calculation_accessory_options,
        "per_meters_select" => $per_meters_select,
        "per_meter_options" => $twc_calculation_per_meter_options,
        "fitting_charges_select" => $fitting_charges_select,
        "fitting_charge_options" => $twc_calculation_fitting_charge_options,
        "quote_items" => $twc_calculation_quote_items,
        "status" => $twc_calculation_status
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


print json_encode(array(1, $twc_calculations, $locations)); // Success

$mysqli->close();