<?php
$path = __FILE__;
require_once "lang/get_language_post.php";

include "logic/db.php";
require_once "logic/encryption.php";
require_once "Users/Patient.php";
$OK = true;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Trim Inputs
    foreach ($_POST as $val) {
        $val = trim($val);
    }

    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);            // type checked
    $phone = $_POST['phone'];                                                     // UNCHECKED TYPE
    $gender = $_POST['gender'];                                                   // type checked
    $address = filter_var($_POST['address'], FILTER_SANITIZE_SPECIAL_CHARS);      // type checked
    $email = $_POST['email'];                                                     // type checked
    $pword = filter_var($_POST['pword'], FILTER_SANITIZE_SPECIAL_CHARS);          // type checked
    $cpword = filter_var($_POST['cpword'], FILTER_SANITIZE_SPECIAL_CHARS);        // type checked
    $year_of_birth = $_POST['year_of_birth'];                                                         // type checked
    // Format Checks
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {$OK = false;}
    if (intval($phone) == 0 || strlen($phone) != 11) {$OK = false;}
    if ($pword != $cpword || strlen($pword) == 0) {$OK = false;}
    $year_of_birth = intval(date("Y")) - intval($year_of_birth);
    if ($year_of_birth <= 0 || $year_of_birth > 130) {$OK = false;}
    // End of Format Checks

    // create object from patient class
    if($OK === true){
        $conn = database::get_connection();
        // check if patient email is exist?
        $hashed_email = encrypt($email);
        $userNameInfo = $conn->prepare("SELECT * FROM patient WHERE email_patient = ?");
        $userNameInfo->bind_param("s", $hashed_email);
        $userNameInfo->execute();
        $userNameResult = $userNameInfo->get_result();
        $row = $userNameResult->fetch_assoc();

        if (!empty($row['email_patient'])) {
            $existMsg = 'Email is already exists';
        } else {
            $patient = new Patient($name,$pword,$phone,$email,$address,$year_of_birth,$gender);
            $success = $patient->sign_up($conn);
            if ($success) {
                $Msg = 'Signup is successful! Please login to continue: ';
            } else {
                $Msg = 'signup is failed.';
            }
        }
    }
}
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
        <h1 class="med-title main-title">Welcome to MedHub Patient Care System</h1>
        <main>
            <form action="signup.php" method="post">
                <?php if ($OK == false):?>
                <p class="alert alert-danger" style="text-align:center;"><strong>Wrong! </strong>Please check for invalid inputs</p>
                <?php endif; ?>
                <?php if (isset($Msg)):?>
                <p class="alert alert-success" style="text-align:center;"><strong>Success! </strong><?php echo $Msg; ?><a href="index.php" class="alert-link">login</a></p>
                <?php endif; ?>
                <?php if (isset($existMsg)):?>
                <p class="alert alert-danger" style="text-align:center;"><strong>Wrong! </strong><?php echo $existMsg; ?></p>
                <?php endif; ?>
                <div class="inp">
                    <label for="name" class="std-text">name</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" class="login"><br>
                </div>
                <div class="inp">
                    <label for="phone" class="std-text">phone number</label>
                    <input type="number" name="phone" id="phone"value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                <div class="inp">
                    <label for="gender" class="std-text">gender</label>
                    <select id="gender" name="gender">
                        <option value="m">male</option>
                        <option value="f">female</option>
                    </select>
                </div>
                <div class="inp">
                    <label for="address" class="std-text">address</label>
                    <input type="text" name="address" id="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                </div>
                <div class="inp">
                    <label for="email" class="std-text">email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br>
                </div>
                <div class="inp">
                    <label for="pword" class="std-text">password</label>
                    <input type="password" id="pword" name="pword" minlength = "6" maxlength = "20" ><br>
                </div>
                <div class="inp">
                    <label for="cpword" class="std-text">confirm password</label>
                    <input type="password" id="cpword" name="cpword" minlength = "6" maxlength = "20" ><br>
                </div>
                <div class="inp">
                    <label for="year_of_birth" class="std-text">Year of Birth</label>
                    <select id="year_of_birth" name="year_of_birth">
                        <?php 
                        $currentYear = date("Y");
                        $startYear = 1900;
                        for ($year = $currentYear; $year >= $startYear; $year--) {
                            $selected = isset($_POST['year_of_birth']) && $_POST['year_of_birth'] == $year ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>
                <br>
                <input type="submit" class="ok-button submit" value="submit">  
            </form>
        </main>
    </body>
</html>