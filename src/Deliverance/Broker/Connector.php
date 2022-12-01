<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

interface Connector extends
    InputCollector,
    OutputBroadcaster,
    ErrorBroadcaster,
    ChannelConnector,
    DataBroadcaster
{
}
