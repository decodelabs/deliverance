<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Deliverance\Channel\Buffer;
use DecodeLabs\Tightrope\Writable;

interface DataReceiver extends Writable
{
    public function write(
        ?string $data,
        int $length = null
    ): int;

    public function writeLine(?string $data = ''): int;

    public function writeBuffer(
        Buffer $buffer,
        int $length
    ): int;
}
