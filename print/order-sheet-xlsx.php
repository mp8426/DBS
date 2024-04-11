<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include './../cPanel/connect.php';

$calculation = filter_input(INPUT_GET, 'calculation'); // Main calculation Name
$calculation_x = str_replace("-", "_", $calculation);

$calculation_code = filter_input(INPUT_GET, 'code');

$cid = filter_input(INPUT_GET, 'cid');
$today_date = date("l, d F y ");

$query_1 = "SELECT name FROM " . $calculation_x . "s WHERE code = ?";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->bind_param('s', $calculation_code);
$stmt_1->execute();
$stmt_1->bind_result($calculation_name);
$stmt_1->fetch();
$stmt_1->close();

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

list($order_number, $sidemark) = array_pad(explode("|", $c_ref), 2, null);


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

$html = '';

$summary = '<table>'
        . '<tr>'
        . '<td valign="center" align="center" height="50" style="border: 1px solid #000000;" colspan="3">'
        . '<img src="' . __DIR__ . '/../profile/logo.jpg" height="45">'
        . '</td>'
        . '<td style="border: 1px solid #000000; text-align: center; font-size:18px;" valign="center" align="center" colspan="3"><strong>' . $calculation_name . ' - ORDER SHEET</strong></td>'
        . '<td style="border: 1px solid #000000; text-align: center;" valign="center" align="center" colspan="2"><strong>Date:</strong><br>' . $today_date . '</td>'
        . '<td valign="center" align="center" style="border: 1px solid #000000;" colspan="2">'
        . 'Client #: <strong>' . $customer_no . '</strong><br>'
        . 'Order #: <strong>' . $cid . '</strong>'
        . '</td>'
        . '<td valign="center" align="center" style="border: 1px solid #000000;" colspan="2">'
        . 'Sidemark<br>'
        . '<strong>' . $sidemark . '</strong>'
        . '</td>'
        . '</tr>'
        . '</table>';

$_GET['type'] = 'order_sheet';
$_GET['no'] = 2;
${$calculation_x . '_code'} = $calculation_code;
include_once __DIR__ . '/../' . $calculation . '/print-xlsx.php';
$html_1 .= ${$calculation_x . '_quote_tables'};
$html_1 .= ${$calculation_x . '_quote_tables_1'};
$html_2 = ${$calculation_x . '_quote_tables_2'};


if($calculation_name === 'Ziptrak' || $calculation_name === 'Ziptrak Skins' || $calculation_name === 'Ziptrak®' || $calculation_name === 'Ziptrak® Interior1' || $calculation_name === 'Zipscreen®' || $calculation_name === 'Zipscreen' || $calculation_name === 'Zipscreen Blinds' || $calculation_name === 'Canvas/Mesh Awnings' || $calculation_name === 'Fixed Guide Awnings' || $calculation_name === 'Straight Drop Spring' || $calculation_name === 'Straight Drop Crank' || $calculation_name === 'Awning Recovers' || $calculation_name === 'eZip Blinds' || $calculation_name === 'Ezip' || $calculation_name === 'Slidetrack Blinds'){

        $html_1 .= '<table border="0" cellspacing="0" cellpadding="5">'
            . '<tr>'
            . '<td style="border: 2px solid #000000; text-align:center; color:#ffffff; background-color: #000000;">TRACK & <br> CHANNEL READY'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; text-align:center; color:#ffffff; background-color: #000000;">ACCESSORIES <br> DONE'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; text-align:center; line-height:30px; color:#ffffff; background-color: #000000;">HOOD DELIVERED'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; text-align:center; color:#ffffff; background-color: #000000;">MESH/PVC <br> DELIVERED'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '<td style="border: 2px solid #000000;">'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td colspan="12" style="border: 2px solid #000000; text-align:left;">POWDERCOATERS'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">Type'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">QTY'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">SIZE'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">CUT'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">P/COATERS'
            . '</td>'
            . '<td colspan="2" style="border: 2px solid #000000; width: 100px; text-align:center;">ARRIVED'
            . '</td>'
            . '<td style="width: 50px;">'
            . '</td>'
            . '<td colspan="4" style="border: 2px solid #000000; width: 315px; text-align:center;">EXTRA`s'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td colspan="2" style="border: 2px solid #000000; width: 100px; text-align:center;">'
            . '</td>'
            . '<td style="width: 50px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:165px; font-size:9px; text-align:center;"><strong>TYPE</strong>'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 40px; font-size:9px;"><strong>QTY</strong>'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:55px; font-size:9px;"><strong>IN STOCK</strong>'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 55px; font-size:9px;"><strong>PICKED</strong>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td colspan="2" style="border: 2px solid #000000; width: 100px; text-align:center;">'
            . '</td>'
            . '<td style="width: 50px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:165px; font-size:10px;">FACE FIT TRACKS'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 40px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:55px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 55px;">'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td colspan="2" style="border: 2px solid #000000; width: 100px; text-align:center;">'
            . '</td>'
            . '<td style="width: 50px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:165px; font-size:10px;">REMOTE CONTROLS'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 40px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:55px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 55px;">'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td colspan="2" style="border: 2px solid #000000; width: 100px; text-align:center;">'
            . '</td>'
            . '<td style="width: 50px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:165px; font-size:10px;">REMOVABLE POST KITS'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 40px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:55px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 55px;">'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 110px; text-align:center;">'
            . '</td>'
            . '<td colspan="2" style="border: 2px solid #000000; width: 100px; text-align:center;">'
            . '</td>'
            . '<td style="width: 50px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:165px; font-size:10px;">WIND SENSORS'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 40px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width:55px;">'
            . '</td>'
            . '<td style="border: 2px solid #000000; width: 55px;">'
            . '</td>'
            . '</tr>'
            . '</table>';
    }

print json_encode([$summary, $html_1, $calculation_name, $html_2]);
