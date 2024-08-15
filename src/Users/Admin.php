<?php
require_once 'User.php';
require_once 'Patient.php';
require_once './logic/encryption.php';

class Admin extends User
{
    function __construct(string $name,string  $password,string  $phone,string  $email,string $address,int $year_of_birth, string $gender, $salary = 0)
    {
        parent::__construct($name, $password, $phone, $email,$address,$year_of_birth , $gender);
        $this->set_salary($salary);
    }
    private $salary;
    public function set_salary($salary)
    {
        $this->salary = floatval($salary);
    }
    public function get_salary(): float
    {
        return $this->salary;
    }
    
    public function register_doctor(Doctor $doc): bool
    {
        // require_once './logic/encryption.php';
        require_once './logic/db.php';
        require_once './logic/helping_functions.php';
        require_once './email/templates.php';

        $conn = database::get_connection();
        
        $stmt = $conn->prepare("SELECT email_doctor FROM doctor WHERE email_doctor=?");
        $hashed_email = encrypt($doc->get_email());
        $stmt->bind_param('s',$hashed_email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() != 0) {
            $conn->close();
            return false;
        } else { 
            // $email = encrypt($doc->get_email());
            $passwd = encrypt( $doc->get_password() );
            $name = encrypt( $doc->get_name());
            $speciality = encrypt( $doc->get_speciality());
            $address = encrypt( $doc->get_address());
            $gender = encrypt( $doc->get_gender());
            $year = encrypt( "".$doc->get_year_of_birth());
            $phone = encrypt($doc->get_phone());
            
            //                                              1             2       3        4         5        6         7        8                  1 2 3 4 5 6 7 8    
            $stmt = $conn->prepare("INSERT INTO doctor (email_doctor ,password , name ,Speciality ,phone , address , gender ,year_of_birth) VALUES (?,?,?,?,?,?,?,?)");
            //                 12345678   
            $stmt->bind_param('ssssssss',
            $hashed_email ,//1
            $passwd,       //2
            $name ,        //3
            $speciality ,  //4
            $phone ,       //5 
            $address,      //6 
            $gender ,      //7
            $year ,        //8 
            
        );
        
        $stmt->execute();
        send_new_doc_email($doc->get_name(),$doc->get_password(),$doc->get_email());
        $conn->close();
        return true ;
    }
}
public function modify_doctor_info()
{
    
}
public function register_staff(Staff $staff)
{
    require_once './logic/db.php';
    require_once './logic/helping_functions.php';
    require_once './email/templates.php';
    
    $conn = database::get_connection();
    $stmt = $conn->prepare("SELECT email_staff FROM staff WHERE email_staff=?");
    $hashed_email = encrypt($staff->get_email());
    $stmt->bind_param('s',$hashed_email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows() != 0) {
        $conn->close();
        return false;
    }else{
        // encrypted data 
        $stmt = $conn->prepare("INSERT INTO staff (email_staff ,password , name ,phone , address , gender ,year_of_birth) VALUES (?,?,?,?,?,?,?)");
        //                 1234567   
        $stmt->bind_param('sssssss',
        $hashed_email ,//1
        $passwd ,      //2
        $name ,        //3
        $phone ,       //4
        $address,      //5
        $gender ,      //6
        $year ,        //7
        
        );
        
        $name = encrypt($staff->get_name());
        $phone = encrypt($staff->get_phone());
        $address = encrypt($staff->get_address());
        $year = encrypt($staff->get_year_of_birth());
        $passwd = encrypt($staff->get_password());
        $gender = encrypt($staff->get_gender());
        
        $stmt->execute();
        send_new_admin_email($staff->get_name(),$staff->get_password(),$staff->get_email());
        $conn->close();
        return true ;
    }

}
public function modify_staff_info()
{
}
public function add_admin(Admin $admin)
{
    require_once './logic/db.php';
    require_once './logic/helping_functions.php';
    require_once './email/templates.php';
    
    $conn = database::get_connection();
    $stmt = $conn->prepare("SELECT email_admin FROM admin WHERE email_admin=?");
    $hashed_email = encrypt($admin->get_email());
    $stmt->bind_param('s',$hashed_email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows() != 0) {
        $conn->close();
        return false;
    }else{
        // encrypted data 
        $stmt = $conn->prepare("INSERT INTO admin (email_admin ,password , name ,phone , address , gender ,year_of_birth) VALUES (?,?,?,?,?,?,?)");
        //                 1234567   
        $stmt->bind_param('sssssss',
        $hashed_email ,//1
        $passwd ,      //2
        $name ,        //3
        $phone ,       //4
        $address,      //5
        $gender ,      //6
        $year ,        //7
        
        );
        
        $name = encrypt($admin->get_name());
        $phone = encrypt($admin->get_phone());
        $address = encrypt($admin->get_address());
        $year = encrypt($admin->get_year_of_birth());
        $passwd = encrypt($admin->get_password());
        $gender = encrypt($admin->get_gender());
        
        $stmt->execute();
        send_new_admin_email($admin->get_name(),$admin->get_password(),$admin->get_email());
        $conn->close();
        return true ;

    }

        
        
    }
}
