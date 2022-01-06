<?php

namespace App\Controller;

use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use App\Model\Author;
use App\Model\Book;
use App\Service\GoogleBookParser;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Security;
use Symfony\Component\HttpClient\HttpClient;

class ReviewController extends ContentController
{
    private static $allowed_actions = [
        'index',
        'book',
        'reviewForm'
    ];

    private static $url_segment = 'review';

    private string $volumeId = '';

    public function index(HTTPRequest $request)
    {
        $search = $request->getVar('q');
        $searchQuery = "q=" . $search;

        $startIndex = $request->getVar("startIndex") ?? 0;

        $langRestriction = $request->getVar("langRestrict") ?? 'any';
        $langRestrictionQuery = $langRestriction ? "&langRestrict=" . $langRestriction : "";

        $maxResults = $request->getVar('maxResults') ?? 10;
        $maxResultsQuery = '&maxResults=' . $maxResults;


        // Get language codes
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://gist.githubusercontent.com/jrnk/8eb57b065ea0b098d571/raw/936a6f652ebddbe19b1d100a60eedea3652ccca6/ISO-639-1-language.json');
        $languageCodes = [["code" => "any", "name" => "Any"]];
        array_push($languageCodes, ...$response->toArray());


        $books = [];
        $pagination = [];

        if ($search) {
            $basicQuery = $searchQuery 
                        . $langRestrictionQuery
                        . $maxResultsQuery;

            $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?'. $basicQuery);
            $responseContent = $response->toArray();
            $books = GoogleBookParser::parseAll($responseContent);

            $pagination = $this->paginator('/review?' . $basicQuery, $responseContent['totalItems'], $startIndex, $maxResults);
            $pagination['pages'] = ArrayList::create($pagination['pages']);
            $pagination = ArrayList::create([$pagination]);
        } 
        
        return $this->customise([
            'Layout' => $this
                        ->customise([
                            'Books' => ArrayList::create($books),
                            'Pagination' => $pagination,
                            'Query' => $search,
                            'Languages' => ArrayList::create($languageCodes),
                            'LangRestriction' => $langRestriction
                        ])
                        ->renderWith('Layout/Books'),
            
        ])->renderWith(['Page']);
    }

    public function book(HTTPRequest $request)
    {
        $volumeId = $request->param('ID');
        $this->volumeId = $volumeId;
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes/' . $volumeId);
        $googleBook = GoogleBookParser::parse($response->toArray());

        $authors = [];
        foreach ($googleBook["authors"] as $googleAuthor) {
            $author = Author::get()->filter(['Name' => $googleAuthor->AuthorName])->first();

            if (!$author) {
                $author = Author::create();
                $author->Name = $googleAuthor->AuthorName;

                $names = explode(" ", $googleAuthor->AuthorName);
                $author->GivenName = $names[0];
                if (count($names) > 2) {
                    $additionalName = "";

                    for ($i = 1; $i < count($names) - 1; $i++) {
                        $additionalName .= $names[$i] . " ";
                    }

                    $author->AdditionalName = $additionalName;
                }
                $author->FamilyName = $names[count($names) - 1];

                $author->write();

                $authors[] = $author;
            }
        }

        $book = Book::get()->filter(['VolumeID' => $volumeId])->first();

        if (!$book) {
            $book = Book::create();
            $book->VolumeID = $volumeId;
            $book->Title = $googleBook["title"];
            $book->ISBN = $googleBook["isbn"];
            $book->Description = $googleBook["description"];

            foreach ($authors as $author) {
                $book->Authors()->add($author);
            }

            $book->write();
        }

        $fields = new FieldList(
            [
                HiddenField::create(
                    'VolumeId',
                    'VolumeId',
                    $volumeId
                ),
                TextField::create(
                    'Title',
                    'Title'
                ),
                DropdownField::create(
                    'Rating',
                    'Rating',
                    [
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5
                    ]
                ),
                TextareaField::create(
                    'Review',
                    'Review'
                )
            ]
        );


        return $this->customise([
            'Layout' => $this
                        ->customise([
                            'Book' => $googleBook,
                        ])
                        ->renderWith('Layout/Review'),
        ])->renderWith(['Page']);

    }

    public function reviewForm()
    {
        $fields = new FieldList(
            [
                HiddenField::create(
                    'VolumeId',
                    'VolumeId',
                    $this->volumeId
                ),
                TextField::create(
                    'Title',
                    'Title'
                ),
                DropdownField::create(
                    'Rating',
                    'Rating',
                    [
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5
                    ]
                ),
                TextareaField::create(
                    'Review',
                    'Review'
                )
            ]
        );

        $form = Form::create($this, 'reviewForm', $fields, new FieldList(FormAction::create('doReview', 'Submit')), new RequiredFields('Title', 'Rating'));
        $form->setFormAction('/review/ReviewForm/');
        return $form;
    }

    public function doReview($data, Form $form)
    {
        $user = Security::getCurrentUser();
        return "Hello Review!!!";
    }

    protected function paginator($query, $count, $startIndex, $perPage): array
    {
        $pagination = [
            'start' => false,
            'current' => false,
            'previous' => false,
            'next' => false,
            'totalPages' => 0,
            'pages' => false,
        ];

        $totalPages = ceil($count / $perPage);

        $currentPage = ceil($startIndex / $perPage) + 1;

        $previousIndex = $startIndex - $perPage;
        if ($previousIndex < 0) {
            $previousIndex = false;
        }

        $nextIndex = $perPage * ($currentPage);
        if ($nextIndex > $count) {
            $nextIndex = false;
        }

        $pagination['start'] = [
            'page' => $previousIndex > 0 ? 1 : false,
            'link' => $previousIndex > 0 ? $query . '&startIndex=0' : false,
        ];

        $pagination['current'] = [
            'page' => $currentPage,
            'link' => $query . '&startIndex=' . $startIndex
        ];
        $pagination['previous'] = [
            'page' => $previousIndex !== false ? $currentPage - 1 : false,
            'link' => $previousIndex !== false ? $query . '&startIndex=' . $previousIndex : false,
        ];
        $pagination['next'] = [
            'page' => $nextIndex ? $currentPage + 1 : false,
            'link' => $nextIndex ? $query . '&startIndex=' . $nextIndex : false,
        ];

        $totalPages = ceil($count / $perPage);  
        $pagination['totalPages'] = $totalPages;
        $pages = [];

        for ($i = 0; $i < 3; $i++) {
            $page = $currentPage + $i - 1;

            if ($currentPage == 1) {
                $page = $currentPage + $i;
            }

            if ($page > $totalPages) {
                break;
            }
            if ($page < 1) {
                continue;
            }
            
            $pages[] = [
                'page' => $page,
                'link' => $query . '&startIndex=' . ($page - 1) * $perPage,
                'currentPage' => $page == $currentPage
            ];
            $pagination['pages'] = $pages;
        } 

        return $pagination;
    }
}