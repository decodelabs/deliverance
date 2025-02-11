<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Tightrope\ReadableGet;

interface DataProvider extends ReadableGet
{
    /**
     * @return $this
     */
    public function setReadBlocking(
        bool $flag
    ): static;

    public function isReadBlocking(): bool;

    public function read(
        int $length
    ): ?string;

    public function readAll(): ?string;
    public function readChar(): ?string;
    public function readLine(): ?string;

    /**
     * @return $this
     */
    public function readTo(
        DataReceiver $writer
    ): static;

    public function isAtEnd(): bool;
}
