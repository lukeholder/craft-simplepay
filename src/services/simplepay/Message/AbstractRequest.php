<?php

namespace webmenedzser\craftsimplepay\services\simplepay\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    /**
     * @var string
     */
    const ENDPOINT_SANDBOX = "https://sandbox.simplepay.hu/payment/";

    /**
     * @var string
     */
    const ENDPOINT_LIVE    = "https://secure.simplepay.hu/payment/";

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest
     */
    public function setSecretKey($value) {
        return $this->setParameter('secretKey', $value);
    }

    /**
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest
     */
    public function setMerchant($value) {
        return $this->setParameter('merchant', $value);
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
     * @return AbstractRequest
     */
    public function setOrderRef($value) {
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
     * @return AbstractRequest
     */
    public function setCustomerEmail($value) {
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
     * @return AbstractRequest
     */
    public function setLanguage($value) {
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
     * @return AbstractRequest
     */
    public function setTimeout($value) {
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
     * @return AbstractRequest
     */
    public function setUrl($value) {
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
     * @return AbstractRequest
     */
    public function setTotal($value) {
        return $this->setParameter('total', $value);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? self::ENDPOINT_SANDBOX : self::ENDPOINT_LIVE;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Random string generation for salt
     *
     * @param integer $length Length of random string, default 32
     *
     * @return string Random string
     */
    protected function getSalt($length = 32)
    {
        $saltBase = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for ($i=1; $i < $length; $i++) {
            $saltBase .= substr($chars, rand(1, strlen($chars)), 1);
        }
        return hash('md5', $saltBase);
    }

    public function getHashString($data)
    {
        $json = json_encode($data);
        $hash = hash_hmac('sha384', $json, $this->getSecretKey());

        return base64_encode($hash);
    }
}
