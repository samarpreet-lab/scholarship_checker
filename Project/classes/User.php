<?php
class User {
    protected $id;
    protected $name;
    protected $email;
    protected $password;

    public function __construct($id, $name, $email, $password) {
        $this->id       = $id;
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
    }

    public function getId()       { return $this->id; }
    public function getName()     { return $this->name; }
    public function getEmail()    { return $this->email; }
    public function getPassword() { return $this->password; }
}
?>
