<?php

namespace App\Controller;

use App\Model\Book;
use App\Model\Review;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\PaginatedList;

class HomepageController extends ContentController
{
    private static $allowed_actions = [
        'SearchBookForm'
    ];

    public function LatestReviews()
    {
        $reviews = Review::get()->sort('Created', 'DESC')->limit(5);
        return $reviews;
    }

    public function RatingStars($rating)
    {
        $stars = '';
        for ($i = 1; $i <= $rating; $i++) {
            $stars .= '⭐';
        }
        return $stars;
    }

    public function SearchBookForm()
    {
        $q = $this->request->getVars('q')['q'] ?? '';
        $form = Form::create(
            $this,
            'SearchBookForm',
            FieldList::create(
                TextField::create('q', 'Book title', $q)
            ),
            FieldList::create(
                FormAction::create('doSearchBook', 'Search')
            )
        );
        $form->setFormMethod('GET');
        return $form;
    }

    public function doSearchBook($data, Form $form)
    {
        $search = $data['q'];
        $searchQuery = $search;
        $this->setURLParams(['q' => $searchQuery]);

        return $this->redirect('/?q=' . $searchQuery);
    }

    public function Query()
    {
        return $this->request->getVars('q')['q'] ?? '';
    }

    public function PaginatedBooks()
    {
        $query = $this->request->getVars('q')['q'] ?? '';
        $books = $this->bookQuery($query);
        
        return (new PaginatedList($books, $this->getRequest()))->setPageLength(5);
    }

    private function bookQuery($query)
    {
        $books = Book::get()
        ->filter([
            'Reviews.Count():GreaterThan' => 0
        ])
        ->filterAny([
            'Title:PartialMatch' => $query,
            'Authors.Name:PartialMatch' => $query,
        ]);
        return $books;
    }

    public function AverageRating($bookId)
    {
        $book = Book::get()->byID($bookId);
        $reviews = $book->Reviews();
        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review->Rating;
        }

        $count = $reviews->count();
        $averageRating = $count ? round($totalRating / $count) : 0;
        return $this->RatingStars($averageRating);
    }
}