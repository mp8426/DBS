<?php

include __DIR__ . '/../../etape/check-data.php';

$result = check_data();

if ($result[0] === 200) {

    include __DIR__ . '/../../cPanel/connect.php';


    $md = $result[1]['md'];
    $mc_db = $result[1]['md'];
    $sc_code = $result[1]['sc'];
    $cid = $result[1]['qn'];
    $m_data = json_decode($result[1]['m_data'], true);

    $query_2 = "SELECT code FROM " . $mc_db . "_fields WHERE " . $mc_db . "_code = ? ORDER BY position ASC";

    $stmt_2 = $mysqli->prepare($query_2);
    $stmt_2->bind_param('s', $sc_code);
    $stmt_2->execute();
    $stmt_2->bind_result($field_code);
    $field_codes = [];
    while ($stmt_2->fetch()) {
        $field_codes[] = $field_code;
    }
    $stmt_2->close();

    foreach ($m_data as $value) {

        $qi_code = uniqid();
        $location = "-";
        $width = $value['w'];
        $drop = $value['d'];
        $type = '';
        $material = "-";
        $colour = "-";
        $price = "0.00";

        $query_1 = "INSERT INTO " . $mc_db . "_quote_items ( code, location, width_x, drop_x, type, material, colour, price, " . $mc_db . "_code, cid ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

        $stmt_1 = $mysqli->prepare($query_1);
        $stmt_1->bind_param('ssssssssss', $qi_code, $location, $width, $drop, $type, $material, $colour, $price, $sc_code, $cid);
        $stmt_1->execute();
        $stmt_1->close();

        $qi_field_code = '';
        $qi_field_name = 'NA';
        $qi_field_price = 0;

        $query_2_1 = "INSERT INTO " . $mc_db . "_quote_item_fields ( code, name, price, " . $mc_db . "_field_code, " . $mc_db . "_quote_item_code, " . $mc_db . "_code, cid ) VALUES ( ?, ?, ?, ?, ?, ?, ? )";
        $stmt_2_1 = $mysqli->prepare($query_2_1);

        foreach ($field_codes as $field_code_x) {

            $stmt_2_1->bind_param('ssdssss', $qi_field_code, $qi_field_name, $qi_field_price, $field_code_x, $qi_code, $sc_code, $cid);
            $stmt_2_1->execute();
        }
        $stmt_2_1->close();
        $stmt_2->close();
    }

    $mysqli->close();

    print json_encode(200);
    http_response_code(200);
} else {
    print json_encode($result[1]);
    http_response_code($result[0]);
}