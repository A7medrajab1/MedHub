<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "patient") {
    header('Location: ./index.php');
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log-out'])) {
    header('Location: ./index.php');
    session_destroy();
    exit;
}

$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/db.php";
require_once "logic/encryption.php";
require_once "Users/Patient.php";
require_once "objs/Appointment.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])){
    $p_email = decrypt($_SESSION['email']);
    if (isset($_POST['patient_email'])) {
        $p_email = filter_var($_POST['patient_email'], FILTER_VALIDATE_EMAIL);
        
    }
    $appmnt = new Appointment();
    $appmnt->set_id(intval(filter_var($_POST['appointment_id'] , FILTER_SANITIZE_NUMBER_INT)));
    // $appmnt->set_doc_email($p_email);
    $appmnt->set_patient_email($p_email);

    if(!$appmnt->assign_patient_to_appointment()){
        echo '<script>alert("Error");</script>';
    }
    if (isset($_POST['patient_email'])) {
        header('Location: ./staff-page.php');

    }

    
}



$query = "SELECT date, time, name, Speciality , appointment_id
        FROM appointment 
        INNER JOIN doctor ON appointment.email_doctor = doctor.email_doctor 
        WHERE appointment.is_reserved = 0";
$result = database::get_connection()->query($query);


// fetch all data about patient to row and use the data to show
$conn = database::get_connection();
$hashed_email = $_SESSION['email'];
$emailInfo = $conn->prepare("SELECT * from patient where email_patient = ?");
$emailInfo->bind_param("s", $hashed_email);
$emailInfo->execute();
$emailResult = $emailInfo->get_result();
$row = $emailResult->fetch_assoc();//row of that email.
$name = $row['name'];

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub | <?php echo $_LANG_DATA->patient;?></title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/patient-page.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a href="./contact.php"><h2 class="std-text">Contact</h2></a>
            <a href="#"><h2 class="std-text">Past Appointments</h2></a>
            <h2 class="right-side std-tip"><?php if(isset($name) && $name !== null) {
                $decrypted_name = decrypt($name);
                $_SESSION['name'] = $decrypted_name;
                echo "<h2 class='right-side std-tip'>$decrypted_name</h2>";}?>
            </h2>
            <form action="./patient-page.php" method="post">
            <style>.btn-log-out {cursor: pointer;}</style>
            <input type="submit" name = "log-out" value="Log out" class="btn btn-default btn-log-out">
            </form>
            <!-- <h2 class="right-side std-tip">Username</h2> -->
        </div>
        <div class="container">
            <div class="search">
            <input type="text" id="doc" name="search_doc" placeholder="<?php echo $_LANG_DATA->ser;?>" onfocus="this.placeholder = ''"
                onblur="this.placeholder = 'Search Doctor/Specialty'" oninput="searchDoc()" class="std-text"  >
            </div>

            <!-- HAS BEEN TESTED WITH 50 CARDS SCALABILITY IS NOT AN ISSUE -->
            <div class="cards">

                <?php if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { ?>

                <div class="card">
                    <div class="items">
                        <img src="imgs/doctor.png" alt="" style="width:200px;height:auto;">
                        <div class="content"> 
                                <div class="card-info" >
                                    <span class="card-text"><?php echo $row['date']; ?></span>
                                    <span class="card-text right-side"><?php echo $row['time']; ?></span>  
                                </div>
                                <div class="card-info">
                                        <span class="card-text"><?php echo $row['name']; ?></span>
                                        <span class="card-text right-side"><?php echo decrypt($row['Speciality']); ?></span>
                                </div>
                            </div>

                            <form action="" method="post">
                                <input type="hidden" name="appointment_id" value="
                                <?php echo $row['appointment_id'];?>
                                ">
                                <button class="ok-button reserve">RESERVE</button>
                            </form>   

                    </div>
                </div>
                <?php }
            } else { ?>
                <p><?php echo $_LANG_DATA->not;?></p>
            <?php } ?>
                
            </div>
            <script src="js/patient.js"></script>

        </div>
    </body>
</html>