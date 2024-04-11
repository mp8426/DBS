<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';
//include __DIR__ . './../scripts/get-price-from-csv-price-sheet-function.php';

$window_price_sheet_6_calculation_quote_tables = "";

$query_1 = "SELECT name, locations, materials_and_colours, accessories, per_meters, fitting_charges FROM window_price_sheet_6_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $window_price_sheet_6_calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($window_price_sheet_6_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $quote_item_no = 1;
    $window_price_sheet_6_calculation_quote_items = "";

    $query_3 = "SELECT code, location, width_x, drop_x, type, material, colour,fullness, notes FROM window_price_sheet_6_calculation_quote_items WHERE window_price_sheet_6_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_price_sheet_6_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_price_sheet_6_calculation_quote_item_code, $window_price_sheet_6_calculation_quote_item_location, $window_price_sheet_6_calculation_quote_item_width, $window_price_sheet_6_calculation_quote_item_drop, $window_price_sheet_6_calculation_quote_item_type, $window_price_sheet_6_calculation_quote_item_material, $window_price_sheet_6_calculation_quote_item_colour, $window_price_sheet_6_calculation_quote_item_fullness, $window_price_sheet_6_calculation_quote_item_notes);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {
        
                $window_price_sheet_6_calculation_quote_tables_1 = '';
        $window_price_sheet_6_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 25px;"><b>No</b></th>'
                . '<th style="width: 60px;"><b>Location</b></th>'
                . '<th style="width: 45px;"><b>Width</b></th>'
                . '<th style="width: 40px;"><b>Drop</b></th>'
                . '<th style="width: 130px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 60px;"><b>Fabric/Colour</b></th>'
                . '<th style="width: 80px;"><b>Fullness</b></th>'
                . '<th style="width: 40px;"><b>Fabric Width</b></th>'
                . '<th style="width: 40px;"><b>Snap Tape Width</b></th>'
                . '<th style="width: 40px;"><b>Cut Drop</b></th>'
                . '<th style="width: 40px;"><b>Hooks Req</b></th>'
                . '<th style="width: 60px;"><b>Control Side</b></th>'
                . '<th style="width: 60px;"><b>Control Type</b></th>'
                . '<th style="width: 60px;"><b>Fix</b></th>'
                . '<th style="width: 60px;"><b>Curtain Heading</b></th>'
                . '<th style="width: 60px;"><b>Curved Track</b></th>'
                . '<th style="width: 60px;"><b>Track Type</b></th>'
                . '<th style="width: 60px;"><b>Track Colour</b></th>'
                . '</tr>';
				

        while ($stmt_3->fetch()) {

            $query_3_0_1 = "SELECT number, description FROM window_price_sheet_6_calculation_fullness_option_dec_sheet WHERE length >= ? AND window_price_sheet_6_calculation_fullness_option_code = ?";

            $stmt_3_0_1 = $mysqli->prepare($query_3_0_1);
            $stmt_3_0_1->bind_param('is', $window_price_sheet_6_calculation_quote_item_width, explode('<->', $window_price_sheet_6_calculation_quote_item_fullness)[1]);
            $stmt_3_0_1->execute();
            $stmt_3_0_1->bind_result($window_price_sheet_6_calculation_quote_item_fullness_number, $window_price_sheet_6_calculation_quote_item_fullness_dection);
            $stmt_3_0_1->fetch();
            $stmt_3_0_1->close();

            $query_3_1 = "SELECT window_price_sheet_6_calculation_quote_item_fields.name, "
                    . "window_price_sheet_6_calculation_fields.side, "
                    . "window_price_sheet_6_calculation_fields.name "
                    . "FROM window_price_sheet_6_calculation_quote_item_fields "
                    . "JOIN window_price_sheet_6_calculation_fields ON "
                    . "window_price_sheet_6_calculation_fields.code = window_price_sheet_6_calculation_quote_item_fields.window_price_sheet_6_calculation_field_code "
                    . "WHERE "
                    . "window_price_sheet_6_calculation_quote_item_fields.window_price_sheet_6_calculation_quote_item_code = ? AND "
                    . "window_price_sheet_6_calculation_quote_item_fields.window_price_sheet_6_calculation_code = ? AND "
                    . "window_price_sheet_6_calculation_quote_item_fields.cid = ?";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_price_sheet_6_calculation_quote_item_code, $window_price_sheet_6_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_price_sheet_6_calculation_quote_item_field_name, $window_price_sheet_6_calculation_quote_item_field_side, $window_price_sheet_6_calculation_field_name);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;


            $quote_item_accessory_no = 1;
            $window_price_sheet_6_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM window_price_sheet_6_calculation_quote_item_accessories WHERE window_price_sheet_6_calculation_quote_item_code = ? AND window_price_sheet_6_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_price_sheet_6_calculation_quote_item_code, $window_price_sheet_6_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_price_sheet_6_calculation_quote_item_accessory_code, $window_price_sheet_6_calculation_quote_item_accessory_name, $window_price_sheet_6_calculation_quote_item_accessory_price, $window_price_sheet_6_calculation_quote_item_accessory_qty, $window_price_sheet_6_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $window_price_sheet_6_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="text-align: left; border-right: 0.5px solid #000000;">' . $quote_item_accessory_no . '. ' . $window_price_sheet_6_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="text-align: center;">' . $window_price_sheet_6_calculation_quote_item_accessory_qty . '</td>'
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();




            $quote_item_per_meter_no = 1;
            $window_price_sheet_6_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM window_price_sheet_6_calculation_quote_item_per_meters WHERE window_price_sheet_6_calculation_quote_item_code = ? AND window_price_sheet_6_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $window_price_sheet_6_calculation_quote_item_code, $window_price_sheet_6_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($window_price_sheet_6_calculation_quote_item_per_meter_code, $window_price_sheet_6_calculation_quote_item_per_meter_name, $window_price_sheet_6_calculation_quote_item_per_meter_price, $window_price_sheet_6_calculation_quote_item_per_meter_width, $window_price_sheet_6_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $window_price_sheet_6_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style=" text-align: left; border-right: 0.5px solid #000000;">' . $quote_item_per_meter_no . '. ' . $window_price_sheet_6_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="text-align: center;">' . $window_price_sheet_6_calculation_quote_item_per_meter_width . '</td>'
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();

            while ($stmt_3_1->fetch()) {

                //$window_price_sheet_6_calculation_quote_item_field_name = explode(' | ', $window_price_sheet_6_calculation_quote_item_field_name)[0];

                if ($window_price_sheet_6_calculation_field_name === 'Location') {
                    $window_price_sheet_6_calculation_quote_item_location_1 = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Opening') {
                    $window_price_sheet_6_calculation_quote_item_opening = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Control Side') {
                    $window_price_sheet_6_calculation_quote_item_Control_Side = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Control Type') {
                    $window_price_sheet_6_calculation_quote_item_Controls = explode(' | ', $window_price_sheet_6_calculation_quote_item_field_name)[0];

                }

                if ($window_price_sheet_6_calculation_field_name === 'Fixing' || $window_price_sheet_6_calculation_field_name === 'Fix') {
                    $window_price_sheet_6_calculation_quote_item_Fixing = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Curtain Heading') {
                    $window_price_sheet_6_calculation_quote_item_curtain_heading = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Curved Track') {
                    $window_price_sheet_6_calculation_quote_item_curved_track = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Track Type') {
                    $window_price_sheet_6_calculation_quote_item_track_type = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Track Colour' || $window_price_sheet_6_calculation_field_name === 'Side Track Colour') {
                    $window_price_sheet_6_calculation_quote_item_track_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Bottom Bar Type') {
                    $window_price_sheet_6_calculation_quote_item_Bottom_Bar_Type = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                // $window_price_sheet_6_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_6_calculation_quote_item_width - 24;
				
                if ($window_price_sheet_6_calculation_field_name === 'Bottom Bar Colour') {
                    $window_price_sheet_6_calculation_quote_item_Bottom_Bar_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Main Slat Colour') {
                    $window_price_sheet_6_calculation_quote_item_Main_Slat_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Stripe Required?') {
                    $window_price_sheet_6_calculation_quote_item_Stripe_Required = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Stripe Type (From Bottom)') {
                    $window_price_sheet_6_calculation_quote_item_Stripe_Type = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Stripe Colour') {
                    $window_price_sheet_6_calculation_quote_item_Stripe_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Box Colour') {
                    $window_price_sheet_6_calculation_quote_item_Box_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Guide Colour') {
                    $window_price_sheet_6_calculation_quote_item_Guide_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Direction') {
                    $window_price_sheet_6_calculation_quote_item_Direction = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Chain Colour') {
                    $window_price_sheet_6_calculation_quote_item_Chain_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_6_calculation_field_name === 'Track Colour' || $window_price_sheet_6_calculation_field_name === 'Side Track Colour') {
                    $window_price_sheet_6_calculation_quote_item_track_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Fitting') {

                    $window_price_sheet_6_calculation_quote_item_Fit = $window_price_sheet_6_calculation_quote_item_field_name;

                    if($window_price_sheet_6_calculation_quote_item_Fit === "REV"){
                        $window_price_sheet_6_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_6_calculation_quote_item_width - 31;
                        $window_price_sheet_6_calculation_quote_item_width_skins = $window_price_sheet_6_calculation_quote_item_width - 31;
                        $window_price_sheet_6_calculation_quote_item_drop_skins = $window_price_sheet_6_calculation_quote_item_drop + 300;
                        $window_price_sheet_6_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_6_calculation_quote_item_width - 31;
                    }
                    else{
                        $window_price_sheet_6_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_6_calculation_quote_item_width - 26;
                        $window_price_sheet_6_calculation_quote_item_width_skins = $window_price_sheet_6_calculation_quote_item_width - 26;
                        $window_price_sheet_6_calculation_quote_item_drop_skins = $window_price_sheet_6_calculation_quote_item_drop + 300;
                        $window_price_sheet_6_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_6_calculation_quote_item_width - 26;
                    }
                    
                    // if($window_price_sheet_6_calculation_quote_item_field_name === "REV"){
                    //     $window_price_sheet_6_calculation_quote_item_width_skins = $window_price_sheet_6_calculation_quote_item_width - 31;
                    // }
                    // else if($window_price_sheet_6_calculation_quote_item_field_name === "FF"){
                    //     $window_price_sheet_6_calculation_quote_item_width_skins = $window_price_sheet_6_calculation_quote_item_width - 26;
                    // }
                    // else{
                    //     $window_price_sheet_6_calculation_quote_item_width_skins = '';
                    // }

                    // if($window_price_sheet_6_calculation_quote_item_field_name === "Reveal"){
                    //     $window_price_sheet_6_calculation_quote_item_width = $window_price_sheet_6_calculation_quote_item_width - 5;
                    // }
                }

                if ($window_price_sheet_6_calculation_field_name === 'Fitting Type') {
                    $window_price_sheet_6_calculation_quote_item_fitting_type = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Bracket Colour') {
                    $window_price_sheet_6_calculation_quote_item_Bracket_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Chain Length') {
                    $window_price_sheet_6_calculation_quote_item_Chain_Size = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Hood / Brackets' || $window_price_sheet_6_calculation_field_name === 'Hood Type') {
                    $window_price_sheet_6_calculation_quote_item_Hood_Type = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Hood / Bracket Colour' || $window_price_sheet_6_calculation_field_name === 'Hood colour') {
                    $window_price_sheet_6_calculation_quote_item_Hood_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Fittment') {
                    $window_price_sheet_6_calculation_quote_item_Fittment = $window_price_sheet_6_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_6_calculation_field_name === 'Side Spline Colour') {
                    $window_price_sheet_6_calculation_quote_item_Side_Spline_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Bottom Rail Colour') {
                    $window_price_sheet_6_calculation_quote_item_Bottom_Rail_Colour = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Bottom Rail Type') {
                    $window_price_sheet_6_calculation_quote_item_Bottom_Rail_Type = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Bottom Locking') {
                    $window_price_sheet_6_calculation_quote_item_Bottom_Locking = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Bottom Bar Seal') {
                    $window_price_sheet_6_calculation_quote_item_Bottom_Bar_Seal = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Fit Type Width') {
                    $window_price_sheet_6_calculation_quote_item_fit_type_width = $window_price_sheet_6_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_6_calculation_field_name === 'Fit Type Height') {
                    $window_price_sheet_6_calculation_quote_item_fit_type_height = $window_price_sheet_6_calculation_quote_item_field_name;
                    
                }
				
            }
            $stmt_3_1->close();

            //$csv_price_sheet = __DIR__ . './../cPanel/price-sheets/' . explode('<->', $window_price_sheet_6_calculation_quote_item_type)[1] . '-' . $window_price_sheet_6_calculation_code . '.csv';

            
            $window_price_sheet_6_calculation_quote_item_width_skins_de = $window_price_sheet_6_calculation_quote_item_width_skins - $window_price_sheet_6_calculation_quote_item_Tube_Type_width_deduction;
            

            if ($window_price_sheet_6_calculation_quote_item_notes) {

                $window_price_sheet_6_calculation_quote_item_notes_table = '<tr>'
                    . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="20">'
                    . nl2br($window_price_sheet_6_calculation_quote_item_notes)
                    . '</td>'
                    . '</tr>';
            } else {
                $window_price_sheet_6_calculation_quote_item_notes_table = "";
            }

            if ($window_price_sheet_6_calculation_quote_item_accessories) {

                $window_price_sheet_6_calculation_quote_item_accessories_table =  '<tr>'
                        . '<td style="border: 0.5px solid #000000;" colspan="20">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                        . '<th style="width: 10%; text-align: center;">Qty</th>'
                        . '</tr>'
                        . $window_price_sheet_6_calculation_quote_item_accessories
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            } else {
                $window_price_sheet_6_calculation_quote_item_accessories_table = "";
            }

            if ($window_price_sheet_6_calculation_quote_item_per_meters) {

                $window_price_sheet_6_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                        . '<td style="border: 0.5px solid #000000;" colspan="20">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                        . '<th style="width: 10%; text-align: center;">Width</th>'
                        . '</tr>'
                        . $window_price_sheet_6_calculation_quote_item_per_meters
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            } else {
                $window_price_sheet_6_calculation_quote_item_per_meters_table = "";
            }

			$window_price_sheet_6_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_6_calculation_quote_item_location)[0] . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_width . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_drop . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_6_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_6_calculation_quote_item_type)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_6_calculation_quote_item_colour)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_6_calculation_quote_item_fullness)[0] . '</td>'
                    . '<td>' . ($window_price_sheet_6_calculation_quote_item_fullness_dection + 160) . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_fullness_dection . '</td>'
                    . '<td>' . ($window_price_sheet_6_calculation_quote_item_drop - 50) . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_fullness_number . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_Control_Side . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_Controls . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_Fixing . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_curtain_heading . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_curved_track . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_track_type . '</td>'
                    . '<td>' . $window_price_sheet_6_calculation_quote_item_track_Colour . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_6_calculation_quote_item_width, $window_price_sheet_6_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_6_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_6_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>'
                    . $window_price_sheet_6_calculation_quote_item_notes_table
                    . $window_price_sheet_6_calculation_quote_item_accessories_table;
			
            $quote_item_no++;
        }

        $window_price_sheet_6_calculation_quote_tables_1 .= '</table>';
        $window_price_sheet_6_calculation_quote_tables .= '</table>';
    } else {
        $window_price_sheet_6_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
