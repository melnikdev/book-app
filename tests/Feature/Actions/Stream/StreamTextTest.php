<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stream;

use App\Actions\Stream\StreamText;
use Symfony\Component\HttpFoundation\StreamedResponse;

test('it returns a streamed response', function () {
    $response = app()->call(StreamText::class, [], 'asController');

    expect($response)->toBeInstanceOf(StreamedResponse::class);
});


test('it sets X-Accel-Buffering header to no', function () {
    $response = app()->call(StreamText::class, [], 'asController');

    expect($response->headers->get('X-Accel-Buffering'))->toBe('no');
});

test('it sets status code to 200', function () {
    $response = app()->call(StreamText::class, [], 'asController');

    expect($response->getStatusCode())->toBe(200);
});



