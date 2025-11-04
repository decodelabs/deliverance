<?php

/**
 * Deliverance
 * @license https://opensource.org/licenses/MIT
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
