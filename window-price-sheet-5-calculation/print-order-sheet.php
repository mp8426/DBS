<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
include __DIR__ . './../cPanel/connect.php';
//include __DIR__ . './../scripts/get-price-from-csv-price-sheet-function.php';

$window_price_sheet_5_calculation_quote_tables = "";

$query_1 = "SELECT name, locations, materials_and_colours, accessories, per_meters, fitting_charges FROM window_price_sheet_5_calculations ORDER BY position ASC";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $window_price_sheet_5_calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($window_price_sheet_5_calculation_name, $locations_select, $materials_and_colours_select, $accessories_select, $per_meters_select, $fitting_charges_select);
$stmt_1->store_result();

while ($stmt_1->fetch()) {

    $quote_item_no = 1;
    $window_price_sheet_5_calculation_quote_items = "";

    $query_3 = "SELECT code, location, width_x, drop_x, type, material, colour, notes FROM window_price_sheet_5_calculation_quote_items WHERE window_price_sheet_5_calculation_code = ? AND cid = ? ORDER BY id ASC";

    $stmt_3 = $mysqli->prepare($query_3);
    $stmt_3->bind_param('ss', $window_price_sheet_5_calculation_code, $cid);
    $stmt_3->execute();
    $stmt_3->bind_result($window_price_sheet_5_calculation_quote_item_code, $window_price_sheet_5_calculation_quote_item_location, $window_price_sheet_5_calculation_quote_item_width, $window_price_sheet_5_calculation_quote_item_drop, $window_price_sheet_5_calculation_quote_item_type, $window_price_sheet_5_calculation_quote_item_material, $window_price_sheet_5_calculation_quote_item_colour, $window_price_sheet_5_calculation_quote_item_notes);
    $stmt_3->store_result();

    if ($stmt_3->num_rows()) {
		if($calculation_name === 'Roller Shutters'){
        $window_price_sheet_5_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th rowspan="2" style="width: 25px;"><b>No</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Location</b></th>'
                . '<th rowspan="2" style="width: 90px;"><b>Sutter Type</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Main Slat Colour</b></th>'
                . '<th rowspan="2" style="width: 60px; background-color: #f2f2f2;"><b>Main Slat Size</b></th>'
                . '<th rowspan="2" style="width: 60px;"><b>Stripe Required</b></th>'
                . '<th rowspan="2" style="width: 60px;"><b>Stripe Type</b></th>'
                . '<th rowspan="2" style="width: 60px;"><b>Stripe Colour</b></th>'
                . '<th rowspan="2" style="width: 35px;"><b>Stripe QTY</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Box Colour</b></th>'
                . '<th rowspan="2" style="width: 50px; background-color: #f2f2f2;"><b>Box Size</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Box Type</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Guide Colour</b></th>'
                . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Guide Size</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Axle Type</b></th>'
                . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Axle Size</b></th>'
                . '<th colspan="2" style="width: 80px;"><b>Finished Blind Size</b></th>'
             //   . '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
             //   . '<th rowspan="2" style="width: 40px;">Main Slat Size</th>'
             //   . '<th rowspan="2" style="width: 40px;">Box Size</th>'
              //  . '<th rowspan="2" style="width: 40px;">Box Type</th>'
                //. '<th rowspan="2" style="width: 40px;">Price</th>'
                //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
                //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
                . '</tr>'
                . '<tr>'
                . '<th style="width: 40px;"><b>Width</b></th>'
                . '<th style="width: 40px;"><b>Drop</b></th>'
               // . '<th style="width: 40px; background-color: #f2f2f2;">Width</th>'
               // . '<th style="width: 40px; background-color: #f2f2f2;">Drop</th>'
                . '</tr>';
				}
			elseif($calculation_name === 'Ziptrak'){
        $window_price_sheet_5_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                . '<th rowspan="2" style="width: 40px;"><b>Location</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Fabric Name & Type</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Fabric/Colour</b></th>'
                . '<th rowspan="2" style="width: 35px;"><b>Fitting</b></th>'
                . '<th rowspan="2" style="width: 35px;"><b>Side Spline Colour</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Side Spline Side</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail colour</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail Type</b></th>'
                . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Bottom Rail Size</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bottom Locking</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bottom Bar Seal</b></th>'
                //. '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
                . '<th rowspan="2" style="width: 50px;"><b>Central Flat Bar Size</b></th>'
                . '<th rowspan="2" style="width: 40px;"><b>Weight Size</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Keyway Type</b></th>'
                . '<th rowspan="2" style="width: 40px;"><b>Keyway Size</b></th>'
                . '<th rowspan="2" style="width: 40px;"><b>Control Type</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Hood Type</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Hood Colour</b></th>'
                . '<th rowspan="2" style="width: 40px;"><b>Hood Size</b></th>'
                . '<th colspan="2" style="width: 70px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
                . '<th colspan="2" style="width: 70px; background-color: #f2f2f2;"><b>Blind Size</b></th>'
                //. '<th rowspan="2" style="width: 40px;">Price</th>'
                //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
                //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
                . '</tr>'
                . '<tr>'
                . '<th style="width: 35px; background-color: #f2f2f2;"><b>Width</b></th>'
                . '<th style="width: 35px; background-color: #f2f2f2;"><b>Drop</b></th>'
                . '<th style="width: 35px; background-color: #f2f2f2;"><b>Width</b></th>'
                . '<th style="width: 35px; background-color: #f2f2f2;"><b>Drop</b></th>'
                . '</tr>';
				}
                elseif($calculation_name === 'Veri Shades'){
                    $window_price_sheet_5_calculation_quote_tables_1 = '';
            $window_price_sheet_5_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                    . '<tr>'
                    . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                    . '<th rowspan="2" style="width: 70px;"><b>Location</b></th>'
                    . '<th rowspan="2" style="width: 110px;"><b>Fabric Name & Type</b></th>'
                    . '<th rowspan="2" style="width: 110px;"><b>Fabric/Colour</b></th>'
                    . '<th rowspan="2" style="width: 60px;"><b>FABRIC DROP</b></th>'
                    . '<th rowspan="2" style="width: 60px;"><b>Panels One Way</b></th>'
                    . '<th rowspan="2" style="width: 60px;"><b>Panels Centre open</b></th>'
                    . '<th rowspan="2" style="width: 60px;"><b>Track Carriers</b></th>'
                    . '<th rowspan="2" style="width: 60px;"><b>Track Size</b></th>'
                    . '<th rowspan="2" style="width: 80px;"><b>Track Colour</b></th>'
                    . '<th rowspan="2" style="width: 80px;"><b>Control Type</b></th>'
                    . '<th rowspan="2" style="width: 80px;"><b>Fitting</b></th>'
                    . '<th rowspan="2" style="width: 80px;"><b>Stacking</b></th>'
                    . '<th colspan="2" style="width: 82px; background-color: #f2f2f2;"><b>Finished Blind Size</b></th>'
                    . '</tr>'
                    . '<tr>'
                    . '<th style="width: 42px; background-color: #f2f2f2;"><b>Width</b></th>'
                    . '<th style="width: 40px; background-color: #f2f2f2;"><b>Drop</b></th>'
                    . '</tr>';
                    }
                    elseif($calculation_name === 'CF90 Cassette & Side Channel Rollers'){
                        
                            $window_price_sheet_5_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                            . '<tr>'
                            . '<th style="width: 50px;"><b>No</b></th>'
                            . '<th style="width: 100px;"><b>Cassette Front</b></th>'
                            . '<th style="width: 110px;"><b>Cassette Back</b></th>'
                            . '<th style="width: 100px;"><b>Track & Cassette Colour</b></th>'
                            . '<th style="width: 110px;"><b>Tracks</b></th>'
                            . '<th style="width: 110px;"><b>Baserail Weight</b></th>'
                            . '</tr>';
                $window_price_sheet_5_calculation_quote_tables_1 = '<br><br><br><table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th rowspan="2" style="width: 30px;"><b>No</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Location</b></th>'
                        . '<th rowspan="2" style="width: 110px;"><b>Fabric Name & Type</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Fabric/Colour</b></th>'
                        . '<th rowspan="2" style="width: 50px;"><b>Tube Type</b></th>'
                        . '<th rowspan="2" style="width: 50px;"><b>Tube Length</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Control Side</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Control Type</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Bracket Colour</b></th>'
                        . '<th rowspan="2" style="width: 60px; background-color: #f2f2f2;"><b>Bottom Bar Size</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Bar Type</b></th>'
                        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Bar Colour</b></th>'
                        . '<th colspan="2" style="width: 90px; background-color: #f2f2f2;"><b>Finished Blind Size</b></th>'
                        //. '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
                        . '<th rowspan="2" style="width: 50px;"><b>Roll Dir</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Fitting</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Track And Cassette Colour</b></th>'
                        . '<th rowspan="2" style="width: 70px;"><b>Electrician REq</b></th>'
                        . '<th colspan="2" style="width: 90px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
                        //. '<th rowspan="2" style="width: 40px;">Price</th>'
                        //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
                        //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
                        . '</tr>'
                        . '<tr>'
                        . '<th style="width: 45px; background-color: #f2f2f2;"><b>Width</b></th>'
                        . '<th style="width: 45px; background-color: #f2f2f2;"><b>Drop</b></th>'
                        . '<th style="width: 45px; background-color: #f2f2f2;"><b>Width</b></th>'
                        . '<th style="width: 45px; background-color: #f2f2f2;"><b>Drop</b></th>'
                        . '</tr>';
                        }
			else{
        $window_price_sheet_5_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Location</b></th>'
                . '<th rowspan="2" style="width: 90px;"><b>Fabric Name & Type</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Fabric/Colour</b></th>'
                . '<th rowspan="2" style="width: 35px;"><b>Tube Type</b></th>'
                . '<th rowspan="2" style="width: 35px;"><b>Tube Length</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Control Side</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Control Type</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bracket Colour</b></th>'
                . '<th rowspan="2" style="width: 50px; background-color: #f2f2f2;"><b>Bottom Bar Size</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bottom Bar Type</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Bottom Bar Colour</b></th>'
                . '<th colspan="2" style="width: 80px; background-color: #f2f2f2;"><b>Finished Blind Size</b></th>'
                //. '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
                . '<th rowspan="2" style="width: 50px;"><b>Roll Dir</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Fitting</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Chain Colour</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Chain Size</b></th>'
                . '<th colspan="2" style="width: 80px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
                //. '<th rowspan="2" style="width: 40px;">Price</th>'
                //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
                //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
                . '</tr>'
                . '<tr>'
                . '<th style="width: 40px; background-color: #f2f2f2;"><b>Width</b></th>'
                . '<th style="width: 40px; background-color: #f2f2f2;"><b>Drop</b></th>'
                . '<th style="width: 40px; background-color: #f2f2f2;"><b>Width</b></th>'
                . '<th style="width: 40px; background-color: #f2f2f2;"><b>Drop</b></th>'
                . '</tr>';
				}

        while ($stmt_3->fetch()) {

            $query_3_1 = "SELECT window_price_sheet_5_calculation_quote_item_fields.name, "
                    . "window_price_sheet_5_calculation_fields.side, "
                    . "window_price_sheet_5_calculation_fields.name "
                    . "FROM window_price_sheet_5_calculation_quote_item_fields "
                    . "JOIN window_price_sheet_5_calculation_fields ON "
                    . "window_price_sheet_5_calculation_fields.code = window_price_sheet_5_calculation_quote_item_fields.window_price_sheet_5_calculation_field_code "
                    . "WHERE "
                    . "window_price_sheet_5_calculation_quote_item_fields.window_price_sheet_5_calculation_quote_item_code = ? AND "
                    . "window_price_sheet_5_calculation_quote_item_fields.window_price_sheet_5_calculation_code = ? AND "
                    . "window_price_sheet_5_calculation_quote_item_fields.cid = ?";

            $stmt_3_1 = $mysqli->prepare($query_3_1);
            $stmt_3_1->bind_param('sss', $window_price_sheet_5_calculation_quote_item_code, $window_price_sheet_5_calculation_code, $cid);
            $stmt_3_1->execute();
            $stmt_3_1->bind_result($window_price_sheet_5_calculation_quote_item_field_name, $window_price_sheet_5_calculation_quote_item_field_side, $window_price_sheet_5_calculation_field_name);
            $stmt_3_1->store_result();
            $field_td_num_rows = $stmt_3_1->num_rows;


            $quote_item_accessory_no = 1;
            $window_price_sheet_5_calculation_quote_item_accessories = "";

            $query_3_2 = "SELECT code, name, price, qty, (price*qty) AS total FROM window_price_sheet_5_calculation_quote_item_accessories WHERE window_price_sheet_5_calculation_quote_item_code = ? AND window_price_sheet_5_calculation_code = ? AND cid = ?";

            $stmt_3_2 = $mysqli->prepare($query_3_2);
            $stmt_3_2->bind_param('sss', $window_price_sheet_5_calculation_quote_item_code, $window_price_sheet_5_calculation_code, $cid);
            $stmt_3_2->execute();
            $stmt_3_2->bind_result($window_price_sheet_5_calculation_quote_item_accessory_code, $window_price_sheet_5_calculation_quote_item_accessory_name, $window_price_sheet_5_calculation_quote_item_accessory_price, $window_price_sheet_5_calculation_quote_item_accessory_qty, $window_price_sheet_5_calculation_quote_item_accessory_total);

            while ($stmt_3_2->fetch()) {

                $window_price_sheet_5_calculation_quote_item_accessories .= '<tr>'
                        . '<td style="text-align: left; border-right: 0.5px solid #000000;">' . $quote_item_accessory_no . '. ' . $window_price_sheet_5_calculation_quote_item_accessory_name . '</td>'
                        . '<td style="text-align: center;">' . $window_price_sheet_5_calculation_quote_item_accessory_qty . '</td>'
                        . '</tr>';
                $quote_item_accessory_no++;
            }
            $stmt_3_2->close();




            $quote_item_per_meter_no = 1;
            $window_price_sheet_5_calculation_quote_item_per_meters = "";

            $query_3_3 = "SELECT code, name, price, width, (price*width) AS total FROM window_price_sheet_5_calculation_quote_item_per_meters WHERE window_price_sheet_5_calculation_quote_item_code = ? AND window_price_sheet_5_calculation_code = ? AND cid = ?";

            $stmt_3_3 = $mysqli->prepare($query_3_3);
            $stmt_3_3->bind_param('sss', $window_price_sheet_5_calculation_quote_item_code, $window_price_sheet_5_calculation_code, $cid);
            $stmt_3_3->execute();
            $stmt_3_3->bind_result($window_price_sheet_5_calculation_quote_item_per_meter_code, $window_price_sheet_5_calculation_quote_item_per_meter_name, $window_price_sheet_5_calculation_quote_item_per_meter_price, $window_price_sheet_5_calculation_quote_item_per_meter_width, $window_price_sheet_5_calculation_quote_item_per_meter_total);

            while ($stmt_3_3->fetch()) {

                $window_price_sheet_5_calculation_quote_item_per_meters .= '<tr>'
                        . '<td style=" text-align: left; border-right: 0.5px solid #000000;">' . $quote_item_per_meter_no . '. ' . $window_price_sheet_5_calculation_quote_item_per_meter_name . '</td>'
                        . '<td style="text-align: center;">' . $window_price_sheet_5_calculation_quote_item_per_meter_width . '</td>'
                        . '</tr>';
                $quote_item_per_meter_no++;
            }
            $stmt_3_3->close();

            while ($stmt_3_1->fetch()) {

                //$window_price_sheet_5_calculation_quote_item_field_name = explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[0];

                // if ($window_price_sheet_5_calculation_quote_item_field_name === 'Locations') {
                //     $window_price_sheet_5_calculation_quote_item_location_1 = $window_price_sheet_5_calculation_quote_item_field_name;
                // }

                if ($window_price_sheet_5_calculation_field_name === 'Location') {
                    $window_price_sheet_5_calculation_quote_item_Location = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Tube Type') {
                    $window_price_sheet_5_calculation_quote_item_Tube_Type = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Track & Cassette Colour') {
                    $window_price_sheet_5_calculation_quote_item_track_cassette_colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Electrician REq?') {
                    $window_price_sheet_5_calculation_quote_item_electician_req = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Fitting Type' || $window_price_sheet_5_calculation_field_name === 'Fit Type') {
                    $window_price_sheet_5_calculation_quote_item_fitting_type = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                /*if ($window_price_sheet_5_calculation_quote_item_field_name === 'Base_Rail_Heading_Type') {
                    $window_price_sheet_5_calculation_quote_item_Base_Rail_Heading_Type = $window_price_sheet_5_calculation_quote_item_field_name;
                }*/

                if ($window_price_sheet_5_calculation_field_name === 'Bottom Bar Type') {
                    $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Type = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_5_calculation_quote_item_width - 30;
				
                if ($window_price_sheet_5_calculation_field_name === 'Bottom Bar Colour') {
                    $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Control Side') {
                    $window_price_sheet_5_calculation_quote_item_Control_Side = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Control Type') {
                    $window_price_sheet_5_calculation_quote_item_Controls = explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[0];

                    /*if ($window_price_sheet_5_calculation_quote_item_per_meter_name === "Rs") {
                        $window_price_sheet_5_calculation_quote_item_width_skins = $window_price_sheet_5_calculation_quote_item_width - explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[1];
                        $window_price_sheet_5_calculation_quote_item_mounting_rail_length = $window_price_sheet_5_calculation_quote_item_width - explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[2];
                    } else
                    if ($window_price_sheet_5_calculation_quote_item_per_meter_name === "Rl") {
                        $window_price_sheet_5_calculation_quote_item_width_skins = $window_price_sheet_5_calculation_quote_item_width - explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[3];
                    } else {
                        $window_price_sheet_5_calculation_quote_item_width_skins = "N/A";
                        $window_price_sheet_5_calculation_quote_item_mounting_rail_length = "N/A";
                    }*/
					$window_price_sheet_5_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_5_calculation_quote_item_width - explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[1];
					$window_price_sheet_5_calculation_quote_item_width_skins = $window_price_sheet_5_calculation_quote_item_width - explode(' | ', $window_price_sheet_5_calculation_quote_item_field_name)[3];
                    $window_price_sheet_5_calculation_quote_item_drop_skins = $window_price_sheet_5_calculation_quote_item_drop + 300;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Main Slat Colour') {
                    $window_price_sheet_5_calculation_quote_item_Main_Slat_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_5_calculation_field_name === 'Stripe Required?') {
                    $window_price_sheet_5_calculation_quote_item_Stripe_Required = $window_price_sheet_5_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_5_calculation_field_name === 'Stripe Type (From Bottom)') {
                    $window_price_sheet_5_calculation_quote_item_Stripe_Type = $window_price_sheet_5_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_5_calculation_field_name === 'Stripe Colour') {
                    $window_price_sheet_5_calculation_quote_item_Stripe_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_5_calculation_field_name === 'Box Colour') {
                    $window_price_sheet_5_calculation_quote_item_Box_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_5_calculation_field_name === 'Guide Colour') {
                    $window_price_sheet_5_calculation_quote_item_Guide_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_5_calculation_field_name === 'Direction') {
                    $window_price_sheet_5_calculation_quote_item_Direction = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Chain Colour') {
                    $window_price_sheet_5_calculation_quote_item_Chain_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Fitting') {

                    $window_price_sheet_5_calculation_quote_item_Fit = $window_price_sheet_5_calculation_quote_item_field_name;

                    if($window_price_sheet_5_calculation_quote_item_field_name === "Reveal"){
                        $window_price_sheet_5_calculation_quote_item_width = $window_price_sheet_5_calculation_quote_item_width - 5;
                    }
                }

                if ($window_price_sheet_5_calculation_field_name === 'Fixing') {
                    $window_price_sheet_5_calculation_quote_item_Fixing = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Bracket Colour') {
                    $window_price_sheet_5_calculation_quote_item_Bracket_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Chain Length') {
                    $window_price_sheet_5_calculation_quote_item_Chain_Size = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Hood / Brackets') {
                    $window_price_sheet_5_calculation_quote_item_Hood_Type = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Hood / Bracket Colour') {
                    $window_price_sheet_5_calculation_quote_item_Hood_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Fittment') {
                    $window_price_sheet_5_calculation_quote_item_Fittment = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Side Spline Colour') {
                    $window_price_sheet_5_calculation_quote_item_Side_Spline_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_5_calculation_field_name === 'Bottom Rail Colour') {
                    $window_price_sheet_5_calculation_quote_item_Bottom_Rail_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_5_calculation_field_name === 'Bottom Rail Type') {
                    $window_price_sheet_5_calculation_quote_item_Bottom_Rail_Type = $window_price_sheet_5_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_5_calculation_field_name === 'Bottom Locking') {
                    $window_price_sheet_5_calculation_quote_item_Bottom_Locking = $window_price_sheet_5_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_5_calculation_field_name === 'Bottom Bar Seal') {
                    $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Seal = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Track Colour') {
                    $window_price_sheet_5_calculation_quote_item_track_Colour = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_5_calculation_field_name === 'Stacking') {
                    $window_price_sheet_5_calculation_quote_item_stacking = $window_price_sheet_5_calculation_quote_item_field_name;
                }

                /* if ($window_price_sheet_5_calculation_quote_item_field_name === 'No_Of_Rail_Brackets') {
                  $window_price_sheet_5_calculation_quote_item_No_Of_Rail_Brackets = $window_price_sheet_5_calculation_quote_item_field_name;
                  }

                  if ($window_price_sheet_5_calculation_quote_item_field_name === 'Bracket_Covers') {
                  $window_price_sheet_5_calculation_quote_item_Bracket_Covers = $window_price_sheet_5_calculation_quote_item_field_name;
                  } */
            }
            $stmt_3_1->close();

            //$csv_price_sheet = __DIR__ . './../cPanel/price-sheets/' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[1] . '-' . $window_price_sheet_5_calculation_code . '.csv';

		if($calculation_name === 'Roller Shutters'){
            $window_price_sheet_5_calculation_quote_tables_1 = '';
			$main_slat_size = $window_price_sheet_5_calculation_quote_item_width - 71;
			$box_size = $window_price_sheet_5_calculation_quote_item_width - 10;
			$axle_size = $window_price_sheet_5_calculation_quote_item_width - 91;

            if ($window_price_sheet_5_calculation_quote_item_notes) {

                $window_price_sheet_5_calculation_quote_item_notes_table = '<tr>'
                    . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="18">'
                    . nl2br($window_price_sheet_5_calculation_quote_item_notes)
                    . '</td>'
                    . '</tr>';
            } else {
                $window_price_sheet_5_calculation_quote_item_notes_table = "";
            }

            if ($window_price_sheet_5_calculation_quote_item_accessories) {

                $window_price_sheet_5_calculation_quote_item_accessories_table =  '<tr>'
                        . '<td style="border: 0.5px solid #000000;" colspan="18">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                        . '<th style="width: 10%; text-align: center;">Qty</th>'
                        . '</tr>'
                        . $window_price_sheet_5_calculation_quote_item_accessories
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            } else {
                $window_price_sheet_5_calculation_quote_item_accessories_table = "";
            }

            if ($window_price_sheet_5_calculation_quote_item_per_meters) {

                $window_price_sheet_5_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                        . '<td style="border: 0.5px solid #000000;" colspan="18">'
                        . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                        . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                        . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                        . '<th style="width: 10%; text-align: center;">Width</th>'
                        . '</tr>'
                        . $window_price_sheet_5_calculation_quote_item_per_meters
                        . '</table>'
                        . '</td>'
                        . '</tr>';
            } else {
                $window_price_sheet_5_calculation_quote_item_per_meters_table = "";
            }

            $window_price_sheet_5_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_location)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[0] . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Main_Slat_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $main_slat_size . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Stripe_Required . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Stripe_Type . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Stripe_Colour . '</td>'
                    . '<td> </td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Box_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $box_size . '</td>'
                    . '<td></td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Guide_Colour .'</td>'
                    . '<td style="background-color: #f2f2f2;"></td>'
                    . '<td></td>'
                    . '<td style="background-color: #f2f2f2;">' . $axle_size . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_width . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_drop . '</td>'
                    //. '<td>' . $window_price_sheet_5_calculation_quote_item_mounting_rail_length . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_5_calculation_quote_item_width, $window_price_sheet_5_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_5_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>'
                    . $window_price_sheet_5_calculation_quote_item_notes_table
                    . $window_price_sheet_5_calculation_quote_item_accessories_table;
			}
           
		else if($calculation_name === 'Ziptrak'){
            $window_price_sheet_5_calculation_quote_tables_1 = '';
			
                    $window_price_sheet_5_calculation_quote_item_Side_Spline_Size = $window_price_sheet_5_calculation_quote_item_drop - 90;
			
                    $window_price_sheet_5_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_5_calculation_quote_item_width - 99;
                    $window_price_sheet_5_calculation_quote_item_Hood_size = $window_price_sheet_5_calculation_quote_item_width - 7;
                    $window_price_sheet_5_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_5_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                    $window_price_sheet_5_calculation_quote_item_Weight_Size = $window_price_sheet_5_calculation_quote_item_width - 199;
                    $window_price_sheet_5_calculation_quote_item_Key_Way_Side = $window_price_sheet_5_calculation_quote_item_width - 100;
                    $window_price_sheet_5_calculation_quote_item_Skins_width_size = $window_price_sheet_5_calculation_quote_item_width - 90;
                    $window_price_sheet_5_calculation_quote_item_Skins_drop_size = $window_price_sheet_5_calculation_quote_item_drop + 150;
                    

                    if ($window_price_sheet_5_calculation_quote_item_notes) {
            
                        $window_price_sheet_5_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="24">'
                            . nl2br($window_price_sheet_5_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_notes_table = "";
                    }
        
                    if ($window_price_sheet_5_calculation_quote_item_accessories) {
        
                        $window_price_sheet_5_calculation_quote_item_accessories_table =  '<tr>'
                                . '<td style="border: 0.5px solid #000000;" colspan="24">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                                . '<th style="width: 10%; text-align: center;">Qty</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_accessories
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_accessories_table = "";
                    }
        
                    if ($window_price_sheet_5_calculation_quote_item_per_meters) {
        
                        $window_price_sheet_5_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                                . '<td style="border: 0.5px solid #000000;" colspan="24">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                                . '<th style="width: 10%; text-align: center;">Width</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_per_meters
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_per_meters_table = "";
                    }

			$window_price_sheet_5_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_location)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_colour)[0] . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Fittment . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Side_Spline_Colour . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Side_Spline_Size . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Rail_Type . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Rail_Size . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Locking . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Central_Flat_Bar_Size . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Weight_Size . '</td>'
                    . '<td></td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Key_Way_Side . '</td>'
                    //. '<td>' . $window_price_sheet_5_calculation_quote_item_mounting_rail_length . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Controls . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Hood_Type . '</td>'
                    . '<td>' . $window_price_sheet_5_calculation_quote_item_Hood_Colour . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Hood_size . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Skins_width_size . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Skins_drop_size . '</td>'
                    . '<td style="background-color: #000; color:#fff;">' . $window_price_sheet_5_calculation_quote_item_width . '</td>'
                    . '<td style="background-color: #000; color:#fff;">' . $window_price_sheet_5_calculation_quote_item_drop . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_5_calculation_quote_item_width, $window_price_sheet_5_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_5_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>'
                    . $window_price_sheet_5_calculation_quote_item_notes_table
                    . $window_price_sheet_5_calculation_quote_item_accessories_table;
			}
            else if($calculation_name === 'Roller Blinds'){
                $window_price_sheet_5_calculation_quote_tables_1 = '';

                if ($window_price_sheet_5_calculation_quote_item_notes) {
    
                    $window_price_sheet_5_calculation_quote_item_notes_table = '<tr>'
                        . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="20">'
                        . nl2br($window_price_sheet_5_calculation_quote_item_notes)
                        . '</td>'
                        . '</tr>';
                } else {
                    $window_price_sheet_5_calculation_quote_item_notes_table = "";
                }
    
                if ($window_price_sheet_5_calculation_quote_item_accessories) {
    
                    $window_price_sheet_5_calculation_quote_item_accessories_table =  '<tr>'
                            . '<td style="border: 0.5px solid #000000;" colspan="20">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                            . '<th style="width: 10%; text-align: center;">Qty</th>'
                            . '</tr>'
                            . $window_price_sheet_5_calculation_quote_item_accessories
                            . '</table>'
                            . '</td>'
                            . '</tr>';
                } else {
                    $window_price_sheet_5_calculation_quote_item_accessories_table = "";
                }
    
                if ($window_price_sheet_5_calculation_quote_item_per_meters) {
    
                    $window_price_sheet_5_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                            . '<td style="border: 0.5px solid #000000;" colspan="20">'
                            . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                            . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                            . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                            . '<th style="width: 10%; text-align: center;">Width</th>'
                            . '</tr>'
                            . $window_price_sheet_5_calculation_quote_item_per_meters
                            . '</table>'
                            . '</td>'
                            . '</tr>';
                } else {
                    $window_price_sheet_5_calculation_quote_item_per_meters_table = "";
                }
                $window_price_sheet_5_calculation_quote_tables .= '<tr nobr="true">'
                        . '<td>' . $quote_item_no . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Location . '</td>'
                        . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[0] . '</td>'
                        . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_colour)[0] . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Tube_Type . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_width_skins_Tube_Length . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Control_Side . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Controls . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Colour . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Size . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Type . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Colour . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_width . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_drop . '</td>'
                        //. '<td>' . $window_price_sheet_5_calculation_quote_item_mounting_rail_length . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Direction . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Fit . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Chain_Colour . '</td>'
                        . '<td>' . $window_price_sheet_5_calculation_quote_item_Chain_Size . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_width_skins . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_drop_skins . '</td>'
                        //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_5_calculation_quote_item_width, $window_price_sheet_5_calculation_quote_item_drop) . '</td>'
                        //. '<td style="background-color: #ff0;">' . $window_price_sheet_5_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                        //. '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Covers . '</td>'
                        . '</tr>'
                        . $window_price_sheet_5_calculation_quote_item_notes_table
                        . $window_price_sheet_5_calculation_quote_item_accessories_table;
                }        
           
                else if($calculation_name === 'Veri Shades'){
                    $window_price_sheet_5_calculation_quote_tables_1 = '';
                    $window_price_sheet_5_calculation_quote_item_width_skins_de = $window_price_sheet_5_calculation_quote_item_width_skins - $window_price_sheet_5_calculation_quote_item_Tube_Type_width_deduction;
        
        
                    if ($window_price_sheet_5_calculation_quote_item_notes) {
        
                        $window_price_sheet_5_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="20">'
                            . nl2br($window_price_sheet_5_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_notes_table = "";
                    }
        
                    if ($window_price_sheet_5_calculation_quote_item_accessories) {
        
                        $window_price_sheet_5_calculation_quote_item_accessories_table =  '<tr>'
                                . '<td style="border: 0.5px solid #000000;" colspan="20">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                                . '<th style="width: 10%; text-align: center;">Qty</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_accessories
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_accessories_table = "";
                    }
        
                    if ($window_price_sheet_5_calculation_quote_item_per_meters) {
        
                        $window_price_sheet_5_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                                . '<td style="border: 0.5px solid #000000;" colspan="20">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                                . '<th style="width: 10%; text-align: center;">Width</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_per_meters
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_per_meters_table = "";
                    }
                    $window_price_sheet_5_calculation_quote_itempanels_one_way = round($window_price_sheet_5_calculation_quote_item_width/103);
                    
                    $window_price_sheet_5_calculation_quote_tables .= '<tr nobr="true">'
                            . '<td>' . $quote_item_no . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_location)[0] . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[0] . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_colour)[0] . '</td>'
                            . '<td>' . ($window_price_sheet_5_calculation_quote_item_drop - 60) . '</td>'
                            . '<td>' . ($window_price_sheet_5_calculation_quote_itempanels_one_way + 1) . '</td>'
                            . '<td>' . ($window_price_sheet_5_calculation_quote_itempanels_one_way + 3) . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_itempanels_one_way . '</td>'
                            . '<td>' . ($window_price_sheet_5_calculation_quote_item_width - 5) . '</td>'
                            // . '<td>' . $window_price_sheet_5_calculation_quote_item_track_type . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_track_Colour. '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Controls . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Fit . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_stacking . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_width . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_drop . '</td>'
                            . '</tr>'
                            . $window_price_sheet_5_calculation_quote_item_notes_table
                            . $window_price_sheet_5_calculation_quote_item_accessories_table
                            // . $window_price_sheet_5_calculation_quote_item_per_meters_table
                            . $table_more;
                }
                   
            
                else if($calculation_name === 'CF90 Cassette & Side Channel Rollers'){
                    $window_price_sheet_5_calculation_quote_item_width_skins_de = $window_price_sheet_5_calculation_quote_item_width_skins - $window_price_sheet_5_calculation_quote_item_Tube_Type_width_deduction;
        
                    if ($window_price_sheet_5_calculation_quote_item_notes) {
        
                        $window_price_sheet_5_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="20">'
                            . nl2br($window_price_sheet_5_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_notes_table = "";
                    }
        
                    if ($window_price_sheet_5_calculation_quote_item_accessories) {
        
                        $window_price_sheet_5_calculation_quote_item_accessories_table =  '<tr>'
                                . '<td style="border: 0.5px solid #000000;" colspan="20">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                                . '<th style="width: 10%; text-align: center;">Qty</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_accessories
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_accessories_table = "";
                    }
        
                    if ($window_price_sheet_5_calculation_quote_item_per_meters) {
        
                        $window_price_sheet_5_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                                . '<td style="border: 0.5px solid #000000;" colspan="20">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                                . '<th style="width: 10%; text-align: center;">Width</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_per_meters
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_per_meters_table = "";
                    }
                    
                    $window_price_sheet_5_calculation_quote_tables_1 .= '<tr nobr="true">'
                            . '<td>' . $quote_item_no . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_location)[0] . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[0] . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_colour)[0] . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Tube_Type . '</td>'
                            . '<td>' . ($window_price_sheet_5_calculation_quote_item_width - 40) . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Control_Side . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Controls . '</td>'
                            // . '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Colour . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_width - 38) . '</td>'
                            // . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Type . '</td>'
                            // . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Colour . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_width . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_drop . '</td>'
                            . '<td>STD</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_fitting_type . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_track_cassette_colour . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_electician_req . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_width - 40) . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_drop + 150) . '</td>'
                            . '</tr>'
                            . $window_price_sheet_5_calculation_quote_item_notes_table
                            . $window_price_sheet_5_calculation_quote_item_accessories_table
                            . $table_more;
                            // . $window_price_sheet_5_calculation_quote_item_per_meters_table
                            

                            $window_price_sheet_5_calculation_quote_tables .= '<tr nobr="true">'
                            . '<td>' . $quote_item_no . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_width - 32) . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_width - 17) . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_track_cassette_colour . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_drop - 96) . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . ($window_price_sheet_5_calculation_quote_item_width -113) . '</td>'
                            . '</tr>';
                    }
           
                else{

                    $window_price_sheet_5_calculation_quote_tables_1 = '';
                    if ($window_price_sheet_5_calculation_quote_item_notes) {

                        $window_price_sheet_5_calculation_quote_item_notes_table = '<tr>'
                            . '<td style="border: 0.5px solid #000000; text-align:left;" colspan="20">'
                            . nl2br($window_price_sheet_5_calculation_quote_item_notes)
                            . '</td>'
                            . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_notes_table = "";
                    }

                    if ($window_price_sheet_5_calculation_quote_item_accessories) {

                        $window_price_sheet_5_calculation_quote_item_accessories_table =  '<tr>'
                                . '<td style="border: 0.5px solid #000000;" colspan="20">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Accessory</th>'
                                . '<th style="width: 10%; text-align: center;">Qty</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_accessories
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_accessories_table = "";
                    }

                    if ($window_price_sheet_5_calculation_quote_item_per_meters) {

                        $window_price_sheet_5_calculation_quote_item_per_meters_table =  '<tr style="font-size: 0.9em;">'
                                . '<td style="border: 0.5px solid #000000;" colspan="20">'
                                . '<table cellpadding="4" cellspacing="0" style="line-height: 6px;">'
                                . '<tr style="font-weight: bold; background-color: #f2f2f2;">'
                                . '<th style="width: 90%; text-align: left; border-right: 0.5px solid #000000;">#. Per Meter</th>'
                                . '<th style="width: 10%; text-align: center;">Width</th>'
                                . '</tr>'
                                . $window_price_sheet_5_calculation_quote_item_per_meters
                                . '</table>'
                                . '</td>'
                                . '</tr>';
                    } else {
                        $window_price_sheet_5_calculation_quote_item_per_meters_table = "";
                    }
                    $window_price_sheet_5_calculation_quote_tables .= '<tr nobr="true">'
                            . '<td>' . $quote_item_no . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_location)[0] . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_5_calculation_quote_item_type)[0] . '</td>'
                            . '<td>' . explode('<->', $window_price_sheet_5_calculation_quote_item_colour)[0] . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Tube_Type . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_width_skins_Tube_Length . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Control_Side . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Controls . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Colour . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Size . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Type . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Bottom_Bar_Colour . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_width . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_drop . '</td>'
                            //. '<td>' . $window_price_sheet_5_calculation_quote_item_mounting_rail_length . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Direction . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Fit . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Chain_Colour . '</td>'
                            . '<td>' . $window_price_sheet_5_calculation_quote_item_Chain_Size . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_width_skins . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_5_calculation_quote_item_drop_skins . '</td>'
                            //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_5_calculation_quote_item_width, $window_price_sheet_5_calculation_quote_item_drop) . '</td>'
                            //. '<td style="background-color: #ff0;">' . $window_price_sheet_5_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                            //. '<td>' . $window_price_sheet_5_calculation_quote_item_Bracket_Covers . '</td>'
                            . '</tr>'
                            . $window_price_sheet_5_calculation_quote_item_notes_table
                            . $window_price_sheet_5_calculation_quote_item_accessories_table;
                    }
            $quote_item_no++;
        }

        $window_price_sheet_5_calculation_quote_tables_1 .= '</table>';
        $window_price_sheet_5_calculation_quote_tables .= '</table>';
    } else {
        $window_price_sheet_5_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();