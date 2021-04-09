<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

interface Channel extends DataProvider, DataReceiver
{
    /**
     * @return resource|object|null
     */
    public function getResource();

    /**
     * @return $this
     */
    public function close(): Channel;
}
