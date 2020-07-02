<?php

namespace webmenedzser\craftsimplepay\models;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

class Settings extends Model
{
    public $successUrl;
    public $failUrl;
    public $cancelUrl;
    public $timeoutUrl;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'successUrl',
                'failUrl',
                'cancelUrl',
                'timeoutUrl'
            ],
        ];

        return $behaviors;
    }
}
