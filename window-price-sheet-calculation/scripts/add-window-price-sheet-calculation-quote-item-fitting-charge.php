<?php
    error_reporting(E_ALL); ini_set('display_errors', 1);
    //include './check-login.php';
    include '../../cPanel/connect.php';
    
        $window_price_sheet_calculation_quote_item_code = filter_input( INPUT_POST, 'window_price_sheet_calculation_quote_item_code' );
        $window_price_sheet_calculation_code = filter_input( INPUT_POST, 'window_price_sheet_calculation_code' );
        $cid = filter_input( INPUT_POST, 'cid' );
        
        $fitting_charge = filter_input( INPUT_POST, 'fitting_charge' );
        
        $fitting_charge_code = explode( "<->", $fitting_charge )[0];
        $fitting_charge_name = explode( "<->", $fitting_charge )[1];
        $fitting_charge_price = str_replace(",", "", explode("<->", $fitting_charge)[2]);
        
        $query_1 = "INSERT INTO window_price_sheet_calculation_quote_item_fitting_charges ( code, name, price, window_price_sheet_calculation_quote_item_code, window_price_sheet_calculation_code, cid ) VALUES( ?, ?, ?, ?, ?, ? )";
        
        $stmt_1 = $mysqli -> prepare( $query_1 );
        $stmt_1 -> bind_param( 'ssssss', $fitting_charge_code, $fitting_charge_name, $fitting_charge_price, $window_price_sheet_calculation_quote_item_code, $window_price_sheet_calculation_code, $cid );
        
        if( $stmt_1 -> execute() ){
            print json_encode( array( 1 )); // Success
        }else{
            print json_encode( array( 2, "Oops! Something went wrong." )); // Error
        }
        $stmt_1 -> close();
        
    $mysqli -> close();