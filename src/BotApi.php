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

class BotApi {

    /**
     * @var Client
     */
    protected $_client;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_response = new Response();
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

        //$data = $this->getOrderData($orderId);
        //$this->_response->sendMessage($userId, $data);
        $this->_response->sendMessage($userId, "Tack för din order, ditt ordernummer är ".$orderId.". Har du några frågor är det bara att skriva :)");
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
            $order = json_decode($res->getBody()->getContents());
            return $this->formatProductOutput((array)$order->orderitems);
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Get recommended products
     *
     * @param  $context
     * @return array|mixed
     */
    public function getRecommendations($context)
    {
        $producttype = $context->get("prod");
        try {
            $res      = $this->_client->get('/rest-api/bot/products/'.$producttype, []);
            $products = json_decode($res->getBody()->getContents());

            return $this->formatProductOutput($products);
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Format product arrays as facebook messenger attachment
     *
     * @param  $products
     * @return array
     */
    public function formatProductOutput($products)
    {
        $data = array(
            "attachment" => array(
                "type" => "template",
                "payload" => array(
                    "template_type" => "generic",
                    "elements" => array()
                )
            )
        );
        foreach ($products as $product) {
            $product = (array)$product;
            if (isset($product['weblink'])) {
                $product['url_key'] = $product['weblink'];
            }

            $data["attachment"]["payload"]["elements"][] = array(
                "title"     => $product["brand"]." ".$product["shortname"],
                "image_url" => "https://ridestore.imgix.net/catalog/product/".$product["image"]."?fm=jpg&q=70&usm=15&chromasub=444&dpr=1&w=400",
                "subtitle"  => isset($product["price"]) ? $product["price"] : "",
                "buttons"   => array(
                    "type"  => "web_url",
                    "url"   => "http://ng.ridestore.com/".$product["url_key"],
                    "title" => "View product"
                )
            );
        }
        return $data;
    }

} 