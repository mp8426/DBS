<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include './../cPanel/connect.php';

$window_price_sheet_calculation_quote_tables_SKINS = "";
$window_price_sheet_calculation_quote_tables_TUBES = "";
$window_price_sheet_calculation_quote_tables_FB = "";

$query_1 = "SELECT code, name, locations, materials_and_colours, accessories, per_meters, fitting_charges FROM window_price_sheet_calculations WHERE code = ? ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($window_price_sheet_calculation_code, $window_price_sheet_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $quote_item_no = 1;
    $window_price_sheet_calculation_quote_items = "";

    $query_3 = "SELECT code, location, width_x, drop_x, type, material, colour FROM window_price_sheet_calculation_quote_items WHERE window_price_sheet_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_price_sheet_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_price_sheet_calculation_quote_item_code, $window_price_sheet_calculation_quote_item_location, $window_price_sheet_calculation_quote_item_width, $window_price_sheet_calculation_quote_item_drop, $window_price_sheet_calculation_quote_item_type, $window_price_sheet_calculation_quote_item_material, $window_price_sheet_calculation_quote_item_colour);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        while ($stmt_3->fetch()) {

            $query_3_1 = "SELECT window_price_sheet_calculation_quote_item_fields.name, "
                    . "window_price_sheet_calculation_fields.side, "
                    . "window_price_sheet_calculation_fields.name "
                    . "FROM window_price_sheet_calculation_quote_item_fields "
                    . "JOIN window_price_sheet_calculation_fields ON "
                    . "window_price_sheet_calculation_fields.code = window_price_sheet_calculation_quote_item_fields.window_price_sheet_calculation_field_code "
                    . "WHERE "
                    . "window_price_sheet_calculation_quote_item_fields.window_price_sheet_calculation_quote_item_code = ? AND "
                    . "window_price_sheet_calculation_quote_item_fields.window_price_sheet_calculation_code = ? AND "
                    . "window_price_sheet_calculation_quote_item_fields.cid = ?";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_price_sheet_calculation_quote_item_code, $window_price_sheet_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_price_sheet_calculation_quote_item_field_name, $window_price_sheet_calculation_quote_item_field_side, $window_price_sheet_calculation_field_name);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;

            $query_3_2 = "SELECT name FROM window_price_sheet_calculation_quote_item_per_meters WHERE window_price_sheet_calculation_quote_item_code = ? AND window_price_sheet_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_price_sheet_calculation_quote_item_code, $window_price_sheet_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_price_sheet_calculation_quote_item_per_meter_name);
            $stmt_3_2->fetch();
            $stmt_3_2->close();

            while ($stmt_3_1->fetch()) {

                // if ($window_price_sheet_calculation_field_name === 'Location_1') {
                //     $window_price_sheet_calculation_quote_item_location_1 = $window_price_sheet_calculation_quote_item_field_name;
                // }

                if ($window_price_sheet_calculation_field_name === 'Hood Type') {
                    $window_price_sheet_calculation_quote_item_hood_Type = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Hood colour') {
                    $window_price_sheet_calculation_quote_item_hood_colour = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Bottom Rail Colour') {
                    $window_price_sheet_calculation_quote_item_bottom_rail_colour = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Bottom Bar Seal') {
                    $window_price_sheet_calculation_quote_item_bottom_bar_seal = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Fitting Type') {
                    $window_price_sheet_calculation_quote_item_fitting_type = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Side Track Colour') {
                    $window_price_sheet_calculation_quote_item_side_track_colour = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Control Option') {
                    $window_price_sheet_calculation_quote_item_Control_Option = $window_price_sheet_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_calculation_field_name === 'Control Side') {
                    $window_price_sheet_calculation_quote_item_Contorl_side = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];
                }

                if ($window_price_sheet_calculation_field_name === 'Control Type') {
                    $window_price_sheet_calculation_quote_item_Control_Type = explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[0];

                    /*if ($window_price_sheet_calculation_quote_item_per_meter_name === "Rs") {
                        $window_price_sheet_calculation_quote_item_width_skins = $window_price_sheet_calculation_quote_item_width - explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[1];
                        $window_price_sheet_calculation_quote_item_mounting_rail_length = $window_price_sheet_calculation_quote_item_width - explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[2];
                    }

                    if ($window_price_sheet_calculation_quote_item_per_meter_name === "Rl") {
                        $window_price_sheet_calculation_quote_item_width_skins = $window_price_sheet_calculation_quote_item_width - explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[3];
                    }*/
					$window_price_sheet_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_calculation_quote_item_width - explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[1];
					$window_price_sheet_calculation_quote_item_width_skins = $window_price_sheet_calculation_quote_item_width - explode(' | ', $window_price_sheet_calculation_quote_item_field_name)[3];
                    $window_price_sheet_calculation_quote_item_drop_skins = $window_price_sheet_calculation_quote_item_drop + 300;
                }

                if ($window_price_sheet_calculation_field_name === 'Direction') {
                    $window_price_sheet_calculation_quote_item_Direction = $window_price_sheet_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_calculation_field_name === 'Fitting') {

                    $window_price_sheet_calculation_quote_item_Fit = $window_price_sheet_calculation_quote_item_field_name;

                    if($window_price_sheet_calculation_quote_item_field_name === "Reveal"){
                        $window_price_sheet_calculation_quote_item_width = $window_price_sheet_calculation_quote_item_width - 5;
                    }
                }
            }
            $stmt_3_1->close();

            // $window_price_sheet_calculation_quote_tables_SKINS .= '<table cellspacing="0" cellpadding="2" style="border:1px solid #000;" nobr="true">'
            //         . '<tr>'
            //         . '<td colspan="2" style="width: 242px; font-weight: bold; text-align: left; border:1px solid #000; line-height: 20px; font-size: 10px;">'
            //         . ' CUSTOMER: ' . strtoupper($q_name_2)
            //         . '</td>'
            //         . '<td style="width: 105px; text-align: center; border:1px solid #000; line-height: 20px; font-size: 8px;">'
            //         . 'START SKINS - ' . $job_no
            //         . '</td>'
            //         . '</tr>'
            //         . '<tr>'
            //         . '<td colspan="2" style="width: 242px; text-align: center; border:1px solid #000; line-height: 25px; font-size: 16px;">'
            //         . $window_price_sheet_calculation_quote_item_width_skins . ' x ' . $window_price_sheet_calculation_quote_item_drop_skins
            //         . '</td>'
            //         . '<td style="width: 105px; font-weight: bold; text-align: center; border:1px solid #000; line-height: 25px; font-size: 14px;">'
            //         . 'SKIN - ' . $quote_item_no
            //         . '</td>'
            //         . '</tr>'
            //         . '<tr>'
            //         . '<td style="text-align: center; border:1px solid #000; line-height: 10px; font-size: 8px;">'
            //         . strtoupper(explode('<->', $window_price_sheet_calculation_quote_item_material)[0])
            //         . '</td>'
            //         . '<td style="text-align: center; border:1px solid #000; line-height: 10px; font-size: 8px;">'
            //         . strtoupper(explode('<->', $window_price_sheet_calculation_quote_item_colour)[0])
            //         . '</td>'
            //         . '<td rowspan="2" style="text-align: center; border:1px solid #000; line-height: 20px;  font-size: 8px;">'
            //         . strtoupper($window_price_sheet_calculation_quote_item_Direction)
            //         . '</td>'
            //         . '</tr>'
            //         . '<tr>'
            //         . '<td style="text-align: center; border:1px solid #000; line-height: 10px; font-size: 8px;">'
            //         . 'LOCATION'
            //         . '</td>'
            //         . '<td style="text-align: center; border:1px solid #000; line-height: 10px; font-size: 8px;">'
            //         . strtoupper(explode('<->', $window_price_sheet_calculation_quote_item_location)[0])
            //         . '</td>'
            //         . '</tr>'
            //         . '</table>';

            // $window_price_sheet_calculation_quote_tables_TUBES .= '<table cellspacing="0" cellpadding="2" style="border:1px solid #000;" nobr="true">'
            //         . '<tr>'
            //         . '<td colspan="2" style="width: 242px; font-weight: bold; text-align: left; border:1px solid #000; line-height: 25px; font-size: 10px;">'
            //         . ' CUSTOMER: ' . strtoupper($q_name_2)
            //         . '</td>'
            //         . '<td style="width: 105px; text-align: center; border:1px solid #000; line-height: 25px; font-size: 8px;">'
            //         . 'START TUBES - ' . $job_no
            //         . '</td>'
            //         . '</tr>'
            //         . '<tr>'
            //         . '<td colspan="2" rowspan="2" style="width: 242px; text-align: center; border:1px solid #000; line-height: 44px; font-size: 22px;">'
            //         . $window_price_sheet_calculation_quote_item_width_skins_Tube_Length . 'mm'
            //         . '</td>'
            //         . '<td style="width: 105px; font-weight: bold; text-align: center; border:1px solid #000; line-height: 22px; font-size: 14px;">'
            //         . 'TUBE - ' . $quote_item_no
            //         . '</td>'
            //         . '</tr>'
            //         . '<tr>'
            //         . '<td style="width: 105px; font-weight: bold; text-align: center; border:1px solid #000; line-height: 22px; font-size: 14px;">'
            //         . strtoupper($window_price_sheet_calculation_quote_item_Tube_Type)
            //         . '</td>'
            //         . '</tr>'
            //         . '</table>';

        $window_price_sheet_calculation_quote_tables_FB .= '<table cellspacing="0" cellpadding="2" style="border:2px solid #000; width: 98%; text-align: center;"  nobr="true">'
                    . '<tr>'
                    . '<td colspan="3" style="width: 100%; font-weight: bold; text-align: center; border-top:1px solid #000; line-height: 25px; font-size: 24px;">'
                    . strtoupper($window_price_sheet_calculation_name)
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="3" style="text-align: center; line-height: 20px; font-size: 15px;">'
                    . strtoupper($q_name_2). '/ ' .   $c_contact_name
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="3" style="font-weight: bold; text-align: center; border-bottom:1px solid #000; line-height: 25px; font-size: 24px;">'
                    .strtoupper(explode('|', $c_ref)[1])
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="3" style="font-weight: bold; text-align: center; border-top:1px solid #000; line-height: 25px; font-size: 15px;">BLIND NUMBER</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="3" style="font-weight: bold; text-align: center; border-bottom:1px solid #000; line-height: 30px; font-size: 40px;">'
                    . $quote_item_no
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td style="width: 50%; font-weight: bold; text-align: center; border:1px solid #000; line-height: 25px; font-size: 16px;">Width</td>'
                    . '<td style="width: 10%; font-weight: bold; text-align: center; border:1px solid #000; line-height: 25px; font-size: 8px;">x</td>'
                    . '<td style="width: 40%; font-weight: bold; text-align: center; border:1px solid #000; line-height: 25px; font-size: 16px;">Height </td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td style="width: 50%; font-weight: bold;  text-align: center; border:1px solid #000; line-height: 30px; font-size: 30px;">'
                    . $window_price_sheet_calculation_quote_item_width
                    . '</td>'
                    . '<td style="width: 10%; font-weight: bold;  text-align: center; border:1px solid #000; line-height: 30px; font-size: 30px;">x</td>'
                    . '<td style="width: 40%; font-weight: bold;  text-align: center; border:1px solid #000; line-height: 30px; font-size: 30px;">'
                    . $window_price_sheet_calculation_quote_item_drop
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height: 25px; font-size: 15px;">Fabric Name and Type </td>'
                    . '<td style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Fabric/Colour</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . explode('<->', $window_price_sheet_calculation_quote_item_material)[0]
                    . '</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . explode('<->', $window_price_sheet_calculation_quote_item_colour)[0]
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Control Type</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_Control_Type
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Control Side</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_Contorl_side
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Hood/Brackets</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_hood_Type
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Hood/Brackets Colour</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_hood_colour
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Bottom Rail Colour</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_bottom_rail_colour
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Bottom Bar Seal</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_bottom_bar_seal
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Fitting Type</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_fitting_type
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td colspan="2" style="font-weight: bold; text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">Track Colour</td>'
                    . '<td style="text-align: center; border:1px solid #000; line-height:25px; font-size: 15px;">'
                    . $window_price_sheet_calculation_quote_item_side_track_colour
                    . '</td>'
                    . '</tr>'
                    . '</table>';

            $quote_item_no++;
        }
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
