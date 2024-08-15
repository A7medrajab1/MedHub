<?php
require_once "logic/db.php";

function generate_random_password($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#%&*()?';
    $charLength = strlen($chars);
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[mt_rand(0, $charLength - 1)];
    }

    return $password;
}

function extract_from_sql ($query) {
    $conn = database::get_connection();
    $result = $conn->query($query); 
  
    if ($result->num_rows > 0)  
    { 
        return $result->fetch_assoc();
    }
}

function get_result_from_sql ($query) {
    $conn = database::get_connection();
    $result = $conn->query($query);

    return $result;
}

function date_to_age(string $year_of_birth) : int {
    $age = intval(date('Y')) - intval(explode("-" , $year_of_birth)[0]);
    return $age;
    
}
// echo date('Y');
// function loadFromDb(){
    // $_SESSION['user_type'] = $userT;
    // $unhashed_name =decrypt($row['name']);
    // $unhashed_phone =decrypt($row['phone']);
    // $unhashed_address =decrypt($row['address']);
    // $unhashed_gender =decrypt($row['gender']);
    // $unhashed_year_of_birth =decrypt($row['year_of_birth']);
    // $patient = new Patient($unhashed_name, $pword, $unhashed_phone,$email, $unhashed_address,$unhashed_year_of_birth,$unhashed_gender);
// }

?>
