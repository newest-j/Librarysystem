<?php

namespace Librarysystem\Entities;

require_once 'Book.php';
require_once 'Magazine.php';
require_once 'Member.php';
require_once 'Borrowing.php';

class Library{
    private $books = [];
    private $magazines = [];
    private $members = [];
    private $borrowings = [];
    

   public static function booksTotal(array $books){
    return count($books);
   }

  public static function magazineTotal(array $magazines){
    return count($magazines);
   }


   public static function getTotalItems(array $books, array $magazines){
    return count($books) + count($magazines);
  }



  public function addBook(Book $book) {
        if (!isset($this->books[$book->isbn])) {
            $this->books[$book->isbn] = $book;
            return true;
        }
        return false; // Book already exists
    }
    

     public function removeBook($isbn) {
        if (isset($this->books[$isbn])) {
            unset($this->books[$isbn]);
            return true;
        }
        return false; // Book not found
    }


     // Add/Remove Magazines
    public function addMagazine(Magazine $magazine) {
        $key = $magazine->title . '_' . $magazine->issueNumber;
        if (!isset($this->magazines[$key])) {
            $this->magazines[$key] = $magazine;
            return true;
        }
        return false; // Magazine already exists
    }


      public function removeMagazine($title, $issueNumber) {
        $key = $title . '_' . $issueNumber;
        if (isset($this->magazines[$key])) {
            unset($this->magazines[$key]);
            return true;
        }
        return false; // Magazine not found
    }



      // Register/Unregister Members
    public function registerMember(Member $member) {
        if (!isset($this->members[$member->memberId])) {
            $this->members[$member->memberId] = $member;
            return true;
        }
        return false; // Member already exists
    }



     public function unregisterMember($memberId) {
        if (isset($this->members[$memberId])) {
            // Check if member has borrowed items
            foreach ($this->borrowings as $borrowing) {
                if ($borrowing->member->memberId === $memberId && $borrowing->returnDate === null) {
                    return false; // Cannot unregister member with borrowed items
                }
            }
            unset($this->members[$memberId]);
            return true;
        }
        return false; // Member not found
    }


        // Borrow Item
    public function borrowItem($itemId, $memberId, $itemType = 'book') {
        $member = $this->getMember($memberId);
        if (!$member) {
            return ['success' => false, 'message' => 'Member not found'];
        }

        $item = null;
        if ($itemType === 'book') {
            $item = $this->getBook($itemId);
        } else if ($itemType === 'magazine') {
            $item = $this->getMagazine($itemId);
        }

        if (!$item) {
            return ['success' => false, 'message' => ucfirst($itemType) . ' not found'];
        }

        if ($item->borrowItem()) {
            $borrowing = new Borrowing($item, $member);
            $this->borrowings[] = $borrowing;
            return ['success' => true, 'message' => ucfirst($itemType) . ' borrowed successfully', 'dueDate' => $borrowing->dueDate];
        } else {
            return ['success' => false, 'message' => 'No copies available'];
        }
    }



     // Return Item
    public function returnItem($itemId, $memberId, $itemType = 'book') {
        foreach ($this->borrowings as $borrowing) {
            $borrowingItemId = ($itemType === 'book') ? $borrowing->item->isbn : $borrowing->item->title . '_' . $borrowing->item->issueNumber;
            
            if ($borrowingItemId === $itemId && 
                $borrowing->member->memberId === $memberId && 
                $borrowing->returnDate === null) {
                
                $borrowing->returnItem();
                $borrowing->item->returnItem();
                return ['success' => true, 'message' => ucfirst($itemType) . ' returned successfully'];
            }
        }
        return ['success' => false, 'message' => 'Borrowing record not found'];
    }



     // Get methods
    public function getBook($isbn) {
        return $this->books[$isbn] ?? null;
    }

    public function getMagazine($title, $issueNumber = null) {
        if ($issueNumber) {
            $key = $title . '_' . $issueNumber;
            return $this->magazines[$key] ?? null;
        }
        // If no issue number, return first match by title
        foreach ($this->magazines as $magazine) {
            if ($magazine->title === $title) {
                return $magazine;
            }
        }
        return null;
    }

    public function getMember($memberId) {
        return $this->members[$memberId] ?? null;
    }

    // Display borrowed items report
    public function getBorrowedItemsReport() {
        $report = [];
        foreach ($this->borrowings as $borrowing) {
            if ($borrowing->returnDate === null) { // Only active borrowings
                $report[] = [
                    'member' => $borrowing->member->name,
                    'memberId' => $borrowing->member->memberId,
                    'item' => $borrowing->item->title,
                    'author' => $borrowing->item->author,
                    'type' => $borrowing->item->getDetails()['type'],
                    'borrowDate' => $borrowing->borrowDate,
                    'dueDate' => $borrowing->dueDate,
                    'daysOverdue' => $borrowing->getDaysOverdue()
                ];
            }
        }
        return $report;
    }

    // Get all items
    public function getAllBooks() {
        return $this->books;
    }

    public function getAllMagazines() {
        return $this->magazines;
    }

    public function getAllMembers() {
        return $this->members;
    }
}
?>