<?php

/**
 * Deliverance
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

/**
 * @phpstan-require-implements Connector
 */
trait ConnectorTrait
{
    use InputCollectorTrait;
    use OutputBroadcasterTrait;
    use ErrorBroadcasterTrait;
    use ChannelConnectorTrait;
    use DataBroadcasterTrait;
}
