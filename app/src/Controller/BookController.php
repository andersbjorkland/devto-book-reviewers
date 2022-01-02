<?php

namespace App\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use Symfony\Component\HttpClient\HttpClient;

class BookController extends Controller
{
    private static $allowed_actions = [
        'index'
    ];

    public function index(HTTPRequest $request)
    {
        $q = $request->getVar('q');

        if ($q) {
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q='.$q);
            $data = $response->toArray();

            return $data['items'][0]['volumeInfo']['title'];
        } 
        
        return "Sorry, no valid query parameter found.";
    }
}