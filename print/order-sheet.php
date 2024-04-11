<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
$cid = filter_input(INPUT_GET, 'cid');

include __DIR__ . '/../connect.php';
include __DIR__ . '/../ticket/functions/get-calculation.php';

// $type_product_code_array = [];
$get_calculation_details = get_calculation($conn, $cid);

$calculations = $get_calculation_details['calculations'];
$product_codes = $get_calculation_details['product_codes'];
$pro_codes = $get_calculation_details['pro_codes'];
$value_11 = implode(',', $pro_codes);

include './../cPanel/connect.php';
$calculation = filter_input(INPUT_GET, 'calculation'); // Main calculation Name
$calculation_x = str_replace("-", "_", $calculation);

$calculation_code = filter_input(INPUT_GET, 'code');

$today_date = date("l, d F y ");

$query_1 = "SELECT name FROM " . $calculation_x . "s WHERE code = ?";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($calculation_name);
$stmt_1->fetch();
$stmt_1->close();

$calculation_name_type = '';
// echo $calculation;
// die();

if($calculation !== 'form-no-2-calculation' && $calculation !== 'furnishing-2-calculation'){
    $query_13 = "SELECT code, type FROM " . $calculation_x . "_quote_items WHERE " . $calculation_x . "_code = ? AND cid = ?";
    
    $stmt_13 = $mysqli->prepare($query_13);
    $stmt_13->bind_param('ss', $calculation_code, $cid);
    $stmt_13->execute();
    $stmt_13->bind_result($code, $quote_item_type);
    $stmt_13->store_result();
    $rowcount = $stmt_13->num_rows;
    $stmt_13->fetch();
    
    $stmt_13->close();
    
    
    $calculation_name_type = explode('<->', $quote_item_type)[0];
}


$type_product_code_array = [];


if($calculation !== 'form-no-2-calculation' && $calculation !== 'furnishing-2-calculation'){
$query_14 = "SELECT type FROM " . $calculation_x . "_quote_items WHERE cid = ? AND " . $calculation_x . "_code = ?  GROUP BY type";

$stmt_14 = $mysqli->prepare($query_14);
$stmt_14->bind_param('ss', $cid, $calculation_code);
$stmt_14->execute();
$stmt_14->bind_result($quote_item_typex);
$stmt_14->store_result();
while ($stmt_14->fetch()) {

    // $calculation_name_array[] = $quote_item_typex;

    // foreach ($calculation_name_array as $value) {

    //     $value_1 .= $value[0] . ',';

    //     //  $calculation_name = explode('<->', $value)[0];
    // }
    if(!$quote_item_typex){
        $tos_code = $quote_item_typex;
    }else{
        $tos_code =  explode('<->', $quote_item_typex)[1];
    }

        $product_code_new = "";
        $query_41 = "SELECT pc.code, tos.product_code_id FROM window_price_sheet_calculation_type_options tos LEFT JOIN  product_codes pc ON tos.product_code_id = pc.id WHERE tos.code = ?  GROUP BY pc.code , tos.product_code_id";

        $stmt_41 = $mysqli->prepare($query_41);
        $stmt_41->bind_param('s', $tos_code);
        $stmt_41->execute();
        $stmt_41->bind_result($product_code_new, $product_code_id);
        $stmt_41->fetch();
            
                $type_product_code_array[] = $product_code_new;
                    
       
        $stmt_41->close();
}
$stmt_14->close();
}

include_once __DIR__ . '/../ticket/functions/business-details.php'; // business_details();

$business_details = business_details();

$business_address_1 = $business_details['address_1'];
$business_address_2 = $business_details['address_2'];
$business_suburb = $business_details['suburb'];
$business_postcode = $business_details['postcode'];
$business_country = $business_details['country'];
$business_email = $business_details['email'];
$business_web = $business_details['web'];
$business_phone = $business_details['phone'];
$business_mobile = $business_details['mobile'];
$business_fax = $business_details['fax'];
$business_abn = $business_details['abn'];
$bank_account_name = $business_details['bank_account_name'];
$bank_account_no = $business_details['bank_account_no'];
$bsb_no = $business_details['bsb_no'];
$tax_name = $business_details['tax_name'];
$tax_percentage = $business_details['tax_percentage'];
$currency_sign = $business_details['currency_sign'];


$query_3 = "SELECT c.customer_no, q.q_name_1, q.q_name_2, q.q_address_1, q.q_address_2, q.q_suburb, q.q_postcode, q.q_email, q.q_phone, q.q_mobile, q.c_ref "
        . "FROM quotes q "
        . "LEFT JOIN customers c ON q.customer_id = c.id "
        . "WHERE quote_no = ?";

