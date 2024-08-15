<?php

$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/db.php";
require_once "logic/helping_functions.php";
require_once "logic/encryption.php";

$curr_patient_email = "";

session_start();
if (!isset($_SESSION['email_patient'])) {
    if (isset($_SESSION['email_staff']) && isset($_SESSION['curr_pat_email'])) {
        $curr_patient_email = $_SESSION['curr_pat_email'];
    } else {
        header('Location: ./index.php');
        exit;
    }
} else {
    $curr_patient_email = $_SESSION['email_patient'];
}

if (isset($_POST['selected_appointment'])) {
    $app_id = $_POST['selected_appointment'];
    get_result_from_sql("UPDATE appointment SET is_paid = 1 WHERE appointment_id = $app_id");
}

if (isset($_POST['cancel_selected_appointment'])) {
    $app_id = $_POST['cancel_selected_appointment'];
    get_result_from_sql("UPDATE appointment SET is_reserved = 0, email_patient = NULL WHERE appointment_id = $app_id");
}


$query = "SELECT *
          FROM appointment
          INNER JOIN prescription ON appointment.appointment_id = prescription.appointment_id 
          INNER JOIN doctor ON appointment.email_doctor = doctor.email_doctor
          WHERE appointment.is_reserved = 1 AND appointment.email_patient = '" . $curr_patient_email . "'";

$result = database::get_connection()->query($query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub | Past Appointments</title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/patient-appointment.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a 
            <?php
            if (isset($_SESSION['curr_pat_email'])) {
                echo 'href="staff-page.php"';
            } else {
                echo 'href="patient-page.php"';
            }
            ?>
            ><h2 class="std-text">Home</h2></a>
            <h2 class="right-side std-tip"><?php $decrypted_name = decrypt($_SESSION['name']); echo $decrypted_name ?></h2>
        </div>
        <div class="container">
            <div class="section">
                <h2>Past Appointments</h2><br>
                <div class="appointments-list">
                    <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $treatment = $row['treatment']; $disease = $row['disease_info']; $bill = $row['price']; $is_paid = $row['is_paid']; $app_id = $row['appointment_id'];
                                echo "<button class='appointment' onclick='choose_appointment(\"$treatment\", \"$disease\", \"$bill\", \"$is_paid\", \"$app_id\")'>
                                        <span>Doctor: {$row['name']}</span>
                                        <span>Time: {$row['data']} {$row['time']}</span>
                                    </button>";
                            }
                        } else {
                            echo "<p class='std-text'>No past appointments found</p>";
                        }
                    ?>
                </div>
            </div> 

            <div class="info-section">
                <div class="insider-section">
                    <h2>Treatment</h2>
                    <p class="std-small-text" id="treatment_display">Medicine Name</p>
                </div>

                <div class="insider-section">
                    <h2>Disease Info</h2>
                    <p class="std-small-text" id="disease_display">Lorem Ipsum Dolor Sit Amet</p>
                </div>

                <div class="insider-section">
                    <h2>Appointment Bill</h2>
                    <p class="std-small-text" id="bill_display">300 USD</p>
                    <button class="ok-button pay std-small-text" id="pay_display" onclick="pay_bill()">Pay Bill</button><br><br>
                    <button class="no-button pay std-small-text" id="cancel_displat" onclick="cancel_appointment()">Cancel Appointment</button>
                </div>
            </div>
        </div>
        <form action="patient-appointments.php" method="post" id="pay_form">
            <input type="hidden" id="selected_appointment" name="selected_appointment">
        </form>
        <form action="patient-appointments.php" method="post" id="cancel_form">
            <input type="hidden" id="cancel_selected_appointment" name="cancel_selected_appointment">
        </form>
    </body>
    <script>
        let selected_appointment_id = "";

        function choose_appointment(treatment, disease_info, bill, paid, app_id) {
            const treatment_display = document.getElementById("treatment_display");
            const disease_display = document.getElementById("disease_display");
            const bill_display = document.getElementById("bill_display");
            const pay_display = document.getElementById("pay_display");
            const cancel_display = document.getElementById("cancel_display");

            selected_appointment_id = app_id;

            treatment_display.innerHTML = treatment;
            disease_display.innerHTML = disease_info;
            bill_display.innerHTML = bill + " EGP";
            pay_display.style.visibility = (paid == 1)? 'hidden' : 'visible';
            cancel_display.style.visibility = (paid == 1)? 'hidden' : 'visible';
        }

        function pay_bill() {
            if (selected_appointment_id != "") {
                console.log("paid");
                const pay_form = document.getElementById("pay_form");
                const selected_appointment = document.getElementById("selected_appointment");
    
                selected_appointment.value = selected_appointment_id;
                pay_form.submit();
            }
        }

        function cancel_appointment() {
            if (selected_appointment_id != "") {
                const cancel_form = document.getElementById("cancel_form");
                const cancel_selected_appointment = document.getElementById("cancel_selected_appointment");
    
                cancel_selected_appointment.value = selected_appointment_id;
                cancel_form.submit();
            }
        }
    </script>
</html>