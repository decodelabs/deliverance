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
    public function isWritable(): bool
    {
        return true;
    }

    protected function checkWritable(): void
    {
        if (!$this->isWritable()) {
            throw Exceptional::Runtime(
                message: 'Writing has been shut down'
            );
        }
    }


    public function writeLine(
        ?string $data = ''
    ): int {
        return $this->write($data . PHP_EOL);
    }

    /**
     * @param int<0,max> $length
     */
    public function writeBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->write($buffer->read($length), $length);
    }
}
