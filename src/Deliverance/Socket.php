<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

/**
 * !IMPORTANT! This interface is not complete and exists for temporary compatibility
 */
interface Socket extends Channel
{
    public string $id { get; }

    public function isStreamBased(): bool;
}
