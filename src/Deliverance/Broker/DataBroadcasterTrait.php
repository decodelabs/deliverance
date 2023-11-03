<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

trait DataBroadcasterTrait
{
    use OutputBroadcasterTrait;
    use ErrorBroadcasterTrait;

    /**
     * Add data receiver for both output and error endpoints
     *
     * @return $this
     */
    public function addDataReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);

        $this->outputReceivers[$id] = $receiver;
        $this->errorReceivers[$id] = $receiver;

        return $this;
    }

    /**
     * Is receiver in any endpoint
     */
    public function hasDataReceiver(
        DataReceiver $receiver
    ): bool {
        $id = spl_object_id($receiver);

        return
            isset($this->outputReceivers[$id]) ||
            isset($this->errorReceivers[$id]);
    }

    /**
     * Remove data receiver from all endpoints
     *
     * @return $this
     */
    public function removeDataReceiver(
        DataReceiver $receiver
    ): static {
        $id = spl_object_id($receiver);

        unset($this->outputReceivers[$id]);
        unset($this->errorReceivers[$id]);

        return $this;
    }
}
