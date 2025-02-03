<?php

declare(strict_types=1);

namespace App\Actions\Stream;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamText
{
    use AsAction;

    public function handle(int $count = 20): \Generator
    {
        for ($i = 0; $i < $count; $i++) {
            yield "({$i}) ".fake()->address().'; ';
        }
    }

    public function asController(ActionRequest $request): StreamedResponse
    {
        return response()->stream(function (): void {
            foreach ($this->handle() as $chunk) {
                echo $chunk;
                ob_flush();
                flush();
                sleep(2);
            }
        }, 200, ['X-Accel-Buffering' => 'no']);
    }

}
