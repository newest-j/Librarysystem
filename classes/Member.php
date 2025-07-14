<?php
class Member {
    public $memberId;
    public $name;
    public $email;
    public $borrowedItems; 

    public function __construct($memberId, $name, $email) {
        // Input validation
        if (empty($memberId) || empty($name) || empty($email)) {
            throw new InvalidArgumentException("Member ID, name, and email are required");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format");
        }

        $this->memberId = $memberId;
        $this->name = $name;
        $this->email = $email;
        $this->borrowedItems = [];
    }

    public function getBorrowedItemsCount() {
        return count($this->borrowedItems);
    }

    public function hasBorrowedItems() {
        return $this->getBorrowedItemsCount() > 0;
    }
}
?>