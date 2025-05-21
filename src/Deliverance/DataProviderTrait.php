<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Exceptional;

/**
 * @phpstan-require-implements DataProvider
 */
trait DataProviderTrait
{
    public function isReadable(): bool
    {
        return true;
    }

    protected function checkReadable(): void
    {
        if (!$this->isReadable()) {
            throw Exceptional::Runtime(
                message: 'Reading has been shut down'
            );
        }
    }



    public function readAll(): ?string
    {
        $this->checkReadable();
        $data = null;

        while (!$this->isAtEnd()) {
            $chunk = $this->read(8192);

            if ($chunk === null) {
                break;
            }

            $data .= $chunk;
        }

        return $data;
    }

    public function readTo(
        DataReceiver $writer
    ): static {
        $this->checkReadable();

        while (!$this->isAtEnd()) {
            $chunk = $this->read(8192);

            if ($chunk === null) {
                break;
            }

            $writer->write($chunk);
        }

        return $this;
    }
}
