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
            ->expectsJson()
            ->withBearerToken()
            ->withPayload([
                'qrType' => 3, // 3 = Thai QR Code, 4 = Credit Card
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

    /**
     * Set amount.
     *
     * @param  float  $amount
     */
    public function setAmount($amount)
    {
        return $this->withPayload([
            'txnAmount' => sprintf('%.2f', $amount),
        ]);
    }

    /**
     * Set currency.
     *
     * @param  string  $currency
     */
    public function setCurrency($currency)
    {
        $currency = strtoupper($currency);

        $currencies = [
            'THB',
        ];

        if (! in_array($currency, $currencies)) {
            throw new \InvalidArgumentException("Currency \"{$currency}\" is not supported.");
        }

        return $this->withPayload([
            'txnCurrencyCode' => $currency,
        ]);
    }

    /**
     * Set metadata.
     *
     * @param  string|array  $metadata
     */
    public function setMetadata($metadata)
    {
        return $this->withPayload([
            'metadata' => is_array($metadata) ? implode(', ', $metadata) : $metadata,
        ]);
    }

    /**
     * Set references
     *
     * @param  string  $references
     */
    public function setReferences(...$references)
    {
        if (count($references) > 4) {
            throw new \InvalidArgumentException('Maximum reference is 4.');
        }

        if (count($references) === 0) {
            throw new \InvalidArgumentException('At least one reference is required.');
        }

        return $this->withPayload(array_merge([
            'reference1' => '',
            'reference2' => '',
            'reference3' => '',
            'reference4' => '',
        ], array_combine(array_map(fn ($i) => "reference{$i}", range(1, count($references))), $references)));
    }
}
