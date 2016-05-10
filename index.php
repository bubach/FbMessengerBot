<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->get('/callback', function (Request $request) use ($app) {
  if ($request->query->get('hub_verify_token') == getenv('VALIDATION_TOKEN')) {
    return new Response($request->query->get('hub_challenge'), 200);
  }
  return new Response('Error, wrong validation token', 500);
});

$app->post('/callback', function (Request $request) use ($app) {
  $content = json_decode($request->getContent(), true);
  $messaging_events = $content['entry'][0]['messaging'];

  $client = new GuzzleHttp\Client();

  foreach ($messaging_events as $event) {
    try {
      $client->request(
        'POST', 'https://graph.facebook.com/v2.6/me/messages?access_token='.getenv('PAGE_ACCESS_TOKEN'), [
            'body' => json_encode([
              'recipient' => [ 'id' => $event['sender']['id'] ],
              'message'   => [ 'text' => $event['message']['text'] ]
            ]),
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ]
        ]
      );
    } catch (Exception $e) {
      error_log($e->getMessage());
    }
  }

  return new Response('', 200);
});

$app->run();