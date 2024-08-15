<?php
require_once 'User.php';

class Staff extends User {
    function __construct(string $name, string $password, string $phone, string $email, string $address, int $year_of_birth, string $gender)
    {
        parent::__construct($name , $password, $phone, $email, $address, $year_of_birth ,$gender);
    }

    /*
    APPOINTMENT OBJECT

        `appointment_id` int(10) NOT NULL,
        `email_doctor` varchar(50) NOT NULL,
        `email_patient` varchar(50) NOT NULL,
        `time` date NOT NULL,
        `price` double(10,2) NOT NULL,
        `visa_info` text NOT NULL,
        `is_paid` tinyint(1) NOT NULL
    */

    public function reserve_appointment_for_patient(string $patient_email, int $appointment_id) {
        require_once './logic/db.php';

        $conn = database::get_connection();
        $stmt = $conn->prepare("UPDATE appointment SET email_patient = '$patient_email' WHERE appointment_id = '$patient_email'");
        $stmt->execute();
        $conn->close();
    }
	
	public function respond_to_patient(string $patient_email, string $msg_title, string $response) {
        require_once './email/email.php';

        send_email($patient_email, $msg_title, $response);
    }
}
