<?php


namespace Librarysystem\Entities;


abstract class LibraryItem {
    public $title;
    public $author;
    public $issueNumber;
    protected $availableCopies;


     public function __construct($title, $author, $issueNumber,  $availableCopies)
    {
        $this->title = $title;
        $this->author = $author;
        $this->issueNumber = $issueNumber;
        $this->availableCopies = $availableCopies;
    }



    abstract public function getDetails();

}
?>