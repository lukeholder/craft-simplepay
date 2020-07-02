<?php
/**
 * Simplepay for Craft Commerce
 *
 * SimplePay payment gateway for Craft Commerce
 *
 * @link      https://www.webmenedzser.hu
 * @copyright Copyright (c) 2020 Ottó Radics
 */

namespace webmenedzser\craftsimplepay\helpers;

use craft\base\Component;
use craft\commerce\elements\Order;
use craft\commerce\Plugin as Commerce;

/**
 * Class SimplePayHelper
 *
 * @package webmenedzser\craftsimplepay\helpers
 */
class SimplePayHelper extends Component
{
    /**
     * Get Order by orderRef string
     *
     * @param string $orderRef
     *
     * @return Order
     * @throws \Exception
     */
    public static function getOrderByOrderRef(string $orderRef)
    {
        /**
         * Random string is appended to order nr. before redirecting to offsite
         * payment gateway. Order nr. in Craft Commerce consists of 32 characters.
         * We should trim the characters to the first 32.
         */
        $orderNumber = substr($orderRef, 0, 32);

        /**
         * Get order by number
         *
         * @var Order $order
         */
        $order = Commerce::getInstance()
            ->getOrders()
            ->getOrderByNumber($orderNumber);

        if (!$order) {
            throw new \Exception('Order not found.');
        }

        return $order;
    }
}
