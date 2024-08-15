<?php
$path = __FILE__;
require_once 'lang/get_language_post.php';
require_once 'logic/db.php';
require_once 'logic/encryption.php';

$OK = true;
// Get Sign in inputs
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])){
    // Trim Inputs
    foreach ($_POST as $val) {
        $val = trim($val);
    }
    // echo $_LANG_DATA->user_opt[0];
    // echo $_LANG_DATA->user_opt[1];
    // echo $_LANG_DATA->user_opt[2];
    // echo $_LANG_DATA->user_opt[3];

    $userT = $_POST['userT'];                                                       // type checked
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);                   // type checked
    $pword = filter_var($_POST['pword'], FILTER_SANITIZE_SPECIAL_CHARS);          // type checked

    // Format Checks
    if (!$email /* || strlen($email) != N */) {$OK = false;}
    if (strlen($pword) == 0) {$OK = false;}
    // End of Format Checks

    // autherization
    if($OK){
        $conn = database::get_connection();
        $hashed_email = encrypt($email);
        $hashed_password = encrypt($pword);
        if($userT == strtolower($_LANG_DATA->user_opt[0])){
            $emailInfo = $conn->prepare("SELECT * from patient where email_patient = ?");
            $emailInfo->bind_param("s", $hashed_email);
            $emailInfo->execute();
            $emailResult = $emailInfo->get_result();
            $row = $emailResult->fetch_assoc();//row of that email.
            if ($row != null) {
                if ($hashed_password == $row['password']) {
                    $_SESSION['email'] = $row['email_patient'];
                    $_SESSION['user_type'] = $userT;
                    header('Location: ./patient-page.php');
                    exit; 
                } else {
                    $wrongMsg = 'Wrong password';
                }
            } else {
                $wrongMsg = 'Email does not exist';
            }
        }
        if($userT ==strtolower($_LANG_DATA->user_opt[2])){
            $emailInfo = $conn->prepare("SELECT * from doctor where email_doctor = ?");
            $emailInfo->bind_param("s", $hashed_email);
            $emailInfo->execute();
            $emailResult = $emailInfo->get_result();
            $row = $emailResult->fetch_assoc();//row of that email.
            if ($row != null) {
                if ($hashed_password == $row['password']) {
                    $_SESSION['email'] = $row['email_doctor'];
                    $_SESSION['user_type'] = $userT;
                    header('Location: ./doctor-page.php');
                    exit; 
                } else {
                    $wrongMsg = 'Wrong password';
                }
            } else {
                $wrongMsg = 'Email does not exist';
            }
        }
        if($userT ==strtolower($_LANG_DATA->user_opt[1])){
            $emailInfo = $conn->prepare("SELECT * from staff where email_staff = ?");
            $emailInfo->bind_param("s", $hashed_email);
            $emailInfo->execute();
            $emailResult = $emailInfo->get_result();
            $row = $emailResult->fetch_assoc();//row of that email.
            if ($row != null) {
                if ($hashed_password == $row['password']) {
                    $_SESSION['email'] = $row['email_staff'];
                    $_SESSION['user_type'] = $userT;
                    header('Location: ./staff-page.php');
                    exit; 
                } else {
                    $wrongMsg = 'Wrong password';
                }
            } else {
                $wrongMsg = 'Email does not exist';
            }
        }
        if($userT ==strtolower($_LANG_DATA->user_opt[3])){
            $emailInfo = $conn->prepare("SELECT * from admin where email_admin = ?");
            $emailInfo->bind_param("s", $hashed_email);
            $emailInfo->execute();
            $emailResult = $emailInfo->get_result();
            $row = $emailResult->fetch_assoc();//row of that email.
            if ($row != null) {
                if ($hashed_password == $row['password']) {
                    $_SESSION['email'] = $row['email_admin'];
                    $_SESSION['user_type'] = $userT;
                    header('Location: ./admin-page.php');
                    exit; 
                } else {
                    $wrongMsg = 'Wrong password';
                }
            } else {
                $wrongMsg = 'Email does not exist';
            }
        }
    }
}
// End Get inputs
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub</title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="./css/bootstrap.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <form action="index.php" class="language-form" method="post">
            <select name="lang" id="lang" onchange="this.form.submit()">
                <option value="en" <?php if ($lang == 'en') {echo 'selected';} ?>>English</option>
                <option value="ar" <?php if ($lang == 'ar') {echo 'selected';} ?>>Arabic</option>
            </select>
        </form>
        <h1 class="med-title main-title"><?php echo $_LANG_DATA->main_title;?></h1>
        <main>
            <form action="index.php" method="post">
                <?php if ($OK == false):?>
                <p class="alert alert-danger" style="text-align:center;"><strong>Wrong! </strong>Please check for invalid inputs</p>
                <?php endif; ?>
                <?php if (isset($wrongMsg)):?>
                <p class="alert alert-danger" style="text-align:center;"><strong>Wrong! </strong><?php  echo $wrongMsg; ?></p>
                <?php endif; ?>
                <a href="signup.php"><p class="register"><?php echo $_LANG_DATA->register_tip; ?></p></a>
                <div class="inp">
                    <label for="userT" class="std-text"><?php echo $_LANG_DATA->user_t;?></label>
                    <select id="userT" name="userT">
                        <option value="patient"><?php echo $_LANG_DATA->user_opt[0];?></option>
                        <option value="staff member"><?php echo $_LANG_DATA->user_opt[1];?></option>
                        <option value="doctor"><?php echo $_LANG_DATA->user_opt[2] ;?> </option>
                        <option value="administrator"><?php echo $_LANG_DATA->user_opt[3] ;?></option>
                    </select>
                </div>
                <div class="inp">
                    <label for="email" class="std-text"><?php echo $_LANG_DATA->user_id;?></label>
                    <input type="email" id="email" name="email" value="" class="login"><br>
                </div>
                <div class="inp">
                    <label for="pword" class="std-text"><?php echo $_LANG_DATA->pword;?></label>
                    <input type="password" id="pword" name="pword" value="" class="login"><br>
                </div>
                <br>
                <input type="submit" class="ok-button submit" value="<?php echo $_LANG_DATA->submit;?>">  
            </form>
        </main>
    </body>
</html>