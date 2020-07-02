<?php
/**
 * Finish
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */

namespace webmenedzser\craftsimplepay\services\simplepay\Sdk;

use webmenedzser\craftsimplepay\services\simplepay\Sdk\SimplePayBase;

class SimplePayFinish extends SimplePayBase
{
    protected $currentInterface = 'finish';
    protected $returnData = [];
    public $transactionBase = [
        'salt' => '',
        'merchant' => '',
        'orderRef' => '',
        'transactionId' => '',
        'originalTotal' => '',
        'approveTotal' => '',
        'currency' => '',
    ];

    /**
     * Run finish
     *
     * @return array $result API response
     */
    public function runFinish()
    {
        return $this->execApiCall();
    }
}
