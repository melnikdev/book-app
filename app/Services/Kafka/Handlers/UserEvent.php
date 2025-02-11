<?php

declare(strict_types=1);

namespace App\Services\Kafka\Handlers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class UserEvent
{
    public function __invoke(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        $userId = $this->getUserId($message);
        if (! $userId) {
            Log::error('User id not found');
            return;
        }

        $user = User::query()->find($userId);
        if (! $user) {
            Log::error('User not found');
            return;
        }

        $user->email_verified_at = now();
        $user->save();
    }

    protected function getUserId(ConsumerMessage $message): int
    {
        $body = $message->getBody();
        if (Arr::exists($body, 'user_id')) {
            return (int) $body['user_id'];
        }
        return 0;
    }
}