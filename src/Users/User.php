<?php

abstract class User {
    private string $name;
    private string $password;
    private string $phone;
    private string $email;
    private string $address;
    private string $gender;
    private int $year_of_birth;

    function __construct(string $name = NULL ,string $password = NULL, string $phone = NULL , string $email = NULL , string $address = NULL , int $year_of_birth = NULL , string $gender = NULL){
        if($name) $this->set_name($name);
        if($password) $this->set_password($password);
        if($password) $this->set_phone($phone);
        if($email) $this->set_email($email);
        if($address) $this->set_address($address);
        if($year_of_birth) $this->set_year_of_birth($year_of_birth);
        if($gender) $this->set_gender($gender);
    }

    public function set_name(string $name){
        $this->name = $name;
    }
    public function get_name():string
    {
        return $this->name ;
    }

    public function set_password(string $password){
        $this->password = $password;
    }
    public function get_password():string{
        return $this->password ;
    }
    
    public function set_email(string $email){
        $this->email = $email;
    }
    public function get_email():string{
        return $this->email ;
    }
  
    public function set_phone(string $phone){
        $this->phone = $phone;
    }
    public function get_phone():string{
        return $this->phone ;
    }

    public function set_address(string $address){
        $this->address = $address;
    }
    public function get_address():string{
        return $this->address ;
    }
    
    public function set_gender(string $gender):bool
    {
        $gender = strtolower($gender);
        if($gender === 'm' || $gender === 'f'){
            $this->gender = $gender;
            return true;
        }
        else return false;
    }

    public function get_gender():string{
        return $this->gender;
    }


    public function set_year_of_birth(int $year_of_birth)
    {
        $this->year_of_birth = $year_of_birth;
    }

    public function get_year_of_birth():int{
        return $this->year_of_birth ;
    }

    public function signin(){
        
    }
    
    
    

}