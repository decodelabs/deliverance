<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

interface DataProvider
{
    public bool $readBlocking { get; set; }

    public function isReadable(): bool;

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
