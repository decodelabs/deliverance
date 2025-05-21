<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Channel;

use DecodeLabs\Deliverance\Channel;
use DecodeLabs\Deliverance\DataProviderTrait;
use DecodeLabs\Deliverance\DataReceiverTrait;
use DecodeLabs\Deliverance\NoReadBlockingTrait;

/**
 * @implements Channel<null>
 */
class Buffer implements Channel
{
    use DataProviderTrait;
    use DataReceiverTrait;
    use NoReadBlockingTrait;

    public mixed $ioResource {
        get => null;
    }

    protected string $buffer = '';
    protected bool $open = true;

    public bool $readable = true {
        get => $this->open && $this->readable;
        set => $this->open && $value;
    }

    public bool $writable = true {
        get => $this->open && $this->writable;
        set => $this->open && $value;
    }

    public function __construct(
        ?string $buffer = null
    ) {
        $this->buffer = (string)$buffer;
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function read(
        int $length
    ): ?string {
        $this->checkReadable();

        $output = substr($this->buffer, 0, $length);
        $this->buffer = substr($this->buffer, $length);

        if (!strlen($output)) {
            $output = null;
        }

        return $output;
    }

    public function readChar(): ?string
    {
        $this->checkReadable();

        if (!strlen($this->buffer)) {
            return null;
        }

        $output = substr($this->buffer, 0, 1);
        $this->buffer = substr($this->buffer, 1);
        return $output;
    }

    public function readLine(): ?string
    {
        $this->checkReadable();

        $output = '';
        $length = strlen($this->buffer);
        $pos = 0;

        while ($pos < $length) {
            if ($this->buffer[$pos] == "\n") {
                $pos++;
                break;
            }

            $output .= $this->buffer[$pos];
            $pos++;
        }

        $this->buffer = substr($this->buffer, $pos);
        return $output;
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

        $this->buffer .= $data;
        return strlen($data);
    }

    public function isAtEnd(): bool
    {
        return !strlen($this->buffer);
    }

    public function close(): static
    {
        $this->open = false;
        return $this;
    }

    public function __toString(): string
    {
        return $this->buffer;
    }
}
