<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

interface DataSender
{
    /**
     * @return $this
     */
    public function setDataReceiver(DataReceiver $receiver): DataSender;
    public function getDataReceiver(): ?DataReceiver;
    public function sendData(): void;
}
