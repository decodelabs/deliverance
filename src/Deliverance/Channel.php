<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

/**
 * @template T of resource|object|null = resource|object|null
 */
interface Channel extends
    DataProvider,
    DataReceiver
{
    /**
     * @var ?T
     */
    public mixed $ioResource { get; }

    /**
     * @return $this
     */
    public function close(): static;
}
