<?php 

namespace App\Service;

use SilverStripe\ORM\ArrayList;

class GoogleBookParser
{
    public static function parse(array $item): array
    {
        $authors = $item['volumeInfo']['authors'] ?? [];

        return [
            'title' => $item['volumeInfo']['title'] ?? '',
            'isbn' => $item['volumeInfo']['industryIdentifiers'][0]['identifier'] ?? '',
            'volumeId' => $item['id'] ?? '',
            'publishedDate' => $item['volumeInfo']['publishedDate'] ?? '',
            'authors' => ($authors ? ArrayList::create(
                array_map(function ($author) {
                    return ['AuthorName' => $author ?? ''];
                }, $item['volumeInfo']['authors'])
            ) : ''),
            'language' => $item['volumeInfo']['language'] ?? '',
            'image' => $item['volumeInfo']['imageLinks']['thumbnail'] ?? '',
            'pageCount' => $item['volumeInfo']['pageCount'] ?? '',
            'categories' => ArrayList::create(
                array_map(function ($category) {
                    return ['CategoryName' => $category ?? ''];
                }, $item['volumeInfo']['categories'] ?? [])
            ),
            'description' => $item['volumeInfo']['description'] ?? '',
        ];
    }

    public static function parseAll(array $response): array
    {
        return array_map(function ($item) {
            return self::parse($item);
        }, $response['items'] ?? []);
    }
}