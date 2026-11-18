<?php

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    require __DIR__ . '/test-local.php',
    [
        'components' => [
            'request' => [
                // Keep this empty in the template: `init` generates a unique local key.
                'cookieValidationKey' => '',
            ],
        ],
    ]
);
