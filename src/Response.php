<?php
/**
 * Copyright Ridestore AB.
 * User: Christoffer Bubach
 * Date: 16-05-14
 * Time: 11:06
 */

namespace Bot;

use GuzzleHttp\Client;

class Response {

    /**
     * Send response
     *
     * @param $userId
     * @param $message
     */
    public function sendMessage($userId, $message)
    {
        $client = new Client();

        if (!is_array($message)) {
            $message = ['text'  => $message];
        }

        $client->request(
            'POST', 'https://graph.facebook.com/v2.6/me/messages?access_token='.getenv('PAGE_ACCESS_TOKEN'), [
                'body' => json_encode([
                    'recipient' => [ 'id'   => $userId ],
                    'message'   => $message
                ]),
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF-8',
                ]
            ]
        );
    }

} 