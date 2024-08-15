<?php

use LDAP\Result;

$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/encryption.php";
require_once "logic/db.php";
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "doctor") {
    // Redirect to login page
    header('Location: ./index.php');
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log-out'])) {
    header('Location: ./index.php');
    session_destroy();
    exit;
}
$today = date("Y-m-d");
// echo $today ;
$conn = database::get_connection();
$result = $conn->query(
    "SELECT name , time ,appointment.email_patient , appointment_id
    FROM appointment 
    INNER JOIN patient 
    ON appointment.email_patient = patient.email_patient
    WHERE is_reserved=1 AND is_done=0 AND date= '".$today."'"
);

// fetch all data about doctor to row and use the data to show
$conn = database::get_connection();
$hashed_email = $_SESSION['email'];
$emailInfo = $conn->prepare("SELECT * from doctor where email_doctor = ?");
$emailInfo->bind_param("s", $hashed_email);
$emailInfo->execute();
$emailResult = $emailInfo->get_result();
$row = $emailResult->fetch_assoc();//row of that email.
if ($emailInfo && isset($row['name'])) {
    $name = $row['name'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedHub | Appointments</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/doctor-page.css">
    <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
        <a href="add-appointment.php">
            <h2 class="std-text">add appointment</h2>
        </a>
        <h2 class="right-side std-tip"><?php if(isset($name) && $name !== null) {
                $decrypted_name = decrypt($name);
                $_SESSION['name'] = $decrypted_name;
                echo "<h2 class='right-side std-tip'> Dr $decrypted_name</h2>";}?>
        </h2>
        <form action="./patient-page.php" method="post">
        <style>.btn-log-out {cursor: pointer;}</style>
        <input type="submit" name = "log-out" value="Log out" class="btn btn-default btn-log-out">
        </form>
    </div>

    <div class="container">
        <h1 class="title">Appointments for today</h1>
        <?php if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {?>

        <form action="">
            <button class="appointment">
                <span class="std-text"><?php echo $row['name'];?></span>
                <span class="std-text"><?php echo $row['time'];?></span>
            </button>
            <input type="hidden" name="p_email" value="<?php echo $row['email_patient'];?>">
            <input type="hidden" name="app_id" value="<?php echo $row['appointment_id'];?>">
        </form>

            <?php } ?>
            <?php }else{
                    echo "<p>No appointments for today</p>";
                    }
            ?>
    </div>
</body>

</html>