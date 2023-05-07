<?php

namespace Farzai\KApi\Support;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

class DT
{
    private $defaultTimezone = 'UTC';

    /**
     * Create a new DateTime instance.
     */
    public function __construct(string $defaultTimezone = null)
    {
        $this->defaultTimezone = $defaultTimezone ?? $this->defaultTimezone;
    }

    /**
     * Create a new DateTime instance.
     */
    public static function now(string $timezone = null): DateTimeInterface
    {
        return new DateTime('now', new DateTimeZone($timezone ?? 'UTC'));
    }
}
