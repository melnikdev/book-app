<?php

namespace Tests\Unit\Services\Kafka\Handlers;

use App\Services\Kafka\Handlers\UserEvent;
use Junges\Kafka\Contracts\ConsumerMessage;

test('getUserId returns the user_id when present in the message body', function () {
    $message = mock(ConsumerMessage::class)
        ->shouldReceive('getBody')
        ->andReturn(['user_id' => 123])
        ->getMock();

    $userEvent = new class extends UserEvent {
        public function getUserId(ConsumerMessage $message): int
        {
            return parent::getUserId($message);
        }
    };

    expect($userEvent->getUserId($message))->toBe(123);
});

test('getUserId returns 0 when user_id is not present in the message body', function () {
    $message = mock(ConsumerMessage::class)
        ->shouldReceive('getBody')
        ->andReturn(['other_key' => 456])
        ->getMock();

    $userEvent = new class extends UserEvent {
        public function getUserId(ConsumerMessage $message): int
        {
            return parent::getUserId($message);
        }
    };

    expect($userEvent->getUserId($message))->toBe(0);
});