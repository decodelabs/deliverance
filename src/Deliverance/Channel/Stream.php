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
use DecodeLabs\Exceptional;
use Throwable;

class Stream implements Channel
{
    use DataProviderTrait;
    use DataReceiverTrait;

    /**
     * @var resource|null
     */
    protected $resource;

    protected ?string $mode = null;
    protected ?bool $readable = null;
    protected ?bool $writable = null;

    /**
     * Init with stream path
     *
     * @param string|resource $stream
     */
    public function __construct(
        $stream,
        ?string $mode = 'a+'
    ) {
        if (empty($stream)) {
            return;
        }

        $isResource = is_resource($stream);

        if (
            $mode === null &&
            !$isResource
        ) {
            return;
        }

        if ($isResource) {
            $this->resource = $stream;
            $this->mode = stream_get_meta_data($this->resource)['mode'];
        } else {
            if (!$resource = fopen((string)$stream, (string)$mode)) {
                throw Exceptional::Io(
                    message: 'Unable to open stream'
                );
            }

            $this->resource = $resource;
            $this->mode = $mode;
        }
    }


    /**
     * Get resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Get mode stream was opened with
     */
    public function getIoMode(): ?string
    {
        return $this->mode;
    }

    /**
     * Set read blocking mode
     */
    public function setReadBlocking(
        bool $flag
    ): static {
        if ($this->resource === null) {
            throw Exceptional::Logic(
                message: 'Cannot set blocking, resource not open'
            );
        }

        stream_set_blocking($this->resource, $flag);
        return $this;
    }

    /**
     * Is this channel in blocking mode?
     */
    public function isReadBlocking(): bool
    {
        if ($this->resource === null) {
            return false;
        }

        $meta = stream_get_meta_data($this->resource);
        return (bool)$meta['blocked'];
    }

    /**
     * Is the resource still accessible?
     */
    public function isReadable(): bool
    {
        if ($this->resource === null) {
            return false;
        }

        if ($this->readable === null) {
            if ($this->mode === null) {
                return false;
            }

            $this->readable = (
                strstr($this->mode, 'r') ||
                strstr($this->mode, '+')
            );
        }

        return $this->readable;
    }

    /**
     * Read up to $length bytes from resource
     *
     * @param int<1, max> $length
     */
    public function read(
        int $length
    ): ?string {
        $this->checkReadable();

        if ($this->resource === null) {
            return null;
        }

        try {
            $output = $this->fread($length);
        } catch (Throwable $e) {
            return null;
        }

        if (
            $output === '' ||
            $output === false
        ) {
            $output = null;
        }

        return $output;
    }

    /**
     * @param int<1, max> $length
     */
    protected function fread(
        int $length
    ): string|false {
        if ($this->resource === null) {
            return false;
        }

        return fread($this->resource, $length);
    }

    /**
     * Read single cgar from resource
     */
    public function readChar(): ?string
    {
        $this->checkReadable();

        if ($this->resource === null) {
            return null;
        }

        try {
            $output = $this->fgetc();
        } catch (Throwable $e) {
            return null;
        }

        if (
            $output === '' ||
            $output === false
        ) {
            $output = null;
        }

        return $output;
    }

    protected function fgetc(): string|false
    {
        if ($this->resource === null) {
            return false;
        }

        return fgetc($this->resource);
    }

    /**
     * Read single line from resource
     */
    public function readLine(): ?string
    {
        $this->checkReadable();

        if ($this->resource === null) {
            return null;
        }

        try {
            $output = $this->fgets();
        } catch (Throwable $e) {
            return null;
        }

        if (
            $output === '' ||
            $output === false
        ) {
            $output = null;
        } else {
            $output = rtrim($output, "\r\n");
        }

        return $output;
    }

    /**
     * @param int<0, max>|null $length
     */
    protected function fgets(
        ?int $length = null
    ): string|false {
        if ($this->resource === null) {
            return false;
        }

        return fgets($this->resource, $length);
    }

    /**
     * Is the resource still writable?
     */
    public function isWritable(): bool
    {
        if ($this->resource === null) {
            return false;
        }

        if ($this->writable === null) {
            if ($this->mode === null) {
                return false;
            }

            $this->writable = (
                strstr($this->mode, 'x') ||
                strstr($this->mode, 'w') ||
                strstr($this->mode, 'c') ||
                strstr($this->mode, 'a') ||
                strstr($this->mode, '+')
            );
        }

        return $this->writable;
    }

    /**
     * Write ?$length bytes to resource
     *
     * @param int<0, max>|null $length
     */
    public function write(
        ?string $data,
        ?int $length = null
    ): int {
        $this->checkWritable();

        if ($this->resource === null) {
            return 0;
        }

        $output = $this->fwrite((string)$data, $length);

        if ($output === false) {
            throw Exceptional::Io(
                message: 'Unable to write to stream',
                data: $this
            );
        }

        return $output;
    }

    /**
     * @param int<0, max>|null $length
     */
    protected function fwrite(
        string $data,
        ?int $length = null
    ): int|false {
        if ($this->resource === null) {
            return false;
        }

        return fwrite($this->resource, $data, $length);
    }

    /**
     * Has this stream ended?
     */
    public function isAtEnd(): bool
    {
        if ($this->resource === null) {
            return true;
        }

        return $this->feof();
    }

    protected function feof(): bool
    {
        if ($this->resource === null) {
            return true;
        }

        return feof($this->resource);
    }

    /**
     * Close the stream
     */
    public function close(): static
    {
        if ($this->resource !== null) {
            try {
                $this->fclose();
            } catch (Throwable $e) {
            }
        }

        $this->resource = null;
        $this->mode = null;
        $this->readable = null;
        $this->writable = null;

        return $this;
    }

    protected function fclose(): bool
    {
        if ($this->resource === null) {
            return false;
        }

        return fclose($this->resource);
    }
}
