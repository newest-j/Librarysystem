<?php

namespace Librarysystem\Actions;
interface Borrowable {
    public function borrowItem();
    public function returnItem();
}
?>