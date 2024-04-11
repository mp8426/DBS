<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . './../cPanel/connect.php';

$cid = filter_input(INPUT_GET, 'cid');
//$layout = !isset($_GET['layout']) ? 1 : (int) filter_input(INPUT_GET, 'layout');
$layout = '1';
$today_date = date("l, d F y ");

//$cid_x and $action = Variables from saveQuote.php
if (!empty($cid_x) && !empty($action)) {
    $cid = $cid_x;
    // this from saveQuote.php for save quote as attachement on story for history purpose
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

$queryt_2 = "SELECT q_name_1, q_name_2, q_address_1, q_address_2, q_suburb, q_postcode, q_email, q_phone, q_mobile, q_fax, c_ref, c_hdfu, DATE_FORMAT(created_date,'%d/%m/%Y'), user_id, customer_id FROM quotes WHERE quote_no = ?";

$stmtt_2 = $mysqli->prepare($queryt_2);
$stmtt_2->bind_param('s', $cid);
$stmtt_2->execute();
$stmtt_2->bind_result($q_name_1, $q_name_2, $q_address_1, $q_address_2, $q_suburb, $q_postcode, $q_email, $q_phone, $q_mobile, $q_fax, $c_ref, $c_hdfu, $q_created_date, $user_id, $customer_id);
$stmtt_2->fetch();
$stmtt_2->close();

$quote_address = $q_address_1 . ' ' . $q_address_2;

$query_3 = "SELECT c_contact_name, c_name_1, c_name_2, c_address_1, c_address_2, c_suburb, c_postcode, c_email, c_phone, c_mobile, c_discount FROM customers WHERE id = ?";

$stmt_3 = $mysqli->prepare($query_3);
$stmt_3->bind_param('i', $customer_id);
$stmt_3->execute();
$stmt_3->bind_result($c_contact_name, $c_name_1, $c_name_2, $c_address_1, $c_address_2, $c_suburb, $c_postcode, $c_email, $c_phone, $c_mobile, $c_discount);
$stmt_3->fetch();
$stmt_3->close();

$customer_address = $c_address_1 . ' ' . $c_address_2;


$query_4 = "SELECT job_no FROM jobs WHERE quote_no = ?";

$stmt_4 = $mysqli->prepare($query_4);
$stmt_4->bind_param('s', $cid);
$stmt_4->execute();
$stmt_4->bind_result($job_no);
$stmt_4->fetch();
$stmt_4->close();

$query_5 = "SELECT staff_id, DATE_FORMAT(assign_date,'%d/%m/%Y') FROM quote_assign WHERE quote_no = ?";

$stmt_5 = $mysqli->prepare($query_5);
$stmt_5->bind_param('s', $cid);
$stmt_5->execute();
$stmt_5->bind_result($assinged_staff_id, $assign_date);
$stmt_5->fetch();
$stmt_5->close();

$query_6 = "SELECT instruction FROM quote_istaller_instructions WHERE cid = ?";

$stmt_6 = $mysqli->prepare($query_6);
$stmt_6->bind_param('s', $cid);
$stmt_6->execute();
$stmt_6->bind_result($instruction);
$stmt_6->fetch();
$stmt_6->close();

$query_9 = "INSERT INTO quotation_print (cid, prints) VALUES (?, 1) ON DUPLICATE KEY UPDATE prints = prints+1";

$stmt_9 = $mysqli->prepare($query_9);
$stmt_9->bind_param('s', $cid);
$stmt_9->execute();
$stmt_9->close();

$deposit_details = '<table border="0" cellspacing="0" cellpadding="5"><tr><td style="width: 190; font-weight: bold;">DEPOSIT HISTORY</td></tr></table>';

$query_10 = "SELECT description, amount, DATE_FORMAT(date_time, '%d/%m/%Y') AS date FROM deposit_details WHERE cid = ?";

$stmt_10 = $mysqli->prepare($query_10);
$stmt_10->bind_param('s', $cid);
$stmt_10->execute();
$stmt_10->bind_result($deposit_detail_description, $deposit_detail_amount, $deposit_detail_date);
while ($stmt_10->fetch()) {
    $deposit_details .= '<table border="0" cellspacing="0" cellpadding="5"><tr><td style="width: 140; border-top: 1px solid #000000;">' . $deposit_detail_description . '<br>' . $deposit_detail_date . '</td><td style="width: 50; border-top: 1px solid #000000; text-align: right; line-height: 22px;">' . number_format($deposit_detail_amount, 2) . '</td></tr></table>';
}
$stmt_10->close();

$signature_file = __DIR__ . '/../signatures/' . $cid . '.png';
if (file_exists($signature_file)) {
    $signature = '<img src="' . $signature_file . '" style="height: 75px; width: auto;">';
} else {
    $path = __FILE__;
    $path_var = explode('/', $path);
    $account = $path_var[6];
    $signature = '<br><br><a href="https://clients.blinq.com.au/' . $account . '/signature.php?cid=' . $cid . '&user_id=' . $user_id . '&ref=pdf"><img src="/var/www/vhosts/blinq.com.au/clients.blinq.com.au/images/click-to-sign-btn.png" style="height:50px;"></a>';
}

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

include __DIR__ . '/../connect.php';
include __DIR__ . '/../ticket/functions/get-calculation.php';

$get_calculation_details = get_calculation($conn, $cid);

$main_prices = $get_calculation_details['main_prices'];
$deposit = $get_calculation_details['deposit'];
$total_fitting_charge = $get_calculation_details['total_fitting_charge'];
$calc_acc_price = $get_calculation_details['calc_acc_price'];
$calc_fit_cha_price = $get_calculation_details['calc_fit_cha_price'];
$calc_per_met_price = $get_calculation_details['calc_per_met_price'];
$total_1 = $get_calculation_details['total_1'];
$extras = $get_calculation_details['extras'];
$extras_amount = $get_calculation_details['extras_amount'];
$sub_total = $get_calculation_details['sub_total'];
$tax = $get_calculation_details['tax'];
//$tax_name = $get_calculation_details['tax_name'];
$total_2 = $get_calculation_details['total_2'];
$discount_1 = $get_calculation_details['discount_1'];
$discount_1_val = $get_calculation_details['discount_1_val'];
$discount_2 = $get_calculation_details['discount_2'];
$grand_total = $get_calculation_details['grand_total'];
$balance = $get_calculation_details['balance'];
$calculations = $get_calculation_details['calculations'];
$records = $get_calculation_details['records'];

require_once('/var/www/vhosts/blinq.com.au/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {

        global $cid;
        global $assinged_staff_name;

        global $business_address_1;
        global $business_address_2;
        global $business_suburb;
        global $business_postcode;
        global $business_email;
        global $business_web;
        global $business_phone;


        /*      $html = '<table border="0" cellspacing="6" cellpadding="0" style="font-size: 0.8em; color: #666; line-height: 10px;">'
            . '<tr>'
            . '<td style="width: 160px;">'
            . '<div>'
            . '<img src="' . __DIR__ . '../../profile/logo.jpg" style="height: 48px; width: auto;">'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #000000; background-color: #F2F2F2; text-align: center; width: 280px;">'
            . '<div>'
            . '<div style="font-weight: bold;">' . $business_phone . '</div>'
            . '<div>' . $business_address_1 . ' ' . $business_address_2 . ' ' . $business_postcode . ' ' . $business_suburb . '</div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #000000; background-color: #F2F2F2; text-align: center; width: 240px;">'
            . '<div>'
            . '<div style="font-weight: bold;">' . $business_web . '</div>'
            . '<div>' . $business_email . '</div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #000000; text-align: center; width: 150px;">'
            . '<div>'
            . '<div style="font-weight: bold;">Salesperson</div>'
            . '<div>' . $assinged_staff_name . '</div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #000000; text-align: center; width: 151px;">'
            . '<div>'
            . '<div style="font-weight: bold;">Quotation No</div>'
            . '<div>' . $cid . '</div>'
            . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>'; */

        $html = '<table border="0" cellspacing="6" cellpadding="0" style="font-size: 0.8em; color: #666; line-height: 10px;">'
            . '<tr>'
            . '<td style="width: 160px;">'
            . '<div>'
            . '<img src="' . __DIR__ . '/../profile/logo.jpg" style="height: 55px; width: auto;">'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #fff; background-color: #fff; text-align: left; width: 311px;">'
            . '<div>'
            . '<div style="font-weight: bold;"><span style="color:#ED2225;">Tel: </span><span style="color:#1F7BBD;">' . $business_phone . '</span></div>'
            . '<div><span style="color:#1F7BBD;">' . $business_address_1 . ' ' . $business_address_2 . ' ' . $business_postcode . ' ' . $business_suburb . '</span></div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #fff; background-color: #fff; text-align: left; width: 260px;">'
            . '<div>'
            . '<div style="font-weight: bold;"><span style="color:#ED2225;">Web: </span><span style="color:#1F7BBD;">' . $business_web . '</span></div>'
            . '<div><span style="color:#ED2225; font-weight: bold;">Email: </span><span style="color:#1F7BBD;">' . $business_email . '</span></div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #fff; text-align: left; width: 130px;">'
            . '<div>'
            . '<div style="font-weight: bold; color:#1F7BBD;">Salesperson</div>'
            . '<div><span style="color:#1F7BBD;">' . $assinged_staff_name . '</span></div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 1px solid #fff; text-align: left; width: 120px;">'
            . '<div>'
            . '<div style="font-weight: bold; color:#ED2225;">Quotation No: </div>'
            . '<div style="color:#1F7BBD;">' . $cid . '</div>'
            . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>';

            $html = '<table border="0" cellspacing="0" cellpadding="5">'
                . '<tr>'
                . '<td style="border: 1px solid #f1f1f1; width: 60%;;">'
                . '<img src="' . __DIR__ . '/../profile/logo.jpg" style="height: 100px; width: auto;">'
                . '</td>'
                . '<td style="border: 1px solid #f1f1f1; text-align: right; width: 40%;">'
                . '<b>' . $business_address_1 . '</b><br>'
                . $business_address_2 . ' ' . $business_postcode . ' ' . $business_suburb . '<br>'
                . $business_phone . '<br>'
                . $business_email . '<br>'
                . $business_web . '<br>'
                . ' <b> Quote No :' . $cid . '</b>'
                . '</td>'
                . '</tr>'
                . '</table>';

        $this->writeHTMLCell($w = 0, $h = 10, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'M', $autopadding = FALSE);
    }

    // Page footer
    public function Footer()
    {

        //$this->cropMark(6, 6, 5, 5, 'TL');
        //$this->cropMark(291, 6, 5, 5, 'TR');
        //$this->cropMark(6, 204, 5, 5, 'BL');
        //$this->cropMark(291, 204, 5, 5, 'BR');
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
$pdf->SetTitle('Office Copy - ' . $cid);
$pdf->SetSubject('');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// By THAS
$pdf->SetMargins(5, 36, 5);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(5);

$pdf->SetPrintHeader(TRUE);
$pdf->SetPrintFooter(TRUE);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_FOOTER);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage('P', 'A4');

$html = '<table border="0" cellspacing="6" cellpadding="0">'
    . '<tr>'
    . '<td>'
    . '<table cellspacing="0" cellpadding="3">'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 120px; text-align: center; line-height: 12px;"><strong>Date:</strong><br>' . $q_created_date . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 120px; text-align: center; line-height: 12px;"><strong>Appointment:</strong><br>' . $assign_date . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 150px; text-align: center; line-height: 12px;"><strong>Lead Source:</strong><br>' . $c_hdfu . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 150px; text-align: left;">Office Use:</td>'
    . '<td style="font-size: 1.0em; border: 1px solid #000000; width: 165px; text-align: center; line-height: 22px;"><strong>OFFICE COPY</strong></td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;"><strong>Customer Details:</strong> ' . $c_contact_name . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;"><strong>Location Details:</strong> ' . $q_name_1 . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 165px; text-align: left;" rowspan="6"><strong>Records:</strong><br><br>' . nl2br($records) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;">Name: ' . $c_name_1 . ' ' . $c_name_2 . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;">Name: ' . $q_name_2 . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;">Address: ' . $customer_address . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;">Address: ' . $quote_address . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Suburb: ' . $c_suburb . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Postcode: ' . $c_postcode . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Suburb: ' . $q_suburb . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Postcode: ' . $q_postcode . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;">Email: ' . $c_email . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 270px; text-align: left;">Email: ' . $q_email . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Phone: ' . $c_phone . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Mobile: ' . $c_mobile . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Phone: ' . $q_phone . '</td>'
    . '<td style="font-size: 0.8em; border: 1px solid #000000; width: 135px; text-align: left;">Mobile: ' . $q_mobile . '</td>'
    . '</tr>'
    . '</table>'
    . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-size: 0.65em;">';

$print_file = $layout == 2 ? 'print-office-copy-2.php' : 'print-office-copy.php';

foreach ($calculations as $calculation) {
    foreach ($calculation as $calculation_x) {
        include_once __DIR__ . '../../' . $calculation_x . '/' . $print_file;
        $html .= ${str_replace('-', '_', $calculation_x) . "_quote_tables"};
    }
}

$html .= '</td>'
    . '</tr>'
    . '</table>';

$html .= '<table cellspacing="6" cellpadding="0" style="font-size: 0.8em;" nobr="true">'
    . '<tr>'
    . '<td>'
    . '<table cellpadding="5" style="border: 1px solid #000000;">'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 300px; text-align: left;" rowspan="7"><strong>Instructions:</strong><br><br>' . nl2br($instruction) . '</td>'
    . '<td style="border: 1px solid #000000; width: 200px; text-align: left; font-size: 0.9em;" rowspan="11">' . $deposit_details . '</td>'
    . '<td style="border-right: 1px solid #000000; text-align: center; width: 205px;"><strong>Payment Method</strong></td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border-right: 1px solid #000000; text-align: center; width: 205px;" colspan="2">' . $bank_account_name . '<br>' . $bank_account_no . '<br>' . $bsb_no . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; text-align: center; background-color: #F2F2F2; width: 205px;"><strong>Quote No. Required with electronic deposits</strong></td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>TOTAL PRICE</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($main_prices, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>ACCESSORIES</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($calc_acc_price, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>EXTRA`S</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($calc_per_met_price + $calc_fit_cha_price + $total_fitting_charge, 2) . '</td>'
    . '</tr>'
    . '<!--tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>SUB TOTAL</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($total_1, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>MARKUP ( ' . $extras . '% )</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($extras_amount, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>TOTAL + MARKUP</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($sub_total, 2) . '</td>'
    . '</tr-->'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>' . $tax_name . '</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($tax, 2) . '</td>'
    . '</tr>'
    . '<!--tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>Package DISCOUNTS (' . $discount_1 . '%)</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($discount_1_val, 2) . '</td>'
    . '</tr-->'
    . '<tr>'
    . '<td style="border-top: 1px solid #000000; border-right: 1px solid #000000; width: 300px; text-align: center; font-size: 0.9em;" rowspan="4"><strong>ACCEPTANCE OF QUOTATION</strong><br>' . $signature . '</td>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em; color:red;"><strong>Package DISCOUNTS</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px; color:red;">' . number_format($discount_1_val + $discount_2, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>CASH, CARD OR EFT PRICE</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($grand_total, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>DEPOSIT</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format($deposit, 2) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="border: 1px solid #000000; width: 125px; font-size: 0.9em;"><strong>BALANCE</strong></td>'
    . '<td style="border-bottom: 1px solid #000000; text-align: right; width: 80px;">' . number_format(round($balance, 2), 2) . '</td>'
    . '</tr>'
    . '</table>'
    . '</td>'
    . '</tr>'
    . '</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
//$action = Variable from saveQuote.php
if ($action === "story_attachement") {
    //$new_attachement_name = Variable from saveQuote.php
    $pdf->Output(__DIR__ . '../../ticket/attachements/' . $new_attachement_name, 'F');
} else {

    if (!isset($_GET['email'])) {
        $pdf->Output('office-copy-' . $cid . '.pdf', 'I');
    } else {
        print $pdf->Output('office-copy-' . $cid . '.pdf', 'S');
    }
}

//============================================================+
// END OF FILE
//============================================================+
