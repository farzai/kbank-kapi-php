<?php

namespace Farzai\KApi\QrPayment\Requests;

use Farzai\KApi\Http\Request;

class RequestThaiQRCode extends Request
{
    public function __construct()
    {
        $this->post('/v1/qrpayment/request')
            ->asJson();
    }
}
