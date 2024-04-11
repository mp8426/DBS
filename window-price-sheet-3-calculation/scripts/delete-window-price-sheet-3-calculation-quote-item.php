<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$window_price_sheet_3_calculation_quote_item_code = filter_input(INPUT_POST, 'code');
//$window_price_sheet_3_calculation_code = filter_input( INPUT_POST, 'window-price-sheet-3-calculation-code' );
$cid = filter_input( INPUT_POST, 'cid' );

$query_1 = 'DELETE FROM window_price_sheet_3_calculation_quote_items WHERE code = ? AND cid = ?';

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('ss', $window_price_sheet_3_calculation_quote_item_code, $cid);
$stmt_1->execute();
$stmt_1->close();

$query_2 = 'DELETE FROM window_price_sheet_3_calculation_quote_item_fields WHERE window_price_sheet_3_calculation_quote_item_code = ? AND cid = ?';

$stmt_2 = $mysqli->prepare($query_2);
$stmt_2->bind_param('ss', $window_price_sheet_3_calculation_quote_item_code, $cid);
$stmt_2->execute();
$stmt_2->close();

$query_3 = 'DELETE FROM window_price_sheet_3_calculation_quote_item_accessories WHERE window_price_sheet_3_calculation_quote_item_code = ? AND cid = ?';

$stmt_3 = $mysqli->prepare($query_3);
$stmt_3->bind_param('ss', $window_price_sheet_3_calculation_quote_item_code, $cid);
$stmt_3->execute();
$stmt_3->close();

$query_4 = 'DELETE FROM window_price_sheet_3_calculation_quote_item_per_meters WHERE window_price_sheet_3_calculation_quote_item_code = ? AND cid = ?';

$stmt_4 = $mysqli->prepare($query_4);
$stmt_4->bind_param('ss', $window_price_sheet_3_calculation_quote_item_code, $cid);
$stmt_4->execute();
$stmt_4->close();


$query_5 = 'DELETE FROM window_price_sheet_3_calculation_quote_item_fitting_charges WHERE window_price_sheet_3_calculation_quote_item_code = ? AND cid = ?';

$stmt_5 = $mysqli->prepare($query_5);
$stmt_5->bind_param('ss', $window_price_sheet_3_calculation_quote_item_code, $cid);
$stmt_5->execute();
$stmt_5->close();

print json_encode(array(1)); // Success

$mysqli->close();
