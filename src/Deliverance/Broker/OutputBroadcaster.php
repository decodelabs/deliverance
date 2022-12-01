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
     * Add receiver on output endpoint
     *
     * @return $this
     */
    public function addOutputReceiver(DataReceiver $receiver): static;

    /**
     * Is receiver registered on input endpoint?
     */
    public function hasOutputReceiver(DataReceiver $receiver): bool;

    /**
     * Remove receiver from output endpoint
     *
     * @return $this
     */
    public function removeOutputReceiver(DataReceiver $receiver): static;

    /**
     * Get list of output receivers
     *
     * @return array<int, DataReceiver>
     */
    public function getOutputReceivers(): array;

    /**
     * Get first output receiver
     */
    public function getFirstOutputReceiver(): ?DataReceiver;
}
