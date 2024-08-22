<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\Channel;

/**
 * @phpstan-require-implements ChannelConnector
 */
trait ChannelConnectorTrait
{
    use InputCollectorTrait;
    use OutputBroadcasterTrait;
    use ErrorBroadcasterTrait;

    /**
     * Add channel to input and output endpoints
     *
     * @return $this
     */
    public function addIoChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);

        $this->inputCollectors[$id] = $channel;
        $this->outputReceivers[$id] = $channel;

        return $this;
    }

    /**
     * Add channel to all endpoints
     *
     * @return $this
     */
    public function addChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);

        $this->inputCollectors[$id] = $channel;
        $this->outputReceivers[$id] = $channel;
        $this->errorReceivers[$id] = $channel;

        return $this;
    }

    /**
     * Is channel in any endpoint
     */
    public function hasChannel(
        Channel $channel
    ): bool {
        $id = spl_object_id($channel);

        return
            isset($this->inputCollectors[$id]) ||
            isset($this->outputReceivers[$id]) ||
            isset($this->errorReceivers[$id]);
    }

    /**
     * Remove channel from all endpoints
     *
     * @return $this
     */
    public function removeChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);
        unset($this->inputCollectors[$id]);
        unset($this->outputReceivers[$id]);
        unset($this->errorReceivers[$id]);
        return $this;
    }
}
