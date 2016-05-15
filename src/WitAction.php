<?php
/**
 * Copyright Ridestore AB.
 * User: Christoffer Bubach
 * Date: 16-05-14
 * Time: 15:29
 */

namespace Bot;

use Tgallice\Wit\ActionMapping;
use Tgallice\Wit\Helper;
use Tgallice\Wit\Model\Context;
use Tgallice\Wit\Model\Step;

class WitAction extends ActionMapping {

    /**
     * @var Response
     */
    private $_response;

    /**
     * @var BotApi
     */
    private $_api;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_response = new Response();
        $this->_api      = new BotApi();
    }

    /**
     * Perform action with API
     *
     * @param  string $sessionId
     * @param  string $actionName
     * @param  Context $context
     * @return Context
     */
    public function action($sessionId, $actionName, Context $context)
    {
        $this->_response->sendMessage($sessionId, sprintf('<info>+ Action : %s</info>', $actionName));

        if ($actionName === 'recommendations') {
            $this->_response->sendMessage($sessionId, $this->_api->getRecommendations($context));
        }

        return $context;
    }

    /**
     * Output Wit.ai text response
     *
     * @param string  $sessionId
     * @param string  $message
     * @param Context $context
     */
    public function say($sessionId, $message, Context $context)
    {
        $this->_response->sendMessage($sessionId, $message);
    }

    /**
     * @param string $sessionId
     * @param Context $context
     * @param string $error
     * @param array $step
     */
    public function error($sessionId, Context $context, $error = '', array $step = null)
    {
        // logging here..
    }

    /**
     * Merge wit.ai context
     *
     * @param  string $sessionId
     * @param  Context $context
     * @param  array $entities
     * @return Context
     */
    public function merge($sessionId, Context $context, array $entities)
    {
        //$this->_response->sendMessage($sessionId, '<info>+ Merge context with :</info>');
        //$this->_response->sendMessage($sessionId, '<comment>'.json_encode($entities, JSON_PRETTY_PRINT).'</comment>');
        $loc = Helper::getFirstEntityValue('prod', $entities);
        $context->add('prod', $loc);

        return $context;
    }

    /**
     * Reached the end of the conversation
     *
     * @param string $sessionId
     * @param Context $context
     */
    public function stop($sessionId, Context $context)
    {
        //$this->_response->sendMessage($sessionId, '<info>+ Stop</info>');
    }

} 