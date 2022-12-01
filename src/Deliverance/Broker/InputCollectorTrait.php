<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataProvider;

trait InputCollectorTrait
{
    /**
    * @var array<int, DataProvider>
    */
    protected array $inputCollectors = [];

    /**
     * Add provider on input endpoint
     *
     * @return $this
     */
    public function addInputProvider(DataProvider $provider): static
    {
        $id = spl_object_id($provider);
        $this->inputCollectors[$id] = $provider;

        return $this;
    }

    /**
     * Is provider registered on input endpoint?
     */
    public function hasInputProvider(DataProvider $provider): bool
    {
        $id = spl_object_id($provider);
        return isset($this->inputCollectors[$id]);
    }

    /**
     * Remove provider from input endpoint
     *
     * @return $this
     */
    public function removeInputProvider(DataProvider $provider): static
    {
        $id = spl_object_id($provider);
        unset($this->inputCollectors[$id]);
        return $this;
    }

    /**
     * Get list of input providers
     *
     * @return array<int, DataProvider>
     */
    public function getInputProviders(): array
    {
        return $this->inputCollectors;
    }

    /**
     * Get first input provider
     */
    public function getFirstInputProvider(): ?DataProvider
    {
        foreach ($this->inputCollectors as $provider) {
            return $provider;
        }

        return null;
    }
}
