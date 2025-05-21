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
     * @var array<int,DataReceiver>
     */
    public array $errorReceivers { get; }

    /**
     * @return $this
     */
    public function addErrorReceiver(
        DataReceiver $receiver
    ): static;

    public function hasErrorReceiver(
        DataReceiver $receiver
    ): bool;

    /**
     * @return $this
     */
    public function removeErrorReceiver(
        DataReceiver $receiver
    ): static;

    public function getFirstErrorReceiver(): ?DataReceiver;
}
