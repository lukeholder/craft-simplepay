<?php
/**
 * Simplepay for Craft Commerce
 *
 * SimplePay payment gateway for Craft Commerce
 *
 * @link      https://www.webmenedzser.hu
 * @copyright Copyright (c) 2020 Ottó Radics
 */

/**
 * SimplePay for Craft Commerce config.php
 *
 * This file exists only as a template for the SimplePay for Craft Commerce plugin settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'craft-simplepay.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    '*' => [
        'testMode' => '1'
    ],

    'production' => [
        'testMode' => ''
    ]
];
