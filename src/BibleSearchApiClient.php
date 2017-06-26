<?php

namespace BibleSearch\Api;

use GuzzleHttp;

class BibleSearchApiClient implements BibleSearchApi
{
    private $client;

    public function __construct(GuzzleHttp\ClientInterface $client) {
        $this->client = $client;
    }

    public static function createWithToken($token, $base_uri = 'https://bibles.org/v2/') {
        return new self(new GuzzleHttp\Client([
            'base_uri' => $base_uri,
            'http_errors' => false,
            'auth' => [$token, 'X'],
            'verify' => false
        ]));
    }

    public function request($method, $endpoint, array $parameters = []) {
        return new Response($this->client->request($method, $endpoint . '.js', $parameters));
    }

    public function search($query, array $versions = [], $offset = null, $limit = null) {
        return $this->request('GET', "search", [
            'query'
        ])->withNestedEntity(function($body) {
            return $body['response']['versions'];
        });
    }
    public function getVersions() {
        return $this->request('GET', "versions", [])->withNestedEntity(function($body) {
            return $body['response']['versions'];
        });
    }
    public function getVersionBooks($version_id, $include_chapters = false, $testament = null) {
        return $this->request('GET', "versions/$version_id/books", [
            'query' => [
                'include_chapters' => $include_chapters,
                'testament' => $testament,
            ]
        ])->withNestedEntity(function($body) {
            return $body['response']['books'];
        });
    }
    public function getVersion($version_id) {
        return $this->request('GET', "versions/$version_id")->withNestedEntity(function($body) {
            return $body['response']['versions'][0];
        });
    }
    public function getBookgroups() {
        return $this->request('GET', "bookgroups")->withNestedEntity(function($body) {
            return $body['response']['bookgroups'];
        });
    }
    public function getBookgroup($bookgroup_id) {
        return $this->request('GET', "bookgroups/$bookgroup_id")->withNestedEntity(function($body) {
            return $body['response']['bookgroups'][0];
        });
    }
    public function getBookgroupBooks($bookgroup_id) {
        return $this->request('GET', "bookgroups/$bookgroup_id/books")->withNestedEntity(function($body) {
            return $body['response']['books'];
        });
    }
    public function getBook($book_id) {
        return $this->request('GET', "books/$book_id")->withNestedEntity(function($body) {
            return $body['response']['books'][0];
        });
    }
    public function getBookChapters($book_id) {
        return $this->request('GET', "books/$book_id/chapters")->withNestedEntity(function($body) {
            return $body['response']['chapters'];
        });
    }
    public function getChapter($chapter_id, $include_marginalia = false) {
        return $this->request('GET', "chapters/$chapter_id")->withNestedEntity(function($body) {
            return $body['response']['chapters'][0];
        });
    }
    public function getChapterVerses($chapter_id, $include_marginalia = false, $start = null, $end = null) {

    }
    public function getVerse($verse_id, $include_marginalia = false) {}
    public function searchVerses(array $parameters = []) {}
    public function searchPassages(array $passages, array $versions, $include_marginalia = false) {}
}
