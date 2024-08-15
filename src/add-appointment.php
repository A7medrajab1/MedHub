<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "doctor") {
    header('Location: ./index.php');
    exit;
}
    require_once 'Users/Doctor.php';
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
        $name = $_SESSION['name'];
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $doc = new Doctor();
        // $doc->fetch_from_db();
        $date = $_POST['date'];
        $time = $_POST['time'];
        if(!$doc->add_appointments($date , $time)){
            echo '<script>alert(\'this appointment already exists\')</script>';
            // var_dump($doc->add_appointments($date , $time));
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub | Add Appointment</title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/add-appointment.css">
        <link rel="stylesheet" href="./css/bootstrap.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    <title>add appointment</title>
</head>
<body>

<div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a href="admin-add.php"><h2 class="std-text">Home</h2></a>
            <h2 class="right-side std-tip"><?php echo $name; ?></h2>
</div>

<div class="container">
    <form action="add-appointment.php" method="post" class="language-form">

        <div>
            <label for="date">date</label>
            <input type="date" required name="date" id="date">
        </div>
        <div>
            <label for="time">time</label>
            <input type="time" required name="time" id="time">
        </div>
        <input type="submit" value="add appointment" class="ok-button ">
    </form>
</div>
    
</body>
</html>