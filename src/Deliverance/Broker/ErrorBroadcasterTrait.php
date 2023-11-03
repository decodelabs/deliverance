<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

trait ErrorBroadcasterTrait
{
    /**
     * @var array<int, DataReceiver>
     */
    protected array $errorReceivers = [];

    /**
     * Add receiver on error endpoint
     *
     * @return $this
     */
    public function addErrorReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);
        $this->errorReceivers[$id] = $receiver;

        return $this;
    }

    /**
     * Is receiver registered at error endpoint?
     */
    public function hasErrorReceiver(
        DataReceiver $receiver
    ): bool {
        $id = spl_object_id($receiver);
        return isset($this->errorReceivers[$id]);
    }

    /**
     * Remove receiver from error endpoint
     *
     * @return $this
     */
    public function removeErrorReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);
        unset($this->errorReceivers[$id]);
        return $this;
    }

    /**
     * Get list of error receivers
     *
     * @return array<int, DataReceiver>
     */
    public function getErrorReceivers(): array
    {
        return $this->errorReceivers;
    }

    /**
     * Get first error receiver
     */
    public function getFirstErrorReceiver(): ?DataReceiver
    {
        foreach ($this->errorReceivers as $receiver) {
            return $receiver;
        }

        return null;
    }
}
