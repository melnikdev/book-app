<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Grpc\ChannelCredentials;
use Illuminate\Console\Command;
use Proto\AddServiceClient;
use Proto\EventRequest;

use const Grpc\STATUS_OK;

class GetEventGrpc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-event-grpc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Start Get Event Grpc");

        $client = new AddServiceClient('localhost:4040', [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);

        $eventRequest = new EventRequest();
        $eventRequest->setId(1);
        list($response, $status) = $client->GetEvent($eventRequest)->wait();
        if ($status->code !== STATUS_OK) {
            $this->info($status->code);
            return;
        }

        dd($response->getId(), $response->getName(), $response->getActive());
    }
}
