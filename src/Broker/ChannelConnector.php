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
     * Add channel to input and output endpoints
     *
     * @return $this
     */
    public function addIoChannel(Channel $channel): static;

    /**
     * Add channel to all endpoints
     *
     * @return $this
     */
    public function addChannel(Channel $channel): static;

    /**
     * Is channel in any endpoint
     */
    public function hasChannel(Channel $channel): bool;

    /**
     * Remove channel from all endpoints
     *
     * @return $this
     */
    public function removeChannel(Channel $channel): static;
}
