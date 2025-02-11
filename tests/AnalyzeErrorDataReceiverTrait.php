<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance\Tests;

use DecodeLabs\Deliverance\ErrorDataReceiver;
use DecodeLabs\Deliverance\ErrorDataReceiverTrait;

class AnalyzeErrorDataReceiverTrait implements ErrorDataReceiver
{
    use ErrorDataReceiverTrait;

    public function writeError(
        ?string $data,
        ?int $length = null
    ): int {
        return 0;
    }
}
