<?php
$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/db.php";
require_once "logic/encryption.php";
require_once "logic/helping_functions.php";
require_once 'objs/Appointment.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['treatment'])){
        $treatment = $_POST['treatment'];
        $disease_title = $_POST['disease_title'];
        $cost = floatval($_POST['cost']);
        $disease_info = $_POST['disease_info'];
        $age = $_POST['age'];
        $appointment_id = intval($_POST['appointment_id']);
        $app = new Appointment(); 
        $app->set_id($appointment_id);
        if($app->handle_doc_prescrpion_form($cost,$treatment,$disease_title , $disease_info , $age)){
            header('Location: doctor-page.php');
        }


    }


}

$id = 1;
$conn = database::get_connection();
$result = $conn->query(
    "SELECT appointment_id , time , patient.email_patient , is_reserved , is_done ,name ,gender ,address , year_of_birth
        FROM appointment 
        INNER JOIN patient 
        ON appointment.email_patient = patient.email_patient
        WHERE appointment_id = " . $id
);
$patient_info_row = $result->fetch_assoc();
// if ($result->num_rows != 1 || $patient_info_row == NULL) {
//     header("HTTP/1.0 404 Not Found");
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedHub | <?php echo $_LANG_DATA->tit;?></title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/doctor-appointment.css">
    <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <h1 class="med-title">MedHub</h1>
        <h1 class="med-line med-title">|</h1>
        <h2 class="right-side std-tip"><?php echo $_LANG_DATA->doc;?></h2>
    </div>
    <main>
        <div class="title">
            <?php
            if ($patient_info_row['is_done'] != 0 || $patient_info_row['is_reserved'] != 1) {
                echo '<h1>This Appointment is done</h1>';
            }


            ?>
            <h1>Appointment For Patient <?php echo $patient_info_row['name'] . ' AT ' . $patient_info_row['time']; ?> </h1>
        </div>
        <form action="doctor-appointment.php" method="post" class="container">
            <input type="hidden" name="appointment_id" value="<?php echo $id ; ?>">
            <input type="hidden" name="age" value="<?php echo date_to_age($patient_info_row['year_of_birth']); ?>">
            <div class="section">

                <?php 
                    $details_result =  
                    $conn->query("SELECT prescription_id , appointment.appointment_id , disease_title 
                    FROM prescription 
                    INNER JOIN appointment
                    ON appointment.appointment_id = prescription.appointment_id
                    where appointment.email_patient = '". Appointment::get_patient_email_from_db($id)."'"
                    );
                    if($details_result->num_rows != 0){?>
                        <h2><?php echo $_LANG_DATA->med;?></h2>
                        <?php
                    while($details_row = $details_result->fetch_assoc()){
                        ?>
                <input readonly type="text" value="<?php echo $details_row['disease_title'] ; ?>" class="std-small-text medrec-entry">
                <?php }}else{ ?>
                    <h2><?php echo $_LANG_DATA->nomed;?></h2>
                    
               <?php } ?>
            </div>
            <div class="section">
                <div class="info">
                    <h2><?php echo $_LANG_DATA->patient;?></h2>
                    <p class="std-small-text"><?php echo $patient_info_row['email_patient']; ?></p>
                    <p class="std-small-text"><?php echo $patient_info_row['name']; ?></p>
                    <p class="std-small-text"><?php echo date_to_age($patient_info_row['year_of_birth']) . " " . strtoupper($patient_info_row['gender']); ?></p>
                    <p class="std-small-text del_on_print"><?php echo $patient_info_row['address']; ?></p>
                </div>
                <div>
                    <label for="disease_info" class="std-small-text del_on_print"><?php echo $_LANG_DATA->diss;?></label><br>
                    <textarea required id="disease_info" name="disease_info" class="std-small-text del_on_print" rows="4" cols="100" placeholder="<?php echo $_LANG_DATA->note;?>"></textarea><br>
                    <!-- <input class="ok-button std-small-text" type="submit" value="Send"> -->
                </div>
            </div>
            <div class="section">
                <div class="prsicrption">
                    <label for="prsicrption" class="std-small-text"><?php echo $_LANG_DATA->tre;?></label><br>
                    <div class="small-form del_on_print">
                        <input type="text" required name="disease_title" placeholder="<?php echo $_LANG_DATA->td;?>" class="std-small-text del-on_print"><br>
                    </div>
                    <textarea required id="prsicrption" name="treatment" class="std-small-text" rows="4" cols="100" placeholder="<?php echo $_LANG_DATA->cn;?>"></textarea><br>
                    <!-- <input class="ok-button std-small-text" type="submit" value="Print"> -->
                </div>

                <label for="cost">
                    <p class="std-small-text del_on_print"><?php echo $_LANG_DATA->bill;?></p>
                </label>
                <div class="small-form del_on_print">
                    <input type="number" required id="cost" name="cost" placeholder="<?php echo $_LANG_DATA->coast;?>" class="std-small-text del-on_print"><br>
                    <input class="ok-button std-small-text del_on_print" type="submit" value="<?php echo $_LANG_DATA->send;?>">
                </div>
            </div>

        </form>
    </main>
</body>

</html>