$stmt_3 = $mysqli->prepare($query_3);
$stmt_3->bind_param('s', $cid);
$stmt_3->execute();
$stmt_3->bind_result($customer_no, $q_name_1, $q_name_2, $q_address_1, $q_address_2, $q_suburb, $q_postcode, $q_email, $q_phone, $q_mobile, $c_ref);
$stmt_3->fetch();
$stmt_3->close();

$address = $q_address_1 . ' ' . $q_address_2 . ' ' . $q_suburb . ' ' . $q_postcode;

list($order_number, $sidemark) = array_pad(explode("|", $c_ref), 2, null);

// $query_3 = "SELECT q_name_1, q_name_2, q_address_1, q_address_2, q_suburb, q_postcode, q_email, q_phone, q_mobile, c_ref FROM quotes WHERE quote_no = ?";

// $stmt_3 = $mysqli->prepare($query_3);
// $stmt_3->bind_param('s', $cid);
// $stmt_3->execute();
// $stmt_3->bind_result($q_name_1, $q_name_2, $q_address_1, $q_address_2, $q_suburb, $q_postcode, $q_email, $q_phone, $q_mobile, $c_ref);
// $stmt_3->fetch();
// $stmt_3->close();


$query_4 = "SELECT job_no FROM jobs WHERE quote_no = ?";

$stmt_4 = $mysqli->prepare($query_4);
$stmt_4->bind_param('s', $cid);
$stmt_4->execute();
$stmt_4->bind_result($job_no);
$stmt_4->fetch();
$stmt_4->close();

$query_5 = "SELECT staff_id FROM quote_assign WHERE quote_no = ?";

$stmt_5 = $mysqli->prepare($query_5);
$stmt_5->bind_param('s', $cid);
$stmt_5->execute();
$stmt_5->bind_result($assinged_staff_id);
$stmt_5->fetch();
$stmt_5->close();

$mysqli->close();

include('/var/www/vhosts/blinq.com.au/control.blinq.com.au/scripts/connect.php');

$query_1xx = "SELECT first_name, last_name FROM blinq_users WHERE id = ?";

$stmt_1xx = $mysqli->prepare($query_1xx);
$stmt_1xx->bind_param('i', $assinged_staff_id);
$stmt_1xx->execute();
$stmt_1xx->bind_result($user_first_name_x, $user_last_name_x);
$stmt_1xx->fetch();
$stmt_1xx->close();
$mysqli->close();

$assinged_staff_name = $user_first_name_x . ' ' . $user_last_name_x;



$qrcode = 'https://chart.apis.google.com/chart?cht=qr&chs=150x150&chld=L|0&chl=' . $cid;

require_once('/var/www/vhosts/blinq.com.au/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {

        global $cid;
        global $customer_no;
        global $calculation_name;
        global $today_date;
        global $q_name_1;
        global $q_name_2;
        global $c_ref;
        global $sidemark;
        global $job_no;
        global $assinged_staff_name;
        global $qrcode;
        global $address;
        global $q_phone;
        global $product_code_id;

        $html = '<table border="1">'
                . '<tr>'
                . '<td style="font-size: 0.8em; vertical-align: middle" >'
                . '&nbsp;<img src="../profile/logo-1.jpg" style="height: 45px; width: auto; vertical-align: middle">'
                . '</td>'
                . '<td style="font-size: 1.1em; text-align: center;">'
                . '<strong></strong><br>'
                . '<strong>' . $calculation_name . ' - Order Sheet</strong>'
                . ''
                . '</td>'
                . '<td style="font-size:0.8em; text-align: center;">'
                . '<strong></strong><br>'
                . '<strong>Name : ' . $q_name_2 . '</strong><br>'
                . '<strong>Phone #: ' . $q_phone . '</strong><br>'
                . '</td>'
                . '<td style="font-size: 0.8em; text-align: center;">'
                . '<strong></strong><br>'
                . '<strong>Address : ' . $address . '</strong>'
                . '<strong></strong>'
                . '</td>'
                . '<td style="font-size: 0.8em; text-align: center;">'
                . '<strong></strong><br>'
                . '<strong>Sidemark : ' . $sidemark . '</strong><br>'
                . '<strong>' . $today_date . '</strong>'
                . '</td>'
                . '<td style="font-size:0.8em; text-align: center;">'
                . '<strong></strong><br>'
                . '<strong>Client #: ' . $customer_no . '</strong><br>'
                . '<strong>Order #: ' . $cid . '</strong><br>'
                . '</td>'
                . '<td style="font-size: 0.8em; text-align: center;">'
                . '<img src="' . $qrcode . '-' . $product_code_id . '&chco=000000" style="height: 50px; width: auto;"> '
                . '</td>'
                // . '<td style="font-size: 0.8em; line-height: 15px; text-align: right;">'
                // . '<strong>Date :</strong> ' . $today_date . '<br>'
                // . '<strong>Customer :</strong>' . $q_name_1 . ' ' . $q_name_2 . '<br>'
                // . '<strong>Ref : </strong> ' . $c_ref . '  <strong> Consultant :</strong> ' . $assinged_staff_name . '<br>'
                // . '<strong>Quote # :</strong> ' . $cid . ' <strong> Job # :</strong> ' . $job_no . '<br>'
                // . '</td>'
                . '</tr>'
                . '</table>';
        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'middle', $autopadding = true);
    }

    // Page footer
    public function Footer() {

        global $business_address_1;
        global $business_address_2;
        global $business_suburb;
        global $business_postcode;
        global $business_email;
        global $business_phone;
        global $business_abn;

        $html = '<p style="color: #CCC; text-align: center;">' . $business_address_1 . ' ' . $business_address_2 . ' ' . $business_suburb . ' ' . $business_postcode . ' | '
                . 'Phone: ' . $business_phone . ' | '
                . 'E-Mail: ' . $business_email . ' | '
                . 'ABN: ' . $business_abn . '</p>';

        $this->writeHTMLCell($w = 0, $h = 0, $x = -7, $y = 190, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'center', $autopadding = true);

        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('Order sheet - ' . $calculation_name . ' - ' . $cid);
$pdf->SetSubject('');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// By THAS
$pdf->SetMargins(5, 25, 5);
$pdf->SetHeaderMargin(8);
$pdf->SetFooterMargin(5);

$pdf->SetPrintHeader(TRUE);
$pdf->SetPrintFooter(TRUE);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 24);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', '', 13);

