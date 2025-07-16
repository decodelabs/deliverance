<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Broker;

use DecodeLabs\Deliverance\DataProvider;

/**
 * @phpstan-require-implements InputCollector
 */
trait InputCollectorTrait
{
    /**
    * @var array<int,DataProvider>
    */
    public protected(set) array $inputProviders = [];

    /**
     * @return $this
     */
    public function addInputProvider(
        DataProvider $provider
    ): static {
        $id = spl_object_id($provider);
        $this->inputProviders[$id] = $provider;

        return $this;
    }

    public function hasInputProvider(
        DataProvider $provider
    ): bool {
        $id = spl_object_id($provider);
        return isset($this->inputProviders[$id]);
    }

    /**
     * @return $this
     */
    public function removeInputProvider(
        DataProvider $provider
    ): static {
        $id = spl_object_id($provider);
        unset($this->inputProviders[$id]);
        return $this;
    }

    public function getFirstInputProvider(): ?DataProvider
    {
        foreach ($this->inputProviders as $provider) {
            return $provider;
        }

        return null;
    }
}
