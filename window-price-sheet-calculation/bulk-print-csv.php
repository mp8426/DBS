<?php

include_once __DIR__ . '/../../check-user-login.php';


// $search_job_date_x_from = mysql_real_escape_string($_POST['date_from']);
// $search_job_date_x_to = mysql_real_escape_string($_POST['date_to']);
$search_job_date_x_from = filter_input(INPUT_POST, 'date_from');
$search_job_date_x_to = filter_input(INPUT_POST, 'date_to');
$action = filter_input(INPUT_POST, 'action');

// echo $search_job_date_x_from;
// echo $search_job_date_x_to;
// echo $action;
if (!empty($search_job_date_x_from) && !empty($search_job_date_x_to) && !empty($action)) {

    include_once __DIR__ . '/../cPanel/connect.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (!empty($search_job_date_x_from) && !empty($search_job_date_x_to)) {

        $search_job_date_x_from_x = DateTime::createFromFormat('d/m/Y', $search_job_date_x_from);
        $search_job_date_x_from_xx = $search_job_date_x_from_x->format('Y-m-d');

        $search_job_date_x_to_x = DateTime::createFromFormat('d/m/Y', $search_job_date_x_to);
        $search_job_date_x_to_xx = $search_job_date_x_to_x->format('Y-m-d');

        $search_job_date_x_query = "submitted_date BETWEEN '$search_job_date_x_from_xx' AND '$search_job_date_x_to_xx'";
    } else if (!empty($search_job_date_x_from) && empty($search_job_date_x_to)) {

        $search_job_date_x_from_x = DateTime::createFromFormat('d/m/Y', $search_job_date_x_from);
        $search_job_date_x_from_xx = $search_job_date_x_from_x->format('Y-m-d');

        $search_job_date_x_to_xx = date('Y-m-d');

        $search_job_date_x_query = "submitted_date BETWEEN '$search_job_date_x_from_xx' AND '$search_job_date_x_to_xx'";
    } else {
        $search_job_date_x_query = "";
    }
    // $job_no = 'J1770';

    $query_1 = "SELECT quote_no, job_no FROM jobs WHERE submitted_date BETWEEN '$search_job_date_x_from_xx' AND '$search_job_date_x_to_xx'";

    $stmt_1 = $mysqli->prepare($query_1);
    // $stmt_1->bind_param('s', $job_no);
    $stmt_1->execute();
    $stmt_1->bind_result($quote_no, $job_no);
    $stmt_1->store_result();
    $num_rowss = $stmt_1->num_rows;

            if (!empty($num_rowss)) {
                echo $quote_no;
                // $csv_file = tmpfile();

                $csv_str = '';

                while ($stmt_1->fetch()) {

                            if (!empty($quote_no) && !empty($job_no) && !empty($action)) {

                                                
                            
                            $quote_item_no = 1;
                            $window_price_sheet_calculation_quote_items = "";
                            $window_price_sheet_calculation_quote_item_price_sub_total = 0;

                            $query_3 = "SELECT qi.code, qi.location, qi.width_x, qi.drop_x, qi.type, qi.material, qi.colour, qi.notes, qi.price, qi.discount FROM window_price_sheet_calculation_quote_items qi LEFT JOIN window_price_sheet_calculation_type_options tos ON SUBSTRING_INDEX(qi.type, '<->', -1) = tos.code WHERE qi.cid = ? AND tos.product_code_id IN(9, 17) ORDER BY qi.id ASC";

                            $stmt_3 = $mysqli->prepare($query_3);
                            $stmt_3->bind_param('s', $quote_no);
                            $stmt_3->execute();
                            $stmt_3->bind_result($window_price_sheet_calculation_quote_item_code, $window_price_sheet_calculation_quote_item_location, $window_price_sheet_calculation_quote_item_width, $window_price_sheet_calculation_quote_item_drop, $window_price_sheet_calculation_quote_item_type, $window_price_sheet_calculation_quote_item_material, $window_price_sheet_calculation_quote_item_colour, $window_price_sheet_calculation_quote_item_notes, $window_price_sheet_calculation_quote_item_price, $window_price_sheet_calculation_quote_item_discount);
                            $stmt_3->store_result();
                            $num_rows = $stmt_3->num_rows;

                            if (!empty($num_rows)) {

                                // $csv_file = tmpfile();

                                $csv_str = '';

                                while ($stmt_3->fetch()) {

                                $width =  $window_price_sheet_calculation_quote_item_width - 24;
                                $drop = $window_price_sheet_calculation_quote_item_drop + 300;
                                $fabric_type = explode('<->', $window_price_sheet_calculation_quote_item_material)[0];
                                $fabric_color = explode('<->', $window_price_sheet_calculation_quote_item_colour)[0];

                                $csv_str .= "START##,ROLLER\n";
                                $csv_str .= "Order Number, $job_no\n";
                                $csv_str .= "Line Number,$quote_item_no\n";
                                $csv_str .= "Quantity,1\n";
                                $csv_str .= "Measured Width,$width\n";
                                $csv_str .= "Measured Drop,$drop\n";
                                $csv_str .= "Design,Plain Rectangle\n";
                                $csv_str .= "Fabric Type,$fabric_type\n";
                                $csv_str .= "Fabric Colour,$fabric_color\n";
                                $csv_str .= "Style,No Deductions";
                                $num_rows !== $quote_item_no ? $csv_str .= "\n,\n" : "\n";

                                $quote_item_no++;
                                }

                                // $metaDatas = stream_get_meta_data($csv_file);

                                if ($action === 'download') {

                                // print json_encode([1, file_get_contents($metaDatas['uri'])]);
                                print json_encode([1, $csv_str, $job_no]);
                                } else if ($action === 'sync') {

                                    session_start();
                                    require '/var/www/vhosts/blinq.com.au/google-api-php-client/vendor/autoload.php';

                                    $client = new Google_Client();
                                    $application_creds = '/var/www/vhosts/blinq.com.au/google-json-keys/gdrive-file-feeder-c2dc9e33e3b6.json';  //the Service Account generated in JSON
                                    $credentials_file = file_exists($application_creds) ? $application_creds : false;
                                    define("APP_NAME", "Gdrive File Feeder");
                                    $client->setAuthConfig($credentials_file);
                                    $client->setApplicationName(APP_NAME);

                                    $client->addScope("https://www.googleapis.com/auth/drive.file");

                                    $client->setAccessType('offline');
                                    $client->getAccessToken();
                                    $drive = new Google_Service_Drive($client);
                                    $file = new Google_Service_Drive_DriveFile($client);
                                    $file->setName($job_no . '.csv');
                                    $file->setMimeType('text/csv');
                                    $file->setParents(['156c4oG6uaw-zFij9n6ACDbNUkKN1vy6c']); // Shared folder ID of cbs.blinq@gmail.com
                                    // $file->setParents(['1-GAsvlyphUXI0eAYv4U8Lyf2ZZwq9Pnq']); // Shared folder ID of cbs.blinq@gmail.com
                                    // gdrive-file-feeder@gdrive-file-feeder.iam.gserviceaccount.com // Service account

                                    try {
                                        $return = $drive->files->create($file, [
                                        'data' => $csv_str,
                                        'uploadType' => 'multipart',
                                        'supportsAllDrives' => 'true'
                                        ]);
                                        // print json_encode([1, "Sucessful upload of " . $file['name'] . "<br>File ID:" . $return->id]);
                                        print json_encode([1, "The file " . $file['name'] . " has been synced successfully", $return->id]);
                                    } catch (Exception $exc) {
                                        print json_encode([2, 'Caught exception: ',  $exc->getMessage()]);
                                    }
                                }
                            } else {

                                print json_encode([2, 'No data Found 1']);
                            }

                            $stmt_3->close();
                            // $mysqli->close();
                            } else {

                            print json_encode([2, 'Oops! No data Found 2']);
                            }
                    } 
            }
            
        else{
                print json_encode([2, 'Oops! No data Found 3']);
        }

        $stmt_1->close();
        $mysqli->close();
}
else {

print json_encode([2, 'Please Select Date']);
}
