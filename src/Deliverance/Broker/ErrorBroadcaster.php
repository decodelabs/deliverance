<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

interface ErrorBroadcaster
{
    /**
     * Add receiver on error endpoint
     *
     * @return $this
     */
    public function addErrorReceiver(
        DataReceiver $receiver
    ): static;

    /**
     * Is receiver registered at error endpoint?
     */
    public function hasErrorReceiver(
        DataReceiver $receiver
    ): bool;

    /**
     * Remove receiver from error endpoint
     *
     * @return $this
     */
    public function removeErrorReceiver(
        DataReceiver $receiver
    ): static;

    /**
     * Get list of error receivers
     *
     * @return array<int, DataReceiver>
     */
    public function getErrorReceivers(): array;

    /**
     * Get first error receiver
     */
    public function getFirstErrorReceiver(): ?DataReceiver;
}
