<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;

class FlashSaleTest extends Command
{
    protected $signature = 'flash-sale:test
                            {--requests=100 : Total request}
                            {--product=1 : Product ID}
                            {--quantity=1 : Quantity}';

    protected $description = 'Simulate flash sale by sending concurrent requests';

    public function handle(): int
    {
        $client = new Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'http_errors' => false,
            'timeout' => 10,
        ]);

        $totalRequests = (int) $this->option('requests');

        $success = 0;
        $failed = 0;

        $requests = function () use ($client, $totalRequests) {

            for ($i = 0; $i < $totalRequests; $i++) {

                yield function () use ($client) {

                    return $client->postAsync('/api/orders', [

                        'json' => [

                            'items' => [

                                [
                                    'product_id' => (int) $this->option('product'),
                                    'quantity' => (int) $this->option('quantity'),
                                ]

                            ]

                        ]

                    ]);
                };
            }
        };

        $pool = new Pool($client, $requests(), [

            'concurrency' => 100,

            'fulfilled' => function ($response) use (&$success, &$failed) {

                if ($response->getStatusCode() === 201) {
                    $success++;
                } else {
                    $failed++;
                }
            },

            'rejected' => function () use (&$failed) {

                $failed++;
            }

        ]);

        $this->info("Sending {$totalRequests} concurrent requests...");

        $pool->promise()->wait();

        $this->newLine();

        $this->info("Finished");

        $this->table(
            ['Success', 'Failed'],
            [
                [$success, $failed]
            ]
        );

        return self::SUCCESS;
    }
}
