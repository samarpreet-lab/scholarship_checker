<?php
class Criteria {
    private $scholarship_id;
    private $min_gpa;
    private $max_income;
    private $eligible_categories;
    private $gender_requirement;

    public function __construct($scholarship_id, $min_gpa, $max_income, $eligible_categories, $gender_requirement) {
        $this->scholarship_id      = $scholarship_id;
        $this->min_gpa             = $min_gpa;
        $this->max_income          = $max_income;
        $this->eligible_categories = $eligible_categories;
        $this->gender_requirement  = $gender_requirement;
    }

    public function getMinGpa()             { return $this->min_gpa; }
    public function getMaxIncome()          { return $this->max_income; }
    public function getEligibleCategories() { return $this->eligible_categories; }
    public function getGenderRequirement()  { return $this->gender_requirement; }
}
?>
