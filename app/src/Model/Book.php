<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class Book extends DataObject
{
    private static $table_name = "Book";

    private static $db = [
        'Title' => 'Varchar(255)',
        'ISBN' => 'Varchar(255)',
        'Description' => 'Text',
    ];

    private static $has_many = [
        'Reviews' => Review::class,
    ];

    private static $many_many = [
        'Authors' => Author::class
    ];
}