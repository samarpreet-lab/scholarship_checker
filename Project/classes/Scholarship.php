<?php
class Scholarship {
    private $id;
    private $name;
    private $description;
    private $amount;
    private $provider;
    private $deadline;

    public function __construct($id, $name, $description, $amount, $provider, $deadline) {
        $this->id          = $id;
        $this->name        = $name;
        $this->description = $description;
        $this->amount      = $amount;
        $this->provider    = $provider;
        $this->deadline    = $deadline;
    }

    public function getId()          { return $this->id; }
    public function getName()        { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getAmount()      { return $this->amount; }
    public function getProvider()    { return $this->provider; }
    public function getDeadline()    { return $this->deadline; }
}
?>
