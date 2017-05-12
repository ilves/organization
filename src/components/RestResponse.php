<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\components;

use Yii;
use yii\base\Event;
use yii\web\Response;

/**
 * RestResponse extends common Response object to handle Exception responses
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class RestResponse extends Response
{
    /** @inheritdoc */
    public $format = yii\web\Response::FORMAT_JSON;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_SEND, [$this, 'handleResponse']);
    }

    /**
     * Handles app response. If exception is detected, sends correct response
     *
     * @param Event $event
     */
    public function handleResponse(Event $event)
    {
        $response = $event->sender;
        $exception = Yii::$app->getErrorHandler()->exception;
        if ($response->statusCode >= 400 && $exception !== null) {
            $response->data = [
                'errors' => [
                    ['message' => $exception->getMessage()],
                ]
            ];
        }
    }
}