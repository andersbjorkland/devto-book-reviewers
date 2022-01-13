<?php 

namespace App\Controller;

use App\Model\Book;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

class BookController extends ContentController
{
    private static $allowed_actions = [
        'view'
    ];

    public function index()
    {
        $search = $this->request->getVar("q") ?? '';
        $sort = $this->request->getVar("sort");

        $fields = DataObject::getSchema()->fieldSpecs(Book::class);
        if (!key_exists($sort, $fields)) {
            $sort = 'Title';
        } 

        if ($search) {
            $books = Book::get()
                ->filter([
                    'Reviews.Count():GreaterThan' => 0
                ])
                ->filterAny([
                    'Title:PartialMatch' => $search,
                    'Authors.Name:PartialMatch' => $search,
                ])
                ->sort($sort);
        } else {
            $books = Book::get()->sort('LastEdited', 'DESC');
        }

        return $this->customise([
            'Layout' => $this
                        ->customise([
                            'Books' => (new PaginatedList($books, $this->getRequest()))
                                        ->setPageLength(5),
                            'Query' => $search,
                        ])
                        ->renderWith('Layout/BookHolder'),
            
        ])->renderWith(['Page']);
    }

    public function view()
    {
        $book = Book::get()->filter([
            'VolumeID' => $this->request->param('ID')
        ])->first();
        return $this->customise([
            'Book' => $book,
            'Layout' => $this
                        ->customise([
                            'Book' => $book,
                            'Reviews' => (new PaginatedList($book->Reviews(), $this->getRequest()))
                                        ->setPageLength(5),
                        ])
                        ->renderWith('Layout/BookPage'),
        ])->renderWith(['Page']);
    }
}