<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs;

use DecodeLabs\Deliverance\Broker;
use DecodeLabs\Deliverance\Channel;
use DecodeLabs\Deliverance\Channel\Buffer;
use DecodeLabs\Deliverance\Channel\Stream;

class Deliverance
{
    /**
     * @param Channel|string|resource $stream
     */
    public static function openStream(
        mixed $stream,
        string $mode = 'a+'
    ): Channel {
        if ($stream instanceof Channel) {
            return $stream;
        }

        return new Stream($stream, $mode);
    }

    public static function openCliInputStream(): Stream
    {
        if (!defined('STDIN')) {
            throw Exceptional::Runtime(
                message: 'STDIN is only available on the CLI SAPI'
            );
        }

        return new Stream(\STDIN, 'r');
    }

    public static function openCliOutputStream(): Stream
    {
        if (!defined('STDOUT')) {
            throw Exceptional::Runtime(
                message: 'STDOUT is only available on the CLI SAPI'
            );
        }

        return new Stream(\STDOUT, 'w');
    }

    public static function openCliErrorStream(): Stream
    {
        if (!defined('STDERR')) {
            throw Exceptional::Runtime(
                message: 'STDERR is only available on the CLI SAPI'
            );
        }

        return new Stream(\STDERR, 'w');
    }


    public static function openHttpInputStream(): Stream
    {
        return new Stream('php://input', 'r');
    }

    public static function openHttpOutputStream(): Stream
    {
        return new Stream('php://output', 'w');
    }



    public static function newBuffer(
        ?string $buffer = null
    ): Buffer {
        return new Buffer($buffer);
    }


    public static function newBroker(): Broker
    {
        return new Broker();
    }

    public static function newCliBroker(): Broker
    {
        return static::newBroker()
            ->addInputProvider(static::openCliInputStream())
            ->addOutputReceiver(static::openCliOutputStream())
            ->addErrorReceiver(static::openCliErrorStream());
    }

    public static function newHttpBroker(): Broker
    {
        return static::newBroker()
            ->addInputProvider(static::openHttpInputStream())
            ->addOutputReceiver(static::openHttpOutputStream());
    }
}
