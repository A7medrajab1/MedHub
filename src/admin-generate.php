<?php
$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "PDFGen/TCPDF/tcpdf.php";
require_once "logic/helping_functions.php";
require_once "logic/encryption.php";

session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "administrator") {
    // Redirect to login page.
    header('Location: ./index.php');
    exit;
}
if(isset($_SESSION['name'])){
    $name = $_SESSION['name'];
}

$conn = database::get_connection();

$financial_nov = extract_from_sql("SELECT COUNT(appointment_id) FROM appointment WHERE is_done != 0");
$financial_avgB = extract_from_sql("SELECT AVG(price) FROM appointment WHERE price != 0");
$financial_total = extract_from_sql("SELECT SUM(price) FROM appointment WHERE is_paid != 0");

$userbase_totalP = extract_from_sql("SELECT COUNT(*) FROM patient");
$userbase_totalS = extract_from_sql("SELECT COUNT(*) FROM staff");
$userbase_totalD = extract_from_sql("SELECT COUNT(*) FROM doctor");
$userbase_totalA = extract_from_sql("SELECT COUNT(*) FROM admin");
$userbase_totalU = $userbase_totalP['COUNT(*)'] + $userbase_totalS['COUNT(*)'] + $userbase_totalD['COUNT(*)'] + $userbase_totalA['COUNT(*)'] ;

$performance_query = "SELECT email_doctor, COUNT(email_doctor) AS 'repeats' FROM appointment GROUP BY email_doctor ORDER BY 'repeats' DESC LIMIT 1;";

$performance_most = extract_from_sql($performance_query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub | <?php echo $_LANG_DATA->generate;?></title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/admin-generate.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a href="./admin-page.php"><h2 class="std-text"><?php echo $_LANG_DATA->home;?></h2></a>
            <h2 class="right-side std-tip"><?php echo $name;?></h2>
        </div>
        <div class="container">
            <div class="section">
                <h2><?php echo $_LANG_DATA->fain;?></h2>

                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->visit;?></span>
                    <span class="std-tip"><?php echo $financial_nov['COUNT(appointment_id)'] ?></span>
                </div>
                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->average;?></span>
                    <span class="std-tip"><?php echo $financial_avgB['AVG(price)'] ?></span>
                </div>
                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->total;?></span>
                    <span class="std-tip"><?php echo $financial_total['SUM(price)'] ?></span>
                </div>
                
            </div>
            <div class="section">
                <h2><?php echo $_LANG_DATA->userbase;?></h2>

                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->users;?></span>
                    <span class="std-tip"><?php echo $userbase_totalU ?></span>
                </div>
                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->patients;?></span>
                    <span class="std-tip"><?php echo $userbase_totalP['COUNT(*)'] ?></span>
                </div>
                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->staff;?></span>
                    <span class="std-tip"><?php echo $userbase_totalS['COUNT(*)'] ?></span>
                </div>
                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->doctor;?></span>
                    <span class="std-tip"><?php echo $userbase_totalD['COUNT(*)'] ?></span>
                </div>
                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->admins;?></span>
                    <span class="std-tip"><?php echo $userbase_totalA['COUNT(*)'] ?></span>
                </div>
                
            </div>
            <div class="section">
                <h2><?php echo $_LANG_DATA->per;?></h2>

                <div class="appointment">
                    <span class="std-text"><?php echo $_LANG_DATA->doc;?></span>
                    <span class="std-tip"><?php if (isset($performance_most['email_doctor'])) echo decrypt($performance_most['email_doctor']) ?></span>
                </div>
                
            </div>

            <form action="admin-generate.php" method="post">
                <button name="print" class="ok-button print-report std-text"><?php echo $_LANG_DATA->print;?></button>
            </form>
        </div>

    </body>
</html>

<?php
if (isset($_POST['print'])) {
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Create HTML
    $html = "<h1>Report As PDF</h1><p>".date('l jS \of F Y h:i:s A')."</p><br><br>";

    $html .= "<h2>Financial Report</h2>";
    $html .= "<p>Number of Visitors: " . $financial_nov['COUNT(appointment_id)'] . "</p>";
    $html .= "<p>Average Bill: " . $financial_avgB['AVG(price)'] . "</p>";
    $html .= "<p>Total Revenue: " . $financial_total['SUM(price)'] . "</p>";

    $html .= "<h2>Userbase Report</h2>";
    $html .= "<p>Total number of users: " . $userbase_totalU . "</p>";
    $html .= "<p>Number of patients: " . $userbase_totalP['COUNT(*)'] . "</p>";
    $html .= "<p>Number of staff members: " . $userbase_totalS['COUNT(*)'] . "</p>";
    $html .= "<p>Number of doctors: " . $userbase_totalD['COUNT(*)'] . "</p>";
    $html .= "<p>Number of admins: " . $userbase_totalA['COUNT(*)'] . "</p>";

    $html .= "<h2>Performance Report</h2>";
    $html .= "<p>Doctor with most visits: " .decrypt( $performance_most['email_doctor']) . "</p>";
    

    // Write HTML content
    $pdf->writeHTML($html);

    // Output PDF
    ob_end_clean();
    date_default_timezone_set('UTC');
    $pdf->Output(date(DATE_RFC2822).'-report.pdf', 'I');
}
?>