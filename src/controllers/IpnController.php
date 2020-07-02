<?php
/**
 * Simplepay for Craft Commerce
 *
 * SimplePay payment gateway for Craft Commerce
 *
 * @link      https://www.webmenedzser.hu
 * @copyright Copyright (c) 2020 Ottó Radics
 */

namespace webmenedzser\craftsimplepay\controllers;

use webmenedzser\craftsimplepay\services\IpnService;

use Craft;
use craft\web\Controller;
use craft\web\Request;

/**
 * This controller should handle IPN requests.
 *
 * @package webmenedzser\craftsimplepay\controllers
 */
class IpnController extends Controller
{
    /**
     * Disable CSRF token validation - the payment gateway will not send a valid CSRF token.
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * Enable anonymous access to this controller.
     *
     * @var bool
     */
    protected $allowAnonymous = true;

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex() : string
    {
        /**
         * @var Request
         */
        $request = Craft::$app->request;

        return IpnService::getResponse($request);
    }
}
