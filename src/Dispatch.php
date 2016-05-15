<?php
/**
 * Copyright Ridestore AB.
 * User: Christoffer Bubach
 * Date: 16-05-14
 * Time: 10:50
 */

namespace Bot;

use Tgallice\Wit\Client;
use Tgallice\Wit\Api;
use Tgallice\Wit\Api\Conversation;
use Exception;

class Dispatch {

    /**
     * @var Api
     */
    protected $_api;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_api = new BotApi();
    }

    /**
     * Dispatcher, handling different types of incoming messages
     *
     * @param $event
     */
    public function dispatchMessage($event)
    {
        try {
            if (isset($event['optin'])) {
                $this->_api->saveNewUser($event['sender']['id'], $event['optin']['ref']);
            } else if (isset($event['message'])) {
                $this->handleMessage($event);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Handle normal plain text message
     *
     * @param $event
     */
    public function handleMessage($event)
    {

        $client = new Client('TT33RF5JHOVLLTKHOJWAYOW2LVDJTQON');
        $api = new Api($client);

        $actionMapping = new WitAction();
        $conversation  = new Conversation($api, $actionMapping);
        $context = $conversation->converse($event['sender']['id'], $event['message']['text']);
    }

}