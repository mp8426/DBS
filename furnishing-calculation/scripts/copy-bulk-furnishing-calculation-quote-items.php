<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');
$furnishing_calculation_quote_item_codes = filter_input(INPUT_POST, 'quote-item-codes', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
//$furnishing_calculation_code = filter_input( INPUT_POST, 'furnishing-calculation-code' );

$code = "";
$codes = array();
foreach ($furnishing_calculation_quote_item_codes as $quote_item_code) {

    $code = uniqid();

    $query_1 = 'INSERT INTO furnishing_calculation_quote_items ( code, location, cost, markup, price, notes, furnishing_calculation_code, cid  ) SELECT ?, location, cost, markup, price, notes, furnishing_calculation_code, cid FROM furnishing_calculation_quote_items WHERE code = ? AND cid = ?';

    $stmt_1 = $mysqli->prepare($query_1);
    $stmt_1->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_1->execute();
    $stmt_1->close();

    $query_2 = "INSERT INTO furnishing_calculation_quote_item_fields ( code, name, price, furnishing_calculation_field_code, furnishing_calculation_quote_item_code, furnishing_calculation_code, cid ) SELECT code, name, price, furnishing_calculation_field_code, ?, furnishing_calculation_code, cid FROM furnishing_calculation_quote_item_fields WHERE furnishing_calculation_quote_item_code = ? AND cid = ?";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_2->execute();
    $stmt_2->close();

    $query_3 = "INSERT INTO furnishing_calculation_quote_item_accessories ( code, name, price, qty, furnishing_calculation_quote_item_code, furnishing_calculation_code, cid ) SELECT code, name, price, qty, ?, furnishing_calculation_code, cid FROM furnishing_calculation_quote_item_accessories WHERE furnishing_calculation_quote_item_code = ? AND cid = ?";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_3->execute();
    $stmt_3->close();

    $query_4 = "INSERT INTO furnishing_calculation_quote_item_per_meters ( code, name, price, width, furnishing_calculation_quote_item_code, furnishing_calculation_code, cid ) SELECT code, name, price, width, ?, furnishing_calculation_code, cid FROM furnishing_calculation_quote_item_per_meters WHERE furnishing_calculation_quote_item_code = ? AND cid = ?";

    $stmt_4 = $mysqli->prepare($query_4);
    $stmt_4->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_4->execute();
    $stmt_4->close();

    $query_5 = "INSERT INTO furnishing_calculation_quote_item_fitting_charges ( code, name, price, furnishing_calculation_quote_item_code, furnishing_calculation_code, cid ) SELECT code, name, price, ?, furnishing_calculation_code, cid FROM furnishing_calculation_quote_item_fitting_charges WHERE furnishing_calculation_quote_item_code = ? AND cid = ?";

    $stmt_5 = $mysqli->prepare($query_5);
    $stmt_5->bind_param('sss', $code, $quote_item_code, $cid);
    $stmt_5->execute();
    $stmt_5->close();

    $codes[$quote_item_code] = $code;
}

print json_encode(array(1, $codes)); // Success

$mysqli->close();
