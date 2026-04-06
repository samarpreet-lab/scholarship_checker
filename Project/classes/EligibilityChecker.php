<?php
class EligibilityChecker {
    private $student;
    private $scholarships;
    private $criteriaList;

    public function __construct($student, $scholarships, $criteriaList) {
        $this->student      = $student;
        $this->scholarships = $scholarships;
        $this->criteriaList = $criteriaList;
    }

    public function check() {
        $matched = [];

        foreach ($this->scholarships as $scholarship) {
            $criteria = $this->criteriaList[$scholarship->getId()] ?? null;

            if (!$criteria) continue;

            // Check GPA
            $gpa_ok = $this->student->getGPA() >= $criteria->getMinGpa();

            // Check income
            $income_ok = $this->student->getIncome() <= $criteria->getMaxIncome();

            // Check category — stored as "SC,ST,OBC" — explode into array
            $categories = explode(',', $criteria->getEligibleCategories());
            $category_ok = in_array($this->student->getCategory(), $categories);

            // Check gender
            $gender_req = $criteria->getGenderRequirement();
            $gender_ok  = ($gender_req === 'Any') || ($gender_req === $this->student->getGender());

            // All conditions must pass
            if ($gpa_ok && $income_ok && $category_ok && $gender_ok) {
                $matched[] = $scholarship;
            }
        }

        // Sort matched scholarships by amount — highest first
        for ($i = 0; $i < count($matched); $i++) {
            for ($j = 0; $j < count($matched) - 1 - $i; $j++) {
                if ($matched[$j]->getAmount() < $matched[$j + 1]->getAmount()) {
                    $temp = $matched[$j];
                    $matched[$j] = $matched[$j + 1];
                    $matched[$j + 1] = $temp;
                }
            }
        }

        return $matched;
    }
}
?>
