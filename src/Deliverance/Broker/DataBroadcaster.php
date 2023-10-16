<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

interface DataBroadcaster extends
    OutputBroadcaster,
    ErrorBroadcaster
{
    /**
     * Add data receiver for both output and error endpoints
     *
     * @return $this
     */
    public function addDataReceiver(DataReceiver $receiver): static;

    /**
     * Is receiver in any endpoint
     */
    public function hasDataReceiver(DataReceiver $receiver): bool;

    /**
     * Remove data receiver from all endpoints
     *
     * @return $this
     */
    public function removeDataReceiver(DataReceiver $receiver): static;
}
