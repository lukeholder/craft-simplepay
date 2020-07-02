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

use webmenedzser\craftsimplepay\services\BackService;

use Craft;
use craft\web\Controller;
use craft\web\Response;

/**
 * This controller will redirect the user to the correct page when the payment process is finished/interrupted.
 *
 * @package webmenedzser\craftsimplepay\controllers
 */
class BackController extends Controller
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
     * @return Response
     * @throws \Exception
     */
    public function actionIndex() : Response
    {
        $request = Craft::$app->request;
        $redirectUrl = BackService::redirectUser($request);

        return Craft::$app->getResponse()->redirect($redirectUrl);
    }
}
