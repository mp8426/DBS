<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$cid = filter_input(INPUT_GET, 'cid');
$layout = !isset($_GET['layout']) ? 1 : (int) filter_input(INPUT_GET, 'layout');
// $layout = '1';
$today_date = date("l, d F y ");

include '../connect.php';
include '../ticket/functions/get-calculation.php';

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
$numcount = $get_calculation_details['numcount'];
$grand_total_defult = $get_calculation_details['grand_total_defult'];

$humm_methad = '';
$cash_methad = '';
if($numcount != 1 || empty($numcount)){
    // $humm_methad = 'color:#ffffff;';
}else{
    // $cash_methad = 'color:#ffffff;';
}

include './../cPanel/connect.php';

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

$deposit_details = '';

$query_10 = "SELECT description, amount, DATE_FORMAT(date_time, '%d/%m/%Y') AS date FROM deposit_details WHERE cid = ?";

$stmt_10 = $mysqli->prepare($query_10);
$stmt_10->bind_param('s', $cid);
$stmt_10->execute();
$stmt_10->bind_result($deposit_detail_description, $deposit_detail_amount, $deposit_detail_date);
while ($stmt_10->fetch()) {
    $deposit_details .= '<table border="0" cellspacing="0" cellpadding="5"><tr><td style="width: 70%; color:#303030; text-align: left;">' . $deposit_detail_description . ' - ' . $deposit_detail_date . '</td>
    <td style="width: 30%; color:#303030; text-align: right; line-height: 22px;">' . number_format($deposit_detail_amount, 2) . '</td></tr></table>';
}
$stmt_10->close();

$payment_credit_card_text = '';
$deposit_payment_button = '';
$balance_payment_button = '';

$query_11 = "DESCRIBE stripe_payment_profile"; // Check stripe_payment_profile exist or not

$stmt_11 = $mysqli->prepare($query_11);

if ($stmt_11->execute()) {

    $stmt_11->close();

    $query_11_1 = "SELECT stripe_account_id, stripe_account_country, status FROM stripe_payment_profile WHERE 1";

    $stmt_11_1 = $mysqli->prepare($query_11_1);
    $stmt_11_1->execute();
    $stmt_11_1->bind_result($stripe_account_id, $stripe_account_country, $payment_status);
    $stmt_11_1->store_result();
    $stmt_11_1->fetch();

    if ($stmt_11_1->num_rows === 1 && !empty($stripe_account_id) && !empty($stripe_account_country) && $payment_status === 1) {

        $payment_credit_card_text = '<strong>We charge the below percentage to accept a credit or debit card payment.<br>1.9% for domestic cards / 2.75% for all other cards.</strong><br><img src="https://www.blinq.com.au/payment/img/card-logos.png" width="140">';

        // Getting clients account folder
        $path = __FILE__;
        $path_var = explode('/', $path);
        $account = $path_var[6];

        if ($deposit !== '0') {
            $deposit_payment_button = '<a href="https://www.blinq.com.au/payment/' . $account . '/' . $cid . '/' . $stripe_account_id . '/' . $stripe_account_country . '/deposit/' . $deposit * 100 . '/"><img src="/var/www/vhosts/blinq.com.au/clients.blinq.com.au/images/stripe-pay-button.png"></a>';
        }
        if ($balance !== '0') {
            $balance_payment_button = '<a href="https://www.blinq.com.au/payment/' . $account . '/' . $cid . '/' . $stripe_account_id . '/' . $stripe_account_country . '/balance/' . $balance * 100 . '/"><img src="/var/www/vhosts/blinq.com.au/clients.blinq.com.au/images/stripe-pay-button.png"></a>';
        }
    }

    $stmt_11_1->close();
} else {
    $stmt_11->close();
}

