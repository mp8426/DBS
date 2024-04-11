<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include './../cPanel/connect.php';

$cid = filter_input(INPUT_GET, 'cid');
$layout = !isset($_GET['layout']) ? 1 : (int) filter_input(INPUT_GET, 'layout');
$today_date = date("l, d F y ");

$query_1 = "SELECT business_address_1, business_address_2, business_suburb, business_postcode, business_country, business_email, business_web, business_phone, business_mobile, business_fax, business_abn, bank_account_name, bank_account_no, bsb_no, tax_name, tax_percentage FROM profile WHERE 1";

$stmt_1 = $mysqli->prepare($query_1);
$stmt_1->execute();
$stmt_1->bind_result($business_address_1, $business_address_2, $business_suburb, $business_postcode, $business_country, $business_email, $business_web, $business_phone, $business_mobile, $business_fax, $business_abn, $bank_account_name, $bank_account_no, $bsb_no, $tax_name, $tax_percentage);
$stmt_1->fetch();
$stmt_1->close();

//$stmt_1->bind_result($company_address, $company_phone, $company_abn, $company_email, $company_web, $company_account_no);

$queryt_2 = "SELECT q_name_1, q_name_2, q_address_1, q_address_2, q_suburb, q_postcode, q_email, q_phone, q_mobile, q_fax, c_ref, c_hdfu, DATE_FORMAT(created_date,'%m/%d/%Y'), customer_id FROM quotes WHERE quote_no = ?";

$stmtt_2 = $mysqli->prepare($queryt_2);
$stmtt_2->bind_param('s', $cid);
$stmtt_2->execute();
$stmtt_2->bind_result($q_name_1, $q_name_2, $q_address_1, $q_address_2, $q_suburb, $q_postcode, $q_email, $q_phone, $q_mobile, $q_fax, $c_ref, $c_hdfu, $q_created_date, $customer_id);
$stmtt_2->fetch();
$stmtt_2->close();

$quote_address = $q_address_1 . ' ' . $q_address_2;

$query_3 = "SELECT c_contact_name, c_name_1, c_name_2, c_address_1, c_address_2, c_suburb, c_postcode, c_email, c_phone, c_mobile FROM customers WHERE id = ?";

$stmt_3 = $mysqli->prepare($query_3);
$stmt_3->bind_param('i', $customer_id);
$stmt_3->execute();
$stmt_3->bind_result($c_contact_name, $c_name_1, $c_name_2, $c_address_1, $c_address_2, $c_suburb, $c_postcode, $c_email, $c_phone, $c_mobile);
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

$query_5 = "SELECT staff_id, DATE_FORMAT(assign_date,'%m/%d/%Y') FROM quote_assign WHERE quote_no = ?";

$stmt_5 = $mysqli->prepare($query_5);
$stmt_5->bind_param('s', $cid);
$stmt_5->execute();
$stmt_5->bind_result($assinged_staff_id, $assign_date);
$stmt_5->fetch();
$stmt_5->close();


/* * *************** Print Quote Items **************** */
$calculations = array();

$query_7 = "SELECT folder, total_column FROM calculations ORDER BY position ASC";

$stmt_7 = $mysqli->prepare($query_7);
$stmt_7->execute();
$stmt_7->bind_result($calculation_folder, $total_column);
$stmt_7->store_result();

while ($stmt_7->fetch()) {

    $calculation_table = str_replace('-', '_', $calculation_folder);
    $calculations[] = array($calculation_folder);
}
$stmt_7->close();

$mysqli->close();

require_once('/var/www/vhosts/blinq.com.au/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    // Page footer
    public function Footer() {

        global $cid;

        /* $this->cropMark(5, 5, 5, 5, 'TL');
          $this->cropMark(205, 5, 5, 5, 'TR');
          $this->cropMark(5, 292, 5, 5, 'BL');
          $this->cropMark(205, 292, 5, 5, 'BR'); */

        // Position at 15 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, $cid . ' - Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

// create new PDF document
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('L', 'mm', array(100, 25), true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('Label - ' . $cid);
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
$pdf->SetMargins(1, 1, 1);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->SetPrintHeader(FALSE);
$pdf->SetPrintFooter(FALSE);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, -5);

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
$pdf->AddPage();

foreach ($calculations as $calculation) {
    foreach ($calculation as $calculation_x) {
        include_once '../' . $calculation_x . '/print-label.php';
        $calculation_folder = str_replace('-', '_', $calculation_x);

        $pdf->writeHTML(${$calculation_folder . "_quote_tables_SKINS"}, true, false, true, false, '');
        $pdf->writeHTML(${$calculation_folder . "_quote_tables_TUBES"}, true, false, true, false, '');
        $pdf->writeHTML(${$calculation_folder . "_quote_tables_FB"}, true, false, true, false, '');
    }
}

// output the HTML content
//$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('Label-' . $cid . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
