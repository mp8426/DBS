<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';

$print_type = $_GET['type']; // " . $print_type . ", customer_copy, order_sheet
$no = +$_GET['no']; // 0 = " . $print_type . ", 1 = customer_copy, 2 = order_sheet

$calculation = filter_input(INPUT_GET, 'calculation'); // Main calculation Name
$calculation_x = str_replace("-", "_", $calculation);

$calculation_code = filter_input(INPUT_GET, 'code');
$window_price_sheet_9_calculation_code = filter_input(INPUT_GET, 'code');

$cid = filter_input(INPUT_GET, 'cid');
$today_date = date("l, d F y ");


$query_1z = "SELECT name FROM " . $calculation_x . "s WHERE code = ?";

$stmt_1z = $mysqli->prepare($query_1z);
$stmt_1z->bind_param('s', $calculation_code);
$stmt_1z->execute();
$stmt_1z->bind_result($calculation_name);
$stmt_1z->fetch();
$stmt_1z->close();

$query_3z = "SELECT q_name_1, q_name_2, q_address_1, q_address_2, q_suburb, q_postcode, q_email, q_phone, q_mobile, c_ref FROM quotes WHERE quote_no = ?";

$stmt_3z = $mysqli->prepare($query_3z);
$stmt_3z->bind_param('s', $cid);
$stmt_3z->execute();
$stmt_3z->bind_result($q_name_1, $q_name_2, $q_address_1, $q_address_2, $q_suburb, $q_postcode, $q_email, $q_phone, $q_mobile, $c_ref);
$stmt_3z->fetch();
$stmt_3z->close();

$query_4z = "SELECT job_no FROM jobs WHERE quote_no = ?";

$stmt_4z = $mysqli->prepare($query_4z);
$stmt_4z->bind_param('s', $cid);
$stmt_4z->execute();
$stmt_4z->bind_result($job_no);
$stmt_4z->fetch();
$stmt_4z->close();

$window_price_sheet_9_calculation_quote_tables = "";
$window_price_sheet_9_calculation_quote_tables_1 = "";

