<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Stream;

use App\Actions\Stream\StreamText;


test('handle method generates 20 yielded values', function () {
    $testCount = 33;
    $generator = app(StreamText::class)->handle($testCount);

    $values = iterator_to_array($generator);
    expect($values)->toHaveCount($testCount);
});