// add a page
$pdf->AddPage('L', 'A4');

${$calculation_x . '_code'} = $calculation_code;
include_once '../' . $calculation . '/print-order-sheet.php';
$html = '<span style="font-size: 0.8em;">' . ${$calculation_x . '_quote_tables_1'} . '</span><br><br>';
$html .= '<span style="font-size: 0.8em;">' . ${$calculation_x . '_quote_tables'} . '</span><br><br>';

if($calculation_name === 'Ziptrak' || $calculation_name === 'Ziptrak Skins' || $calculation_name === 'Ziptrak®' || $calculation_name === 'Ziptrak® Interior1' || $calculation_name === 'Zipscreen' || $calculation_name === 'Zipscreen®' || $calculation_name === 'Zipscreen Blinds' || $calculation_name === 'Canvas/Mesh Awnings' || $calculation_name === 'Fixed Guide Awnings' || $calculation_name === 'Straight Drop Spring' || $calculation_name === 'Straight Drop Crank' || $calculation_name === 'Awning Recovers' || $calculation_name === 'eZip Blinds' || $calculation_name === 'Slidetrack Blinds' || $calculation_name === 'Ezip' || $calculation_name === 'eZip Blinds' || $calculation_name === 'Zipscreen ®'){

    $html .= '<table border="0" cellspacing="0" cellpadding="5" style="font-size:0.7em;">'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center; color:#fff; background-color: #000000;"><strong>TRACK & CHANNEL READY</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center; color:#fff; background-color: #000000;"><strong>ACCESSORIES DONE</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center; color:#fff; background-color: #000000;"><strong>HOOD DELIVERED</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center; color:#fff; background-color: #000000;"><strong>MESH/PVC DELIVERED</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 315px;">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 1015px; text-align:left;">POWDERCOATERS'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center;">Type'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center;">QTY'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">SIZE'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px; text-align:center;">CUT'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">P/COATERS'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 130px; text-align:center;">ARRIVED'
        . '</td>'
        . '<td style="width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 315px; text-align:center;">EXTRA`s'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 130px; text-align:center;">'
        . '</td>'
        . '<td style="width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:165px; font-size:9px; text-align:center;"><strong>TYPE</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 40px; font-size:9px;"><strong>QTY</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width:55px; font-size:9px;"><strong>IN STOCK</strong>'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 55px; font-size:9px;"><strong>PICKED</strong>'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 130px; text-align:center;">'
        . '</td>'
        . '<td style="width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:165px; font-size:10px;">FACE FIT TRACKS'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 40px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:55px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 55px;">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 130px; text-align:center;">'
        . '</td>'
        . '<td style="width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:165px; font-size:10px;">REMOTE CONTROLS'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 40px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:55px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 55px;">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 130px; text-align:center;">'
        . '</td>'
        . '<td style="width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:165px; font-size:10px;">REMOVABLE POST KITS'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 40px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:55px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 55px;">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 2px solid #000; width: 120px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 80px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 100px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 110px; text-align:center;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 130px; text-align:center;">'
        . '</td>'
        . '<td style="width: 50px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:165px; font-size:10px;">WIND SENSORS'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 40px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width:55px;">'
        . '</td>'
        . '<td style="border: 2px solid #000; width: 55px;">'
        . '</td>'
        . '</tr>'
        . '</table>';
}

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('order-sheet-' . str_replace("-", " ", $calculation_name) . '-' . $cid . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+