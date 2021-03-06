<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Deliverance\Channel\Buffer;

class Broker implements DataProvider, DataReceiver, ErrorDataReceiver
{
    /**
     * @var array<int, DataProvider>
     */
    protected $input = [];

    /**
     * @var array<int, DataReceiver>
     */
    protected $output = [];

    /**
     * @var array<int, DataReceiver>
     */
    protected $error = [];

    /**
     * Add provider on input endpoint
     *
     * @return $this
     */
    public function addInputProvider(DataProvider $provider): Broker
    {
        $id = spl_object_id($provider);
        $this->input[$id] = $provider;

        return $this;
    }

    /**
     * Is provider registered on input endpoint?
     */
    public function hasInputProvider(DataProvider $provider): bool
    {
        $id = spl_object_id($provider);
        return isset($this->input[$id]);
    }

    /**
     * Remove provider from input endpoint
     *
     * @return $this
     */
    public function removeInputProvider(DataProvider $provider): Broker
    {
        $id = spl_object_id($provider);
        unset($this->input[$id]);
        return $this;
    }

    /**
     * Get list of input providers
     *
     * @return array<int, DataProvider>
     */
    public function getInputProviders(): array
    {
        return $this->input;
    }

    /**
     * Get first input provider
     */
    public function getFirstInputProvider(): ?DataProvider
    {
        foreach ($this->input as $provider) {
            return $provider;
        }

        return null;
    }


    /**
     * Add receiver on output endpoint
     *
     * @return $this
     */
    public function addOutputReceiver(DataReceiver $receiver): Broker
    {
        $id = spl_object_id($receiver);
        $this->output[$id] = $receiver;

        return $this;
    }

    /**
     * Is receiver registered on input endpoint?
     */
    public function hasOutputReceiver(DataReceiver $receiver): bool
    {
        $id = spl_object_id($receiver);
        return isset($this->output[$id]);
    }

    /**
     * Remove receiver from output endpoint
     *
     * @return $this
     */
    public function removeOutputReceiver(DataReceiver $receiver): Broker
    {
        $id = spl_object_id($receiver);
        unset($this->output[$id]);
        return $this;
    }

    /**
     * Get list of output receivers
     *
     * @return array<int, DataReceiver>
     */
    public function getOutputReceivers(): array
    {
        return $this->output;
    }

    /**
     * Get first output receiver
     */
    public function getFirstOutputReceiver(): ?DataReceiver
    {
        foreach ($this->output as $receiver) {
            return $receiver;
        }

        return null;
    }


    /**
     * Add receiver on error endpoint
     *
     * @return $this
     */
    public function addErrorReceiver(DataReceiver $receiver): Broker
    {
        $id = spl_object_id($receiver);
        $this->error[$id] = $receiver;

        return $this;
    }

    /**
     * Is receiver registered at error endpoint?
     */
    public function hasErrorReceiver(DataReceiver $receiver): bool
    {
        $id = spl_object_id($receiver);
        return isset($this->error[$id]);
    }

    /**
     * Remove receiver from error endpoint
     *
     * @return $this
     */
    public function removeErrorReceiver(DataReceiver $receiver): Broker
    {
        $id = spl_object_id($receiver);
        unset($this->error[$id]);
        return $this;
    }

    /**
     * Get list of error receivers
     *
     * @return array<int, DataReceiver>
     */
    public function getErrorReceivers(): array
    {
        return $this->error;
    }

    /**
     * Get first error receiver
     */
    public function getFirstErrorReceiver(): ?DataReceiver
    {
        foreach ($this->error as $receiver) {
            return $receiver;
        }

        return null;
    }



    /**
     * Add channel to input and output endpoints
     *
     * @return $this
     */
    public function addIoChannel(Channel $channel): Broker
    {
        $id = spl_object_id($channel);

        $this->input[$id] = $channel;
        $this->output[$id] = $channel;

        return $this;
    }

    /**
     * Add channel to all endpoints
     *
     * @return $this
     */
    public function addChannel(Channel $channel): Broker
    {
        $id = spl_object_id($channel);

        $this->input[$id] = $channel;
        $this->output[$id] = $channel;
        $this->error[$id] = $channel;

        return $this;
    }

