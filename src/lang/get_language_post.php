<?php
// Set Language
$lang = 'en';
if (isset($_POST['lang'])){
    $lang = $_POST['lang'];
    setcookie('lang',$lang,time()+60*60*24*30);
} 
elseif (isset($_COOKIE['lang'])){
    $lang = $_COOKIE['lang'];
}

$lang_buff = fopen("lang/".basename($path, ".php")."/".$lang.".json", "r") or die("Unable to open file!");
$_LANG_DATA = json_decode(fread($lang_buff, fstat($lang_buff)['size']));
fclose($lang_buff);
// End Set Language
?>

