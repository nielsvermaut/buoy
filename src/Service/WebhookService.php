<?php

namespace Buoy\Service;

use Buoy\Model\Webhook;
use GuzzleHttp\Client;

class WebhookService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fireWebhook(Webhook $webhook): void
    {
        if ($webhook->getHeaders()['Content-Type'] ?? '' === 'application/json') {
            $this->client->request(
                $webhook->getMethod(),
                $webhook->getUrl(),
                [
                    'headers' => $webhook->getHeaders(),
                    'json' => $webhook->getPayload(),
                ]
            );

            return;
        }

        $this->client->request(
            $webhook->getMethod(),
            $webhook->getUrl(),
            [
                'headers' => $webhook->getHeaders(),
                'form_params' => $webhook->getPayload(),
            ]
        );
    }
}
