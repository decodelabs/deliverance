<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Deliverance\Broker\Connector;
use DecodeLabs\Deliverance\Broker\ConnectorTrait;
use DecodeLabs\Deliverance\Channel\Buffer;

class Broker implements
    DataProvider,
    DataReceiver,
    Connector
{
    use ConnectorTrait;


    protected bool $inputEnabled = true;



    /**
     * Set input enabled
     *
     * @return $this
     */
    public function setInputEnabled(
        bool $enabled
    ): static {
        $this->inputEnabled = $enabled;
        return $this;
    }

    /**
     * Is input enabled
     */
    public function isInputEnabled(): bool
    {
        return $this->inputEnabled;
    }





    /**
     * Add channel to input and output endpoints
     *
     * @return $this
     */
    public function addIoChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);

        $this->inputCollectors[$id] = $channel;
        $this->outputReceivers[$id] = $channel;

        return $this;
    }

    /**
     * Add channel to all endpoints
     *
     * @return $this
     */
    public function addChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);

        $this->inputCollectors[$id] = $channel;
        $this->outputReceivers[$id] = $channel;
        $this->errorReceivers[$id] = $channel;

        return $this;
    }

    /**
     * Is channel in any endpoint
     */
    public function hasChannel(
        Channel $channel
    ): bool {
        $id = spl_object_id($channel);

        return
            isset($this->inputCollectors[$id]) ||
            isset($this->outputReceivers[$id]) ||
            isset($this->errorReceivers[$id]);
    }

    /**
     * Remove channel from all endpoints
     *
     * @return $this
     */
    public function removeChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);
        unset($this->inputCollectors[$id]);
        unset($this->outputReceivers[$id]);
        unset($this->errorReceivers[$id]);
        return $this;
    }




    /**
     * Get channel resource
     *
     * @return resource|object|null
     */
    public function getResource()
    {
        return null;
    }


    /**
     * Set all input channels as blocking
     *
     * @return $this
     */
    public function setReadBlocking(
        bool $flag
    ): static {
        foreach ($this->inputCollectors as $provider) {
            $provider->setReadBlocking($flag);
        }

        return $this;
    }

    /**
     * Any any input channels blocking?
     */
    public function isReadBlocking(): bool
    {
        foreach ($this->inputCollectors as $provider) {
            if ($provider->isReadBlocking()) {
                return true;
            }
        }

        return false;
    }


    /**
     * Are any input channels readable?
     */
    public function isReadable(): bool
    {
        if (!$this->inputEnabled) {
            return false;
        }

        foreach ($this->inputCollectors as $provider) {
            if ($provider->isReadable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Read $length from first readable input channel
     */
    public function read(
        int $length
    ): ?string {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputCollectors as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($line = $provider->read($length))) {
                return $line;
            }
        }

        return null;
    }

    /**
     * Read all from first readable input channel
     */
    public function readAll(): ?string
    {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputCollectors as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($line = $provider->readAll())) {
                return $line;
            }
        }

        return null;
    }

    /**
     * Read char from first readable input channel
     */
    public function readChar(): ?string
    {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputCollectors as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($char = $provider->readChar())) {
                return $char;
            }
        }

        return null;
    }

    /**
     * Read line from first readable input channel
     */
    public function readLine(): ?string
    {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputCollectors as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($line = $provider->readLine())) {
                return $line;
            }
        }

        return null;
    }

    /**
     * Read all available data from input channels and pass to external channel
     *
     * @return $this
     */
    public function readTo(
        DataReceiver $writer
    ): static {
        while (!$this->isAtEnd()) {
            $chunk = $this->read(8192);

            if ($chunk === null) {
                break;
            }

            $writer->write($chunk);
        }

        return $this;
    }


    /**
     * Are any output channels writable?
     */
    public function isWritable(): bool
    {
        foreach ($this->outputReceivers as $receiver) {
            if ($receiver->isWritable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Write data, limit of $length, to output channels
     */
    public function write(
        ?string $data,
        int $length = null
    ): int {
        if (
            $length === 0 ||
            $data === null
        ) {
            return 0;
        } elseif ($length === null) {
            $length = strlen($data);
        }

        foreach ($this->outputReceivers as $receiver) {
            if (!$receiver->isWritable()) {
                continue;
            }

            for ($written = 0, $result = 0; $written < $length; $written += $result) {
                $result = $receiver->write(substr($data, $written), $length - $written);
            }
        }

        return $length;
    }

    /**
     * Write line to error channels
     */
    public function writeLine(
        ?string $data = ''
    ): int {
        return $this->write($data . PHP_EOL);
    }

    /**
     * Write buffer to output channels
     */
    public function writeBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->write($buffer->read($length), $length);
    }


    /**
     * Are any error channels writable?
     */
    public function isErrorWritable(): bool
    {
        foreach ($this->errorReceivers as $receiver) {
            if ($receiver->isWritable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Write data, limit of $length, to error channels
     */
    public function writeError(
        ?string $data,
        int $length = null
    ): int {
        if ($length === 0 || $data === null) {
            return 0;
        } elseif ($length === null) {
            $length = strlen($data);
        }

        foreach ($this->errorReceivers as $receiver) {
            if (!$receiver->isWritable()) {
                continue;
            }

            for ($written = 0, $result = 0; $written < $length; $written += $result) {
                $result = $receiver->write(substr($data, $written), $length - $written);
            }
        }

        return $length;
    }

    /**
     * Write line to error channels
     */
    public function writeErrorLine(
        ?string $data = ''
    ): int {
        return $this->writeError($data . PHP_EOL);
    }

    /**
     * Write buffer to error channels
     */
    public function writeErrorBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->writeError($buffer->read($length), $length);
    }


    /**
     * Are all input channels at end?
     */
    public function isAtEnd(): bool
    {
        foreach ($this->inputCollectors as $provider) {
            if (!$provider->isAtEnd()) {
                return false;
            }
        }

        return true;
    }
}
