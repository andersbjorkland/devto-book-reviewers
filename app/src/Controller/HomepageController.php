<?php

namespace App\Controller;

use App\Model\Book;
use App\Model\Review;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\TextField;

class HomepageController extends ContentController
{
    private static $allowed_actions = [
        'index',
        'SearchBookForm'
    ];

    private string $query = "";

    public function index()
    {
        return $this->render();
    }

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
        // $form->setFormAction($this->Link('book'));
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


    public function BookQuery($query)
    {
        $books = Book::get()->filter(['Title:PartialMatch' => $query]);
        return $books;
    }

    public function SearchForm()
    {
        return '😝';
    }
}