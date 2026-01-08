<?php

namespace App\Http\Controllers;

use DoniaShaker\MediaLibrary\MediaController;
use GuzzleHttp\Client;

abstract class Controller
{
    public $media_controller;
    protected $guzzleClient;
    protected $attributes;

    public function __construct()
    {
        $this->media_controller = new MediaController();

        $this->guzzleClient = new Client();
    }

    public function sendSMS($to, $message)
    {
        $this->attributes = [
            'api_key'  => env('SMS_PROVIDER_TOKEN'),
            'to'       => $to,
            'message'  => $message,
            'sender'   => env('SMS_NAME'),
            'response' => 'json',
            'username' => env('SMS_USERNAME'),
        ];

        $response = $this->guzzleClient->request(
            'GET',
            env('SMS_URL'),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('SMS_PROVIDER_TOKEN'),
                    'Accept'        => 'application/json',
                ],
                'query'   => $this->attributes,
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }
}
