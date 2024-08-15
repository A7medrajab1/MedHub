<?php
require_once "User.php";
require_once "Patient.php";

class Doctor extends User{
    // private $salary;
    private $speciality;
    function __construct(string $name = null , string $password = null, string $phone = null, string $email = null , string $address = null,string $speciality = null,int $year_of_birth = null , string $gender = null, $salary = 0)
    {
        parent::__construct($name , $password, $phone, $email ,$address,$year_of_birth , $gender);
        // $this->salary = floatval($salary);
        if ($speciality) $this->speciality = $speciality;
    }

    public function set_speciality(string $speciality){
        $this->speciality = $speciality;
    }
    public function get_speciality():string{
        return $this->speciality ;
    }


    function fetch_from_db(): bool{
        if(!isset($_SESSION['email'])){
            return false;
        }
        require_once './logic/db.php';
        // require_once './logic/helping_functions.php';
        // require_once './email/templates.php';
        
        $conn = database::get_connection();
        
        $query = "SELECT email_doctor FROM doctor WHERE email_doctor = ".$_SESSION['email'];
        $result = $conn->query($query); 
        
        if ($result->num_rows > 0)  
        { 
            $row = $result->fetch_assoc();
            $this->set_name(decrypt($row['name']));
            $this->set_email(decrypt($row['email']));
            $this->set_address(decrypt($row['address']));
            $this->set_speciality(decrypt($row['Speciality']));
            $this->set_year_of_birth(decrypt($row['year_of_birth']));
            $this->set_phone(decrypt($row['phone']));
            $this->set_gender(decrypt($row['gender']));
            
            return true;
        }
        return false;
    }
    function lookupAppointments(): void{
        
    }
    function add_appointments(string $date , string $time){
        require_once './logic/db.php';
        $conn = database::get_connection();
        
        $query = "SELECT * FROM appointment WHERE email_doctor = '".$_SESSION['email'] . "' AND time = '" . $time . "' AND date = '".$date."'";
        $result = $conn->query($query); 
        if($result->num_rows == 0){
            //                                            1            2     3            1 2 3      
            $stmt = $conn->prepare("INSERT INTO appointment (email_doctor  , time ,date ) VALUES (?,?,?)");
            //                 123  
            $stmt->bind_param('sss',
                $doc_email ,    //1
                $time         , //2
                $date         , //3 
            );
            $doc_email = $_SESSION['email'];
            
            return $stmt->execute(); 


        }
        else return false ;

    }
    function writePrescription(): void{

    }
    function addToMedicalHistory(Patient $patient){
        

    }
    function seeAllPatientInfo(string $patient_email): mixed{
        

    }
    function sendPatientBill(Patient $patient): void{

    }
}