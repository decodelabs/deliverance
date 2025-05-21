<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataReceiver;

/**
 * @phpstan-require-implements DataBroadcaster
 */
trait DataBroadcasterTrait
{
    use OutputBroadcasterTrait;
    use ErrorBroadcasterTrait;

    /**
     * @return $this
     */
    public function addDataReceiver(
        DataReceiver $receiver,
        bool $input = true,
        bool $output = true,
    ): static {
        $id = spl_object_id($receiver);

        if($input) {
            $this->outputReceivers[$id] = $receiver;
        }

        if($output) {
            $this->errorReceivers[$id] = $receiver;
        }

        return $this;
    }

    public function hasDataReceiver(
        DataReceiver $receiver
    ): bool {
        $id = spl_object_id($receiver);

        return
            isset($this->outputReceivers[$id]) ||
            isset($this->errorReceivers[$id]);
    }

    /**
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
