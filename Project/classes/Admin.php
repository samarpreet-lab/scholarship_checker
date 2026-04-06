<?php
require_once 'User.php';

class Admin extends User {
    public function __construct($id, $name, $email, $password) {
        parent::__construct($id, $name, $email, $password);
    }
}
?>
