<?php
$path = __FILE__;
require_once "lang/get_language_post.php";
require_once "logic/helping_functions.php";
require_once "logic/encryption.php";
require_once "email/email.php";

$msg_id = "";
$msg = "";

if (isset($_POST['show-msg'])) {
    foreach ($_POST['show-msg'] as $id => $val) {
        $msg_id = $id;
        $msg = decrypt(extract_from_sql("SELECT * from message WHERE message_id = $id")['message']);
    }
}

if (isset($_POST['response']) && isset($_POST['msg_id']) && $_POST['msg_id'] != "") {
    $msg_id = $_POST['msg_id'];
    $update_query = "UPDATE message SET responded = 1 WHERE message_id = $msg_id";
    get_result_from_sql($update_query);
    
    $email_to_respond = decrypt(extract_from_sql("SELECT * from message WHERE message_id = $msg_id")['patient_email']);
    $response = $_POST['response'];
    send_email($email_to_respond, "response to your recent inquiry", $response);
    $msg_id = "";
}

$inbox = get_result_from_sql("SELECT * FROM message WHERE responded = 0");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub | Staff</title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/staff-respond.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a href="#"><h2 class="std-text">Home</h2></a>
            <h2 class="right-side std-tip">Staff's Full Name</h2>
        </div>
        <div class="container">
            <div class="section">
                <textarea readonly class="message-field std-text" style="transition:0;opacity:1;">
                <?php echo $msg; ?>
                </textarea>
                <form action="staff-respond.php" method="post">
                    <textarea id="response" name="response" class="message-field std-text" rows="4" cols="100" placeholder="Response Massage.."></textarea><br>
                    <input class="ok-button std-text respond" type="submit" value="Respond">
                    <input type="hidden" name="msg_id" value="<?php echo $msg_id; ?>">
                </form>
            </div>
            <div class="section">
                <h2 class="std-text">Inbox</h2>
                <form action="staff-respond.php" method="post">
                    <?php
                    if (isset($inbox) && $inbox->num_rows > 0) {
                        while ($row = $inbox->fetch_assoc()) {
                            echo '<input type="submit" class="inbox-msg std-text" value="' . $row['message_title'] . '" name=show-msg[' . $row['message_id'] . ']>';
                        }
                    } else {
                        echo '<input type="button" disabled class="inbox-msg std-text" value="EMPTY INBOX">';
                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>