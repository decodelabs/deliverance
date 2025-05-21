<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

interface OutputBroadcaster
{
    /**
     * @var array<int,DataReceiver>
     */
    public array $outputReceivers { get; }

    /**
     * @return $this
     */
    public function addOutputReceiver(
        DataReceiver $receiver
    ): static;

    public function hasOutputReceiver(
        DataReceiver $receiver
    ): bool;

    /**
     * @return $this
     */
    public function removeOutputReceiver(
        DataReceiver $receiver
    ): static;

    public function getFirstOutputReceiver(): ?DataReceiver;
}
