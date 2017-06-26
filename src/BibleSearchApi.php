<?php

namespace BibleSearch\Api;

interface BibleSearchApi
{
    public function search($query, array $versions = [], $offset = null, $limit = null);
    public function getVersions();
    public function getVersionBooks($version_id, $include_chapters = false, $testament = null);
    public function getVersion($version_id);
    public function getBookgroups();
    public function getBookgroup($bookgroup_id);
    public function getBookgroupBooks($bookgroup_id);
    public function getBook($book_id);
    public function getBookChapters($book_id);
    public function getChapter($chapter_id, $include_marginalia = false);
    public function getChapterVerses($chapter_id, $include_marginalia = false, $start = null, $end = null);
    public function getVerse($verse_id, $include_marginalia = false);
    public function searchVerses(array $parameters = []);
    public function searchPassages(array $passages, array $versions, $include_marginalia = false);
}
