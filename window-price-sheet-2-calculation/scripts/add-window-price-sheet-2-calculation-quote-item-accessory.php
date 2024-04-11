<?php
    error_reporting(E_ALL); ini_set('display_errors', 1);
    //include './check-login.php';
    include '../../cPanel/connect.php';
    
        $window_price_sheet_2_calculation_quote_item_code = filter_input( INPUT_POST, 'window_price_sheet_2_calculation_quote_item_code' );
        $window_price_sheet_2_calculation_code = filter_input( INPUT_POST, 'window_price_sheet_2_calculation_code' );
        $cid = filter_input( INPUT_POST, 'cid' );
        
        $accessory = filter_input( INPUT_POST, 'accessory' );
        $accessory_qty = filter_input( INPUT_POST, 'accessory_qty' );
        $accessory_mm = filter_input( INPUT_POST, 'accessory_mm' );
        
        $accessory_code = explode( "<->", $accessory )[0];
        $accessory_name = explode( "<->", $accessory )[1] . ' | ' . $accessory_mm;
        $accessory_price = str_replace(",", "", explode("<->", $accessory)[2]);
        
        $query_1 = "INSERT INTO window_price_sheet_2_calculation_quote_item_accessories ( code, name, price, qty, window_price_sheet_2_calculation_quote_item_code, window_price_sheet_2_calculation_code, cid ) VALUES( ?, ?, ?, ?, ?, ?, ? )";
        
        $stmt_1 = $mysqli -> prepare( $query_1 );
        $stmt_1 -> bind_param( 'sssssss', $accessory_code, $accessory_name, $accessory_price, $accessory_qty, $window_price_sheet_2_calculation_quote_item_code, $window_price_sheet_2_calculation_code, $cid );
        
        if( $stmt_1 -> execute() ){
            print json_encode( array( 1 )); // Success
        }else{
            print json_encode( array( 2, "Oops! Something went wrong." )); // Error
        }
        $stmt_1 -> close();
        
    $mysqli -> close();