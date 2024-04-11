<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';
//include __DIR__ . './../scripts/get-price-from-csv-price-sheet-function.php';

$window_price_sheet_2_calculation_quote_tables = "";

$query_1 = "SELECT name, locations, materials_and_colours, accessories, per_meters, fitting_charges FROM window_price_sheet_2_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $window_price_sheet_2_calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($window_price_sheet_2_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $quote_item_no = 1;
    $window_price_sheet_2_calculation_quote_items = "";

    $query_3 = "SELECT code, location, width_x, drop_x, type, material, colour FROM window_price_sheet_2_calculation_quote_items WHERE window_price_sheet_2_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_price_sheet_2_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_price_sheet_2_calculation_quote_item_code, $window_price_sheet_2_calculation_quote_item_location, $window_price_sheet_2_calculation_quote_item_width, $window_price_sheet_2_calculation_quote_item_drop, $window_price_sheet_2_calculation_quote_item_type, $window_price_sheet_2_calculation_quote_item_material, $window_price_sheet_2_calculation_quote_item_colour);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {

        $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th rowspan="2" style="width: 25px;">No</th>'
                . '<th rowspan="2" style="width: 50px;">Location</th>'
                . '<th rowspan="2" style="width: 80px;">Fabric Name & Type</th>'
                . '<th rowspan="2" style="width: 60px;">Fabric/Colour</th>'
                . '<th rowspan="2" style="width: 30px;">Tube Type</th>'
                . '<th rowspan="2" style="width: 35px;">Tube Length</th>'
                . '<th rowspan="2" style="width: 50px;">Control Side</th>'
                . '<th rowspan="2" style="width: 50px;">Control Type</th>'
                . '<th rowspan="2" style="width: 50px;">Bracket Colour</th>'
                . '<th rowspan="2" style="width: 50px; background-color: #f2f2f2;">Bottom Bar Size</th>'
                . '<th rowspan="2" style="width: 50px;">Bottom Bar Type</th>'
                . '<th rowspan="2" style="width: 50px;">Bottom Bar Colour</th>'
                . '<th colspan="2" style="width: 60px; background-color: #f2f2f2;">Finished Blind Size</th>'
                //. '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
                . '<th rowspan="2" style="width: 40px;">Roll Dir</th>'
                . '<th rowspan="2" style="width: 40px;">Fitting</th>'
                . '<th rowspan="2" style="width: 40px;">Chain Colour</th>'
                . '<th rowspan="2" style="width: 40px;">Chain Size</th>'
                . '<th rowspan="2" style="width: 40px;">Fabric Width</th>'
                . '<th rowspan="2" style="width: 40px;">Fabric Height</th>'
                . '<th rowspan="2" style="width: 40px;">Tube Size - X</th>'
                . '<th rowspan="2" style="width: 40px;">Bottom Rail Size</th>'
                . '<th colspan="2" style="width: 60px; background-color: #f2f2f2;">Skin Size</th>'
                //. '<th rowspan="2" style="width: 40px;">Price</th>'
                //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
                //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
                . '</tr>'
                . '<tr>'
                . '<th style="width: 30px; background-color: #f2f2f2;">Width</th>'
                . '<th style="width: 30px; background-color: #f2f2f2;">Drop</th>'
                . '<th style="width: 30px; background-color: #f2f2f2;">Width</th>'
                . '<th style="width: 30px; background-color: #f2f2f2;">Drop</th>'
                . '</tr>';

        while ($stmt_3->fetch()) {

            $query_3_1 = "SELECT window_price_sheet_2_calculation_quote_item_fields.name, "
                    . "window_price_sheet_2_calculation_fields.side, "
                    . "window_price_sheet_2_calculation_fields.name "
                    . "FROM window_price_sheet_2_calculation_quote_item_fields "
                    . "JOIN window_price_sheet_2_calculation_fields ON "
                    . "window_price_sheet_2_calculation_fields.code = window_price_sheet_2_calculation_quote_item_fields.window_price_sheet_2_calculation_field_code "
                    . "WHERE "
                    . "window_price_sheet_2_calculation_quote_item_fields.window_price_sheet_2_calculation_quote_item_code = ? AND "
                    . "window_price_sheet_2_calculation_quote_item_fields.window_price_sheet_2_calculation_code = ? AND "
                    . "window_price_sheet_2_calculation_quote_item_fields.cid = ?";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_price_sheet_2_calculation_quote_item_code, $window_price_sheet_2_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_price_sheet_2_calculation_quote_item_field_name, $window_price_sheet_2_calculation_quote_item_field_side, $window_price_sheet_2_calculation_field_name);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;


            $quote_item_per_meter_no = 1;
            $window_price_sheet_2_calculation_quote_item_per_meters = "";

            $query_3_2 = "SELECT name, width FROM window_price_sheet_2_calculation_quote_item_per_meters WHERE window_price_sheet_2_calculation_quote_item_code = ? AND window_price_sheet_2_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_price_sheet_2_calculation_quote_item_code, $window_price_sheet_2_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_price_sheet_2_calculation_quote_item_per_meter_name, $window_price_sheet_2_calculation_quote_item_per_meter_width);
            $stmt_3_2->fetch();
            $stmt_3_2->close();

            while ($stmt_3_1->fetch()) {

                //$window_price_sheet_2_calculation_quote_item_field_name = explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[0];

                // if ($window_price_sheet_2_calculation_quote_item_field_name === 'Locations') {
                //     $window_price_sheet_2_calculation_quote_item_location_1 = $window_price_sheet_2_calculation_quote_item_field_name;
                // }

                if ($window_price_sheet_2_calculation_field_name === 'Fabric Height') {
                    $window_price_sheet_2_calculation_quote_item_Fabric_Height = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Fabric Width') {
                    $window_price_sheet_2_calculation_quote_item_Fabric_Width = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Tube Size - X') {
                    $window_price_sheet_2_calculation_quote_item_Tube_Size = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Rail Size') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Location') {
                    $window_price_sheet_2_calculation_quote_item_Location = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Tube Type') {
                    $window_price_sheet_2_calculation_quote_item_Tube_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                /*if ($window_price_sheet_2_calculation_quote_item_field_name === 'Base_Rail_Heading_Type') {
                    $window_price_sheet_2_calculation_quote_item_Base_Rail_Heading_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }*/

                if ($window_price_sheet_2_calculation_field_name === 'Bottom Bar Type') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_2_calculation_quote_item_width - 24;
				
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Bar Colour') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Control Side') {
                    $window_price_sheet_2_calculation_quote_item_Control_Side = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Control Type') {
                    $window_price_sheet_2_calculation_quote_item_Controls = explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[0];
                    
                    // Strat Edit 12-11-2021
					// $window_price_sheet_2_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[1];
					// $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[3];
                    // $window_price_sheet_2_calculation_quote_item_drop_skins = $window_price_sheet_2_calculation_quote_item_drop + 300;
                    // End Edit 12-11-2021
                }

                if ($window_price_sheet_2_calculation_field_name === 'Direction') {
                    $window_price_sheet_2_calculation_quote_item_Direction = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Chain Colour') {
                    $window_price_sheet_2_calculation_quote_item_Chain_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Fitting') {

                    $window_price_sheet_2_calculation_quote_item_Fit = $window_price_sheet_2_calculation_quote_item_field_name;

                    if($window_price_sheet_2_calculation_quote_item_Fit === "REV"){
                        $window_price_sheet_2_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_2_calculation_quote_item_width - 31;
                        $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - 31;
                        $window_price_sheet_2_calculation_quote_item_drop_skins = $window_price_sheet_2_calculation_quote_item_drop + 300;
                        $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_2_calculation_quote_item_width - 31;
                    }
                    else{
                        $window_price_sheet_2_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_2_calculation_quote_item_width - 26;
                        $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - 26;
                        $window_price_sheet_2_calculation_quote_item_drop_skins = $window_price_sheet_2_calculation_quote_item_drop + 300;
                        $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_2_calculation_quote_item_width - 26;
                    }
                }

                if ($window_price_sheet_2_calculation_field_name === 'Fixing') {
                    $window_price_sheet_2_calculation_quote_item_Fixing = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Bracket Colour') {
                    $window_price_sheet_2_calculation_quote_item_Bracket_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Chain Length') {
                    $window_price_sheet_2_calculation_quote_item_Chain_Size = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                /* if ($window_price_sheet_2_calculation_quote_item_field_name === 'No_Of_Rail_Brackets') {
                  $window_price_sheet_2_calculation_quote_item_No_Of_Rail_Brackets = $window_price_sheet_2_calculation_quote_item_field_name;
                  }

                  if ($window_price_sheet_2_calculation_quote_item_field_name === 'Bracket_Covers') {
                  $window_price_sheet_2_calculation_quote_item_Bracket_Covers = $window_price_sheet_2_calculation_quote_item_field_name;
                  } */
            }
            $stmt_3_1->close();

            //$csv_price_sheet = __DIR__ . './../cPanel/price-sheets/' . explode('<->', $window_price_sheet_2_calculation_quote_item_type)[1] . '-' . $window_price_sheet_2_calculation_code . '.csv';

            $window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Location . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_2_calculation_quote_item_type)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_colour)[0] . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_Type . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_width_skins_Tube_Length . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Control_Side . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Controls . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bracket_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Type . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop . '</td>'
                    //. '<td>' . $window_price_sheet_2_calculation_quote_item_mounting_rail_length . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Direction . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Fit . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Chain_Colour . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Chain_Size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Fabric_Width . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Fabric_Height . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_Size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width_skins . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop_skins . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_2_calculation_quote_item_width, $window_price_sheet_2_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_2_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_2_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>';

            $quote_item_no++;
        }

        $window_price_sheet_2_calculation_quote_tables .= '</table>';
    } else {
        $window_price_sheet_2_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
