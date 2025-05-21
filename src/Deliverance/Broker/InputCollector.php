<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataProvider;

interface InputCollector
{
    /**
     * @var array<int,DataProvider>
     */
    public array $inputProviders { get; }

    /**
     * @return $this
     */
    public function addInputProvider(
        DataProvider $provider
    ): static;

    public function hasInputProvider(
        DataProvider $provider
    ): bool;

    /**
     * @return $this
     */
    public function removeInputProvider(
        DataProvider $provider
    ): static;

    public function getFirstInputProvider(): ?DataProvider;
}
