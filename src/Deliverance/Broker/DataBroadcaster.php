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
     * @return $this
     */
    public function addDataReceiver(
        DataReceiver $receiver,
        bool $input = true,
        bool $output = true,
    ): static;

    public function hasDataReceiver(
        DataReceiver $receiver
    ): bool;

    /**
     * @return $this
     */
    public function removeDataReceiver(
        DataReceiver $receiver
    ): static;
}
