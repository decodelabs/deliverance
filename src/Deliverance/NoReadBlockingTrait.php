<?php

/**
 * Deliverance
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

trait NoReadBlockingTrait
{
    public bool $readBlocking {
        get => true;
        set {
            // No-op
        }
    }
}
