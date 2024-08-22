<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

/**
 * @phpstan-require-implements OutputBroadcaster
 */
trait OutputBroadcasterTrait
{
    /**
     * @var array<int, DataReceiver>
     */
    protected array $outputReceivers = [];

    /**
     * Add receiver on output endpoint
     *
     * @return $this
     */
    public function addOutputReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);
        $this->outputReceivers[$id] = $receiver;

        return $this;
    }

    /**
     * Is receiver registered on input endpoint?
     */
    public function hasOutputReceiver(
        DataReceiver $receiver
    ): bool {
        $id = spl_object_id($receiver);
        return isset($this->outputReceivers[$id]);
    }

    /**
     * Remove receiver from output endpoint
     *
     * @return $this
     */
    public function removeOutputReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);
        unset($this->outputReceivers[$id]);
        return $this;
    }

    /**
     * Get list of output receivers
     *
     * @return array<int, DataReceiver>
     */
    public function getOutputReceivers(): array
    {
        return $this->outputReceivers;
    }

    /**
     * Get first output receiver
     */
    public function getFirstOutputReceiver(): ?DataReceiver
    {
        foreach ($this->outputReceivers as $receiver) {
            return $receiver;
        }

        return null;
    }
}
