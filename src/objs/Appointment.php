<?php

require 'Prescription.php';

class Appointment{
    private int $ID;
    private string $time;
    private string $date;
    private string $patient_email;
    private string $doc_email;
    private float $price;
    public bool $is_paid;
    public bool $is_researved;
    public bool $is_done;

    private Prescription $prescription;
    
    /*
    *  Pre : appoiment is in db
    *  Post : 
            true if assigned succesfully
            false if failed
    */
    public function assign_patient_to_appointment():bool{
        require_once 'logic/db.php';
        $conn = database::get_connection();
        
        $stmt = $conn->prepare("UPDATE appointment SET email_patient = ? , is_reserved = 1 WHERE appointment_id = ?"); 
        $stmt->bind_param('si',$email_patient , $id);
        $email_patient = encrypt($this->get_patient_email());
        $id = $this->get_id();
        return $stmt->execute();
        
    }
    
    public function is_in_db():bool{
        require_once 'logic/db.php';
        $conn = database::get_connection();
        
        $stmt = $conn->prepare("SELECT * FROM appointment WHERE appointment_id = ?"); 
        $stmt->bind_param('i' , $id);
        if($stmt->num_rows() == 0 ) return false;
        else return true ;
    }

    public function set_id(int $ID){
        $this->ID = $ID;
    }
    public function get_id(){
        return $this->ID;
    }

    public function set_time(string $time){
        $this->time = $time;
        
    }
    public function get_time():string{
        return $this->time;

    }
    public function set_date(string $date)  {
        $this->date = $date;
        
    }
    public function get_date() : string {
        return $this->date;
    }
    public function set_patient_email(string $patient_email)
    {
        $this->patient_email = $patient_email;
        
    }
    public function get_patient_email():string
    {
        return $this->patient_email;

    }
    public static function get_patient_email_from_db(int $id):string{
        require_once 'logic/db.php';
        $conn = database::get_connection(); 
        $res = $conn->query("SELECT email_patient FROM appointment WHERE appointment_id = ".$id);
        return $res->fetch_assoc()['email_patient'];
    }
    public function set_doc_email(string $email){
        $this->doc_email = $email;
    }
    public function get_doc_email() : string
    {
        return $this->doc_email;
    }

    public function set_price(float $price){
        $this->price = $price;
    }
    public function get_price():float{
        return $this->price;
    }

    public function set_prescription(Prescription $prescription){
        $this->prescription = $prescription;
    }
    public function get_prescription():Prescription
    {
        return $this->prescription;
    }
    
    function handle_doc_prescrpion_form(float $cost , string $treatment , string $disease_title , string $disease_info , string $age) : bool {
        $this->prescription = new Prescription();
        $this->prescription->set_age(intval($age));
        $this->prescription->set_treatment($treatment);
        $this->prescription->set_disease_info($disease_info);
        $this->prescription->set_disease_title($disease_title);
        require_once './logic/db.php';
        $conn = database::get_connection();
        if($this->prescription->save_prescription($this->get_id()) && 
           $conn->query('UPDATE appointment set is_done = 1 , price = '.$cost.' WHERE appointment_id = '.$this->ID)){

            return true;


        }else {
            return false;
        }

        
    }




}