<?php
$path = __FILE__;
require_once 'lang/get_language_post.php';

require_once 'Users/Admin.php';
require_once 'Users/Doctor.php';
require_once 'Users/Staff.php';
require_once './logic/helping_functions.php';

session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != "administrator") {
    // Redirect to login page.
    header('Location: ./index.php');
    exit;
}
if(isset($_SESSION['name'])){
    $nameadmin = $_SESSION['name'];
}

$admin = new Admin('xdd','xddd','xddd','xddd','xddd',20 , 'm');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['usertype'] ,
         $_POST['email'] , 
         $_POST['Name'],
         $_POST['address'],
         $_POST['email'],
         $_POST['phone'],
         $_POST['year_of_birth'] , 
         $_POST['gender'])) {

        $user_type = filter_var($_POST['usertype'], FILTER_SANITIZE_SPECIAL_CHARS); 
        $name = filter_var($_POST['Name'], FILTER_SANITIZE_SPECIAL_CHARS); 
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS); 
        $year_of_birth = intval(filter_var($_POST['year_of_birth'], FILTER_SANITIZE_NUMBER_INT)); 
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS); 
        $address = filter_var($_POST['address'], FILTER_SANITIZE_SPECIAL_CHARS); 
        $passwd = generate_random_password();
        if ($user_type === 'doctor') {
            $speciality = $_POST['speciality'];

            $new_doc = new Doctor($name ,$passwd , $phone,$email,$address,$speciality ,$year_of_birth , $gender);
            if (!$admin->register_doctor($new_doc)){
                echo '<script>alert(\'this doc already exists\')</script>';
                
            }
            
        }
        else if($user_type === 'admin'){
            $new_admin = new Admin($name,$passwd,$phone,$email,$address,$year_of_birth,$gender);
            if(!$admin->add_admin($new_admin)){
                echo '<script>alert(\'this admin already exists\')</script>';
            }
        }
        else if($user_type === 'staff'){
            $new_staff = new Staff($name,$passwd,$phone,$email,$address,$year_of_birth,$gender);
            if(!$admin->register_staff($new_staff)){
                echo '<script>alert(\'this admin already exists\')</script>';
            }
        }
    }else echo "<script>alert('All data are required')</script>";
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedHub | Add User</title>
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/admin-add.css">
        <link rel="icon" href="./imgs/icon.ico" type="image/x-icon">
    </head>
    
    <body>
        <div class="navbar">
            <h1 class="med-title">MedHub</h1><h1 class="med-line med-title">|</h1>
            <a href="./admin-page.php"><h2 class="std-text"> <?php echo $_LANG_DATA->Home;?></h2></a>
            <?php if (isset($name)) {echo "<h2 class='right-side std-tip'>$nameadmin</h2>";}?>
            </h2>
        </div>

        <div class="container">
            <div class="section">
                <div>
                    <h2>  <?php echo $_LANG_DATA->Add;?></h2>
                    <!-- <button class="ok-button std-small-text" style="width:250px;">Generate User ID</button> -->
                </div>

                <form action="admin-add.php" method="post">
                    <div class="form-elem">
                        <label for="userid" class="std-text"> <?php echo $_LANG_DATA->Email;?> </label>
                        <input type="text" id="userid" name="email" placeholder="<?php echo $_LANG_DATA->Email;?>" class="std-small-text">
                    </div>

                    <div class="form-elem">
                        <label for="usertype" class="std-text"><?php echo $_LANG_DATA->user_type;?></label>
                        <select id="usertype" name="usertype">
                          <option value="admin"><?php echo $_LANG_DATA->admin;?></option>
                          <option value="staff"><?php echo $_LANG_DATA->staff;?></option>
                          <option value="doctor"><?php echo $_LANG_DATA->doctor;?></option>
                          <!-- <option value="patient">Patient</option> -->
                        </select>
                    </div>

                    <div class="form-elem">
                        <label for="Name" class="std-text"><?php echo $_LANG_DATA->name;?></label>
                        <input type="text" id="Name" name="Name" placeholder="<?php echo $_LANG_DATA->name;?>" class="std-small-text">
                    </div>

                    <div class="form-elem">
                        <label for="Phone" class="std-text"><?php echo $_LANG_DATA->phone;?></label>
                        <input type="text" id="phone" name="phone" placeholder="<?php echo $_LANG_DATA->phone;?>" class="std-small-text">
                    </div>

                    <div class="form-elem">
                        <label for="year_of_birth" class="std-text"><?php echo $_LANG_DATA->year;?></label>
                        <input type="text" id="year_of_birth" name="year_of_birth" placeholder="<?php echo $_LANG_DATA->year;?>" class="std-small-text">
                    </div>

                    <div class="form-elem">
                        <label for="address" class="std-text"><?php echo $_LANG_DATA->address;?></label>
                        <input type="text" id="address" name="address" placeholder="<?php echo $_LANG_DATA->address;?>" class="std-small-text">
                    </div>

                    <div class="form-elem">
                        <label for="gender" class="std-text"><?php echo $_LANG_DATA->gender;?></label>
                        <select id="gender" name="gender">
                          <option value="m"><?php echo $_LANG_DATA->male;?></option>
                          <option value="f"><?php echo $_LANG_DATA->fmale;?></option>
                        </select>
                    </div>
                    <div class="form-elem">
                        <label for="speciality" class="std-text"><?php echo $_LANG_DATA->speciality;?></label>
                        <select disabled id="speciality" name="speciality">
                          <!-- <option>choose doc speciality</option> -->
                          <option value="type1"><?php echo $_LANG_DATA->type1;?></option>
                          <option value="type2"><?php echo $_LANG_DATA->type2;?></option>
                        </select>
                    </div>

                    <input type="submit" value="<?php echo $_LANG_DATA->add;?>" class="ok-button std-small-text add-user">
                </form>
            </div>
        </div>
    </body>
    <script src="js/admin_add.js"></script>
</html>
