<?php

namespace Farzai\KApi\QrPayment;

use Farzai\KApi\AbstractWebhookHandler;
use Farzai\KApi\Contracts\ServerRequestInterface;

class PaymentNotificationCallback extends AbstractWebhookHandler
{
    /**
     * Validate the webhook request.
     */
    protected function validate(ServerRequestInterface $request): void
    {
        $this
            ->method($request, 'POST')
            ->contentType($request, 'application/json');
    }
}
