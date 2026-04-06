<?php
require_once 'User.php';

class Student extends User {
    private $gpa;
    private $income;
    private $category;
    private $gender;

    public function __construct($id, $name, $email, $password, $gpa, $income, $category, $gender) {
        parent::__construct($id, $name, $email, $password);
        $this->gpa      = $gpa;
        $this->income   = $income;
        $this->category = $category;
        $this->gender   = $gender;
    }

    public function getGPA()      { return $this->gpa; }
    public function getIncome()   { return $this->income; }
    public function getCategory() { return $this->category; }
    public function getGender()   { return $this->gender; }
}
?>