$query_1 = "SELECT name, locations, materials_and_colours, accessories, per_meters, fitting_charges FROM window_price_sheet_9_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $window_price_sheet_9_calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($window_price_sheet_9_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $quote_item_no = 1;
    $window_price_sheet_9_calculation_quote_items = "";

    $query_3 = "SELECT code, location, width_x, drop_x, type, material, colour FROM window_price_sheet_9_calculation_quote_items WHERE window_price_sheet_9_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_price_sheet_9_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_price_sheet_9_calculation_quote_item_code, $window_price_sheet_9_calculation_quote_item_location, $window_price_sheet_9_calculation_quote_item_width, $window_price_sheet_9_calculation_quote_item_drop, $window_price_sheet_9_calculation_quote_item_type, $window_price_sheet_9_calculation_quote_item_material, $window_price_sheet_9_calculation_quote_item_colour);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

    if($calculation_name === 'Ziptrak' || $calculation_name === 'Ziptrak®' || $calculation_name === 'Ziptrak® Interior1'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size:15px; text-align: center;" border="1">'
                . '<tr style="border: 2px solid #000000;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Side Spline Colour</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size:14px; text-align: center;" border="1">'
                        . '<tr style="border: 1px solid #000000;">'
                        . '<th rowspan="2" style="width: 20px; font-size:14px;"><b>No</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Location</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Fabric Name & Type</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Fabric/Colour</b></th>'
                        . '<th colspan="2" style="width: 60px; font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; font-size:14px; background-color: #d8d6d7;"><b>Skin Size</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Control Type</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Control Side</b></th>'
                        // . '<th rowspan="2" style="width: 35px;"><b>Side Spline Colour</b></th>'
                        . '<th rowspan="2" style="width: 50px; font-size:14px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Type</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Size</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Hood/Brackets</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Hood/Brackets Colour</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Hood/Brackets Size</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail Type</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail Colour</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Bottom Bar Size</b></th>'
                        // . '<th rowspan="2" style="width: 35px;"><b>Bottom Bar Seal</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Fittment</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Fitting Type</b></th>'
                        // . '<th rowspan="2" style="width: 35px;"><b>Track Colour</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Weight Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Flat Bar Size</b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size:18px; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;">'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    } else if($calculation_name === 'Zipscreen' || $calculation_name === 'Zipscreen®' || $calculation_name === 'Zipscreen Blinds'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size:18px; text-align: center;" border="2">'
                . '<tr style="border: 1px solid #000000; font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000; font-size:14px;"><b>No</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000; font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000; font-size:14px;"><b>Fabric/Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000; font-size:14px;"><b>Side Spline Colour</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000; font-size:14px;"><b>Control Type</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000; font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000; font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000; font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000; font-size:14px;"><b>Bottom Rail Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000; font-size:14px;"><b>Bottom Bar Seal</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000; font-size:14px;"><b>Fitting Type</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000; font-size:14px;"><b>Track Colour</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="text-align: center;" border="1">'
                        . '<tr style="border: 1px solid #000000; font-size:14px;">'
                        . '<th rowspan="2" style="width: 20px; font-size:14px;"><b>No</b></th>'
                        . '<th colspan="2" style="text-align: center; width: 60px; font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="text-align: center; width: 60px; font-size:14px; background-color: #d8d6d7;"><b>Skin Size</b></th>'
                        . '<th rowspan="2" style="width: 50px; font-size:14px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Type</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Hood/Brackets Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Bottom Bar Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Weight Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b></b></th>'
                        . '</tr>'
                        . '<tr style="border: 1px solid #000000; font-size:14px;">'
                        . '<th style="text-align: center; width: 30px; border: 1px solid #000000; font-size:14px;"><b>Width</b></th>'
                        . '<th style="text-align: center; width: 30px; border: 1px solid #000000; font-size:14px;"><b>Height</b></th>'
                        . '<th style="text-align: center; width: 30px; border: 1px solid #000000; font-size:14px; background-color: #d8d6d7;"><b>Width</b></th>'
                        . '<th style="text-align: center; width: 30px; border: 1px solid #000000; font-size:14px; background-color: #d8d6d7;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size:14px; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }
    else if($calculation_name === 'Slidetrack Blinds'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Side Spline Colour</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 20px; font-size:14px;"><b>No</b></th>'
                        . '<th colspan="2" style="width: 60px; font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; font-size:14px; background-color: #d8d6d7;"><b>Skin Size</b></th>'
                        . '<th rowspan="2" style="width: 50px; font-size:14px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Type</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Hood/Brackets Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Bottom Bar Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Weight Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b></b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; font-size:14px;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }

    else if($calculation_name === 'eZip Blinds' || $calculation_name === 'Ezip'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th colspan="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 20px; font-size:14px;"><b>No</b></th>'
                        . '<th colspan="2" style="width: 60px; font-size:14px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; font-size:14px; background-color: #d8d6d7;"><b>Skin Size</b></th>'
                        . '<th rowspan="2" style="width: 50px; font-size:14px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Type</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Hood/Brackets Size</b></th>'
                        . '<th rowspan="2" style="width: 40px; font-size:14px;"><b>Bottom Bar Size</b></th>'
                        . '<th colspan="2" rowspan="2" style="width: 40px; font-size:14px;"><b>Weight Size</b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }
    else if($calculation_name === 'Canvas/Mesh Awnings'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th colspan ="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Side Spline Colour</b></th>'
                // . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                // . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th colspan ="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                // . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Valance Type</b></th>'
                . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Arm Size</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                        . '<th colspan="2" style="width: 60px; border: 1px solid #000000; font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; background-color: #d8d6d7; border: 1px solid #000000; font-size:14px;"><b>Skin Size</b></th>'
                        . '<th rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Type</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 35px; font-size:14px;"><b>Tube Size</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 40px; font-size:14px;"><b>Hood/Brackets Size</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 40px; font-size:14px;"><b>Bottom Bar Size</b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; border: 1px solid #000000; font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; border: 1px solid #000000; font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000; font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000; font-size:14px;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }
    else if($calculation_name === 'Fixed Guide Awnings'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th colspan ="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Side Spline Colour</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                // . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Valance Type</b></th>'
                . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Arm Size</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                        . '<th colspan="2" style="width: 60px; border: 1px solid #000000;  font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Skin Size</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px;"><b>Tube Type</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 35px;"><b>Tube Size</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 40px;"><b>Hood/Brackets Size</b></th>'
                        . '<th rowspan="2" style="width: 40px;"><b>Bottom Bar Size</b></th>'
                        . '<th rowspan="2" style="width: 40px;"><b>Weight Size</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b></b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }
    else if($calculation_name === 'Straight Drop Crank'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th colspan ="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Side Spline Colour</b></th>'
                // . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                // . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Valance Type</b></th>'
                . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Arm Size</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                        . '<th colspan="2" style="width: 60px; border: 1px solid #000000;  font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Skin Size</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px;"><b>Tube Type</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 35px;"><b>Tube Size</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 40px;"><b>Hood/Brackets Size</b></th>'
                        . '<th rowspan="2" style="width: 40px;"><b>Bottom Bar Size</b></th>'
                        . '<th rowspan="2" style="width: 40px;"><b>Weight Size</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b></b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }
    else if($calculation_name === 'Straight Drop Spring'){
        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th style="width: 20px; border: 1px solid #000000;  font-size:14px;"><b>No</b></th>'
                . '<th colspan ="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fabric/Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Side Spline Colour</b></th>'
                // . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Type</b></th>'
                // . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Control Side</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;"><b>Hood/Brackets Colour</b></th>'
                . '<th colspan="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Rail Colour</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Bottom Bar Seal</b></th>'
                . '<th colspan ="2" style="width: 50px; border: 1px solid #000000;  font-size:14px;"><b>Fitting Type</b></th>'
                // . '<th style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Track Colour</b></th>'
                . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Valance Type</b></th>'
                // . '<th colspan ="2" style="width: 35px; border: 1px solid #000000;  font-size:14px;"><b>Arm Size</b></th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                        . '<th colspan="2" style="width: 60px; border: 1px solid #000000;  font-size:14px;"><b>Finish Size</b></th>'
                        . '<th colspan="2" style="width: 60px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Skin Size</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Side Spline Side</b></th>'
                        . '<th rowspan="2" style="width: 35px;"><b>Tube Type</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 35px;"><b>Tube Size</b></th>'
                        . '<th colspan ="2" rowspan="2" style="width: 40px;"><b>Hood/Brackets Size</b></th>'
                        . '<th colspan="2" rowspan="2" style="width: 40px;"><b>Bottom Bar Size</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b>Weight Size</b></th>'
                        // . '<th rowspan="2" style="width: 40px;"><b></b></th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 30px; border: 1px solid #000000; font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; border: 1px solid #000000; font-size:14px;"><b>Height</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Width</b></th>'
                        . '<th style="width: 30px; background-color: #d8d6d7; border: 1px solid #000000;  font-size:14px;"><b>Height</b></th>'
                        . '</tr>';

    
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';

    }
    else{

        $window_price_sheet_9_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr style="border: 1px solid #000000;  font-size:14px;">'
                . '<th rowspan="2" style="width: 25px;">No</th>'
                . '<th rowspan="2" style="width: 70px;">Location</th>'
                . '<th rowspan="2" style="width: 90px;">Fabric Name & Type</th>'
                . '<th rowspan="2" style="width: 70px;">Fabric/Colour</th>'
                . '<th rowspan="2" style="width: 30px;">Tube Type</th>'
                . '<th rowspan="2" style="width: 35px;">Tube Length</th>'
                . '<th rowspan="2" style="width: 70px;">Control Side</th>'
                . '<th rowspan="2" style="width: 70px;">Control Type</th>'
                . '<th rowspan="2" style="width: 50px;">Bracket Colour</th>'
                . '<th rowspan="2" style="width: 50px; background-color: #f2f2f2;">Bottom Bar Size</th>'
                . '<th rowspan="2" style="width: 50px;">Bottom Bar Type</th>'
                . '<th rowspan="2" style="width: 50px;">Bottom Bar Colour</th>'
                . '<th colspan="2" style="width: 80px; background-color: #f2f2f2;">Finished Blind Size</th>'
                //. '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
                . '<th rowspan="2" style="width: 50px;">Roll Dir</th>'
                . '<th rowspan="2" style="width: 50px;">Fitting</th>'
                . '<th rowspan="2" style="width: 50px;">Chain Colour</th>'
                . '<th rowspan="2" style="width: 50px;">Chain Size</th>'
                . '<th colspan="2" style="width: 80px; background-color: #f2f2f2;">Skin Size</th>'
                //. '<th rowspan="2" style="width: 40px;">Price</th>'
                //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
                //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
                . '</tr>'
                . '<tr>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;">Width</th>'
                . '<th style="width: 40px; border: 1px solid #000000;  font-size:14px;">Drop</th>'
                . '<th style="width: 40px; background-color: #f2f2f2; border: 1px solid #000000;  font-size:14px;">Width</th>'
                . '<th style="width: 40px; background-color: #f2f2f2; border: 1px solid #000000;  font-size:14px;">Drop</th>'
                . '</tr>';

                $window_price_sheet_9_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">';
                $window_price_sheet_9_calculation_quote_tables_2 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.7em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 20px;"><b>Job Reference</b></th>'
                . '<th style="width: 40px;"><b>Window Width</b></th>'
                . '<th style="width: 50px;"><b>Window Drop</b></th>'
                . '<th style="width: 50px;"><b>Cut Width</b></th>'
                . '<th style="width: 50px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Nest Group</b></th>'
                . '<th style="width: 40px;"><b>Fabric Type</b></th>'
                . '<th style="width: 35px;"><b>Decsription </b></th>'
                . '</tr>';
    }

        while ($stmt_3->fetch()) {

            $query_3_1 = "SELECT window_price_sheet_9_calculation_quote_item_fields.name, "
                    . "window_price_sheet_9_calculation_fields.side, "
                    . "window_price_sheet_9_calculation_fields.name "
                    . "FROM window_price_sheet_9_calculation_quote_item_fields "
                    . "JOIN window_price_sheet_9_calculation_fields ON "
                    . "window_price_sheet_9_calculation_fields.code = window_price_sheet_9_calculation_quote_item_fields.window_price_sheet_9_calculation_field_code "
                    . "WHERE "
                    . "window_price_sheet_9_calculation_quote_item_fields.window_price_sheet_9_calculation_quote_item_code = ? AND "
                    . "window_price_sheet_9_calculation_quote_item_fields.window_price_sheet_9_calculation_code = ? AND "
                    . "window_price_sheet_9_calculation_quote_item_fields.cid = ?";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_price_sheet_9_calculation_quote_item_code, $window_price_sheet_9_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_price_sheet_9_calculation_quote_item_field_name, $window_price_sheet_9_calculation_quote_item_field_side, $window_price_sheet_9_calculation_field_name);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;


            $quote_item_per_meter_no = 1;
            $window_price_sheet_9_calculation_quote_item_per_meters = "";

            $query_3_2 = "SELECT name, width FROM window_price_sheet_9_calculation_quote_item_per_meters WHERE window_price_sheet_9_calculation_quote_item_code = ? AND window_price_sheet_9_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_price_sheet_9_calculation_quote_item_code, $window_price_sheet_9_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_price_sheet_9_calculation_quote_item_per_meter_name, $window_price_sheet_9_calculation_quote_item_per_meter_width);
            $stmt_3_2->fetch();
            $stmt_3_2->close();

            while ($stmt_3_1->fetch()) {

                //$window_price_sheet_9_calculation_quote_item_field_name = explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[0];

                // if ($window_price_sheet_9_calculation_quote_item_field_name === 'Locations') {
                //     $window_price_sheet_9_calculation_quote_item_location_1 = $window_price_sheet_9_calculation_quote_item_field_name;
                // }

                if ($window_price_sheet_9_calculation_field_name === 'Tube Type') {
                    $window_price_sheet_9_calculation_quote_item_Tube_Type = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                /*if ($window_price_sheet_9_calculation_quote_item_field_name === 'Base_Rail_Heading_Type') {
                    $window_price_sheet_9_calculation_quote_item_Base_Rail_Heading_Type = $window_price_sheet_9_calculation_quote_item_field_name;
                }*/

                if ($window_price_sheet_9_calculation_field_name === 'Bottom Bar Type') {
                    $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Type = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                // $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_9_calculation_quote_item_width - 24;
				
                if ($window_price_sheet_9_calculation_field_name === 'Bottom Bar Colour') {
                    $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Control Side') {
                    $window_price_sheet_9_calculation_quote_item_Control_Side = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Control Type') {
                    $window_price_sheet_9_calculation_quote_item_Controls = explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[0];

                    /*if ($window_price_sheet_9_calculation_quote_item_per_meter_name === "Rs") {
                        $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[1];
                        $window_price_sheet_9_calculation_quote_item_mounting_rail_length = $window_price_sheet_9_calculation_quote_item_width - explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[2];
                    } else
                    if ($window_price_sheet_9_calculation_quote_item_per_meter_name === "Rl") {
                        $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[3];
                    } else {
                        $window_price_sheet_9_calculation_quote_item_width_skins = "N/A";
                        $window_price_sheet_9_calculation_quote_item_mounting_rail_length = "N/A";
                    }*/
                    
                    // Strat Edit 12-11-2021
					// $window_price_sheet_9_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_9_calculation_quote_item_width - explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[1];
					// $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - explode(' | ', $window_price_sheet_9_calculation_quote_item_field_name)[3];
                    // $window_price_sheet_9_calculation_quote_item_drop_skins = $window_price_sheet_9_calculation_quote_item_drop + 300;
                    // End Edit 12-11-2021
                }

                if ($window_price_sheet_9_calculation_field_name === 'Direction') {
                    $window_price_sheet_9_calculation_quote_item_Direction = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Chain Colour') {
                    $window_price_sheet_9_calculation_quote_item_Chain_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Fitting') {

                    $window_price_sheet_9_calculation_quote_item_Fit = $window_price_sheet_9_calculation_quote_item_field_name;

                    if($window_price_sheet_9_calculation_quote_item_Fit === "REV"){
                        $window_price_sheet_9_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_9_calculation_quote_item_width - 31;
                        $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - 31;
                        $window_price_sheet_9_calculation_quote_item_drop_skins = $window_price_sheet_9_calculation_quote_item_drop + 300;
                        $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_9_calculation_quote_item_width - 31;
                    }
                    else{
                        $window_price_sheet_9_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_9_calculation_quote_item_width - 26;
                        $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - 26;
                        $window_price_sheet_9_calculation_quote_item_drop_skins = $window_price_sheet_9_calculation_quote_item_drop + 300;
                        $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_9_calculation_quote_item_width - 26;
                    }

                    // if($window_price_sheet_9_calculation_quote_item_field_name === "REV"){
                    //     $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - 31;
                    // }
                    // if($window_price_sheet_9_calculation_quote_item_field_name === "FF"){
                    //     $window_price_sheet_9_calculation_quote_item_width_skins = $window_price_sheet_9_calculation_quote_item_width - 26;
                    // }
                }
                if ($window_price_sheet_9_calculation_field_name === 'Track Colour' || $window_price_sheet_9_calculation_field_name === 'Side Track Colour') {
                    $window_price_sheet_9_calculation_quote_item_track_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Fitting Type') {
                    $window_price_sheet_9_calculation_quote_item_fitting_type = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Fixing') {
                    $window_price_sheet_9_calculation_quote_item_Fixing = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Bracket Colour') {
                    $window_price_sheet_9_calculation_quote_item_Bracket_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }


                if ($window_price_sheet_9_calculation_field_name === 'Bracket Colour') {
                    $window_price_sheet_9_calculation_quote_item_Bracket_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Chain Length') {
                    $window_price_sheet_9_calculation_quote_item_Chain_Size = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Hood / Brackets' || $window_price_sheet_9_calculation_field_name === 'Hood Type') {
                    $window_price_sheet_9_calculation_quote_item_Hood_Type = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Hood / Bracket Colour' || $window_price_sheet_9_calculation_field_name === 'Hood colour') {
                    $window_price_sheet_9_calculation_quote_item_Hood_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Fittment') {
                    $window_price_sheet_9_calculation_quote_item_Fittment = $window_price_sheet_9_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_9_calculation_field_name === 'Side Spline Colour') {
                    $window_price_sheet_9_calculation_quote_item_Side_Spline_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Bottom Rail Colour') {
                    $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour = $window_price_sheet_9_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Bottom Rail Type') {
                    $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Type = $window_price_sheet_9_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Bottom Locking') {
                    $window_price_sheet_9_calculation_quote_item_Bottom_Locking = $window_price_sheet_9_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Bottom Bar Seal') {
                    $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Seal = $window_price_sheet_9_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Fit Type Width') {
                    $window_price_sheet_9_calculation_quote_item_fit_type_width = $window_price_sheet_9_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Fit Type Height') {
                    $window_price_sheet_9_calculation_quote_item_fit_type_height = $window_price_sheet_9_calculation_quote_item_field_name;
                    
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Box Size') {
                    $window_price_sheet_9_calculation_quote_item_box_size = $window_price_sheet_9_calculation_quote_item_field_name;
                    
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Valance Type') {
                    $window_price_sheet_9_calculation_quote_item_valance_type = $window_price_sheet_9_calculation_quote_item_field_name;
                    
                }
				
                if ($window_price_sheet_9_calculation_field_name === 'Arm Size') {
                    $window_price_sheet_9_calculation_quote_item_arm_size = $window_price_sheet_9_calculation_quote_item_field_name;
                    
                }

                /* if ($window_price_sheet_9_calculation_quote_item_field_name === 'No_Of_Rail_Brackets') {
                  $window_price_sheet_9_calculation_quote_item_No_Of_Rail_Brackets = $window_price_sheet_9_calculation_quote_item_field_name;
                  }

                  if ($window_price_sheet_9_calculation_quote_item_field_name === 'Bracket_Covers') {
                  $window_price_sheet_9_calculation_quote_item_Bracket_Covers = $window_price_sheet_9_calculation_quote_item_field_name;
                  } */
            }
            $stmt_3_1->close();

            //$csv_price_sheet = __DIR__ . './../cPanel/price-sheets/' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[1] . '-' . $window_price_sheet_9_calculation_code . '.csv';


            if($calculation_name === 'Ziptrak' || $calculation_name === 'Ziptrak®' || $calculation_name === 'Ziptrak® Interior1'){
			
                $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop - 90;
        
                $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 99;
                $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width - 7;
                $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 199;
                $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 100;
                $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
                $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 90;
                $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 150;

            $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                    // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_fitting_type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_track_Colour . '</td>'
                    . '</tr>';

                    $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Colour . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                            //. '<td>' . $window_price_sheet_9_calculation_quote_item_mounting_rail_length . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Type . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
        
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                            
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Fittment . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_fitting_type . '</td>'
                            // . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_track_Colour . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size . '</td>'
                            //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_9_calculation_quote_item_width, $window_price_sheet_9_calculation_quote_item_drop) . '</td>'
                            //. '<td style="background-color: #ff0;">' . $window_price_sheet_9_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                            //. '<td>' . $window_price_sheet_9_calculation_quote_item_Bracket_Covers . '</td>'
                            . '</tr>';
           
   
                    $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                    . '</tr>';

            $quote_item_no++;
            } else if($calculation_name === 'Zipscreen' || $calculation_name === 'Zipscreen®' || $calculation_name === 'Zipscreen Blinds'){
			
                $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 250;
        
                $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 121;
                $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width - 66;
                $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 450;
                $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 121;
                $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
                $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 82;
                $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 310;

            $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_fitting_type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_track_Colour . '</td>'
                    . '</tr>';

                    $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                            . '</tr>';
                            // $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                            //         . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                            //         . '</tr>';
           
   
                    $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                    . '</tr>';

            $quote_item_no++;
            } else if($calculation_name === 'Slidetrack Blinds'){
			
                $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 120;
        
                $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 81;
                $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width - 7;
                $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 181;
                $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 121;
                $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
                $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 73;
                $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 310;

            $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_fitting_type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_track_Colour . '</td>'
                    . '</tr>';

                    $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                            . '</tr>';
           
   
                    $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                    . '</tr>';

            $quote_item_no++;
            }


            else if($calculation_name === 'Ezip' || $calculation_name === 'eZip Blinds'){
			
                $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 250;
        
                $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 150;
                $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width - 5;
                $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 250;
                $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 58;
                $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
                $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 120;
                $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 310;
			

            $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_fitting_type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_track_Colour . '</td>'
                    . '</tr>';

                    $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . ($window_price_sheet_9_calculation_quote_item_width-58) . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Side_Spline_Size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                            . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                            . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                            . '</tr>';
           
   
                    $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                    . '</tr>';

            $quote_item_no++;
            }
            else if($calculation_name === 'Canvas/Mesh Awnings'){
			
                $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 250;
        
                $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 53;
                $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width;
                $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 450;
                $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 57;
                $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
                $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 55;
                $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 400;

                $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_valance_type . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_arm_size . '</td>'
                        . '</tr>';
    
                        $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                                . '</tr>';
               
       
                        $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                        . '</tr>';
 
             $quote_item_no++;
             } 
            
             else if($calculation_name === 'Fixed Guide Awnings'){
              
              
              $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 250;
          
              $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 108;
              $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width;
              $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
              $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 208;
              $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 71;
              $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
              $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 106;
              $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 400;
  
              $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                      . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_valance_type . '</td>'
                      . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_arm_size . '</td>'
                      . '</tr>';
  
                      $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                              . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                              . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                              . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                              . '</tr>';
             
     
                      $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                      . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                      . '</tr>';
  
           $quote_item_no++;
          }  
            
          else if($calculation_name === 'Straight Drop Crank'){
           
           
           $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 250;
       
           $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 73;
           $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width;
           $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
           $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 173;
           $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 70;
           $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
           $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 75;
           $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 400;

           $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                   . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                //    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                   . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_valance_type . '</td>'
                   . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_arm_size . '</td>'
                   . '</tr>';

                   $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                           . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                           . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                           . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                           . '</tr>';
          
  
                   $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                   . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                   . '</tr>';

        $quote_item_no++;
       }  
            
       else if($calculation_name === 'Straight Drop Spring'){
        
        
        $window_price_sheet_9_calculation_quote_item_Side_Spline_Size = $window_price_sheet_9_calculation_quote_item_drop + 250;
    
        $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_9_calculation_quote_item_width - 68;
        $window_price_sheet_9_calculation_quote_item_Hood_size = $window_price_sheet_9_calculation_quote_item_width;
        $window_price_sheet_9_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size/2) - 80);
        $window_price_sheet_9_calculation_quote_item_Weight_Size = $window_price_sheet_9_calculation_quote_item_width - 173;
        $window_price_sheet_9_calculation_quote_item_Tube_size = $window_price_sheet_9_calculation_quote_item_width - 57;
        $window_price_sheet_9_calculation_quote_item_Key_Way_Side = $window_price_sheet_9_calculation_quote_item_width - 100;
        $window_price_sheet_9_calculation_quote_item_Skins_width_size = $window_price_sheet_9_calculation_quote_item_width - 70;
        $window_price_sheet_9_calculation_quote_item_Skins_drop_size = $window_price_sheet_9_calculation_quote_item_drop + 400;
   
        $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
             //    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
             //    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Hood_Type . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_Colour . '</td>'
                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_fitting_type . '</td>'
                . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_valance_type . '</td>'
             //    . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_arm_size . '</td>'
                . '</tr>';
   
                $window_price_sheet_9_calculation_quote_tables_1 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_width_size . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_Skins_drop_size . '</td>'
                        . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_Tube_size . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Hood_size . '</td>'
                        . '<td colspan="2" style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Rail_Size . '</td>'
                     //    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Weight_Size . '</td>'
                        . '</tr>';
       
   
                $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px; background-color: #d8d6d7;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                . '</tr>';
   
     $quote_item_no++;
    } 
            else{
            $window_price_sheet_9_calculation_quote_tables .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_colour)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Tube_Type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width_skins_Tube_Length . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Control_Side . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Controls . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bracket_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2; text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Size . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Type . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Bottom_Bar_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2; text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                    . '<td style="background-color: #f2f2f2; text-align: center; border: 1px solid #000000;  font-size:14px; ">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                    //. '<td>' . $window_price_sheet_9_calculation_quote_item_mounting_rail_length . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Direction . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Fit . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Chain_Colour . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_Chain_Size . '</td>'
                    . '<td style="background-color: #f2f2f2; text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                    . '<td style="background-color: #f2f2f2; text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_9_calculation_quote_item_width, $window_price_sheet_9_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_9_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_9_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>';
                    $window_price_sheet_9_calculation_quote_tables_1 .= '';
                    $window_price_sheet_9_calculation_quote_tables_2 .= '<tr nobr="true" style="border: 1px solid #000000;  font-size:14px;" >'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $quote_item_no . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_width_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . $window_price_sheet_9_calculation_quote_item_drop_skins . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;"></td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_9_calculation_quote_item_type)[0] . '</td>'
                    . '<td style="text-align: center; border: 1px solid #000000;  font-size:14px;">' . explode('<->', $window_price_sheet_9_calculation_quote_item_location)[0] . '</td>'
                    . '</tr>';

            $quote_item_no++;
            }
        }

        $window_price_sheet_9_calculation_quote_tables .= '</table>';
        $window_price_sheet_9_calculation_quote_tables_1 .= '</table>';
        $window_price_sheet_9_calculation_quote_tables_2 .= '</table>';
    } else {
        $window_price_sheet_9_calculation_quote_tables .= "";
        $window_price_sheet_9_calculation_quote_tables_1 .= "";
        $window_price_sheet_9_calculation_quote_tables_2 .= "";
    }
	
                $window_price_sheet_9_calculation_total_table_colspan = $table_td_num_rows + $field_td_num_rows + $price_td_num_rows;
				
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
/*
$html = '<table width="990" border="0" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; vertical-align: top;width:990px;">
          <tr>
           <td style="font-size: 1.8em; text-align: center; width:450px;"   colspan="6">
		   <img src="https://clients.blinq.com.au/doors-blinds-and-shutters-5b597cf13a075/profile/logo.jpg" style="height:90; width:auto;" height="90">
		   </td>
		   <td  style="font-size: 1.8em; text-align: center; width:200px;"  colspan="8"><strong>' . $calculation_name . '</strong><br>Order Sheet</td>
		   <td  style="font-size: 0.8em; line-height: 15px; text-align: right; width:250px;"  colspan="6">
                <strong>Date :</strong> ' . $today_date . '<br>
                <strong>Customer :</strong>' . $q_name_1 . ' ' . $q_name_2 . '<br>
                <strong>Ref : </strong> ' . $c_ref . '<br>
                <strong>Quote # :</strong> ' . $cid . ' / <strong>Job # :</strong> ' . $job_no . '<br>
                </td>
                </tr>
				</table>
				<table>'
		//	. $window_price_sheet_9_calculation_quote_items
			. $window_price_sheet_9_calculation_quote_tables
			. '</table>';
			
//	echo $html;
	
	print($html);
*/