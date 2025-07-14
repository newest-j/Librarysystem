<?php

namespace Librarysystem\Entities;


require_once 'LibraryItem.php';
require_once './Actions/Borrowable.php';

use Librarysystem\Actions\Borrowable;


class Magazine extends LibraryItem implements Borrowable {

    public function __construct($title, $author, $issueNumber, $availableCopies) {
        // Input validation
        if (empty($title) || empty($author)) {
            throw new \InvalidArgumentException("Title and author are required");
        }
        
        if ($availableCopies < 0) {
            throw new \InvalidArgumentException("Available copies cannot be negative");
        }

        parent::__construct($title, $author, $issueNumber, $availableCopies);
    }

    public function getDetails() {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'issueNumber' => $this->issueNumber,
            'availableCopies' => $this->availableCopies,
            'type' => 'Magazine'
        ];
    }

    public function borrowItem() {
        if ($this->availableCopies > 0) {
            $this->availableCopies--;
            return true;
        }
        return false;
    }

    public function returnItem() {
        $this->availableCopies++;
        return true;
    }
}
?>