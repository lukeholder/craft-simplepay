<?php
/**
 *  Copyright (C) 2020 OTP Mobil Kft.
 *
 *  PHP version 7
 *
 *  This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  SDK
 * @package   SimplePayV2
 * @author    SimplePay IT Support <itsupport@otpmobil.com>
 * @copyright 2020 OTP Mobil Kft.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link      http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */

namespace webmenedzser\craftsimplepay\services\simplepay\Sdk;

use webmenedzser\craftsimplepay\services\simplepay\Sdk\Traits\SignatureTrait as Signature;
use webmenedzser\craftsimplepay\services\simplepay\Sdk\Traits\CommunicationTrait as Communication;
use webmenedzser\craftsimplepay\services\simplepay\Sdk\Traits\ViewsTrait as Views;
use webmenedzser\craftsimplepay\services\simplepay\Sdk\Traits\LoggerTrait as Logger;

/**
 * Base class for SimplePay implementation
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayBase
{
    use Signature;
    use Communication;
    use Views;
    use Logger;

    const SDK_VERSION = 'SimplePay_PHP_SDK_2.0.9_200130';
    const HASH_ALGO = 'sha384';

    const ENDPOINT_SANDBOX = 'https://sandbox.simplepay.hu/payment';
    const ENDPOINT_LIVE = 'https://secure.simplepay.hu/payment';

    const INTERFACE_START = '/v2/start';
    const INTERFACE_FINISH = '/v2/finish';
    const INTERFACE_REFUND = '/v2/refund';
    const INTERFACE_QUERY = '/v2/query';

    public $config = [];
    public $logTransactionId = 'N/A';
    public $logOrderRef = 'N/A';
    public $logPath = '';

    protected $hashAlgo = self::HASH_ALGO;

    protected $api = [
        'sandbox' => self::ENDPOINT_SANDBOX,
        'live' => self::ENDPOINT_LIVE
    ];

    protected $apiInterface = [
        'start' => self::INTERFACE_START,
        'finish' => self::INTERFACE_FINISH,
        'refund' => self::INTERFACE_REFUND,
        'query' => self::INTERFACE_QUERY,
    ];

    protected $headers = [];
    protected $logSeparator = '|';
    protected $logContent = [];
    protected $debugMessage = [];
    protected $currentInterface = '';
    protected $phpVersion = 7;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->logContent['runMode'] = strtoupper($this->currentInterface);
        $ver = (float)phpversion();
        $this->logContent['PHP'] = $ver;
        if (is_numeric($ver)) {
            if ($ver < 7.0) {
                $this->phpVersion = 5;
            }
        }

        $this->addData('sdkVersion', $this->getSdkString());

        foreach ($data as $key => $value) {
            $this->config[$key] = $value;
            $this->addData($key, $value);
        }
    }

    /**
     * Add uniq config field
     *
     * @param string $key   Config field name
     * @param string $value Vonfig field value
     *
     * @return void
     */
    public function addConfigData($key = '', $value = '')
    {
        if ($key == '') {
            $key = 'EMPTY_CONFIG_KEY';
        }
        $this->config[$key] = $value;
    }

    /**
     * Add complete config array
     *
     * @param string $config Populated config array
     *
     * @return void
     */
    public function addConfig($config = [])
    {
        foreach ($config as $configKey => $configValue) {
            $this->config[$configKey] = $configValue;
        }
    }

    /**
     * Add uniq transaction field
     *
     * @param string $key   Data field name
     * @param string $value Data field value
     *
     * @return void
     */
    public function addData($key = '', $value = '')
    {
        if ($key == '') {
            $key = 'EMPTY_DATA_KEY';
        }
        $this->transactionBase[$key] = $value;
    }

    /**
     * Add data to a group
     *
     * @param string $group Data group name
     * @param string $key   Data field name
     * @param string $value Data field value
     *
     * @return void
     */
    public function addGroupData($group = '', $key = '', $value = '')
    {
        if (!isset($this->transactionBase[$group])) {
            $this->transactionBase[$group] = [];
        }
        $this->transactionBase[$group][$key] = $value;
    }

    /**
     * Add item to pay
     *
     * @param string $itemData A product or service for pay
     *
     * @return void
     */
    public function addItems($itemData = [])
    {
        $item = [
            'ref' => '',
            'title' => '',
            'description' => '',
            'amount' => 0,
            'price' => 0,
            'tax' => 0,
        ];

        if (!isset($this->transactionBase['items'])) {
            $this->transactionBase['items'] = [];
        }

        foreach ($itemData as $itemKey => $itemValue) {
            $item[$itemKey] = $itemValue;
        }
        $this->transactionBase['items'][] = $item;
    }

    /**
     * Shows transaction base data
     *
     * @return array $this->transactionBase Transaction data
     */
    public function getTransactionBase()
    {
        return $this->transactionBase;
    }

    /**
     * Shows API call return data
     *
     * @return array $this->returnData Return data
     */
    public function getReturnData()
    {
        return $this->convertToArray($this->returnData);
    }

    /**
     * Check data if JSON, or set data to JSON
     *
     * @param string $data Data
     *
     * @return string JSON encoded data
     */
    public function checkOrSetToJson($data = '')
    {
        $json = '[]';
        //empty
        if ($data === '') {
            $json =  json_encode([]);
        }
        //array
        if (is_array($data)) {
            $json =  json_encode($data);
        }
        //object
        if (is_object($data)) {
            $json =  json_encode($data);
        }
        //json
        $result = @json_decode($data);
        if ($result !== null) {
            $json =  $data;
        }
        //serialized
        $result = @unserialize($data);
        if ($result !== false) {
            $json =  json_encode($result);
        }
        return $json;
    }

    /**
     * Serves header array
     *
     * @param string $hash     Signature for validation
     * @param string $language Landuage of content
     *
     * @return array Populated header array
     */
    protected function getHeaders($hash = '')
    {
        $headers = [
            'Accept-language: ' . $this->config['language'],
            'Content-type: application/json',
            'Signature: ' . $hash,
        ];
        return $headers;
    }

    /**
     * Random string generation for salt
     *
     * @param integer $length Lemgth of random string, default 32
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

    /**
     * API URL settings depend on function
     *
     * @return string
     */
    protected function getEndpoint($interface)
    {
        $baseUrl = $this->config['SANDBOX'] ?
            self::ENDPOINT_SANDBOX :
            self::ENDPOINT_LIVE;

        $interfaceSegment = $this->apiInterface[$interface];

        return $baseUrl . $interfaceSegment;
    }

    /**
     * Convert object to array
     *
     * @param object $obj Object to transform
     *
     * @return array $new Result array
     */
    protected function convertToArray($obj)
    {
        if (is_object($obj)) {
            $obj = (array) $obj;
        }
        $new = $obj;
        if (is_array($obj)) {
            $new = [];
            foreach ($obj as $key => $val) {
                $new[$key] = $this->convertToArray($val);
            }
        }
        return $new;
    }

    /**
     * Creates a 1-dimension array from a 2-dimension one
     *
     * @param array $arrayForProcess Array to be processed
     *
     * @return array $return          Flat array
     */
    protected function getFlatArray($arrayForProcess = [])
    {
        $array = $this->convertToArray($arrayForProcess);
        $return = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $subArray = $this->getFlatArray($value);
                foreach ($subArray as $subKey => $subValue) {
                    $return[$key . '_' . $subKey] = $subValue;
                }
            } elseif (!is_array($value)) {
                $return[$key] = $value;
            }
        }
        return $return;
    }

    /**
     * Set config variables
     *
     * @return void
     */
    protected function setConfig()
    {
        $this->config['api'] = 'live';
        if ($this->config['SANDBOX']) {
            $this->config['api'] = 'sandbox';
        }

        $this->config['logPath'] = 'log';
        if (isset($this->config['LOG_PATH'])) {
            $this->config['logPath'] = $this->config['LOG_PATH'];
        }

        $this->config['autoChallenge'] = false;
        if (isset($this->config['AUTOCHALLENGE'])) {
            $this->config['autoChallenge'] = $this->config['AUTOCHALLENGE'];
        }
    }

    protected function getSdkString()
    {
        return self::SDK_VERSION . ':' . hash_file('md5', __FILE__);
    }

    /**
     * Transaction preparation
     *
     * All settings before start transaction
     *
     * @return void
     */
    protected function prepare()
    {
        $this->setConfig();
        $this->logContent['callState1'] = 'PREPARE';
        $this->transactionBase['merchant'] = $this->config['merchant'];
        $this->transactionBase['salt'] = $this->getSalt();
        $this->transactionBase['language'] = $this->config['language'];
        $this->content = $this->getHashBase($this->transactionBase);
        $this->logContent = array_merge($this->logContent, $this->transactionBase);
        $this->config['computedHash'] = $this->getSignature($this->config['merchantKey'], $this->content);

        $this->logContent['computedHashBase'] = $this->content;

        $this->headers = $this->getHeaders(
            $this->config['computedHash']
        );
    }

    /**
     * Execute API call and returns with result
     *
     * @return array $result
     */
    protected function execApiCall()
    {
        $this->prepare();
        $transaction = [];

        $this->logContent['callState2'] = 'REQUEST';
        $this->logContent['sendApiUrl'] = $this->getEndpoint($this->currentInterface);
        $this->logContent['sendContent'] = $this->content;
        $this->logContent['sendSignature'] = $this->config['computedHash'];

        $commRresult = $this->runCommunication(
            $this->getEndpoint($this->currentInterface),
            $this->content,
            $this->headers
        );

        $this->logContent['callState3'] = 'RESULT';

        //call result
        $result = explode("\r\n", $commRresult);
        $transaction['responseBody'] = end($result);

        //signature
        foreach ($result as $resultItem) {
            $headerElement = explode(":", $resultItem);
            if (isset($headerElement[0]) && isset($headerElement[1])) {
                $header[$headerElement[0]] = $headerElement[1];
            }
        }

        $this->logContent['header'] = $header;

        $transaction['responseSignature'] = $this->getSignatureFromHeader($header);

        //check transaction validity
        $transaction['responseSignatureValid'] = false;

        if ($this->isCheckSignature($transaction['responseBody'], $transaction['responseSignature'])) {
            $transaction['responseSignatureValid'] = true;
        }

        //fill transaction data
        if (is_object(json_decode($transaction['responseBody']))) {
            foreach (json_decode($transaction['responseBody']) as $key => $value) {
                $transaction[$key] = $value;
            }
        }

        if (isset($transaction['transactionId'])) {
            $this->logTransactionId = $transaction['transactionId'];
        } elseif (isset($transaction['cardId'])) {
            $this->logTransactionId = $transaction['cardId'];
        }
        if (isset($transaction['orderRef'])) {
            $this->logOrderRef = $transaction['orderRef'];
        }

        $this->returnData = $transaction;

        return $transaction;
    }
}



