<?php

namespace App\Exceptions\Api;

class NotAdminException extends ApiException
{
    public function __construct($message = null, $statusCode = 400)
    {
        $message = $message ? $message : translate('exception.not_admin');

        parent::__construct($message, $statusCode);
    }
}
