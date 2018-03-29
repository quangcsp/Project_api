<?php

return [
    'default_code'  => 500,
    'code' => [
        '304' => 'not_modified',
        '400' => 'bad_request',
        '401' => 'unauthorized',
        '403' => 'access_denied',
        '404' => 'not_found',
        '405' => 'method_not_allowed',
        '422' => 'validator_error',
        '500' => 'internal_server_error',
        '502' => 'bad_gateway',
        '503' => 'service_unavailable',
        '504' => 'gateway_timeout'
    ],
];
