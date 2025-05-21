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

    protected(set) mixed $ioResource = null;
    public bool $inputEnabled = true;

    public bool $readBlocking {
        get {
            foreach ($this->inputProviders as $provider) {
                if ($provider->readBlocking) {
                    return true;
                }
            }

            return false;
        }
        set {
            foreach ($this->inputProviders as $provider) {
                $provider->readBlocking = $value;
            }
        }
    }

    /**
     * @return $this
     */
    public function addChannel(
        Channel $channel,
        bool $input = true,
        bool $output = true,
        bool $error = true
    ): static {
        $id = spl_object_id($channel);

        if ($input) {
            $this->inputProviders[$id] = $channel;
        }

        if ($output) {
            $this->outputReceivers[$id] = $channel;
        }

        if ($error) {
            $this->errorReceivers[$id] = $channel;
        }

        return $this;
    }

    public function hasChannel(
        Channel $channel
    ): bool {
        $id = spl_object_id($channel);

        return
            isset($this->inputProviders[$id]) ||
            isset($this->outputReceivers[$id]) ||
            isset($this->errorReceivers[$id]);
    }

    /**
     * @return $this
     */
    public function removeChannel(
        Channel $channel
    ): static {
        $id = spl_object_id($channel);
        unset($this->inputProviders[$id]);
        unset($this->outputReceivers[$id]);
        unset($this->errorReceivers[$id]);
        return $this;
    }



    public function isReadable(): bool
    {
        if (!$this->inputEnabled) {
            return false;
        }

        foreach ($this->inputProviders as $provider) {
            if ($provider->isReadable()) {
                return true;
            }
        }

        return false;
    }

    public function read(
        int $length
    ): ?string {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputProviders as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($line = $provider->read($length))) {
                return $line;
            }
        }

        return null;
    }

    public function readAll(): ?string
    {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputProviders as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($line = $provider->readAll())) {
                return $line;
            }
        }

        return null;
    }

    public function readChar(): ?string
    {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputProviders as $provider) {
            if (!$provider->isReadable()) {
                continue;
            }

            if (null !== ($char = $provider->readChar())) {
                return $char;
            }
        }

        return null;
    }

    public function readLine(): ?string
    {
        if (!$this->inputEnabled) {
            return null;
        }

        foreach ($this->inputProviders as $provider) {
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


    public function isWritable(): bool
    {
        foreach ($this->outputReceivers as $receiver) {
            if ($receiver->isWritable()) {
                return true;
            }
        }

        return false;
    }

    public function write(
        ?string $data,
        ?int $length = null
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

    public function writeLine(
        ?string $data = ''
    ): int {
        return $this->write($data . PHP_EOL);
    }

    public function writeBuffer(
        Buffer $buffer,
        int $length
    ): int {
        return $this->write($buffer->read($length), $length);
    }


    public function isErrorWritable(): bool
    {
        foreach ($this->errorReceivers as $receiver) {
            if ($receiver->isWritable()) {
                return true;
            }
        }

        return false;
    }

    public function writeError(
        ?string $data,
        ?int $length = null
    ): int {
        if (
            $length === 0 ||
            $data === null
        ) {
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


    public function isAtEnd(): bool
    {
        foreach ($this->inputProviders as $provider) {
            if (!$provider->isAtEnd()) {
                return false;
            }
        }

        return true;
    }
}
