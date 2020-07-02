<?php

namespace webmenedzser\craftsimplepay\services\simplepay\Message;

use webmenedzser\craftsimplepay\services\simplepay\Message\AbstractRequest;
use webmenedzser\craftsimplepay\services\simplepay\Message\PurchaseResponse;
use webmenedzser\craftsimplepay\services\simplepay\Sdk\SimplePayStart;

class PurchaseRequest extends AbstractRequest
{
    /**
     * Get API Endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . 'v2/start';
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [
            'salt' => $this->getSalt(),
            'merchantKey' => $this->getSecretKey(),
            'merchant' => $this->getMerchant(),
            'orderRef' => $this->getOrderRef(),
            'currency' => $this->getCurrency(),
            'customerEmail' => $this->getCustomerEmail(),
            'language' => $this->getLanguage(),
            'total' => $this->getTotal(),
            'url' => $this->getUrl(),
            'SANDBOX' => $this->getTestMode()
        ];
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $simplePayStart = new SimplePayStart($this->getData());
        $result = $simplePayStart->runStart();

        return $this->response = new PurchaseResponse($this, $result['paymentUrl']);
    }
}
