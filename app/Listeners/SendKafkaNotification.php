<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class SendKafkaNotification implements ShouldQueue
{
    /**
     * Handle the event.
     * @throws \Exception
     */
    public function handle(UserRegisteredEvent $event): void
    {
        $message = new Message(
            body: [
                'user_id' => $event->user->id,
                'message' => 'user_registered',
            ],
        );

        $producer = Kafka::publish(config('kafka.brokers'))
            ->onTopic(env('KAFKA_USER_TOPIC'))
            ->withMessage($message);

        $producer->send();
    }
}
