<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Kafka\Handlers\UserEvent;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;

class UserConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:user-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume user-events topic';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $consumer = Kafka::consumer(['user-events'])
            ->withBrokers(config('kafka.brokers'))
            ->withAutoCommit()
            ->withHandler(new UserEvent)
            ->build();

        try {
            $consumer->consume();
        } catch (Exception|ConsumerException $e) {
            Log::error($e->getMessage());
        }
    }
}
