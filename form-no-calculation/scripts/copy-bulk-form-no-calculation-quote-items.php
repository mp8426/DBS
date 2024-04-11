<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');
$form_no_calculation_quote_item_codes = filter_input(INPUT_POST, 'quote-item-codes', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
//$form_no_calculation_code = filter_input( INPUT_POST, 'form-no-calculation-code' );

$code = "";
$codes = array();
foreach ($form_no_calculation_quote_item_codes as $quote_item_code) {

    $code = uniqid();

    $query_1 = 'INSERT INTO form_no_calculation_quote_items ( code, location, cost, markup, price, notes, form_no_calculation_code, cid  ) SELECT ?, location, cost, markup, price, notes, form_no_calculation_code, cid FROM form_no_calculation_quote_items WHERE code = ? AND cid = ?';

    $stmt_1 = $mysqli->prepare($query_1);
    $stmt_1->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_1->execute();
    $stmt_1->close();

    $query_2 = "INSERT INTO form_no_calculation_quote_item_fields ( code, name, price, form_no_calculation_field_code, form_no_calculation_quote_item_code, form_no_calculation_code, cid ) SELECT code, name, price, form_no_calculation_field_code, ?, form_no_calculation_code, cid FROM form_no_calculation_quote_item_fields WHERE form_no_calculation_quote_item_code = ? AND cid = ?";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_2->execute();
    $stmt_2->close();

    $query_3 = "INSERT INTO form_no_calculation_quote_item_accessories ( code, name, price, qty, form_no_calculation_quote_item_code, form_no_calculation_code, cid ) SELECT code, name, price, qty, ?, form_no_calculation_code, cid FROM form_no_calculation_quote_item_accessories WHERE form_no_calculation_quote_item_code = ? AND cid = ?";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_3->execute();
    $stmt_3->close();

    $query_4 = "INSERT INTO form_no_calculation_quote_item_per_meters ( code, name, price, width, form_no_calculation_quote_item_code, form_no_calculation_code, cid ) SELECT code, name, price, width, ?, form_no_calculation_code, cid FROM form_no_calculation_quote_item_per_meters WHERE form_no_calculation_quote_item_code = ? AND cid = ?";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_4->execute();
    $stmt_4->close();

    $query_5 = "INSERT INTO form_no_calculation_quote_item_fitting_charges ( code, name, price, form_no_calculation_quote_item_code, form_no_calculation_code, cid ) SELECT code, name, price, ?, form_no_calculation_code, cid FROM form_no_calculation_quote_item_fitting_charges WHERE form_no_calculation_quote_item_code = ? AND cid = ?";

    $stmt_5 = $mysqli->prepare($query_5);
    $stmt_5->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_5->execute();
    $stmt_5->close();

    $codes[$quote_item_code] = $code;
}

print json_encode(array(1, $codes)); // Success

$mysqli->close();
