<?php

namespace App\Controller;

use App\Model\Review;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\TextField;

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

    public function SearchBookForm()
    {
        $q = $this->request->getVars()['q'] ?? '';
        $form = Form::create(
            $this,
            'SearchBookForm',
            FieldList::create(
                TextField::create('q', 'Book title', $q)
            ),
            FieldList::create(
                FormAction::create(false, 'Search')
            )
        );
        $form->setFormMethod('GET');
        $form->setFormAction('book');
        return $form;
    }
}