<?php
/**
 * Copyright Ridestore AB.
 * User: Christoffer Bubach
 * Date: 16-05-14
 * Time: 11:05
 */

namespace Bot;

use GuzzleHttp\Client;
use Exception;

class Api {

    /**
     * @var Client
     */
    protected $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'http://dev.ridestore.se/',
        ]);
    }

    /**
     * Connect fb messenger userId with order
     *
     * @param $userId
     * @param $orderId
     */
    public function saveNewUser($userId, $orderId)
    {
        $this->_client->get('/rest-api/bot/setOrderFbId/'.$userId, [
            'query' => ['id' => $orderId]
        ]);

        $data = $this->getOrderData($orderId);
        $response = new Response();
        /*
        foreach ($data->orderitems as $item) {

        }
        $response->sendOrder($userId, );
        */
    }

    /**
     * Get order data from API
     *
     * @param  $orderId
     * @return array|mixed
     */
    public function getOrderData($orderId)
    {
        try {
            $res = $this->_client->get('/rest-api/bot/getOrder', [
                'query' => ['id' => $orderId]
            ]);
            return json_decode($res->getBody()->getContents());
        } catch (Exception $e) {
            return array();
        }
    }

} 