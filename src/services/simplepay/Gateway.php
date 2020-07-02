<?php

namespace webmenedzser\craftsimplepay\services\simplepay;

use Omnipay\Common\AbstractGateway;
use webmenedzser\craftsimplepay\services\simplepay\Message\PurchaseRequest;
use webmenedzser\craftsimplepay\services\simplepay\Message\CompletePurchaseRequest;

class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'OTP SimplePay v2.1';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'secretKey' => '',
            'merchant' => '',
            'testMode' => false,
            'timeoutUrl' => '',
        ];
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        $this->getParameter('secretKey');
    }

    /**
     * @param $value
     */
    public function setSecretKey($value)
    {
        $this->setParameter('secretKey', $value);
    }

    /**
     * @return string
     */
    public function getMerchant()
    {
        $this->getParameter('merchant');
    }

    /**
     * @param $value
     */
    public function setMerchant($value)
    {
        $this->setParameter('merchant', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderRef()
    {
        return $this->getParameter('orderRef');
    }

    /**
     * @param $value
     *
     * @return Gateway
     */
    public function setOrderRef($value)
    {
        return $this->setParameter('orderRef', $value);
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->getParameter('customerEmail');
    }

    /**
     * @param $value
     *
     * @return Gateway
     */
    public function setCustomerEmail($value)
    {
        return $this->setParameter('customerEmail', $value);
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     *
     * @return Gateway
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->getParameter('timeout');
    }

    /**
     * @param $value
     *
     * @return Gateway
     */
    public function setTimeout($value)
    {
        return $this->setParameter('timeout', $value);
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->getParameter('url');
    }

    /**
     * @param $value
     *
     * @return Gateway
     */
    public function setUrl($value)
    {
        return $this->setParameter('url', $value);
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->getParameter('total');
    }

    /**
     * @param $value
     *
     * @return Gateway
     */
    public function setTotal($value)
    {
        return $this->setParameter('total', $value);
    }

    /**
     * @inheritDoc
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
