<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\DataReceiver;

use Closure;
use DecodeLabs\Deliverance\DataReceiver;
use DecodeLabs\Deliverance\DataReceiverTrait;

class Proxy implements DataReceiver
{
    use DataReceiverTrait;

    protected object $receiver;
    protected Closure $writer;

    public bool $writable = true;

    public function __construct(
        object $receiver,
        callable $writer
    ) {
        $this->receiver = $receiver;
        $this->writer = Closure::fromCallable($writer);
    }


    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function write(
        ?string $data,
        ?int $length = null
    ): int {
        $this->checkWritable();

        if ($data === null) {
            return 0;
        }

        if ($length !== null) {
            $data = substr($data, 0, $length);
        }

        ($this->writer)($this->receiver, $data);
        return strlen($data);
    }
}
