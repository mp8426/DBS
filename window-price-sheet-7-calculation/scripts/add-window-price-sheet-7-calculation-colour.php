<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//include './check-login.php';
include '../../cPanel/connect.php';

$code = uniqid();
$name = trim(preg_replace("/[[:blank:]]+/", " ", filter_input(INPUT_POST, 'colour_name')));
$material_code = filter_input(INPUT_POST, 'material_code');

if ($name !== "") {

    $query_1 = "INSERT INTO colours ( code, name, material_code ) VALUES( ?, ?, ? )";

    $stmt_1 = $mysqli->prepare($query_1);
    $stmt_1->bind_param('sss', $code, $name, $material_code);

    if ($stmt_1->execute()) {
        print json_encode(array(1, $code, $name)); // Success
    } else {
        print json_encode(array(2, "Oops! Something went wrong. Please try again later.")); // Error
    }
    $stmt_1->close();
} else {
    print json_encode(array(2, "Error! Something went wrong. Please try again later.")); // Error
}

$mysqli->close();


