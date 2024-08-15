<?php
$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/db.php";
require_once "logic/encryption.php";

session_start();
// session 
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "staff member") {
    // Redirect to login page
    header('Location: ./index.php');
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log-out'])) {
    // Redirect to login page
    header('Location: ./index.php');
    session_destroy();
    exit;
}



$query = "SELECT date, time, name, Speciality , appointment_id
                      FROM appointment 
                      INNER JOIN doctor ON appointment.email_doctor = doctor.email_doctor 
                      WHERE appointment.is_reserved = 0";

$result = database::get_connection()->query($query);

$conn = database::get_connection();
$hashed_email = $_SESSION['email'];
$emailInfo = $conn->prepare("SELECT * from staff where email_staff = ?");
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
    <title>MedHub | Patient Appointment</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/staff-page.css">
    <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
        <a href="#"><h2 class="std-text">Inbox</h2></a>
        <a href="./patient-appointments.php"><h2 class="std-text">Patient's Appointments</h2></a>
        <h2 class="right-side std-tip"><?php if(isset($name) && $name !== null) {
                $decrypted_name = decrypt($name);
                echo "<h2 class='right-side std-tip'>$decrypted_name</h2>";}?>
        </h2>
        <form action="./staff-page.php" method="post">
        <style>.btn-log-out {cursor: pointer;}</style>
        <input type="submit" name = "log-out" value="Log out" class="btn btn-default btn-log-out">
        </form>
    </div>
    <div class="container">
        <div class="search">
                <input type="text" id="doc" name="search_doc" placeholder="Search Doctor/Specialty" onfocus="this.placeholder = ''"
                onblur="this.placeholder = 'Search Doctor/Specialty'" oninput="searchDoc()" class="std-text"  >
                <input type="email" id="pid" name="search_pid" placeholder="Patient Email" class="std-text">
            </form>
        </div>

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
                                        <span class="card-text"><?php echo decrypt($row['name']); ?></span>
                                        <span class="card-text right-side"><?php echo decrypt($row['Speciality']); ?></span>
                                </div>
                            </div>
                            <form action="patient-page.php" method="post">
                                <input type="hidden" name="appointment_id" 
                                value="<?php echo  $row['appointment_id']?>">
                                <input type="hidden" class="patient_email" name="patient_email" value="">
                                <button class="ok-button reserve">RESERVE</button>
                            </form>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <p>No appointments found</p>
            <?php } ?>
        </div>
        <script src="js/staff.js"></script>
        
    </div>

</body>
</html>







