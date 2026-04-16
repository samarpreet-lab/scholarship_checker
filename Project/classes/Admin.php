<?php
require_once 'User.php';

class Admin extends User {
    private $access_level;

    public function __construct($id, $name, $email, $password, $access_level = 'full') {
        parent::__construct($id, $name, $email, $password);
        $this->access_level = $access_level;
    }

    public function getAccessLevel() { return $this->access_level; }
}
?>
