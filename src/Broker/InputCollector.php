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
     * Add provider on input endpoint
     *
     * @return $this
     */
    public function addInputProvider(DataProvider $provider): static;

    /**
     * Is provider registered on input endpoint?
     */
    public function hasInputProvider(DataProvider $provider): bool;

    /**
     * Remove provider from input endpoint
     *
     * @return $this
     */
    public function removeInputProvider(DataProvider $provider): static;

    /**
     * Get list of input providers
     *
     * @return array<int, DataProvider>
     */
    public function getInputProviders(): array;

    /**
     * Get first input provider
     */
    public function getFirstInputProvider(): ?DataProvider;
}
