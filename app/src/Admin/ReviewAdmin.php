<?php 

namespace App\Admin;

use SilverStripe\Forms\FieldList;
use App\Model\Author;
use App\Model\Book;
use App\Model\Review;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;

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