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
    /**
     * Is the resource still writable?
     */
    public function isErrorWritable(): bool
    {
        return true;
    }

    /**
     * Check the resource is readable and throw exception if not
     */
    protected function checkErrorWritable(): void
    {
        if (!$this->isErrorWritable()) {
            throw Exceptional::Runtime(
                message: 'Error writing has been shut down'
            );
        }
    }


    /**
     * Write a single line of data
     */
    public function writeErrorLine(
        ?string $data = ''
    ): int {
        return $this->writeError($data . PHP_EOL);
    }

    /**
     * Pluck and write $length bytes from buffer
     */
    public function writeErrorBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->writeError($buffer->read($length), $length);
    }
}
