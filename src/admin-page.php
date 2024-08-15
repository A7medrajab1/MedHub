<?php
require_once "logic/encryption.php";
require_once "logic/helping_functions.php";
// $_SESSION['name'] = encrypt("TEMP ADMIN");

session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "administrator") {
    // Redirect to login page.
    header('Location: ./index.php');
    exit;
}
// log out
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log-out'])) {
    header('Location: ./index.php');
    session_destroy();
    exit;
}

$path = __FILE__;
require_once "lang/get_language_post.php";

if (isset($_POST["selected_user"])) {
    $encrypted_email = encrypt($_POST['selected_user']);
    // destructive but no unique identifier for each user exists
    get_result_from_sql("DELETE FROM patient WHERE email_patient = '$encrypted_email'");
    get_result_from_sql("DELETE FROM doctor WHERE email_doctor = '$encrypted_email'");
    get_result_from_sql("DELETE FROM staff WHERE email_staff = '$encrypted_email'");
    get_result_from_sql("DELETE FROM admin WHERE email_admin = '$encrypted_email'");
}

$patients = get_result_from_sql("SELECT * FROM patient");
$doctors = get_result_from_sql("SELECT * FROM doctor");
$staffs = get_result_from_sql("SELECT * FROM staff");
$admins = get_result_from_sql("SELECT * FROM admin");

// fetch all data about patient to row and use the data to show
$conn = database::get_connection();
$hashed_email = $_SESSION['email'];
$emailInfo = $conn->prepare("SELECT * from admin where email_admin = ?");
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
        <title>MedHub | <?php echo $_LANG_DATA->admin;?></title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/admin-page.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a href="admin-add.php"><h2 class="std-text"><?php echo $_LANG_DATA->add;?></h2></a>
            <a href="admin-generate.php"><h2 class="std-text"><?php echo $_LANG_DATA->gene;?></h2></a>
            <h2 class="right-side std-tip"><?php $decrypted_name = decrypt($name); echo $decrypted_name; $_SESSION['name'] =$decrypted_name; ?></h2>
            <form action="./patient-page.php" method="post">
            <style>.btn-log-out {cursor: pointer;}</style>
            <input type="submit" name = "log-out" value="Log out" class="btn btn-default btn-log-out">
            </form>
        </div>
        <div class="container">
            <div class="section">
                <input type="text" onchange="search_user()" id="search" name="search" placeholder="<?php echo $_LANG_DATA->ser;?>" class="std-text search"><br>
                <br>
                <?php
                while ($row = $patients->fetch_assoc()) {
                    $row = decrypt_arr($row);
                    $dname = $row["name"]; $dgender = $row["gender"]; $dage = $row["date_of_birth"]; $dmail = $row['email_patient'];
                    echo "<input type='button' onclick='display_info(\"$dname\", \"$dgender\", \"$dage\", \"$dmail\")' class='result std-text' value='Patient: $dname'>";
                }
                while ($row = $doctors->fetch_assoc()) {
                    $row = decrypt_arr($row);
                    $dname = $row["name"]; $dgender = $row["gender"]; $dage = $row["year_of_birth"]; $dmail = $row['email_doctor'];
                    echo "<input type='button' onclick='display_info(\"$dname\", \"$dgender\", \"$dage\", \"$dmail\")'' class='result std-text' value='Doctor: $dname'>";
                }
                while ($row = $staffs->fetch_assoc()) {
                    $row = decrypt_arr($row);
                    $dname = $row["name"]; $dgender = $row["gender"]; $dage = $row["year_of_birth"]; $dmail = $row['email_staff'];
                    echo "<input type='button' onclick='display_info(\"$dname\", \"$dgender\", \"$dage\", \"$dmail\")' class='result std-text' value='Staff: $dname'>";
                }
                while ($row = $admins->fetch_assoc()) {
                    $row = decrypt_arr($row);
                    $dname = $row["name"]; $dgender = $row["gender"]; $dage = $row["year_of_birth"]; $dmail = $row['email_admin'];
                    echo "<input type='button' onclick='display_info(\"$dname\", \"$dgender\", \"$dage\", \"$dmail\")' class='result std-text' value='Admin: $dname'>";
                }
                ?>
            </div>
            <div class="section usr-info">
                <h2><?php echo $_LANG_DATA->inform;?></h2>
                <br>
                <input value="" disabled type="text" placeholder="<?php echo $_LANG_DATA->name;?>" id="name_display" class="std-text"><br>
                <input value="" disabled type="text" placeholder="<?php echo $_LANG_DATA->gen;?>" id="gender_display" class="std-text"><br>
                <input value="" disabled type="text" placeholder="<?php echo $_LANG_DATA->age;?>" id="age_display" class="std-text"><br>
                <br>
                <input class="send no-button std-text" type="button" onclick="delete_user()" value="<?php echo $_LANG_DATA->del;?>">
            </div>
        </div>
    </body>
    <form action="admin-page.php" method="post" id="delete_form">
        <input type="hidden" id="selected_user" name="selected_user">
    </form>
    <script>
        let selected_user = ""; // email

        function search_user() {
            const search = document.getElementById("search").value.toLowerCase();
            const results = document.querySelectorAll('.result');

            results.forEach(result => {
                const name = result.value.toLowerCase();
                console.log(name);

                if (name.includes(search)) {
                    result.style.display = ""; 
                } else {
                    result.style.display = "none"; 
                }
            });
        }

        function display_info (name, gender, date, email) {
            const name_display = document.getElementById("name_display");
            const gender_display = document.getElementById("gender_display");
            const age_display = document.getElementById("age_display");

            selected_user = email;

            name_display.value = name;
            gender_display.value = gender;
            age_display.value = date;
        }

        function delete_user () {
            const delete_form = document.getElementById("delete_form");
            const selected_user_input = document.getElementById("selected_user");

            selected_user_input.value = selected_user;
            delete_form.submit();
        }
    </script>
</html>