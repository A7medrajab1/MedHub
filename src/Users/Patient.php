<?php
require_once 'User.php';
require_once "logic/encryption.php";

class Patient extends User {

	
	
    function __construct(string $name, string $password, string $phone, string $email,string $address,int $year_of_birth ,string $gender)
    {
        parent::__construct($name , $password, $phone, $email,$address,$year_of_birth , $gender);
        $this->set_year_of_birth($year_of_birth);
    }

    // public function loadFromDb(){

    // }


    public function reserve_appointment(Appointment $app):bool {
        $app->set_patient_email($this->get_email());
        return $app->assign_patient_to_appointment();
    }
	
	public function cancel_appointment() {

    }
	
	public function reschedule_appointment() {

    }
	
	public function lookup_latest_prescription() {

    }
	
	public function contact_staff( ) {

    }
	
	public function pay_bill(Appointment $appointment_id) :bool{

            $appointment_id->is_reserved = 1;
            
            return true;
        
    }
	
    public function sign_up($conn) {
        // Hash the data before storing it in the database

        $hashed_name = encrypt($this->get_name());
        $hashed_password = encrypt($this->get_password());
        $hashed_phone = encrypt($this->get_phone());
        $hashed_email = encrypt($this->get_email());
        $hashed_address = encrypt($this->get_address());
        $hashed_gender = encrypt($this->get_gender());
        $hashed_year_of_birth = encrypt("".$this->get_year_of_birth());
        // Prepare and execute the SQL statement
        $conn = database::get_connection();
        $stmt = $conn->prepare("INSERT INTO patient (name, phone, gender, address, email_patient, password, year_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $hashed_name, $hashed_phone, $hashed_gender, $hashed_address, $hashed_email, $hashed_password, $hashed_year_of_birth); 
        $success = $stmt->execute();
        
        return $success;
    }
}
