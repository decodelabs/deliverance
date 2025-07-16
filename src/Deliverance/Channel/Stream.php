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

/**
 * @implements Channel<resource>
 */
class Stream implements Channel
{
    use DataProviderTrait;
    use DataReceiverTrait;

    /**
     * @var resource|null
     */
    public protected(set) mixed $ioResource = null;

    public bool $readBlocking {
        get {
            if ($this->ioResource === null) {
                return false;
            }

            $meta = stream_get_meta_data($this->ioResource);
            return (bool)$meta['blocked'];
        }
        set {
            if ($this->ioResource === null) {
                return;
            }

            stream_set_blocking($this->ioResource, $value);
        }
    }

    public protected(set) ?string $ioMode = null;
    protected ?bool $readable = null;
    protected ?bool $writable = null;

    /**
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
            $this->ioResource = $stream;
            $this->ioMode = stream_get_meta_data($this->ioResource)['mode'];
        } else {
            if (!$ioResource = fopen((string)$stream, (string)$mode)) {
                throw Exceptional::Io(
                    message: 'Unable to open stream'
                );
            }

            $this->ioResource = $ioResource;
            $this->ioMode = $mode;
        }
    }

    public function isReadable(): bool
    {
        if ($this->ioResource === null) {
            return false;
        }

        if ($this->readable === null) {
            if ($this->ioMode === null) {
                return false;
            }

            $this->readable = (
                strstr($this->ioMode, 'r') ||
                strstr($this->ioMode, '+')
            );
        }

        return $this->readable;
    }

    /**
     * @param int<1,max> $length
     */
    public function read(
        int $length
    ): ?string {
        $this->checkReadable();

        if ($this->ioResource === null) {
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
     * @param int<1,max> $length
     */
    protected function fread(
        int $length
    ): string|false {
        if ($this->ioResource === null) {
            return false;
        }

        return fread($this->ioResource, $length);
    }

    public function readChar(): ?string
    {
        $this->checkReadable();

        if ($this->ioResource === null) {
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
        if ($this->ioResource === null) {
            return false;
        }

        return fgetc($this->ioResource);
    }

    public function readLine(): ?string
    {
        $this->checkReadable();

        if ($this->ioResource === null) {
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
     * @param int<0,max>|null $length
     */
    protected function fgets(
        ?int $length = null
    ): string|false {
        if ($this->ioResource === null) {
            return false;
        }

        return fgets($this->ioResource, $length);
    }

    public function isWritable(): bool
    {
        if ($this->ioResource === null) {
            return false;
        }

        if ($this->writable === null) {
            if ($this->ioMode === null) {
                return false;
            }

            $this->writable = (
                strstr($this->ioMode, 'x') ||
                strstr($this->ioMode, 'w') ||
                strstr($this->ioMode, 'c') ||
                strstr($this->ioMode, 'a') ||
                strstr($this->ioMode, '+')
            );
        }

        return $this->writable;
    }

    /**
     * @param int<0,max>|null $length
     */
    public function write(
        ?string $data,
        ?int $length = null
    ): int {
        $this->checkWritable();

        if ($this->ioResource === null) {
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
     * @param int<0,max>|null $length
     */
    protected function fwrite(
        string $data,
        ?int $length = null
    ): int|false {
        if ($this->ioResource === null) {
            return false;
        }

        return fwrite($this->ioResource, $data, $length);
    }

    public function isAtEnd(): bool
    {
        if ($this->ioResource === null) {
            return true;
        }

        return $this->feof();
    }

    protected function feof(): bool
    {
        if ($this->ioResource === null) {
            return true;
        }

        return feof($this->ioResource);
    }

    public function close(): static
    {
        if ($this->ioResource !== null) {
            try {
                $this->fclose();
            } catch (Throwable $e) {
            }
        }

        $this->ioResource = null;
        $this->ioMode = null;
        $this->readable = null;
        $this->writable = null;

        return $this;
    }

    protected function fclose(): bool
    {
        if ($this->ioResource === null) {
            return false;
        }

        return fclose($this->ioResource);
    }
}
