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
     * @return $this
     */
    public function addChannel(
        Channel $channel,
        bool $input = true,
        bool $output = true,
        bool $error = true
    ): static {
        $id = spl_object_id($channel);

        if ($input) {
            $this->inputProviders[$id] = $channel;
        }

        if ($output) {
            $this->outputReceivers[$id] = $channel;
        }

        if ($error) {
            $this->errorReceivers[$id] = $channel;
        }

        return $this;
    }

    public function hasChannel(
        Channel $channel
    ): bool {
        $id = spl_object_id($channel);

        return
            isset($this->inputProviders[$id]) ||
            isset($this->outputReceivers[$id]) ||
            isset($this->errorReceivers[$id]);
    }

    /**
     * @return $this
     */
    public function removeChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);
        unset($this->inputProviders[$id]);
        unset($this->outputReceivers[$id]);
        unset($this->errorReceivers[$id]);
        return $this;
    }
}
