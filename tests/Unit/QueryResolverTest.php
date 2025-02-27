<?php

use App\Models\Post;
use App\Support\QueryResolver;

use function Pest\Laravel\get;

uses(Tests\TestCase::class);

beforeEach(function () {
    Post::factory(20)
        ->create();
});

it('can resolve sort query', function ($query, $expectedQuery) {

    get(route('posts.index', $query));

    $resolver = new QueryResolver();

    $sortQuery = $resolver->sortQuery('title');

    expect($sortQuery)->toEqual($expectedQuery);
})->with([
    [
        ['sortBy' => 'title', 'direction' => 'asc', 'page' => 1],
        ['sortBy' => 'title', 'direction' => 'desc'],
    ],
    [
        ['sortBy' => 'title', 'direction' => 'desc'],
        [],
    ],
    [
        ['published' => true, 'search' => 'test', 'page' => 1],
        ['published' => true, 'search' => 'test', 'sortBy' => 'title', 'direction' => 'asc'],
    ],
]);

it('can resolve sort arrow', function ($query, $expectedArrow) {

    get(route('posts.index', $query));

    $resolver = new QueryResolver();

    $sortArrow = $resolver->sortArrow('title');

    expect($sortArrow)->toEqual($expectedArrow);
})->with([
    [
        ['sortBy' => 'title', 'direction' => 'asc', 'page' => 1],
        '&darr;',
    ],
    [
        ['sortBy' => 'title', 'direction' => 'desc'],
        '&uarr;',
    ],
    [
        ['published' => true, 'search' => 'test', 'page' => 1],
        '&darr;&uarr;',
    ],
]);

it('can resolve published query', function ($query, $expectedQuery) {

    get(route('posts.index', $query));

    $resolver = new QueryResolver();

    $publishedQuery = $resolver->publishedQuery();

    expect($publishedQuery)->toEqual($expectedQuery);
})->with([
    [
        ['published' => true, 'search' => 'test', 'page' => 1],
        ['search' => 'test', 'published' => null],
    ],
    [
        ['search' => 'test', 'page' => 1],
        ['published' => true, 'search' => 'test'],
    ],
]);

it('can resolve published label', function ($query, $expectedLabel) {

    get(route('posts.index', $query));

    $resolver = new QueryResolver();

    $publishedLabel = $resolver->publishedLabel();

    expect($publishedLabel)->toEqual($expectedLabel);
})->with([
    [
        ['published' => true, 'search' => 'test', 'page' => 1],
        'All Posts',
    ],
    [
        ['search' => 'test', 'page' => 1],
        'Published Posts',
    ],
]);

it('can resolve search query', function ($query, $expectedQuery) {

    get(route('posts.index', $query));

    $resolver = new QueryResolver();

    $searchQuery = $resolver->searchQuery();

    expect($searchQuery)->toEqual($expectedQuery);
})->with([
    [
        ['published' => true, 'search' => 'test', 'page' => 1],
        ['published' => true],
    ],
    [
        ['published' => true, 'page' => 1, 'sortBy' => 'title', 'direction' => 'asc'],
        ['published' => true, 'sortBy' => 'title', 'direction' => 'asc'],
    ],
]);

it('can resolve search value', function ($query, $expectedValue) {

    get(route('posts.index', $query));

    $resolver = new QueryResolver();

    $searchValue = $resolver->searchValue();

    expect($searchValue)->toEqual($expectedValue);
})->with([
    [
        ['published' => true, 'search' => 'test', 'page' => 1],
        'test',
    ],
    [
        ['published' => true, 'page' => 1, 'sortBy' => 'title', 'direction' => 'asc'],
        null,
    ],
]);
