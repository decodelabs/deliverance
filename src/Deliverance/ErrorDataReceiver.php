<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Deliverance\Channel\Buffer;

interface ErrorDataReceiver
{
    public function isErrorWritable(): bool;

    public function writeError(
        ?string $data,
        int $length = null
    ): int;

    public function writeErrorLine(
        ?string $data = ''
    ): int;

    public function writeErrorBuffer(
        Buffer $buffer,
        int $length
    ): int;
}
