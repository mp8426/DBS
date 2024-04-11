<?php

//include './check-login.php';
include '../../cPanel/connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$cid = filter_input(INPUT_POST, 'cid');
$window_curtain_calculation_quote_item_codes = filter_input(INPUT_POST, 'quote-item-codes', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
//$window_curtain_calculation_code = filter_input( INPUT_POST, 'window-curtain-calculation-code' );

$code = "";
$codes = array();
foreach ($window_curtain_calculation_quote_item_codes as $quote_item_code) {

    $code = uniqid();

    $query_1 = 'INSERT INTO window_curtain_calculation_quote_items ( code, location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, continuous_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty, price, notes, window_curtain_calculation_code, cid  ) SELECT ?, location, width, right_return, left_return, overlap, fullness, supplier, fabric, fabric_colour, qty_drop, curtain_type_1, continuous_meter, curtain_type_2, drop_x, hem_heading, pattern_repeate, fabric_cut_length, fabric_qty, price, notes, window_curtain_calculation_code, cid FROM window_curtain_calculation_quote_items WHERE code = ? AND cid = ?';

    $stmt_1 = $mysqli->prepare($query_1);
    $stmt_1->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_1->execute() or trigger_error($stmt_1->error, E_USER_ERROR);
    $stmt_1->close();

    $query_2 = "INSERT INTO window_curtain_calculation_quote_item_fields ( code, name, price, window_curtain_calculation_field_code, window_curtain_calculation_quote_item_code, window_curtain_calculation_code, cid ) SELECT code, name, price, window_curtain_calculation_field_code, ?, window_curtain_calculation_code, cid FROM window_curtain_calculation_quote_item_fields WHERE window_curtain_calculation_quote_item_code = ? AND cid = ?";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_2->execute() or trigger_error($stmt_2->error, E_USER_ERROR);
    $stmt_2->close();

    $query_3 = "INSERT INTO window_curtain_calculation_quote_item_accessories ( code, name, price, qty, window_curtain_calculation_quote_item_code, window_curtain_calculation_code, cid ) SELECT code, name, price, qty, ?, window_curtain_calculation_code, cid FROM window_curtain_calculation_quote_item_accessories WHERE window_curtain_calculation_quote_item_code = ? AND cid = ?";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_3->execute() or trigger_error($stmt_3->error, E_USER_ERROR);
    $stmt_3->close();

    $query_4 = "INSERT INTO window_curtain_calculation_quote_item_per_meters ( code, name, price, width, window_curtain_calculation_quote_item_code, window_curtain_calculation_code, cid ) SELECT code, name, price, width, ?, window_curtain_calculation_code, cid FROM window_curtain_calculation_quote_item_per_meters WHERE window_curtain_calculation_quote_item_code = ? AND cid = ?";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_4->execute() or trigger_error($stmt_4->error, E_USER_ERROR);
    $stmt_4->close();

    $query_5 = "INSERT INTO window_curtain_calculation_quote_item_fitting_charges ( code, name, price, window_curtain_calculation_quote_item_code, window_curtain_calculation_code, cid ) SELECT code, name, price, ?, window_curtain_calculation_code, cid FROM window_curtain_calculation_quote_item_fitting_charges WHERE window_curtain_calculation_quote_item_code = ? AND cid = ?";

    $stmt_5 = $mysqli->prepare($query_5);
    $stmt_5->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_5->execute() or trigger_error($stmt_5->error, E_USER_ERROR);
    $stmt_5->close();

    $codes[$quote_item_code] = $code;
}

print json_encode(array(1, $codes, $cid)); // Success

$mysqli->close();
