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
		if($calculation_name === 'Roller Shutters'){
            $window_price_sheet_2_calculation_quote_tables_1 = '';
        $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th rowspan="2" style="width: 25px;"><b>No</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Location</b></th>'
                . '<th rowspan="2" style="width: 90px;"><b>Sutter Type</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Main Slat Colour</b></th>'
                . '<th rowspan="2" style="width: 50px; background-color: #f2f2f2;"><b>Main Slat Size</b></th>'
                . '<th rowspan="2" style="width: 50px; background-color: #f2f2f2;"><b>Main Slat Qty</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Stripe Required</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Stripe Type</b></th>'
                . '<th rowspan="2" style="width: 60px;"><b>Stripe Colour</b></th>'
                . '<th rowspan="2" style="width: 35px;"><b>Stripe QTY</b></th>'
                . '<th rowspan="2" style="width: 70px;"><b>Box Colour</b></th>'
                . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Box Size</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Box Length</b></th>'
                // . '<th rowspan="2" style="width: 50px;"><b>Axle Type</b></th>'
                . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Axle Size</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Guide Colour</b></th>'
                . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Guide Size</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Fit Type Width</b></th>'
                . '<th rowspan="2" style="width: 50px;"><b>Fit Type Height</b></th>'
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
                elseif($calculation_name === 'Zipscreen'){
            $window_price_sheet_2_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                    . '<tr>'
                    . '<th style="width: 50px;"><b>No</b></th>'
                    . '<th style="width: 100px;"><b>Fabric Name & Type</b></th>'
                    . '<th style="width: 100px;"><b>Fabric/Colour</b></th>'
                    . '<th style="width: 75px;"><b>Side Spline Colour</b></th>'
                    . '<th style="width: 70px;"><b>Control Type</b></th>'
                    . '<th style="width: 70px;"><b>Control Side</b></th>'
                    . '<th style="width: 100px;"><b>Hood/Brackets</b></th>'
                    . '<th style="width: 100px;"><b>Hood/Brackets Colour</b></th>'
                    . '<th style="width: 100px;"><b>Bottom Rail Colour</b></th>'
                    . '<th style="width: 100px;"><b>Bottom Bar Seal</b></th>'
                    . '<th style="width: 75px;"><b>Fitting Type</b></th>'
                    . '<th style="width: 75px;"><b>Track Colour</b></th>'
                    . '</tr>';
                    
            $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
            . '<tr>'
            . '<th rowspan="2" style="width: 50px;"><b>No</b></th>'
            . '<th colspan="2" style="width: 150px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
            . '<th colspan="2" style="width: 150px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
            . '<th rowspan="2" style="width: 105px;"><b>Side Spline Side</b></th>'
            . '<th rowspan="2" style="width: 105px;"><b>Tube Type</b></th>'
            . '<th rowspan="2" style="width: 105px;"><b>Tube Size</b></th>'
            . '<th rowspan="2" style="width: 110px;"><b>Hood/Brackets Size</b></th>'
            . '<th rowspan="2" style="width: 120px;"><b>Bottom Bar Size</b></th>'
            . '<th rowspan="2" colspan="2" style="width: 120px;"><b>Weight Size</b></th>'
            // . '<th rowspan="2" style="width: 100px;"><b>Flat Bar Size</b></th>'
            . '</tr>'
            . '<tr>'
            . '<th style="width: 75px; background-color: #f2f2f2;"><b>Width</b></th>'
            . '<th style="width: 75px; background-color: #f2f2f2;"><b>Height</b></th>'
            . '<th style="width: 75px; background-color: #f2f2f2;"><b>Width</b></th>'
            . '<th style="width: 75px; background-color: #f2f2f2;"><b>Height</b></th>'
            . '</tr>';
                    }
                
               
           
                else if($calculation_name === 'Ezip' || $calculation_name === 'eZip Blinds'){
                    $window_price_sheet_2_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                            . '<tr>'
                            . '<th style="width: 50px;"><b>No</b></th>'
                            . '<th style="width: 100px;"><b>Fabric Name & Type</b></th>'
                            . '<th style="width: 100px;"><b>Fabric/Colour</b></th>'
                            // . '<th style="width: 75px;"><b>Side Spline Colour</b></th>'
                            . '<th style="width: 70px;"><b>Control Type</b></th>'
                            . '<th style="width: 70px;"><b>Control Side</b></th>'
                            . '<th colspan="2" style="width: 175px;"><b>Hood/Brackets</b></th>'
                            . '<th style="width: 100px;"><b>Hood/Brackets Colour</b></th>'
                            . '<th style="width: 100px;"><b>Bottom Rail Colour</b></th>'
                            . '<th style="width: 100px;"><b>Bottom Bar Seal</b></th>'
                            . '<th style="width: 75px;"><b>Fitting Type</b></th>'
                            . '<th style="width: 75px;"><b>Track Colour</b></th>'
                            . '</tr>';
                            
                    $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                    . '<tr>'
                    . '<th rowspan="2" style="width: 50px;"><b>No</b></th>'
                    . '<th colspan="2" style="width: 150px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
                    . '<th colspan="2" style="width: 150px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
                    . '<th rowspan="2" style="width: 105px;"><b>Side Spline Side</b></th>'
                    . '<th rowspan="2" style="width: 105px;"><b>Tube Type</b></th>'
                    . '<th rowspan="2" style="width: 105px;"><b>Tube Size</b></th>'
                    . '<th rowspan="2" style="width: 110px;"><b>Hood/Brackets Size</b></th>'
                    . '<th rowspan="2" style="width: 120px;"><b>Bottom Bar Size</b></th>'
                    . '<th rowspan="2" colspan="2" style="width: 120px;"><b>Weight Size</b></th>'
                    // . '<th rowspan="2" style="width: 100px;"><b>Flat Bar Size</b></th>'
                    . '</tr>'
                    . '<tr>'
                    . '<th style="width: 75px; background-color: #f2f2f2;"><b>Width</b></th>'
                    . '<th style="width: 75px; background-color: #f2f2f2;"><b>Height</b></th>'
                    . '<th style="width: 75px; background-color: #f2f2f2;"><b>Width</b></th>'
                    . '<th style="width: 75px; background-color: #f2f2f2;"><b>Height</b></th>'
                    . '</tr>';
                }
                elseif($calculation_name === 'Ziptrak' || $calculation_name === 'Ziptrak Skins'){
                $window_price_sheet_2_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                        . '<tr>'
                        . '<th style="width: 50px;"><b>No</b></th>'
                        . '<th style="width: 100px;"><b>Fabric Name & Type</b></th>'
                        . '<th style="width: 100px;"><b>Fabric/Colour</b></th>'
                        . '<th style="width: 75px;"><b>Side Spline Colour</b></th>'
                        . '<th style="width: 70px;"><b>Control Type</b></th>'
                        . '<th style="width: 70px;"><b>Control Side</b></th>'
                        . '<th style="width: 100px;"><b>Hood/Brackets</b></th>'
                        . '<th style="width: 100px;"><b>Hood/Brackets Colour</b></th>'
                        . '<th style="width: 100px;"><b>Bottom Rail Colour</b></th>'
                        . '<th style="width: 100px;"><b>Bottom Bar Seal</b></th>'
                        . '<th style="width: 75px;"><b>Fitting Type</b></th>'
                        . '<th style="width: 75px;"><b>Track Colour</b></th>'
                        . '</tr>';
                        
                $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th rowspan="2" style="width: 50px;"><b>No</b></th>'
                . '<th colspan="2" style="width: 120px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
                . '<th colspan="2" style="width: 120px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
                . '<th rowspan="2" style="width: 105px;"><b>Side Spline Side</b></th>'
                . '<th rowspan="2" style="width: 105px;"><b>Tube Type</b></th>'
                . '<th rowspan="2" style="width: 105px;"><b>Tube Size</b></th>'
                . '<th rowspan="2" style="width: 110px;"><b>Hood/Brackets Size</b></th>'
                . '<th rowspan="2" style="width: 100px;"><b>Bottom Bar Size</b></th>'
                . '<th rowspan="2" style="width: 100px;"><b>Weight Size</b></th>'
                . '<th rowspan="2" style="width: 100px;"><b>Flat Bar Size</b></th>'
                . '</tr>'
                . '<tr>'
                . '<th style="width: 60px; background-color: #f2f2f2;"><b>Width</b></th>'
                . '<th style="width: 60px; background-color: #f2f2f2;"><b>Height</b></th>'
                . '<th style="width: 60px; background-color: #f2f2f2;"><b>Width</b></th>'
                . '<th style="width: 60px; background-color: #f2f2f2;"><b>Height</b></th>'
                . '</tr>';
                        }
			elseif($calculation_name === 'Canvas/Mesh Awnings' || $calculation_name === 'Fixed Guide Awnings' || $calculation_name === 'Straight Drop Spring' || $calculation_name === 'Straight Drop Crank' || $calculation_name === 'Awning Recovers'){
        $window_price_sheet_2_calculation_quote_tables_1 = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
                . '<tr>'
                . '<th style="width: 50px;"><b>No</b></th>'
                // . '<th rowspan="2" style="width: 40px;"><b>Location</b></th>'
                . '<th style="width: 100px;"><b>Fabric Name & Type</b></th>'
                . '<th style="width: 100px;"><b>Fabric/Colour</b></th>'
                // . '<th style="width: 60px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
                // . '<th style="width: 60px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
                . '<th style="width: 70px;"><b>Control Type</b></th>'
                . '<th style="width: 70px;"><b>Control Side</b></th>'
                . '<th style="width: 75px;"><b>Side Spline Colour</b></th>'
                // . '<th style="width: 50px;"><b>Side Spline Side</b></th>'
                // . '<th style="width: 65px;"><b>Tube Type</b></th>'
                // . '<th style="width: 65px;"><b>Tube Size</b></th>'
                . '<th style="width: 100px;"><b>Hood/Brackets</b></th>'
                . '<th style="width: 100px;"><b>Hood/Brackets Colour</b></th>'
                // . '<th style="width: 40px;"><b>Hood/Brackets Size</b></th>'
                // . '<th style="width: 50px;"><b>Bottom Rail Type</b></th>'
                . '<th style="width: 100px;"><b>Bottom Rail Colour</b></th>'
                // . '<th style="width: 40px;"><b>Bottom Bar Size</b></th>'
                . '<th style="width: 100px;"><b>Bottom Bar Seal</b></th>'
                // . '<th style="width: 40px;"><b>Fittment</b></th>'
                . '<th style="width: 75px;"><b>Fitting Type</b></th>'
                . '<th style="width: 75px;"><b>Track Colour</b></th>'
                // . '<th style="width: 40px;"><b>Weight Size</b></th>'
                // . '<th style="width: 40px;"><b>Flat Bar Size</b></th>'
                . '</tr>';
                
        $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
        . '<tr>'
        . '<th rowspan="2" style="width: 50px;"><b>No</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Location</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Fabric Name & Type</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Fabric/Colour</b></th>'
        . '<th colspan="2" style="width: 120px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
        . '<th colspan="2" style="width: 120px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Control Type</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Control Side</b></th>'
        // . '<th rowspan="2" style="width: 35px;"><b>Side Spline Colour</b></th>'
        . '<th rowspan="2" style="width: 105px;"><b>Side Spline Side</b></th>'
        . '<th rowspan="2" style="width: 105px;"><b>Tube Type</b></th>'
        . '<th rowspan="2" style="width: 105px;"><b>Tube Size</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Hood/Brackets</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Hood/Brackets Colour</b></th>'
        . '<th rowspan="2" style="width: 110px;"><b>Hood/Brackets Size</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail Type</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail Colour</b></th>'
        . '<th rowspan="2" style="width: 100px;"><b>Bottom Bar Size</b></th>'
        // . '<th rowspan="2" style="width: 35px;"><b>Bottom Bar Seal</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Fittment</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Fitting Type</b></th>'
        // . '<th rowspan="2" style="width: 35px;"><b>Track Colour</b></th>'
        . '<th rowspan="2" style="width: 100px;"><b>Weight Size</b></th>'
        . '<th rowspan="2" style="width: 100px;"><b>Flat Bar Size</b></th>'
        . '</tr>'
        . '<tr>'
        . '<th style="width: 60px; background-color: #f2f2f2;"><b>Width</b></th>'
        . '<th style="width: 60px; background-color: #f2f2f2;"><b>Height</b></th>'
        . '<th style="width: 60px; background-color: #f2f2f2;"><b>Width</b></th>'
        . '<th style="width: 60px; background-color: #f2f2f2;"><b>Height</b></th>'
        // . '<th rowspan="2" style="width: 20px;"><b>No</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Location</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Fabric Name & Type</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Fabric/Colour</b></th>'
        // . '<th colspan="2" style="width: 70px; background-color: #f2f2f2;"><b>Finish Size</b></th>'
        // . '<th colspan="2" style="width: 70px; background-color: #f2f2f2;"><b>Skin Size</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Control Type</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Control Side</b></th>'
        // . '<th rowspan="2" style="width: 35px;"><b>Side Spline Colour</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Side Spline Side</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Tube Type</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Tube Size</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Hood/Brackets</b></th>'
        // . '<th rowspan="2" style="width: 35px;"><b>Fitting</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail colour</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Rail Type</b></th>'
        // . '<th rowspan="2" style="width: 40px; background-color: #f2f2f2;"><b>Bottom Rail Size</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Locking</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Bottom Bar Seal</b></th>'
        // //. '<th rowspan="2" style="width: 50px;">Mounting Rail Length </th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Central Flat Bar Size</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Weight Size</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Keyway Type</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Keyway Size</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Hood Type</b></th>'
        // . '<th rowspan="2" style="width: 50px;"><b>Hood Colour</b></th>'
        // . '<th rowspan="2" style="width: 40px;"><b>Hood Size</b></th>'
        // . '<th colspan="2" style="width: 70px; background-color: #f2f2f2;"><b>Blind Size</b></th>'
        // //. '<th rowspan="2" style="width: 40px;">Price</th>'
        // //. '<th rowspan="2" style="width: 30px;">No. Of Rail Bkts</th>'
        // //. '<th rowspan="2" style="width: 40px;">Bracket Covers</th>'
        // . '</tr>'
        // . '<tr>'
        // . '<th style="width: 35px; background-color: #f2f2f2;"><b>Width</b></th>'
        // . '<th style="width: 35px; background-color: #f2f2f2;"><b>Drop</b></th>'
        // . '<th style="width: 35px; background-color: #f2f2f2;"><b>Width</b></th>'
        // . '<th style="width: 35px; background-color: #f2f2f2;"><b>Drop</b></th>'
        // . '<th style="width: 35px; background-color: #f2f2f2;"><b>Width</b></th>'
        // . '<th style="width: 35px; background-color: #f2f2f2;"><b>Drop</b></th>'
        . '</tr>';
				}
			else{
                $window_price_sheet_2_calculation_quote_tables_1 = '';
        $window_price_sheet_2_calculation_quote_tables = '<table cellspacing="0" cellpadding="4" style="font-size: 0.9em; text-align: center;" border="1">'
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

                if ($window_price_sheet_2_calculation_field_name === 'Location') {
                    $window_price_sheet_2_calculation_quote_item_location_1 = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Tube Type') {
                    // $window_price_sheet_2_calculation_quote_item_Tube_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                    $window_price_sheet_2_calculation_quote_item_Tube_Type = explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[0];
                    $window_price_sheet_2_calculation_quote_item_Tube_Type_width_deduction = explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[3];
                }

                /*if ($window_price_sheet_2_calculation_quote_item_field_name === 'Base_Rail_Heading_Type') {
                    $window_price_sheet_2_calculation_quote_item_Base_Rail_Heading_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }*/

                if ($window_price_sheet_2_calculation_field_name === 'Bottom Bar Type') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                // $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Size = $window_price_sheet_2_calculation_quote_item_width - 24;
				
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Bar Colour') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Control Side') {
                    $window_price_sheet_2_calculation_quote_item_Control_Side = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Control Type') {
                    $window_price_sheet_2_calculation_quote_item_Controls = explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[0];

                    /*if ($window_price_sheet_2_calculation_quote_item_per_meter_name === "Rs") {
                        $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[1];
                        $window_price_sheet_2_calculation_quote_item_mounting_rail_length = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[2];
                    } else
                    if ($window_price_sheet_2_calculation_quote_item_per_meter_name === "Rl") {
                        $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[3];
                    } else {
                        $window_price_sheet_2_calculation_quote_item_width_skins = "N/A";
                        $window_price_sheet_2_calculation_quote_item_mounting_rail_length = "N/A";
                    }*/
                    // Strat Edit 12-11-2021
					// $window_price_sheet_2_calculation_quote_item_width_skins_Tube_Length = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[1];
                    // if(!$window_price_sheet_2_calculation_quote_item_width_skins){
                    //     $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - explode(' | ', $window_price_sheet_2_calculation_quote_item_field_name)[3];
                    // }
                    // $window_price_sheet_2_calculation_quote_item_drop_skins = $window_price_sheet_2_calculation_quote_item_drop + 300;
                    
                    // End Edit 12-11-2021
                }

                if ($window_price_sheet_2_calculation_field_name === 'Main Slat Colour') {
                    $window_price_sheet_2_calculation_quote_item_Main_Slat_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Stripe Required?') {
                    $window_price_sheet_2_calculation_quote_item_Stripe_Required = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Stripe Type (From Bottom)') {
                    $window_price_sheet_2_calculation_quote_item_Stripe_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Stripe Colour') {
                    $window_price_sheet_2_calculation_quote_item_Stripe_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Box Colour') {
                    $window_price_sheet_2_calculation_quote_item_Box_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Guide Colour') {
                    $window_price_sheet_2_calculation_quote_item_Guide_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Direction') {
                    $window_price_sheet_2_calculation_quote_item_Direction = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Chain Colour') {
                    $window_price_sheet_2_calculation_quote_item_Chain_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
                if ($window_price_sheet_2_calculation_field_name === 'Track Colour' || $window_price_sheet_2_calculation_field_name === 'Side Track Colour') {
                    $window_price_sheet_2_calculation_quote_item_track_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
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
                    
                    // if($window_price_sheet_2_calculation_quote_item_field_name === "REV"){
                    //     $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - 31;
                    // }
                    // else if($window_price_sheet_2_calculation_quote_item_field_name === "FF"){
                    //     $window_price_sheet_2_calculation_quote_item_width_skins = $window_price_sheet_2_calculation_quote_item_width - 26;
                    // }
                    // else{
                    //     $window_price_sheet_2_calculation_quote_item_width_skins = '';
                    // }

                    // if($window_price_sheet_2_calculation_quote_item_field_name === "Reveal"){
                    //     $window_price_sheet_2_calculation_quote_item_width = $window_price_sheet_2_calculation_quote_item_width - 5;
                    // }
                }

                if ($window_price_sheet_2_calculation_field_name === 'Fitting Type') {
                    $window_price_sheet_2_calculation_quote_item_fitting_type = $window_price_sheet_2_calculation_quote_item_field_name;
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

                if ($window_price_sheet_2_calculation_field_name === 'Hood / Brackets' || $window_price_sheet_2_calculation_field_name === 'Hood Type') {
                    $window_price_sheet_2_calculation_quote_item_Hood_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Hood / Bracket Colour' || $window_price_sheet_2_calculation_field_name === 'Hood colour') {
                    $window_price_sheet_2_calculation_quote_item_Hood_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Fittment') {
                    $window_price_sheet_2_calculation_quote_item_Fittment = $window_price_sheet_2_calculation_quote_item_field_name;
                }

                if ($window_price_sheet_2_calculation_field_name === 'Side Spline Colour') {
                    $window_price_sheet_2_calculation_quote_item_Side_Spline_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Rail Colour') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Colour = $window_price_sheet_2_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Rail Type') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Type = $window_price_sheet_2_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Locking') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Locking = $window_price_sheet_2_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Bottom Bar Seal') {
                    $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Seal = $window_price_sheet_2_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Fit Type Width') {
                    $window_price_sheet_2_calculation_quote_item_fit_type_width = $window_price_sheet_2_calculation_quote_item_field_name;
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Fit Type Height') {
                    $window_price_sheet_2_calculation_quote_item_fit_type_height = $window_price_sheet_2_calculation_quote_item_field_name;
                    
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Box Size') {
                    $window_price_sheet_2_calculation_quote_item_box_size = $window_price_sheet_2_calculation_quote_item_field_name;
                    
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Valance Type') {
                    $window_price_sheet_2_calculation_quote_item_valance_type = $window_price_sheet_2_calculation_quote_item_field_name;
                    
                }
				
                if ($window_price_sheet_2_calculation_field_name === 'Arm Size') {
                    $window_price_sheet_2_calculation_quote_item_arm_size = $window_price_sheet_2_calculation_quote_item_field_name;
                    
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

		if($calculation_name === 'Roller Shutters'){
			$main_slat_size = $window_price_sheet_2_calculation_quote_item_width + $window_price_sheet_2_calculation_quote_item_fit_type_width - 71;
			$box_length = $window_price_sheet_2_calculation_quote_item_width - 10;
			$axle_size = $window_price_sheet_2_calculation_quote_item_width - 91;
			$width = $window_price_sheet_2_calculation_quote_item_width + $window_price_sheet_2_calculation_quote_item_fit_type_width;
            

            if($window_price_sheet_2_calculation_quote_item_fit_type_height === 'Finished Size'){
                $window_price_sheet_2_calculation_quote_item_fit_type_height_num = 0;
                }
                else{                       
                    $window_price_sheet_2_calculation_quote_item_fit_type_height_num = + $window_price_sheet_2_calculation_quote_item_box_size;
                    // $window_price_sheet_2_calculation_quote_item_fit_type_height_num = is_numeric($window_price_sheet_2_calculation_quote_item_fit_type_height);
                }

			$drop = $window_price_sheet_2_calculation_quote_item_drop + $window_price_sheet_2_calculation_quote_item_fit_type_height_num ;
            $guide_size = $drop - $window_price_sheet_2_calculation_quote_item_box_size;
			// $width = $window_price_sheet_2_calculation_quote_item_width + $window_price_sheet_2_calculation_quote_item_fit_type_width;
			$main_slat_qty = $main_slat_size;
            $window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_location_1 . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_material)[0] . ' - ' . explode('<->', $window_price_sheet_2_calculation_quote_item_type)[0] . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Main_Slat_Colour . '</td>'
                    . '<td>' . $main_slat_size . '</td>'
                    . '<td></td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Stripe_Required . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Stripe_Type . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Stripe_Colour . '</td>'
                    . '<td> </td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Box_Colour . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_box_size . '</td>'
                    . '<td>' . $box_length . '</td>'
                    . '<td>' . $axle_size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Guide_Colour .'</td>'
                    . '<td>' . $guide_size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_fit_type_width . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_fit_type_height . '</td>'
                    . '<td>' . $width . '</td>'
                    . '<td>' . $drop . '</td>'
                    //. '<td>' . $window_price_sheet_2_calculation_quote_item_mounting_rail_length . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_2_calculation_quote_item_width, $window_price_sheet_2_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_2_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_2_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>';
			}
           
            else if($calculation_name === 'Zipscreen'){
			
                $window_price_sheet_2_calculation_quote_item_Side_Spline_Size = $window_price_sheet_2_calculation_quote_item_drop + 250;
        
                $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_2_calculation_quote_item_width - 121;
                $window_price_sheet_2_calculation_quote_item_Hood_size = $window_price_sheet_2_calculation_quote_item_width - 66;
                $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                $window_price_sheet_2_calculation_quote_item_Weight_Size = $window_price_sheet_2_calculation_quote_item_width - 450;
                $window_price_sheet_2_calculation_quote_item_Tube_size = $window_price_sheet_2_calculation_quote_item_width - 121;
                $window_price_sheet_2_calculation_quote_item_Key_Way_Side = $window_price_sheet_2_calculation_quote_item_width - 100;
                $window_price_sheet_2_calculation_quote_item_Skins_width_size = $window_price_sheet_2_calculation_quote_item_width - 82;
                $window_price_sheet_2_calculation_quote_item_Skins_drop_size = $window_price_sheet_2_calculation_quote_item_drop + 310;
                        
                $window_price_sheet_2_calculation_quote_tables_1 .= '<tr nobr="true">'
                . '<td>' . $quote_item_no . '</td>'
                . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_material)[0] . '</td>'
                . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_colour)[0] . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Colour . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Controls . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Control_Side . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Type . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Colour . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_fitting_type . '</td>'
                . '<td>' . $window_price_sheet_2_calculation_quote_item_track_Colour . '</td>'
                . '</tr>';
    
                $window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                        . '<td>' . $quote_item_no . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_width_size . '</td>'
                        . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_drop_size . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Size . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_Type . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_size . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_size . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size . '</td>'
                        . '<td colspan="2">' . $window_price_sheet_2_calculation_quote_item_Weight_Size . '</td>'
                        // . '<td>' . $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size . '</td>'
                        . '</tr>';
                }
               
           
                else if($calculation_name === 'Ziptrak' || $calculation_name === 'Ziptrak Skins'){
                    
                            $window_price_sheet_2_calculation_quote_item_Side_Spline_Size = $window_price_sheet_2_calculation_quote_item_drop - 90;
                    
                            $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_2_calculation_quote_item_width - 99;
                            $window_price_sheet_2_calculation_quote_item_Hood_size = $window_price_sheet_2_calculation_quote_item_width - 7;
                            $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                            $window_price_sheet_2_calculation_quote_item_Weight_Size = $window_price_sheet_2_calculation_quote_item_width - 199;
                            $window_price_sheet_2_calculation_quote_item_Tube_size = $window_price_sheet_2_calculation_quote_item_width - 100;
                            $window_price_sheet_2_calculation_quote_item_Key_Way_Side = $window_price_sheet_2_calculation_quote_item_width - 100;
                            $window_price_sheet_2_calculation_quote_item_Skins_width_size = $window_price_sheet_2_calculation_quote_item_width - 90;
                            $window_price_sheet_2_calculation_quote_item_Skins_drop_size = $window_price_sheet_2_calculation_quote_item_drop + 150;
                            
                    $window_price_sheet_2_calculation_quote_tables_1 .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_material)[0] . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_colour)[0] . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Colour . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Controls . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Control_Side . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Type . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Colour . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_fitting_type . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_track_Colour . '</td>'
                    . '</tr>';
        
                    $window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                            . '<td>' . $quote_item_no . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_width_size . '</td>'
                            . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_drop_size . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Size . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_Type . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_size . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_size . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Weight_Size . '</td>'
                            . '<td>' . $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size . '</td>'
                            . '</tr>';
                    }
               
           
                    else if($calculation_name === 'Ezip' || $calculation_name === 'eZip Blinds'){
			
                        $window_price_sheet_2_calculation_quote_item_Side_Spline_Size = $window_price_sheet_2_calculation_quote_item_drop + 250;
                
                        $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_2_calculation_quote_item_width - 150;
                        $window_price_sheet_2_calculation_quote_item_Hood_size = $window_price_sheet_2_calculation_quote_item_width - 5;
                        $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                        $window_price_sheet_2_calculation_quote_item_Weight_Size = $window_price_sheet_2_calculation_quote_item_width - 250;
                        $window_price_sheet_2_calculation_quote_item_Tube_size = $window_price_sheet_2_calculation_quote_item_width - 58;
                        $window_price_sheet_2_calculation_quote_item_Key_Way_Side = $window_price_sheet_2_calculation_quote_item_width - 100;
                        $window_price_sheet_2_calculation_quote_item_Skins_width_size = $window_price_sheet_2_calculation_quote_item_width - 120;
                        $window_price_sheet_2_calculation_quote_item_Skins_drop_size = $window_price_sheet_2_calculation_quote_item_drop + 310;
                                
                        $window_price_sheet_2_calculation_quote_tables_1 .= '<tr nobr="true">'
                        . '<td>' . $quote_item_no . '</td>'
                        . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_material)[0] . '</td>'
                        . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_colour)[0] . '</td>'
                        // . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Colour . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Controls . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Control_Side . '</td>'
                        . '<td colspan="2">' . $window_price_sheet_2_calculation_quote_item_Hood_Type . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Colour . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Colour . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Seal . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_fitting_type . '</td>'
                        . '<td>' . $window_price_sheet_2_calculation_quote_item_track_Colour . '</td>'
                        . '</tr>';
            
                        $window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                                . '<td>' . $quote_item_no . '</td>'
                                . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width . '</td>'
                                . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop . '</td>'
                                . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_width_size . '</td>'
                                . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_drop_size . '</td>'
                                . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Size . '</td>'
                                . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_Type . '</td>'
                                . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_size . '</td>'
                                . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_size . '</td>'
                                . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size . '</td>'
                                . '<td colspan="2">' . $window_price_sheet_2_calculation_quote_item_Weight_Size . '</td>'
                                // . '<td>' . $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size . '</td>'
                                . '</tr>';
                        }
                   
               
           
		else if($calculation_name === 'Canvas/Mesh Awnings' || $calculation_name === 'Fixed Guide Awnings' || $calculation_name === 'Straight Drop Spring' || $calculation_name === 'Straight Drop Crank' || $calculation_name === 'Awning Recovers'){
			
                    $window_price_sheet_2_calculation_quote_item_Side_Spline_Size = $window_price_sheet_2_calculation_quote_item_drop - 90;
			
                    $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size = $window_price_sheet_2_calculation_quote_item_width - 99;
                    $window_price_sheet_2_calculation_quote_item_Hood_size = $window_price_sheet_2_calculation_quote_item_width - 7;
                    $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size = ceil(($window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size/2) - 80);
                    $window_price_sheet_2_calculation_quote_item_Weight_Size = $window_price_sheet_2_calculation_quote_item_width - 199;
                    $window_price_sheet_2_calculation_quote_item_Tube_size = $window_price_sheet_2_calculation_quote_item_width - 100;
                    $window_price_sheet_2_calculation_quote_item_Key_Way_Side = $window_price_sheet_2_calculation_quote_item_width - 100;
                    $window_price_sheet_2_calculation_quote_item_Skins_width_size = $window_price_sheet_2_calculation_quote_item_width - 90;
                    $window_price_sheet_2_calculation_quote_item_Skins_drop_size = $window_price_sheet_2_calculation_quote_item_drop + 150;
                    
			$window_price_sheet_2_calculation_quote_tables_1 .= '<tr nobr="true">'
            . '<td>' . $quote_item_no . '</td>'
            . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_material)[0] . '</td>'
            . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_colour)[0] . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Controls . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Control_Side . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Colour . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Type . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_Colour . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Colour . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Bar_Seal . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_fitting_type . '</td>'
            . '<td>' . $window_price_sheet_2_calculation_quote_item_track_Colour . '</td>'
            . '</tr>';

			$window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_width_size . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_Skins_drop_size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Side_Spline_Size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_Type . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Tube_size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Hood_size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Bottom_Rail_Size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Weight_Size . '</td>'
                    . '<td>' . $window_price_sheet_2_calculation_quote_item_Central_Flat_Bar_Size . '</td>'
                    . '</tr>';
			}
           
		else{
            $window_price_sheet_2_calculation_quote_item_width_skins_de = $window_price_sheet_2_calculation_quote_item_width_skins - $window_price_sheet_2_calculation_quote_item_Tube_Type_width_deduction;
            
			$window_price_sheet_2_calculation_quote_tables .= '<tr nobr="true">'
                    . '<td>' . $quote_item_no . '</td>'
                    . '<td>' . explode('<->', $window_price_sheet_2_calculation_quote_item_location)[0] . '</td>'
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
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_width_skins_de . '</td>'
                    . '<td style="background-color: #f2f2f2;">' . $window_price_sheet_2_calculation_quote_item_drop_skins . '</td>'
                    //. '<td>' . get_price_from_csv($csv_price_sheet, $window_price_sheet_2_calculation_quote_item_width, $window_price_sheet_2_calculation_quote_item_drop) . '</td>'
                    //. '<td style="background-color: #ff0;">' . $window_price_sheet_2_calculation_quote_item_No_Of_Rail_Brackets . '</td>'
                    //. '<td>' . $window_price_sheet_2_calculation_quote_item_Bracket_Covers . '</td>'
                    . '</tr>';
			}
            $quote_item_no++;
        }

        $window_price_sheet_2_calculation_quote_tables_1 .= '</table>';
        $window_price_sheet_2_calculation_quote_tables .= '</table>';
    } else {
        $window_price_sheet_2_calculation_quote_tables .= "";
    }
    $stmt_3->close();
}
$stmt_1->close();

$mysqli->close();
