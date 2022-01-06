<?php

namespace App\Controller;

use App\Service\GoogleBookParser;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use Symfony\Component\HttpClient\HttpClient;

class ReviewController extends ContentController
{
    private static $allowed_actions = [
        'index',
    ];

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