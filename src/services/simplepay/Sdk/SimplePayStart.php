<?php
/**
 * Start transaction
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */

namespace webmenedzser\craftsimplepay\services\simplepay\Sdk;

use webmenedzser\craftsimplepay\services\simplepay\Sdk\SimplePayBase;

class SimplePayStart extends SimplePayBase
{
    protected $currentInterface = 'start';
    public $transactionBase = [
        'salt' => '',
        'merchant' => '',
        'orderRef' => '',
        'currency' => '',
        'customerEmail' => '',
        'sdkVersion' => '',
        'methods' => ['CARD'],
        'url' => 'http://beac.localhost:3000/back.php'
    ];

    /**
     * Send initial data to SimplePay API for validation
     * The result is the payment link to where website has to redirect customer
     *
     */
    public function runStart()
    {
        return $this->execApiCall();
    }
}
