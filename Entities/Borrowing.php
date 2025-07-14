<?php

namespace Librarysystem\Entities;

class Borrowing {
    public $item; 
    public $member;
    public $borrowDate;
    public $dueDate;
    public $returnDate;

    public function __construct($item, Member $member, $loanPeriodDays = 14) {
        $this->item = $item;
        $this->member = $member;
        $this->borrowDate = new \DateTime();
        $this->dueDate = new \DateTime();
        $this->dueDate->add(new \DateInterval('P' . $loanPeriodDays . 'D')); // Add loan period
        $this->returnDate = null;

        // Add the item to the member's borrowed items
        $this->member->borrowedItems[] = $item;
    }

    public function returnItem() {
        $this->returnDate = new \DateTime();
        
        // Remove item from member's borrowed items
        $key = array_search($this->item, $this->member->borrowedItems);
        if ($key !== false) {
            unset($this->member->borrowedItems[$key]);
            $this->member->borrowedItems = array_values($this->member->borrowedItems); // Re-index array
        }
    }

    public function getDaysOverdue() {
        if ($this->returnDate !== null) {
            return 0; // Item has been returned
        }
        
        $today = new \DateTime();
        if ($today > $this->dueDate) {
            $interval = date_diff($today , $this->dueDate);
            return $interval->days;
        }
        return 0; // Not overdue
    }

    public function isOverdue() {
        return $this->getDaysOverdue() > 0;
    }
}
?>