$signature_file = __DIR__ . '/../signatures/' . $cid . '.png';
if (file_exists($signature_file)) {
    $signature = '<img src="' . $signature_file . '" style="height: 75px; width: auto;">';
} else {
    $path = __FILE__;
    $path_var = explode('/', $path);
    $account = $path_var[6];
    $signature = '<a href="https://clients.blinq.com.au/' . $account . '/signature.php?cid=' . $cid . '&user_id=' . $user_id . '&ref=pdf"><img src="/var/www/vhosts/blinq.com.au/clients.blinq.com.au/images/click-to-sign-btn.png" style="height:50px;"></a>';
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



// $print_file = $layout == 2 ? 'print-customer-copy-2.php' : 'print-customer-copy.php';
switch ($layout) {
    case 2:
      $print_file = 'print-customer-copy-2.php';
      break;
    case 3:
      $print_file = 'print-customer-copy-3.php';
      break;
    default:
      $print_file = 'print-customer-copy.php';
  }
  
  
  
  $price_list = array();

  
  $html2 = '';

  foreach ($calculations as $calculation) {
      foreach ($calculation as $calculation_x) {
          include_once '../' . $calculation_x . '/' . $print_file;
          $html2 .= ${str_replace('-', '_', $calculation_x) . "_quote_tables"};
      }
  }


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

        // Add background image
        $background_path = 'quote_template/Background_vert.jpg';
   /*     $html = '<table border="0" cellspacing="6" cellpadding="0" style="font-size: 0.9em; color: #666; line-height: 10px;">'
            . '<tr>'
            . '<td style="width: 160px;">'
            . '<div>'
            . '<img src="../profile/logo.jpg" style="height: 48px; width: auto;">'
            . '</div>'
            . '</td>'
            . '<td style="border: 0.5px solid #787877; background-color: #F2F2F2; text-align: center; width: 280px;">'
            . '<div>'
            . '<div style="font-weight: bold;">' . $business_phone . '</div>'
            . '<div>' . $business_address_1 . ' ' . $business_address_2 . ' ' . $business_postcode . ' ' . $business_suburb . '</div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 0.5px solid #787877; background-color: #F2F2F2; text-align: center; width: 240px;">'
            . '<div>'
            . '<div style="font-weight: bold;">' . $business_web . '</div>'
            . '<div>' . $business_email . '</div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 0.5px solid #787877; text-align: center; width: 150px;">'
            . '<div>'
            . '<div style="font-weight: bold;">Salesperson</div>'
            . '<div>' . $assinged_staff_name . '</div>'
            . '</div>'
            . '</td>'
            . '<td style="border: 0.5px solid #787877; text-align: center; width: 151px;">'
            . '<div>'
            . '<div style="font-weight: bold;">Quotation No</div>'
            . '<div>' . $cid . '</div>'
            . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>';*/

    // get the current page break margin
    $bMargin = $this->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $this->AutoPageBreak;
    // disable auto-page-break
    $this->SetAutoPageBreak(false, 0);
    // set bacground image
    $this->Image($background_path, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $this->SetAutoPageBreak($auto_page_break, $bMargin);



    //   with icons
    $html = '<table border="0" cellspacing="6" cellpadding="0" style="font-size: 0.9em; color: #303030; line-height: 8px;">'
    . '<tr>'
    .'<td style="width:1%;"></td>'
    . '<td style="width: 57%; vertical-align: top;">'
        . '<div style=" text-align: center; vertical-align: middle; padding: 10px; line-height:30px;">'  . '<img src="quote_template/logo.png" style="width: auto;">'
        . '</div>'
    . '</td>'
    . '<td style="width:4%;"></td>'
    . '<td style=" width: 37%;">'
        . '<div style="padding: 8px;">'
        . '<div style="line-height:13px; font-size:13px;"><strong>' . $business_address_1 . '</strong> <br>' . $business_address_2 . ' ' . $business_postcode . ' ' . $business_suburb . '</div>'
        . '<div style="line-height:11px;  font-size:13px;">' . $business_phone . '</div>'
        . '<div style="line-height:11px;  font-size:13px;">' . $business_email . '</div>'
        . '<div style="line-height:11px;  font-size:13px;">' . $business_web . '</div>'
        .'<div style="font-size:16px; text-align: center; vertical-align: middle; ">'
            . '<div style="font-weight: 6px; font-size:14px; text-align: center; vertical-align: middle; padding: 10px;"><strong>Quote No</strong> : '.$cid.'</div>'
        .'</div>'
        . '</div>'
    . '</td>'
    . '<td style="width:1%;"></td>'
    . '</tr>'
    . '</table>';

    $this->SetFillColor(229, 229, 229); // RGB values for #e5e5e5
    $this->RoundedRect(124, 37.9, 77, 8.5, 2, '#e5e5e5', 'F');

    $this->Image('quote_template/location.png', 124, 10, 3.4, 3.4, '', '', '', false, 300, '', false, false, 1, false, false, false);
    $this->Image('quote_template/phone.png', 124, 20, 3.4, 3.4, '', '', '', false, 300, '', false, false, 1, false, false, false);
    $this->Image('quote_template/mail.png', 124, 26.1, 3.4, 3.4, '', '', '', false, 300, '', false, false, 1, false, false, false);
    $this->Image('quote_template/web.png', 124, 31.7, 3.4, 3.4, '', '', '', false, 300, '', false, false, 1, false, false, false);

				
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
$pdf->SetTitle('Customer Copy - ' . $cid);
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
$pdf->SetMargins(5, 58, 5);
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

    $test_count = 0; 
    
    $quote_summary = '<table cellspacing="0" cellpadding="3" style="color: #525252; width:100%;">'
    .'<tr>'
    . '<td style="font-size: 1em; border-bottom: 0.5px solid #616060; color: #277cbe; width: 50%; text-align: left;"><strong>Quote Summary:</strong></td>'
    . '<td style="font-size: 1em; border-bottom: 0.5px solid #616060; color:#303030; width: 25%; text-align: center;"><strong>Quantity:</strong></td>'
    . '<td style="font-size: 1em; border-bottom: 0.5px solid #616060; color:#303030; width: 25%; text-align: center;"><strong>Price:</strong></td>'
    .'</tr>';

    foreach ($price_list as $key) {
        
            $quote_summary .='<tr>'
            .'<td style="font-size: 1em; border-top: 0.5px solid #616060; border-bottom: 0.5px solid #616060; color:#303030; width: 50%; text-align: left;"><strong>' . $key['p_name'] . '</strong></td>'
            .'<td style="font-size: 1em; border-top: 0.5px solid #616060; border-bottom: 0.5px solid #616060; color:#303030; width:25%; text-align: center;">' . $key['quantity'] . '</td>'
            .'<td style="font-size: 1em; border-top: 0.5px solid #616060; border-bottom: 0.5px solid #616060; color:#303030; width: 25%; text-align: center;">' . $key['price'] . '</td>'
            .'</tr>';
    }
    
    // Add the specific record after all items are printed
    $quote_summary .= '
    <tr>
        <td style="font-size: 1em; border-top: 0.5px solid #616060; color: #277cbe; width: 50%; text-align: left;"><strong>Total Package Price</strong></td>
        <td style="font-size: 1em; border-top: 0.5px solid #616060; color:#303030; width: 25%; text-align: center;"><strong></strong></td>
        <td style="font-size: 1em; border-top: 0.5px solid #616060; color:#303030; width: 25%; text-align: center;"><strong>' . number_format($grand_total, 2) . '</strong></td>
    </tr>';
    $quote_summary  .='</table>';



    $html = '<table border="0" cellspacing="6" cellpadding="0" style="color: #525252;">'
        . '<tr>'
        . '<td>'
        . '<table cellspacing="0" cellpadding="3">'
        . '<tr>'
        . '<td style="font-size: 0.8em; border-bottom: 0.5px solid #616060; background-color:#eaeaea; color:#303030; margin-top:5px; width: 17.5%; text-align: center; line-height: 12px; height: 18px;"><strong>Date:</strong><br>' . $q_created_date . '</td>'
        . '<td style="font-size: 0.8em; border-bottom: 0.5px solid #616060; background-color:#eaeaea; color:#303030; margin-top:5px; width: 17.5%; text-align: center; line-height: 12px; height: 18px;"><strong>Appointment:</strong><br>' . $assign_date . '</td>'
        . '<td style="font-size: 0.8em; border-bottom: 0.5px solid #616060; background-color:#eaeaea; color:#303030; margin-top:5px; width: 17.5%; text-align: center; line-height: 12px; height: 18px;"><strong>Lead Source:</strong><br>' . $c_hdfu . '</td>'
        . '<td style="font-size: 0.8em; border-bottom: 0.5px solid #616060; background-color:#eaeaea; color:#303030; margin-top:5px; width: 17.5%; text-align: left; line-height: 12px; height: 18px;"><strong>Office Use:</strong></td>'
        . '<td style="font-size: 1.0em; font-weight: normal;  width: 30.5%; text-align: center; line-height: 22px; color:white; background-color:#2d82c4; ">CUSTOMER COPY</td>'
        . '</tr>'
        
        . '<tr>'
        . '<td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 35%; text-align: left;"><strong>Customer Details:</strong> ' . $c_contact_name . '</td>'
        // . '<td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 350px; text-align: left;"><strong>Location Details:</strong> ' . $q_name_1 . '</td>'
        //. '<td style="font-size: 0.8em; border: 0.5px solid #616060; color: #277cbe; width: 150px; text-align: left;"><strong>Quote Summary:</strong></td>'
        //. '<td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 100px; text-align: left;"><strong>Quantity:</strong></td>'
        //. '<td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 100px; text-align: left;"><strong>Price:</strong></td>'
        
        . '<td style="padding: 0; font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 35%; text-align: left;" rowspan="6">' . $quote_summary . '</td>'

        . '<td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 30.5%; text-align: left;" rowspan="6"><strong>Records:</strong><br><br>' . nl2br($records) . '</td>'
        . '</tr>';

        
        
        

        $html .='<tr>
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 35%; text-align: left;"><strong>Name:</strong> ' . $c_name_1 . ' ' . $c_name_2 . '</td>
                </tr>'.
                '<tr>
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 35%; text-align: left;"><strong>Address:</strong> ' . $customer_address . '</td> 
                </tr>'.
                '<tr>
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 17.5%; text-align: left;"><strong>Suburb:</strong> ' . $c_suburb . '</td>
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 17.5%; text-align: left;">Postcode: ' . $c_postcode . '</td>
                </tr>'.
                '<tr>
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 35%; text-align: left;"><strong>Email:</strong> ' . $c_email . '</td>
                </tr>
                <tr> 
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 17.5%; text-align: left;"><strong>Phone:</strong> ' . $c_phone . '</td>
                    <td style="font-size: 0.8em; border: 0.5px solid #616060; color:#303030; width: 17.5%; text-align: left;">Mobile: ' . $c_mobile . '</td>
                </tr>';
        
                


$html.=
        '</table>'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 0.8em;">';



$html .= $html2;

$html .= '</td>'
    . '</tr>'
    . '</table>';


    $rowspan = 6;
    $ex_tr = '';
    
    if (!empty($calc_acc_price)) {
    
        $ex_tr .= '<tr>'
            . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>ACCESSORIES</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($calc_acc_price, 2) . '</td>'
            . '</tr>';
    
        $rowspan = $rowspan + 2;
    }
    
    if (!empty($calc_per_met_price)) {
    
        $ex_tr .= '<tr>'
            . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>PER METERS</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($calc_per_met_price, 2) . '</td>'
            . '</tr>';
    
        $rowspan++;
    }
    
    if (!empty($calc_fit_cha_price) || !empty($total_fitting_charge)) {
    
        $ex_tr .= '<tr>'
            . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>FITTING CHARGE</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($calc_fit_cha_price + $total_fitting_charge, 2) . '</td>'
            . '</tr>';
    
        $rowspan++;
    }
    
    if (!empty($extra_services_rows)) {
    
        $ex_tr .= $extra_services_rows;
    
        $rowspan += $extra_services_rows_count;
    }
    
    if (!empty($ex_tr)) {
    
        $ex_tr .= '<tr>'
            . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>SUB TOTAL</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($sub_total, 2) . '</td>'
            . '</tr>';
    
        $rowspan++;
    }
    
    $ex_tr_2 = '';
    
    if (!empty($discount_1_val)) {
    
        $ex_tr_2 .= '<tr>'
            . '<td style="border: 0.5px solid #616060; color: #277cbe width: 19.5%;"><strong>DISCOUNT (' . $discount_1 . '%)</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($discount_1_val, 2) . '</td>'
            . '</tr>';
    
        $rowspan++;
    }
    
    if (!empty($discount_2)) {
    
        $ex_tr_2 .= '<tr>'
            . '<td style="border: 0.5px solid #616060; color: #277cbe; width: 19.5%;"><strong>DISCOUNT ( ' . $currency_sign . ' )</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($discount_2, 2) . '</td>'
            . '</tr>';
    
        $rowspan++;
    }
    
    $bottom_rowspan = 0;
    $rowspan_reduced = $rowspan-3;
    $rowspan_increased = $rowspan+1;
    
    $deposit_tr = '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%; font-size: 15px;"><br><br><strong>BALANCE</strong></td>'
        . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%; font-size: 15px;"><br><br><strong>' . number_format(round($balance, 2), 2) . '</strong></td>';
    
    $balance_tr = '';
    
    if (!empty($deposit)) {
    
        $bottom_rowspan = 'rowspan="2"';
    
        $deposit_tr = '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>DEPOSIT</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($deposit, 2) . '</td>';
    
        $balance_tr = '<tr>'
            . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%; font-size: 15px;"><br><br><strong>BALANCE</strong></td>'
            . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%; font-size: 15px;"><br><br><strong>' . number_format(round($balance, 2), 2) . '</strong></td>'
            . '</tr>';
    }





    $html .= '<table cellspacing="6" cellpadding="0" style="font-size: 0.8em;" nobr="true">'
    . '<tr>'
    . '<td>'
    . '<table cellpadding="5" style="border: 0.5px solid #616060; color:#303030;">'
    . '<tr>'
    // . '<td style="border: 0.5px solid #616060; color:#303030; width: 500px; text-align: left;" rowspan="' . $rowspan . '"><br><br>' . nl2br($instruction) . '</td>'
    . '<td style="border: 0.5px solid #616060; color:#303030; width: 50%; text-align: left;" rowspan="' . $rowspan_reduced . '"><br><br>' . $signature . '</td>'
    .'<td style="border: 0.5px solid #616060; color:#303030; width: 20%; text-align: left; font-weight: bold;"> DEPOSIT HISTORY </td>'
    . '<td style="border-right: 0.5px solid #787877; border-bottom: 0.5px solid #787877; text-align: center; width: 30%;"><strong>Payment Method</strong></td>'
    . '</tr>'

    . '<tr>'
    . '<td style="border: 0.5px solid #616060; color:#303030; width: 20%; text-align: center; font-size: 0.9em;" rowspan="' . $rowspan_increased . '">' . $payment_credit_card_text . '<br>' . $deposit_details . '</td>'
    . '<td style="border-right: 0.5px solid #787877; text-align: center; width: 30%;" colspan="2">' . $bank_account_name . '<br>' . $bank_account_no . '<br>' . $bsb_no . '</td>'
    . '</tr>'
    
    . '<tr>'
    . '<td style="border: 0.5px solid #616060; color:#303030; text-align: center; width: 30%;"><strong>Quote No. Required with electronic deposits</strong></td>'
    . '</tr>'
    
    . '<tr>'
    . '<td style="border: 0.5px solid #616060; width: 19.5%;"><strong>TOTAL PRICE</strong></td>'
    . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' .number_format($total_1, 2) . '</td>'
    . '</tr>'
    . $ex_tr
    // . '<tr>'
    // . '<td style="border: 0.5px solid #616060; color:#303030; width: 115px;"><strong>MARKUP ( ' . $extras . '% )</strong></td>'
    // . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 190px;">' . number_format($extras_amount, 2) . '</td>'
    // . '</tr>'
    // . '<tr>'
    // . '<td style="border: 0.5px solid #616060; color:#303030; width: 115px;"><strong>TOTAL + MARKUP</strong></td>'
    // . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 190px;">' . number_format($sub_total, 2) . '</td>'
    // . '</tr>'
    . '<tr>'
    . '<td style="border-top: 1px solid #616060; border-right: 0.5px solid #787877; width: 50%; text-align: center; line-height:18px;" rowspan="4"><strong>THAT BY ACCEPTING THE QUOTE YOU HAVE READ OUR TERMS & CONDITIONS </strong> <br> </td>'
    . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>' . $tax_name . '</strong></td>'
    . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($tax, 2) . '</td>'
    . '</tr>'
    // . $ex_tr_2
    . '<tr>'
    . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>Package DISCOUNTS</strong></td>'
    . '<td style="border-bottom: 0.5px solid #787877; color: #277cbe; text-align: right; width: 11.0%;">' . number_format($grand_total, 2) . '</td>'
    . '</tr>'

    . '<tr>'
    . '<td style="border: 0.5px solid #616060; color:#303030 width: 155px;"><strong><img src="../profile/humm-logo.jpg" style="height: 15px; width: auto;"></strong></td>'
    . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%; ' . $humm_methad . '"><strong>' . number_format($grand_total_defult + ($grand_total_defult * 0.15), 2) . '</strong></td>'
    . '</tr>'

    . '<tr>'
    . '<td style="border: 0.5px solid #616060; color:#303030; width: 19.5%;"><strong>CASH, CARD OR EFT PRICE</strong></td>'
    . '<td style="border-bottom: 0.5px solid #787877; text-align: right; width: 11.0%;">' . number_format($grand_total, 2) . '</td>'
    . '</tr>'
    
    . '</table>'
    . '</td>'
    . '</tr>'
    . '</table>';








    $html .= '<table cellspacing="0" cellpadding="0" style="font-size: 0.67em;" nobr="true">'
    . '<tr>'
    . '<td style="width:50%;" align="left">'
    . '<table cellpadding="1" style="border: 0.5px solid #fff;">'
    . '<tr>'
    . '<td style="width:15%; font-weight: bold;" >1. Definitions</td>'
    . '<td style="text-align: right; width: 85%;"></td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>“Seller” shall mean Doors Blinds and Shutters Pty its successors and assigns or any person acting on behalf of
        and without authority Doors Blinds and Shutters Australia Pty Ltd</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>“Client” shall mean the client or any person acting on behalf of and with the authority of the Client.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>“Guarantor” means that person (or persons), or entity, who agrees to be liable for the debit of the Client on a	principal debtor basis.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >IV.</td>'
    . '<td>“Goods” shall mean goods supplied by the Seller to the Client (and where the context so permits shall include
        any supply of services as hereinafter defined) and are as described on the invoices, quotations, work
        authorisation or any other forms supplied by the Seller to the Client.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >V.</td>'
    . '<td>“Services” shall mean all services supplied by the seller to the client and includes any advice or
        recommendations (and where the context so permits shall include any supply of Goods as defined above)</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;">VI.</td>'
    . '<td>“Price” shall mean the cost of the Goods as agreed between the Seller and the Client subject to clause 3 of the contract.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">2. Acceptance</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>Any instructions received by the Seller from the Client for the supply of Goods and/or the Client’s acceptance of
            Goods supplied by the Seller shall constitute acceptance of the terms and conditions contained herein.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>Where more than one Client has entered into the agreement, the Clients shall by jointly and severally liable for
        all payments of the Price.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>The Client undertakes to give the Seller at least fourteen (14) days notice of any changes in the Client’s name,
address and/or any other change in the Client’s details.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >IV.</td>'
    . '<td>The Client undertakes to give the Seller at least fourteen (14) days notice of any changes in the Client’s name,
address and/or any other change in the Client’s details.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">3. Price and Payment</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The Price shall be indicated on invoices provided by the Seller to the Client in respect of Goods supplied.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>At the Seller’s sole discretion, a deposit to 30% of the Price may be required..</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>Time for Payment for the Goods shall be of the essence and will be stated on the invoice or any other forms. If
no time is stated, then payment shall be due seven (7) days following the date of invoice.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >IV.</td>'
    . '<td>At the Seller’s sole discretion final Payment shall be due on delivery of the Goods</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >V.</td>'
    . '<td>Payment will be made by cash, or by credit card, or by debit card, or by any other method as agreed to by the Client and the Seller.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >VI.</td>'
    . '<td>GST and other taxes and duties that may be applicable shall be added to the Price except when they are expressly included in the Price.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">4. Delivery of Goods</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The Goods shall be delivered at the Seller’s cost to the Client’s address. The Client shall make all arrangements
        necessary to take delivery of the Goods whenever they are tendered for delivery.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>The Goods shall be delivered to the Client’s nominated carrier. The carrier shall be deemed to be the Client’s
agent.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>Delivery of the Goods to a third party nominated by the Client is deemed to be delivery to the Client for the
purposes of the agreement.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >IV.</td>'
    . '<td>The Goods shall be delivered at the Client’s cost to either the Client’s nominated address or the Client’s
nominated carrier. The Client’s nominated carrier shall be deemed to be the Client’s agent. The Client shall
make all arrangements necessary to take delivery of the Goods whenever they are tendered for delivery.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >V.</td>'
    . '<td>The Seller may deliver the Goods by separate instalments. Each separate instalment shall be invoiced and paid
for in accordance with the provisions in these terms and conditions.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >VI.</td>'
    . '<td>The Client shall take delivery of the Goods tendered not withstanding that the quantity so delivered be either
greater or less than the quantity purchased provided that;</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. Such discrepancy in quantity shall not exceed 5%, and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. The Price shall be adjusted pro rata to the discrepancy.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >VII.</td>'
    . '<td>The Failure of the Seller to deliver shall not entitle either party to treat this contract as repudiated.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >VIII.</td>'
    . '<td>The Seller shall not be liable for any loss of damage whatever due to failure by the Seller to deliver the Goods (or any of them) promptly at all.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">5. Risk</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>If the Seller retains ownership of the Goods nonetheless, all the risk for the Goods passes to the Client for delivery.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>If any of the Goods are damaged or destroyed following delivery but prior to ownership passing to the Client,
the Seller is entitled to receive all insurance proceeds payable for the Goods. The production of these terms and
conditions by the Seller is sufficient evidence of the Seller’s rights to receive the insurance proceeds without
the need for any person dealing with the Seller to make further enquiries.</td>'
    . '</tr>'
    . '<tr>'
    . '<td  style="font-weight: bold;"  colspan="2">6. Title</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>It is the intention of the Seller and agreed by the Client that the ownership of the Goods shall not pass until:</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>	I. The Client has paid all amounts owing for the particular Goods, and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>	II. The Client has met all other obligations due by the Client to the Seller in respect of all contracts between the Seller and the Client.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>Receipt by the Seller of any form of payment other than cash shall not be deemed to be payment until that
        form of payment has been honoured, cleared or recognized and until then the Seller’s ownership or rights in
        respect to the Goods shall continue.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>v. The Client is only a bailee of the Goods and until such time as the Seller has received
payment in full for the Goods, then the Client shall hold any proceeds from the sale for
disposal of the Goods or trust for the Seller: and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. Where practicable the Goods shall be kept separate and indefinable until the Seller shall
have received payment and all other obligations of the Client are met; and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. Until such time as ownership of the Goods shall pass from the Seller to the Client, the Seller
may give notice in writing to the Client to return the Goods or any of them to the Seller.
Upon such notice, the rights of the Client to obtain ownership or any other interest in the
Goods shall cease; and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iii. The Seller shall have the right of stopping the Goods in transit whether or not delivery has
been made; and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iv. If the Client fails to return the Goods to the Seller then the Seller or the agent may enter
upon and into land and premises owned, occupied or used by the Client, or any premises as
the invitee of the Client, where the goods are situated and take possession of the Goods;
and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>v. The Client is only a bailee of the Goods and until such time as the Seller has received
payment in full for the Goods, then the Client shall hold any proceeds from the sale for
disposal of the Goods or trust for the Seller: and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>vi. The Client shall not deal with the money of the Seller in any way which maybe adverse to
the Seller: and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>vii. The Client shall not charge the Goods in any way, nor grant, nor otherwise give any interest
in the Goods while they remain the property of the Seller: and</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>viii. The Seller can issue proceedings to recover the Price of the Goods;</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ix. Until such time that ownership in the Goods passes to the Client, if the Goods are converted
into other products, the parties agree that the Seller will be the owner of the products.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">7. Client’s Disclaimer</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The Client hereby disclaims any right to rescind, or cancel the contract or to sue for damages or to claim
restitution arising out of any misrepresentation made so the Client by the Seller and the Client acknowledges
that the Goods are bought retrying solely upon the Client’s skill and judgement.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">8. Defects</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The Client shall inspect the Goods on delivery and shall within seven (7) days notify the Seller of any alleged
defect, shortage of quantity, damage or failure to comply with the description or quote. The Client shall afford
the Seller an opportunity to inspect the goods within a reasonable time following delivery. If the Client believes
the Goods are defective in any way. If the Client shall fail to comply with these provisions, the Goods shall be
presumed to be free of any defect or damage. For defective Goods, which the Seller has agreed in writing that
the Client is entitled to reject the Seller’s liability is limited to either (at the Seller’s Discretion) replacing the
Goods or repairing the Goods.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">9. Warranty</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>For Goods not manufactured by the Seller, the warranty shall be the current warranty provided by the
manufacturer of the Goods. The Seller shall not be bound by, nor responsible for any term, condition,
representation or warranty given by the manufacturer of the Goods.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: left; font-weight: bold;" colspan="2">10. The Commonwealth Trade Practises Act 1974 and Fair Trading Acts</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>Nothing in this agreement is intended to have the effect of contracting out any applicable provisions of the
Commonwealth Trade Practises Act 1974 or the Fair Trading Acts in each of the States and Territories of
Australia, except to the extent by those Acts where applicable.</td>'
    . '</tr>'
    . '</table>'
    . '</td>'
    . '<td style="width:50%;">'
    . '<table cellpadding="1" style="border: 0.5px solid #fff;">'
    . '<tr>'
    . '<td style="text-align: left;font-weight: bold;" colspan="2">11. Intellectual Property</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right; width:15%;" >I.</td>'
    . '<td style="width:85%;">Where the Seller had designed, drawn or written Goods for the Client, then the copyright in those designs and
drawings shall remain vested in the Seller and shall only be used by the Client at the Seller’s discretion.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>The Client warrants that all designs or instructions to the Seller will not cause the Seller to infringe any patent,
registered design or trademark in the execution or the Client’s order.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: left; font-weight: bold;" colspan="2">12. Default & Consequence of default</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right; width:15%;">I.</td>'
    . '<td style="width:85%;">Interest on overdue invoices shall accrue from the date when payment becomes due daily until the date of
        payment at a rate of 2.5% per calendar month and such interest shall compound monthly at such a rate after as
        well as before any judgement.</td>'
    . '</tr>'		
    . '<tr>'
    . '<td style="text-align: right;">II.</td>'
    . '<td>If the Client defaults in payment of any invoice when due, the Client shall indemnify the Seller from and against
        or cost and disbursements incurred by the Seller in perusing the debt, including legal costs on a solicitor and
        own client basis and the Seller’s collection agency costs.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;">III.</td>'
    . '<td>Without prejudice to any other remedies, the Seller may have, if at any time the Client is in breach of any
        obligation (including those related to payment), the Seller may suspend or terminate the supply of goods to the
        Client and any of its obligations under the terms and conditions. The Seller will not be liable to the Client for
        any loss or damage the Client suffers because the Seller exercised its right under this clause.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;">IV.</td>'
    . '<td>If any account remains overdue after thirty (30) days, then an amount of the greater of $20.00 or 10% of the
            amount overdue (up to a maximum of $200.00) shall be levied for administration fees which sum shall become
            immediately due and payable.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;">V.</td>'
    . '<td>Without prejudice to the Seller’s other remedies at law, the Seller shall be entitled to cancel all or any part of
            any order of the Client which remains unperformed in addition to and without prejudice to any other remedies
            and all amounts owning to the Seller shall, whether or not due for payment, and become immediately payable
            in the event that:</td>'
    . '</tr>'
    . '<tr>'
    . '<td></td>'
    . '<td>i. Any money payable to the Seller becomes overdue, or in the Seller’s opinion, the Client will
        be unable to meet its payments as they fall due; or</td>'
    . '</tr>'
    . '<tr>'
    . '<td></td>'
    . '<td>ii. The Client becomes insolvent, convene a meeting with its creditors or proposes or enters
        into an arrangement with creditors, or makes an assignment for the benefit of its creditors; or</td>'
    . '</tr>'
    . '<tr>'
    . '<td></td>'
    . '<td>iii. A receiver, manager, liquidator (provisional or otherwise) or simular person is appointed in respect of the Client or any asset of the Client.</td>'
    . '</tr>'
    . '<tr>'
    . '<td></td>'
    . '<td>iv. Discounts: All discounts provided are conditional upon payments being made on time. Such
            discount shall be voided and full price of goods be payable by the client in default of
            payment terms.</td>'
    . '</tr>'		
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">13. Security and Charge</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>Despite anything to the contrary contained herein or any other rights which the Seller may have howsoever;</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. where the Client and/or the Guarantor (if any) is the Owner of land, Reality or any other
        asset capable of being charged, both the Client and/or the Guarantor agree to mortgage
        and/or charge all of the joint and/or several interest in the said land, realty or any other
        asset to the Seller or the Seller’s nominee to secure all amounts and other monetary
        obligations payable under the terms and conditions. The Client and/or the Guarantor
        acknowledge and agree that the Seller (or the Seller’s Nominee) shall be entitled to lodge
        where appropriate a caveat, which caveat shall be released once all payments and other
        monetary obligations payable hereunder have been met.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. should the Seller elect to proceed in any manor in accordance with this clause and/or its
sub-clauses, the client and/or Guarantor indemnify the Seller from and against all the
Seller’s costs and disbursements including legal costs on a solicitor and own client basis.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iii. the Client and/or the guarantor (if any) agree to irrevocably nominate constitute and
appoint the Seller or the Seller’s nominee as the Client’s and/or Guarantors true and lawful
Attorney to perform all necessary Acts to give effect to the provisions to this clause 13.1</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">14. Cancellation</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The Seller may cancel these terms and conditions or cancel delivery of Goods at any time before the Goods are
        delivered by giving written notice. On giving such notice, the Seller shall repay to the Client any sums paid in
        respect of the price. The Seller shall not be liable for any loss or damage whatever arising from such
        cancellation.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">15. Cancellation & Fees</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The seller may charge a 10% administration fee of total order on the event that no works have been
commenced</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>The seller may charge a 10% administration fee of total order and $100 service fee on the event that a check
measure has been completed.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>The seller may charge the total value of the purchase in the event that the products have been produced before
installation has been appointed.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >IV.</td>'
    . '<td> All cancellation of orders must be by written notice.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">16. Privacy Act 1998</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>The Client and/or the Guarantor/s agree for the Seller to obtain from a credit reporting agency a credit report
        containing personal credit information about the Client and Guarantor/s in relation to credit provided by the
        Seller.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>The Client and/or the guarantor/s agree that the Seller may exchange information about the Client and the
            Guarantor/s with those credit providers, either named as trade references by the Client or named in a
            consumer credit report issued by a credit reporting agency for the following purposes:</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. To assess an application by Client: and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. To notify other credit providers of a default by the client: and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iii. To exchange information with other credit providers as to the status of this credit account,
where the Client is in default with other credit providers: and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iv. To assess the credit worthiness or Client and/or Guarantor/s</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >III.</td>'
    . '<td>The Client consents to the Seller being given a consumer credit report to collect overdue payment on
commercial credit (Section 18K (1) (h) Privacy Act 1988).</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >IV.</td>'
    . '<td>The Client agrees that personal credit information provided may be used and retained by the Seller for the
following purposes and for other purposes as shall be agreed between the Client and the Seller or required by
law from time to time:</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. Provision or goods, and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. Marketing of Goods by the Seller as agents or distributors in relation to the Goods: and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iii. Analysing verifying and/or checking the Client’s credit, payment and/or status in relation to
provision of goods, and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iv. Processing of any payment instructions, direct debit facilities and/or credit status in relation
to provision of Goods. And/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>v. Enabling the daily operation of Clients account and/or the collection of amounts
outstanding in the Client’s account in relation to the Goods.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >V.</td>'
    . '<td>The Seller may give information about the Client to a credit reporting agency for the following purposes:</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. to obtain a consumer credit report about the client, and/or</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. allow the credit reporting agency to create or maintain a credit information file containing information about the Client</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">17. Unpaid Sellers Rights</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>Where the Client has left any item with a Seller for repair, modification, exchange or for the Seller to perform
any other service in relation to the item and Seller has not received or been tendered the whole of the price, or
the payment has been dishonoured, the Seller shall have:</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>i. a lien on the item;</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>ii. the right to retain the item for the price while the Seller is in procession of the item;</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" ></td>'
    . '<td>iii. a right to sell the item</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >II.</td>'
    . '<td>The lien of the Seller shall continue despite the commencement of proceeding, or judgement for the price
having been obtained.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="font-weight: bold;"  colspan="2">18. General</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>I. If any provision of these terms and conditions shall be invalid, void, illegal, or unenforceable the validity,
existence, legality and enforceability of the remaining provisions shall not be affected, prejudiced or impaired.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>II. These terms and conditions and any contract to which they apply shall be governed by the laws of Victoria and
subject to the jurisdiction of the courts of Victoria.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>III. The Seller shall be under no liability whatsoever to the Client for any indirect loss and/or expense (including loss
of profit suffered by the Client arising out of a breech by the Seller of these terms and conditions.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>IV. In the event of any breech of this contract by the Seller, the remedies of the Client shall be limited to damages.
Under no circumstances shall the liability of the Seller exceed the price of the Goods.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>V. The Client shall not be entitled to set off against or deduct from the Price any sums owed or claimed to be
owed to the Client by the Seller.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>VI. The Seller may licence or sub-contract all or any part of its rights and obligations without the Client’s consent.</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>VII. The Seller reserves the right to review these terms and conditions at anytime. If following any such review,
there is to be any change to the terms and conditions, then that change will take effect from the date on which</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="text-align: right;" >I.</td>'
    . '<td>VIII. Neither party shall be liable for any default due to any Act of God, war, terrorism, strike, lockout, industrial
action, fire, flood, drought, storm or other event beyond the reasonable control of either party.</td>'
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

if (!isset($_GET['email'])) {
    $pdf->Output('customer-copy-' . $cid . '.pdf', 'I');
} else {
    print $pdf->Output('customer-copy-' . $cid . '.pdf', 'S');
}

//============================================================+
// END OF FILE
//============================================================+
