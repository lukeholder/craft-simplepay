<?php
/**
 * Refund
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */

namespace webmenedzser\craftsimplepay\services\simplepay\Sdk;

use webmenedzser\craftsimplepay\services\simplepay\Sdk\SimplePayBase;

class SimplePayRefund extends SimplePayBase
{
    protected $currentInterface = 'refund';
    protected $returnData = [];
    public $transactionBase = [
        'salt' => '',
        'merchant' => '',
        'orderRef' => '',
        'transactionId' => '',
        'currency' => '',
    ];

    /**
     * Run refund
     *
     * @return array $result API response
     */
    public function runRefund()
    {
        if ($this->transactionBase['orderRef'] == '') {
            unset($this->transactionBase['orderRef']);
        }
        if ($this->transactionBase['transactionId'] == '') {
            unset($this->transactionBase['transactionId']);
        }
        $this->logTransactionId = @$this->transactionBase['transactionId'];
        $this->logOrderRef = @$this->transactionBase['orderRef'];
        return $this->execApiCall();
    }
}
