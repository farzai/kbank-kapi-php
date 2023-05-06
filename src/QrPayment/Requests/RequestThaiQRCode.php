<?php

namespace Farzai\KApi\QrPayment\Requests;

use Farzai\KApi\Http\Request;

class RequestThaiQRCode extends Request
{
    /**
     * Create a new request instance.
     */
    public function __construct()
    {
        $this
            ->post('/v1/qrpayment/request')
            ->asJson()
            ->withBearerToken()
            ->withPayload([
                'qrType' => 3,
                'txnCurrencyCode' => 'THB',
                'reference1' => '',
                'reference2' => '',
                'reference3' => '',
                'reference4' => '',
            ]);
    }

    /**
     * Set partner information.
     *
     * @param  string  $partnerTransactionID
     * @param  string  $partnerID
     * @param  \DateTimeInterface|string  $requestDateTime This will be formatted to: 2018-04-05T12:30:00+07:00
     */
    public function setPartner($partnerTransactionID, $partnerID, $partnerSecret, $requestDateTime)
    {
        // Format to: 2018-04-05T12:30:00+07:00
        if ($requestDateTime instanceof \DateTimeInterface) {
            $requestDateTime = $requestDateTime->format('Y-m-d\TH:i:sP');
        }

        return $this->withPayload([
            'partnerTxnUid' => $partnerTransactionID,
            'partnerId' => $partnerID,
            'partnerSecret' => $partnerSecret,
            'requestDt' => $requestDateTime,
        ]);
    }

    public function setMerchant($id)
    {
        return $this->withPayload([
            'merchantId' => $id,
        ]);
    }

    public function setTerminal($id)
    {
        return $this->withPayload([
            'terminalId' => $id,
        ]);
    }
}
