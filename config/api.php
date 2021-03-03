<?php

return [
    /**
     * 接口频率限制
     */
    'rate_limits' => [
        'access' => env('RATE_LIMITS', '60,1'),
        'sign' => env('SIGN_RATE_LIMITS', '10,1'),
    ]
];