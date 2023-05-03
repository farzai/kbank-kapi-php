<?php

namespace Farzai\KApi\Logger;

use Psr\Log\LoggerInterface;

class NullLogger implements LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param  mixed[]  $context
     */
    public function emergency($message, array $context = []): void
    {
        //
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param  mixed[]  $context
     */
    public function alert($message, array $context = []): void
    {
        //
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param  mixed[]  $context
     */
    public function critical($message, array $context = []): void
    {
        //
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param  mixed[]  $context
     */
    public function error($message, array $context = []): void
    {
        //
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param  mixed[]  $context
     */
    public function warning($message, array $context = []): void
    {
        //
    }

    /**
     * Normal but significant events.
     *
     * @param  mixed[]  $context
     */
    public function notice($message, array $context = []): void
    {
        //
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param  mixed[]  $context
     */
    public function info($message, array $context = []): void
    {
        //
    }

    /**
     * Detailed debug information.
     *
     * @param  mixed[]  $context
     */
    public function debug($message, array $context = []): void
    {
        //
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param  mixed  $level
     * @param  mixed[]  $context
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, $message, array $context = []): void
    {
        //
    }
}
