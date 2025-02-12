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
 * @phpstan-require-implements DataReceiver
 */
trait DataReceiverTrait
{
    /**
     * Is the resource still writable?
     */
    public function isWritable(): bool
    {
        return true;
    }

    /**
     * Check the resource is readable and throw exception if not
     */
    protected function checkWritable(): void
    {
        if (!$this->isWritable()) {
            throw Exceptional::Runtime(
                message: 'Writing has been shut down'
            );
        }
    }


    /**
     * Write a single line of data
     */
    public function writeLine(
        ?string $data = ''
    ): int {
        return $this->write($data . PHP_EOL);
    }

    /**
     * Pluck and write $length bytes from buffer
     *
     * @param int<0, max> $length
     */
    public function writeBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->write($buffer->read($length), $length);
    }
}
