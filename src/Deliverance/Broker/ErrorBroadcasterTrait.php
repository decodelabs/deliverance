<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

/**
 * @phpstan-require-implements ErrorBroadcaster
 */
trait ErrorBroadcasterTrait
{
    /**
     * @var array<int,DataReceiver>
     */
    public protected(set) array $errorReceivers = [];

    /**
     * @return $this
     */
    public function addErrorReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);
        $this->errorReceivers[$id] = $receiver;

        return $this;
    }

    public function hasErrorReceiver(
        DataReceiver $receiver
    ): bool {
        $id = spl_object_id($receiver);
        return isset($this->errorReceivers[$id]);
    }

    /**
     * @return $this
     */
    public function removeErrorReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);
        unset($this->errorReceivers[$id]);
        return $this;
    }

    public function getFirstErrorReceiver(): ?DataReceiver
    {
        foreach ($this->errorReceivers as $receiver) {
            return $receiver;
        }

        return null;
    }
}
