<?php

session_start();

$_SESSION['username'] = 'Gmgom';


// echo 'Session variable: '. $_SESSION['username']. '<br>';

session_destroy();

// echo 'Session variable after destruction: ';
// if (isset($_SESSION['username'])) {
//     echo $_SESSION['username'];
// } else {
//     echo 'Not set';
// }


if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

header('Location: index.php');
exit;

?>


