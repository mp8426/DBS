<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . './../cPanel/connect.php';

$cid = filter_input(INPUT_GET, 'cid');
$layout = !isset($_GET['layout']) ? 1 : (int) filter_input(INPUT_GET, 'layout');
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

$deposit_details = 'DEPOSIT HISTORY<br>';

$query_10 = "SELECT description, amount, DATE_FORMAT(date_time, '%d/%m/%Y') AS date FROM deposit_details WHERE cid = ?";

$stmt_10 = $mysqli->prepare($query_10);
$stmt_10->bind_param('s', $cid);
$stmt_10->execute();
$stmt_10->bind_result($deposit_detail_description, $deposit_detail_amount, $deposit_detail_date);
while ($stmt_10->fetch()) {
    $deposit_details .= $deposit_detail_description . '-' . $deposit_detail_date . '-' . number_format($deposit_detail_amount, 2) . '<br>';
}
$stmt_10->close();

$signature_file = __DIR__ . '/../signatures/' . $cid . '.png';
if (file_exists($signature_file)) {
    $signature = '<img src="' . $signature_file . '" width="100">';
} else {
    $path = __FILE__;
    $path_var = explode('/', $path);
    $account = $path_var[6];
    $signature = '';
    //$signature = '<a href="https://clients.blinq.com.au/' . $account . '/signature.php?cid=' . $cid . '&user_id=' . $user_id . '&ref=pdf"><img src="/var/www/vhosts/blinq.com.au/clients.blinq.com.au/images/click-to-sign-btn.png" style="height:50px;"></a>';
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

$html = '';

$summary = '<table border="1" cellspacing="6" cellpadding="0">'
        . '<tr>'
        . '<td valign="center" align="center" height="50" style="border: 1px solid #000000; width: 50px;">'
        . '<img src="' . __DIR__ . '/../profile/logo.jpg" height="45">'
        . '</td>'
        . '<td valign="center" align="center" style="border: 1px solid #000000; width: 30px; background-color: #F2F2F2;">'
        . $business_phone . '<br>'
        . $business_address_1 . ' ' . $business_address_2 . ' ' . $business_postcode . ' <br>' . $business_suburb
        . '</td>'
        . '<td valign="center" align="center" style="border: 1px solid #000000; width: 30px; background-color: #F2F2F2;">'
        . $business_web . '<br>'
        . $business_email
        . '</td>'
        . '<td valign="center" align="center" style="border: 1px solid #000000; width: 30px;">'
        . 'Salesperson<br>'
        . $assinged_staff_name
        . '</td>'
        . '<td valign="center" align="center" style="border: 1px solid #000000; width: 30px;">'
        . 'Quotation No<br>'
        . '<strong>' . $cid . '</strong>'
        . '</td>'
        . '</tr>'
        . '</table>';

$summary .= '<table>'
        . '<tr>'
        . '<td style="text-align: center;" valign="center" align="center" height="40"><strong>Date:</strong><br>' . $q_created_date . '</td>'
        . '<td style="text-align: center;" valign="center" align="center"><strong>Appointment:</strong><br>' . $assign_date . '</td>'
        . '<td style="text-align: center;" valign="center" align="center"><strong>Lead Source:</strong><br>' . $c_hdfu . '</td>'
        . '<td style="text-align: left; font-size:18px;" valign="center" align="center"><strong>CUSTOMER COPY</strong></td>'
        . '<td style="text-align: center;" valign="center" align="center"><strong>Office Use</strong></td>'
        . '</tr>'
        . '<tr>'
        . '<td style="text-align: left;" colspan="2"><strong>Customer Details:</strong> ' . $c_contact_name . '</td>'
        . '<td style="text-align: left;" colspan="2"><strong>Location Details:</strong> ' . $q_name_1 . '</td>'
        . '<td style="text-align: left;" valign="center" align="center" rowspan="6"></td>'
        . '</tr>'
        . '<tr>'
        . '<td style="text-align: left;" colspan="2">Name: ' . $c_name_1 . ' ' . $c_name_2 . '</td>'
        . '<td style="text-align: left;" colspan="2">Name: ' . $q_name_2 . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="text-align: left;" colspan="2">Address: ' . $customer_address . '</td>'
        . '<td style="text-align: left;" colspan="2">Address: ' . $quote_address . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="text-align: left;" colspan="2">Suburb: ' . $c_suburb . ', Postcode: ' . $c_postcode . '</td>'
        . '<td style="text-align: left;" colspan="2">Suburb: ' . $q_suburb . ', Postcode: ' . $q_postcode . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="text-align: left;" colspan="2">Email: ' . $c_email . '</td>'
        . '<td style="text-align: left;" colspan="2">Email: ' . $q_email . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="text-align: left;" colspan="2">Phone: ' . $c_phone . ' | Mobile: ' . $c_mobile . '</td>'
        . '<td style="text-align: left;" colspan="2">Phone: ' . $q_phone . ' | Mobile: ' . $q_mobile . '</td>'
        . '</tr>'
        . '</table>';

$_GET['type'] = 'customer_copy';
$_GET['no'] = 1;
foreach ($calculations as $calculation) {
    foreach ($calculation as $calculation_x) {
        $file = __DIR__ . '/../' . $calculation_x . '/print-xlsx.php';
        if (file_exists($file)) {
            include_once $file;
            $html .= ${str_replace('-', '_', $calculation_x) . "_quote_tables"};
        }
    }
}

$summary .= '<table>'
        . '<tr>'
        . '<td style="border: 1px solid #000000;" colspan="2"><strong>Instructions:</strong></td>'
        . '<td style="border: 1px solid #000000;" align="center"><strong>Deposite History</strong></td>'
        . '<td style="border: 1px solid #000000;" colspan="2"><strong>Payment Method</strong></td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 1px solid #000000;" colspan="2" rowspan="3" valign="top">' . nl2br($instruction) . '</td>'
        . '<td style="border: 1px solid #000000;" valign="top" rowspan="3" align="center">' . $deposit_details . '</td>'
        . '<td style="border: 1px solid #000000;" colspan="2" valign="top" align="center" height="50">' . $bank_account_name . '<br>' . $bank_account_no . '<br>' . $bsb_no . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 1px solid #000000;" colspan="2"><strong>Quote No. Required with electronic deposits</strong></td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 1px solid #000000;"><strong>DISCOUNT (' . $discount_1 . '%)</strong></td>'
        . '<td style="border-bottom: 1px solid #000000; text-align: right;">' . number_format($discount_1_val, 2) . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border-top: 1px solid #000000; border-right: 1px solid #000000; text-align: center;" valign="top"><strong>ACCEPTANCE OF QUOTATION</strong></td>'
        . '<td style="border-top: 1px solid #000000; text-align: center;"><strong>FITTED AND OPERATING TO MY SATISFACTION</strong></td>'
        . '<td style="border: 1px solid #000000;" rowspan="2" valign="center" align="left"><strong>FITTER:</strong></td>'
        . '<td style="border: 1px solid #000000;"><strong>DISCOUNT ( ' . $currency_sign . ' )</strong></td>'
        . '<td style="border-bottom: 1px solid #000000; text-align: right;">' . number_format($discount_2, 2) . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border-top: 1px solid #000000;" rowspan="3" valign="top" align="center">' . $signature . '</td>'
        . '<td rowspan="3" valign="center" align="left">NAME:<br>SIGNATURE:</td>'
        . '<td style="border: 1px solid #000000;"><strong>CASH, CARD OR EFT PRICE</strong></td>'
        . '<td style="border-bottom: 1px solid #000000; text-align: right;">' . number_format($grand_total, 2) . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 1px solid #000000;" rowspan="2" valign="center" align="left"><strong>DATE FITTED:</strong></td>'
        . '<td style="border: 1px solid #000000;"><strong>DEPOSIT</strong></td>'
        . '<td style="border-bottom: 1px solid #000000; text-align: right;">' . number_format($deposit, 2) . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="border: 1px solid #000000;"><strong>BALANCE</strong></td>'
        . '<td style="border-bottom: 1px solid #000000; text-align: right;">' . number_format(round($balance, 2), 2) . '</td>'
        . '</tr>'
        . '</table>';

print json_encode([$summary, $html]);
