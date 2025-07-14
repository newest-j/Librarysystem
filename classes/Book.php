<?php
require_once 'LibraryItem.php';
require_once './interface/Borrowable.php';

class Book extends LibraryItem implements Borrowable {
    public $isbn;

    public function __construct($title, $author, $issueNumber, $availableCopies, $isbn) {
        // Input validation
        if (empty($title) || empty($author) || empty($isbn)) {
            throw new InvalidArgumentException("Title, author, and ISBN are required");
        }
        
        if ($availableCopies < 0) {
            throw new InvalidArgumentException("Available copies cannot be negative");
        }

        parent::__construct($title, $author, $issueNumber, $availableCopies);
        $this->isbn = $isbn;
    }

    public function getDetails() {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'issueNumber' => $this->issueNumber,
            'availableCopies' => $this->availableCopies,
            'isbn' => $this->isbn,
            'type' => 'Book'
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


