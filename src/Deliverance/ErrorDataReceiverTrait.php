<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Deliverance\Channel\Buffer;
use DecodeLabs\Exceptional;

/**
 * @phpstan-require-implements ErrorDataReceiver
 */
trait ErrorDataReceiverTrait
{
    public function isErrorWritable(): bool
    {
        return true;
    }

    protected function checkErrorWritable(): void
    {
        if (!$this->isErrorWritable()) {
            throw Exceptional::Runtime(
                message: 'Error writing has been shut down'
            );
        }
    }


    public function writeErrorLine(
        ?string $data = ''
    ): int {
        return $this->writeError($data . PHP_EOL);
    }

    public function writeErrorBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->writeError($buffer->read($length), $length);
    }
}
