<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Channel;

use DecodeLabs\Deliverance\Channel;
use DecodeLabs\Deliverance\DataProvider;
use DecodeLabs\Deliverance\DataProviderTrait;
use DecodeLabs\Deliverance\DataReceiver;
use DecodeLabs\Deliverance\DataReceiverTrait;

class Buffer implements Channel
{
    use DataProviderTrait;
    use DataReceiverTrait;

    /**
     * @var string
     */
    protected $buffer;

    /**
     * @var bool
     */
    protected $open = true;

    /**
     * @var bool
     */
    protected $readable = true;

    /**
     * @var bool
     */
    protected $writable = true;

    /**
     * Init with stream path
     */
    public function __construct(string $buffer = null)
    {
        $this->buffer = (string)$buffer;
    }


    /**
     * Get resource
     */
    public function getResource()
    {
        return $this;
    }

    /**
     * Set read blocking mode
     */
    public function setReadBlocking(bool $flag): DataProvider
    {
        return $this;
    }

    /**
     * Is this channel in blocking mode?
     */
    public function isReadBlocking(): bool
    {
        return false;
    }

    /**
     * Set as readable
     */
    public function setReadable(bool $flag): Channel
    {
        $this->readable = $flag;
        return $this;
    }

    /**
     * Is the resource still accessible?
     */
    public function isReadable(): bool
    {
        return $this->open && $this->readable;
    }

    /**
     * Read up to $length bytes from resource
     */
    public function read(int $length): ?string
    {
        $this->checkReadable();

        $output = substr($this->buffer, 0, $length);
        $this->buffer = substr($this->buffer, $length);

        if (!strlen($output)) {
            $output = null;
        }

        return $output;
    }

    /**
     * Read single char from resource
     */
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

    /**
     * Read single line from resource
     */
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


    /**
     * Set as writable
     */
    public function setWritable(bool $flag): DataReceiver
    {
        $this->writable = $flag;
        return $this;
    }

    /**
     * Is the resource still writable?
     */
    public function isWritable(): bool
    {
        return $this->open && $this->writable;
    }

    /**
     * Write ?$length bytes to resource
     */
    public function write(?string $data, int $length = null): int
    {
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

    /**
     * Has this stream ended?
     */
    public function isAtEnd(): bool
    {
        return !strlen($this->buffer);
    }

    /**
     * Close the stream
     */
    public function close(): Channel
    {
        $this->open = false;
        return $this;
    }

    /**
     * Convert to string
     */
    public function __toString(): string
    {
        return $this->buffer;
    }
}
