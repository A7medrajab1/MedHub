<?php
/*
    - disease_info:string
    - age:intger
    - treatment:string 
*/
class Prescription{
    private string $disease_info;
    private string $disease_title;
    private int $age ;
    private string $treatment;

    public function set_disease_info(string $disease_info){
        $this->disease_info = $disease_info;
    }
    public function get_disease_info():string{
        return $this->disease_info;
    }
    public function set_age(int $age):bool{
        $this->age = $age;
        return true;
        
    }
    public function get_age():int{
        return $this->age;
    }
    public function set_treatment(string $treatment):bool{
        $this->treatment = $treatment;
        return true;
        
    }
    public function get_treatment():string{
        return $this->treatment;
    }
    public function set_disease_title(string $disease_title):bool{
        $this->disease_title = $disease_title;
        return true;
        
    }
    public function get_disease_title():string{
        return $this->disease_title;
    }

    function save_prescription(int $Pre_id) : bool {
        require_once './logic/db.php';
        require_once './logic/helping_functions.php';
        require_once './email/templates.php';

        $conn = database::get_connection();

        
        $stmt = $conn->prepare("INSERT INTO prescription (appointment_id , treatment  ,disease_info , disease_title , age) VALUES (?,?,?,?,?)");
        //                 12345  
        $stmt->bind_param('issss',
        $Pre_id ,      //1
        $treatment,    //2
        $disease_info ,   //3
        $disease_title ,  //4
        $age 
        
        );
        $treatment = encrypt($this->treatment);
        $disease_info = encrypt($this->disease_info);
        $age = encrypt(''.$this->age);
        
        return $stmt->execute();
        
    }

}