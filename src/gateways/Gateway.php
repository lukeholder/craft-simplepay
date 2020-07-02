<?php

namespace webmenedzser\craftsimplepay\gateways;

use webmenedzser\craftsimplepay\helpers\TemplateHelper;
use webmenedzser\craftsimplepay\services\simplepay\Gateway as OmnipayGateway;
use webmenedzser\craftsimplepay\services\IpnService;

use Craft;
use craft\commerce\base\RequestResponseInterface;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\Transaction;
use craft\commerce\Plugin as Commerce;
use craft\commerce\omnipay\base\OffsiteGateway;
use craft\helpers\UrlHelper;
use craft\helpers\Json;
use craft\web\Response;

use Omnipay\Common\AbstractGateway;

class Gateway extends OffsiteGateway
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $merchant;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * @var string
     */
    public $currency;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t(
            'craft-simplepay',
            'OTP SimplePay v2.1'
        );
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app
            ->getView()
            ->renderTemplate(
                'craft-simplepay/gatewaySettings',
                [
                    'gateway' => $this
                ]
            );
    }

    /**
     * @inheritdoc
     */
    public function getPaymentTypeOptions(): array
    {
        return [
            'purchase' => Craft::t(
                'commerce',
                'Purchase (Authorize and Capture Immediately)'
            )
        ];
    }

    public function getPaymentFormHtml(array $params)
    {
        $imageUrl = TemplateHelper::getHorizontalLogoUrl();
        $alt = Craft::t('craft-simplepay', 'Pay with OTP SimplePay');

        return "<div class='simplepay-logo-wrapper'><img alt='$alt' title='$alt' class='simplepay-logo' src='$imageUrl' /></div>";
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createGateway(): AbstractGateway
    {
        /** @var OmnipayGateway $gateway */
        $gateway = static::createOmnipayGateway($this->getGatewayClassName());
        $carts = Commerce::getInstance()->getCarts();
        $cart = $carts ? $carts->getCart() : null;

        if (!$cart) {
            throw new \Exception('The cart is empty.');
        }

        $orderRef = $cart->number . random_int(100000, 999999);

        $gateway->setMerchant(Craft::parseEnv($this->merchant));
        $gateway->setSecretKey(Craft::parseEnv($this->secretKey));
        $gateway->setTestMode(Craft::$app->config->general->devMode);
        $gateway->setCurrency($this->currency);
        $gateway->setOrderRef($orderRef);
        $gateway->setCustomerEmail($cart->customer->email);
        $gateway->setLanguage('HU');
        $gateway->setTotal($cart->total);
        $gateway->setUrl(UrlHelper::actionUrl(
            'craft-simplepay/back?orderRef=' . $orderRef
        ));

        return $gateway;
    }

    /**
     * @inheritdoc
     */
    protected function getGatewayClassName()
    {
        return '\\'.OmnipayGateway::class;
    }

    // Private Methods
    // =========================================================================
}