    /**
     * Is channel in any endpoint
     */
    public function hasChannel(Channel $channel): bool
    {
        $id = spl_object_id($channel);

        return
            isset($this->input[$id]) ||
            isset($this->output[$id]) ||
            isset($this->error[$id]);
    }

    /**
     * Remove channel from all endpoints
     *
     * @return $this
     */
    public function removeChannel(Channel $channel): Broker
    {
        $id = spl_object_id($channel);
        unset($this->input[$id]);
        unset($this->output[$id]);
        unset($this->error[$id]);
        return $this;
    }


    /**
     * Add data receiver for both output and error endpoints
     *
     * @return $this
     */
    public function addDataReceiver(DataReceiver $receiver): Broker
    {
        $id = spl_object_id($receiver);

        $this->output[$id] = $receiver;
        $this->error[$id] = $receiver;

        return $this;
    }

    /**
     * Is receiver in any endpoint
     */
    public function hasDataReceiver(DataReceiver $receiver): bool
    {
        $id = spl_object_id($receiver);

        return
            isset($this->output[$id]) ||
            isset($this->error[$id]);
    }

    /**
     * Remove data receiver from all endpoints
     *
     * @return $this
     */
    public function removeDataReceiver(DataReceiver $receiver): Broker
    {
        $id = spl_object_id($receiver);

        unset($this->output[$id]);
        unset($this->error[$id]);

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
    public function setReadBlocking(bool $flag): DataProvider
    {
        foreach ($this->input as $provider) {
            $provider->setReadBlocking($flag);
        }

        return $this;
    }

    /**
     * Any any input channels blocking?
     */
    public function isReadBlocking(): bool
    {
        foreach ($this->input as $provider) {
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
        foreach ($this->input as $provider) {
            if ($provider->isReadable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Read $length from first readable input channel
     */
    public function read(int $length): ?string
    {
        foreach ($this->input as $provider) {
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
        foreach ($this->input as $provider) {
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
        foreach ($this->input as $provider) {
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
        foreach ($this->input as $provider) {
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
    public function readTo(DataReceiver $writer): DataProvider
    {
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
        foreach ($this->output as $receiver) {
            if ($receiver->isWritable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Write data, limit of $length, to output channels
     */
    public function write(?string $data, int $length = null): int
    {
        if ($length === 0 || $data === null) {
            return 0;
        } elseif ($length === null) {
            $length = strlen($data);
        }

        foreach ($this->output as $receiver) {
            if (!$receiver->isWritable()) {
                continue;
            }

            for ($written = 0; $written < $length; $written += $result) {
                $result = $receiver->write(substr($data, $written), $length - $written);
            }
        }

        return $length;
    }

    /**
     * Write line to error channels
     */
    public function writeLine(?string $data = ''): int
    {
        return $this->write($data . PHP_EOL);
    }

    /**
     * Write buffer to output channels
     */
    public function writeBuffer(Buffer $buffer, int $length): int
    {
        return $this->write($buffer->read($length), $length);
    }


    /**
     * Are any error channels writable?
     */
    public function isErrorWritable(): bool
    {
        foreach ($this->error as $receiver) {
            if ($receiver->isWritable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Write data, limit of $length, to error channels
     */
    public function writeError(?string $data, int $length = null): int
    {
        if ($length === 0 || $data === null) {
            return 0;
        } elseif ($length === null) {
            $length = strlen($data);
        }

        foreach ($this->error as $receiver) {
            if (!$receiver->isWritable()) {
                continue;
            }

            for ($written = 0; $written < $length; $written += $result) {
                $result = $receiver->write(substr($data, $written), $length - $written);
            }
        }

        return $length;
    }

    /**
     * Write line to error channels
     */
    public function writeErrorLine(?string $data = ''): int
    {
        return $this->writeError($data . PHP_EOL);
    }

    /**
     * Write buffer to error channels
     */
    public function writeErrorBuffer(Buffer $buffer, int $length): int
    {
        return $this->writeError($buffer->read($length), $length);
    }


    /**
     * Are all input channels at end?
     */
    public function isAtEnd(): bool
    {
        foreach ($this->input as $provider) {
            if (!$provider->isAtEnd()) {
                return false;
            }
        }

        return true;
    }
}
