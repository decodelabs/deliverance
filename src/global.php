<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

/**
 * global helpers
 */
namespace DecodeLabs\Deliverance
{
    use DecodeLabs\Deliverance;
    use DecodeLabs\Veneer;

    // Register the Veneer facade
    Veneer::register(Context::class, Deliverance::class);
}
