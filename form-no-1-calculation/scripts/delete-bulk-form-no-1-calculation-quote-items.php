<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$cid = filter_input(INPUT_POST, 'cid');
$form_no_1_calculation_quote_item_codes = filter_input(INPUT_POST, 'quote-item-codes', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$form_no_1_calculation_quote_item_codes_x = implode(",", array_map(function( $x ) {
            return "'$x'";
        }, $form_no_1_calculation_quote_item_codes));

$query_1 = 'DELETE FROM form_no_1_calculation_quote_items WHERE code IN( ' . $form_no_1_calculation_quote_item_codes_x . ' )  AND cid = ?';

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $cid);
$stmt_1->execute();
$stmt_1->close();


$query_2 = 'DELETE FROM form_no_1_calculation_quote_item_fields WHERE form_no_1_calculation_quote_item_code IN( ' . $form_no_1_calculation_quote_item_codes_x . ' )  AND cid = ?';

$stmt_2 = $mysqli->prepare($query_2);
$stmt_2->bind_param('s', $cid);
$stmt_2->execute();
$stmt_2->close();


$query_3 = 'DELETE FROM form_no_1_calculation_quote_item_accessories WHERE form_no_1_calculation_quote_item_code IN( ' . $form_no_1_calculation_quote_item_codes_x . ' )  AND cid = ?';

$stmt_3 = $mysqli->prepare($query_3);
$stmt_3->bind_param('s', $cid);
$stmt_3->execute();
$stmt_3->close();


$query_4 = 'DELETE FROM form_no_1_calculation_quote_item_per_meters WHERE form_no_1_calculation_quote_item_code IN( ' . $form_no_1_calculation_quote_item_codes_x . ' )  AND cid = ?';

$stmt_4 = $mysqli->prepare($query_4);
$stmt_4->bind_param('s', $cid);
$stmt_4->execute();
$stmt_4->close();


$query_5 = 'DELETE FROM form_no_1_calculation_quote_item_fitting_charges WHERE form_no_1_calculation_quote_item_code IN( ' . $form_no_1_calculation_quote_item_codes_x . ' )  AND cid = ?';

$stmt_5 = $mysqli->prepare($query_5);
$stmt_5->bind_param('s', $cid);
$stmt_5->execute();
$stmt_5->close();

print json_encode(array(1)); // Success

$mysqli->close();
