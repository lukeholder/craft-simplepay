<?php

namespace webmenedzser\craftsimplepay\services\simplepay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessful() : bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRedirect() : bool
    {
        return true;
    }

    /**
     * @return mixed|string|null
     */
    public function getRedirectUrl()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getRedirectMethod() : string
    {
        return 'POST';
    }
}
