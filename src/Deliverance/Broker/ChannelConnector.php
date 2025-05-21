<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\Channel;

interface ChannelConnector extends
    InputCollector,
    OutputBroadcaster,
    ErrorBroadcaster
{
    /**
     * @return $this
     */
    public function addChannel(
        Channel $channel,
        bool $input = true,
        bool $output = true,
        bool $error = true
    ): static;

    public function hasChannel(
        Channel $channel
    ): bool;

    /**
     * @return $this
     */
    public function removeChannel(
        Channel $channel
    ): static;
}
