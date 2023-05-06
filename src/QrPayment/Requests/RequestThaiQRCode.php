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
            ->asThaiQrCode()
            ->setCurrency('THB');
    }

    /**
     * Set partner information.
     *
     * @param  string  $partnerID
     * @param  string  $partnerTransactionID
     * @param  string  $partnerSecret
     * @param  \DateTimeInterface|string  $requestDateTime This will be formatted to: 2018-04-05T12:30:00+07:00
     */
    public function setPartner($partnerID, $partnerSecret, $partnerTransactionID, $requestDateTime)
    {
        // Format to: 2018-04-05T12:30:00+07:00
        if ($requestDateTime instanceof \DateTimeInterface) {
            $requestDateTime = $requestDateTime->format('Y-m-d\TH:i:sP');
        }

        // Validate partnerTxnUid length
        $partnerTransactionID = (string) $partnerTransactionID;
        if (strlen($partnerTransactionID) > 15) {
            // Documentation not mention about error code if partnerTxnUid is longer than 15 characters.
            throw new \InvalidArgumentException('Partner transaction ID (partnerTxnUid) must not be longer than 15 characters.');
        }

        return $this->withPayload([
            'partnerTxnUid' => (string) $partnerTransactionID,
            'partnerId' => (string) $partnerID,
            'partnerSecret' => (string) $partnerSecret,
            'requestDt' => (string) $requestDateTime,
        ]);
    }

    /**
     * Set qrcode type to type 3 (Thai QR Code).
     */
    public function asThaiQrCode()
    {
        return $this->setQrType(3);
    }

    /**
     * Set QR type.
     *
     * @param  int  $qrType 3 = Thai QR Code, 4 = Credit Card
     */
    public function setQrType($qrType)
    {
        return $this->withPayload([
            'qrType' => (string) $qrType,
        ]);
    }

    /**
     * Set merchant information.
     *
     * @param  string  $id
     */
    public function setMerchant($id)
    {
        return $this->withPayload([
            'merchantId' => (string) $id,
        ]);
    }

    /**
     * Set terminal information.
     *
     * @param  string  $id
     */
    public function setTerminal($id)
    {
        return $this->withPayload([
            'terminalId' => (string) $id,
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
            throw new \InvalidArgumentException('Maximum of 4 references are allowed.');
        }

        if (count($references) === 0) {
            throw new \InvalidArgumentException('At least one reference is required.');
        }

        return $this->withPayload(array_merge([
            'reference1' => '',
            'reference2' => null,
            'reference3' => null,
            'reference4' => null,
        ], array_combine(array_map(fn ($i) => "reference{$i}", range(1, count($references))), $references)));
    }
}
