<?php

namespace webmenedzser\craftsimplepay\services\simplepay\Message;

use Omnipay\Common\Message\ResponseInterface;
use webmenedzser\craftsimplepay\services\simplepay\Message\AbstractRequest;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
