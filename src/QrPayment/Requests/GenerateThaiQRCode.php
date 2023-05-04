<?php

namespace Farzai\KApi\QrPayment\Requests;

use Farzai\KApi\Http\Request;

class GenerateThaiQRCode extends Request
{
    public function __construct()
    {
        $this->to('/v1/qrpayment/request')
            ->asJson()
            ->withToken();
    }
}
