<?php

namespace webmenedzser\craftsimplepay\services;

use craft\commerce\elements\Order;
use webmenedzser\craftsimplepay\CraftSimplepay;

use Craft;
use craft\base\Component;

class ResultService extends Component
{
    private $notification;
    private $routes;
    private $messages;

    /**
     * @var Order
     */
    private $order;

    public function __construct($notification, $order, $config = [])
    {
        parent::__construct($config);

        $this->order = $order;

        $this->notification = $notification;
        $this->routes = [
            'SUCCESS' => $this->order->returnUrl,
            'FAIL' => CraftSimplepay::getInstance()->getSettings()->failUrl,
            'CANCEL' => CraftSimplepay::getInstance()->getSettings()->cancelUrl,
            'TIMEOUT' => CraftSimplepay::getInstance()->getSettings()->timeoutUrl
        ];
        $this->messages = [
            'SUCCESS' => Craft::t('craft-simplepay', 'Payment successful'),
            'FAIL' => Craft::t('craft-simplepay', 'Payment failed'),
            'CANCEL' => Craft::t('craft-simplepay', 'Payment cancelled'),
            'TIMEOUT' => Craft::t('craft-simplepay', 'Payment timed out')
        ];

        $this->checkOrderPaid();
        $this->setFlash();
    }

    public function getRedirectUrl()
    {
        return $this->routes[$this->notification['e']];
    }

    public function getMessage()
    {
        return $this->messages[$this->notification['e']];
    }

    public function setFlash()
    {
        $error = !($this->notification['e'] === 'SUCCESS') ? 'error' : 'notice';

        Craft::$app->session->setFlash($error, $this->getMessage());
    }

    public function isSuccessful()
    {
        return $this->notification['e'] === 'SUCCESS';
    }

    public function checkOrderPaid()
    {
        if ($this->isSuccessful()) {
            $this->order->markAsComplete();
        }
    }
}
