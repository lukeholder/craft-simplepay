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
         * Get order by number
         *
         * @var Order $order
         */
        $order = Commerce::getInstance()
            ->getOrders()
            ->getOrderByNumber($orderRef);

        if (!$order) {
            throw new \Exception('Order not found.');
        }

        return $order;
    }
}
