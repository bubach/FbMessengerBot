<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bot\Dispatch;
use Bot\BotApi;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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

    $dispatch = new Dispatch();
    //$logger = new Logger('MessengerBOT');
    //$logger->pushHandler(new StreamHandler(__DIR__.'/logs/app.log', Logger::WARNING));

    foreach ($messaging_events as $event) {
        //$logger->addAlert("Event", $event);
        $dispatch->dispatchMessage($event);
    }

    return new Response('', 200);
});

$app->run();