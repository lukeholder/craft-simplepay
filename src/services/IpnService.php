<?php
/**
 * Simplepay for Craft Commerce
 *
 * SimplePay payment gateway for Craft Commerce
 *
 * @link      https://www.webmenedzser.hu
 * @copyright Copyright (c) 2020 Ottó Radics
 */

namespace webmenedzser\craftsimplepay\services;

use webmenedzser\craftsimplepay\helpers\SimplePayHelper;
use webmenedzser\craftsimplepay\services\simplepay\Sdk\SimplePayIpn;

use Craft;
use craft\base\Component;
use craft\commerce\Plugin as Commerce;
use craft\web\Request;

/**
 * Class IpnService
 *
 * @package webmenedzser\craftsimplepay\services
 */
class IpnService extends Component
{
    /**
     * @param Request $request
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getResponse(Request $request) : string
    {
        $orderRef = $request->getParam('orderRef');
        $order = SimplePayHelper::getOrderByOrderRef($orderRef);

        /**
         * Get Gateway ID from Order
         */
        $gatewayId = $order->gatewayId;
        $gateway = Commerce::getInstance()
            ->gateways
            ->getGatewayById($gatewayId);

        if (!$gateway) {
            throw new \Exception('Gateway not found.');
        }

        /**
         * Call SimplePay SDK to process the response
         */
        $simplePayIpn = new SimplePayIpn([
            'SANDBOX' => Craft::$app->config->general->devMode,
            'merchant' => $gateway->merchant,
            'merchantKey' => $gateway->secretKey
        ]);

        $json = json_encode($request->getBodyParams());

        if (!$simplePayIpn->isIpnSignatureCheck($json)) {
            return '';
        }

        $transaction = $order->getLastTransaction();
        if (!$transaction) {
            return '';
        }

        /**
         * Call gateway's completePayment method to make Commerce mark order as paid.
         */
        Commerce::getInstance()->payments->completePayment($transaction, $customError);

        $confirmation = $simplePayIpn->getIpnConfirmContent();

        Craft::$app->response
            ->getHeaders()
            ->add('Accept-language', 'EN');

        Craft::$app->response
            ->getHeaders()
            ->add('Content-type', 'application/json');

        Craft::$app->response
            ->getHeaders()
            ->add('Signature', $confirmation['signature']);

        return $confirmation['confirmContent'];
    }
}
