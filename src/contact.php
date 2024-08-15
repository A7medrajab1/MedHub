<?php
$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/db.php";
require_once "logic/encryption.php";
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ./index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $messages = $_POST['message'];
    $title = $_POST['title'];
    // insert the data into db
    $conn = database::get_connection();
    $stmt = $conn->prepare("INSERT INTO message (patient_email, message_title, message) VALUES (?, ?, ?)");
    $enc_email = $_SESSION['email'];
    $enc_msg = encrypt($messages);
    $enc_titel = encrypt($title);
    $stmt->bind_param("sss", $enc_email, $enc_titel, $enc_msg);
    $success = $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedHub | <?php echo $_LANG_DATA->conn;?></title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/contact.css">
    <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
        <a href="patient-page.php"><h2 class="std-text"><?php echo $_LANG_DATA->home;?></h2></a>
        <h2 class="right-side std-tip"><?php echo $_SESSION['name'];?></h2>
    </div>
    <div class="container">
        <div class="section">
            <h2 class="std-text"><?php echo $_LANG_DATA->tit;?></h2>
            <?php if (isset($success)): ?>
                <p class="alert alert-success" style="text-align:center;"><strong><?php echo $_LANG_DATA->suc;?> </strong><?php echo "<?php echo $_LANG_DATA->has;?>" ?></p>
            <?php endif; ?> 
            <form action="" method="post">
                <input type="text" id="title" name="title" placeholder="<?php echo $_LANG_DATA->sub;?>" class="title std-text"><br>
                <textarea id="message" name="message" class="std-text message-field" rows="4" cols="100" placeholder="<?php echo $_LANG_DATA->mess;?>"></textarea><br>
                <input class="ok-button send std-text" type="submit" value="<?php echo $_LANG_DATA->send;?>" name="submit"/>
            </form>
            <p class="std-text"><?php echo $_LANG_DATA->final;?></p>
        </div>
    </div>
</body>
</html>
