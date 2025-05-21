<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

interface Channel extends
    DataProvider,
    DataReceiver
{
    /**
     * @var resource|object|null
     */
    public mixed $ioResource { get; }

    /**
     * @return $this
     */
    public function close(): static;
}
