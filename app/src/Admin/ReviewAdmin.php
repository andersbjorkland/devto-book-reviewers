<?php 

namespace App\Admin;

use App\Model\Author;
use App\Model\Book;
use App\Model\Review;
use SilverStripe\Admin\ModelAdmin;

class ReviewAdmin extends ModelAdmin
{
    private static $managed_models = [
        Author::class,
        Book::class,
        Review::class,
    ];

    private static $url_segment = 'reviews';

    private static $menu_title = 'Reviews';

    private static $menu_icon_class = 'font-icon-book';
}