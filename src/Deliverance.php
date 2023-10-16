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
     * Open a stream Channel
     *
     * @param Channel|string|resource $stream
     */
    public static function openStream(
        $stream,
        string $mode = 'a+'
    ): Channel {
        if ($stream instanceof Channel) {
            return $stream;
        }

        return new Stream($stream, $mode);
    }

    /**
     * Open a STDIN Channel
     */
    public static function openCliInputStream(): Stream
    {
        if (!defined('STDIN')) {
            throw Exceptional::Runtime(
                'STDIN is only available on the CLI SAPI'
            );
        }

        return new Stream(\STDIN, 'r');
    }

    /**
     * Open a STDOUT Channel
     */
    public static function openCliOutputStream(): Stream
    {
        if (!defined('STDOUT')) {
            throw Exceptional::Runtime(
                'STDOUT is only available on the CLI SAPI'
            );
        }

        return new Stream(\STDOUT, 'w');
    }

    /**
     * Open a STDERR Channel
     */
    public static function openCliErrorStream(): Stream
    {
        if (!defined('STDERR')) {
            throw Exceptional::Runtime(
                'STDERR is only available on the CLI SAPI'
            );
        }

        return new Stream(\STDERR, 'w');
    }


    /**
     * Open HTTP input Channel
     */
    public static function openHttpInputStream(): Stream
    {
        return new Stream('php://input', 'r');
    }

    /**
     * Open HTTP output Channel
     */
    public static function openHttpOutputStream(): Stream
    {
        return new Stream('php://output', 'w');
    }



    /**
     * Create a new buffer Channel
     */
    public static function newBuffer(
        ?string $buffer = null
    ): Buffer {
        return new Buffer($buffer);
    }


    /**
     * New IO Broker
     */
    public static function newBroker(): Broker
    {
        return new Broker();
    }

    /**
     * Create STD IO Broker
     */
    public static function newCliBroker(): Broker
    {
        return static::newBroker()
            ->addInputProvider(static::openCliInputStream())
            ->addOutputReceiver(static::openCliOutputStream())
            ->addErrorReceiver(static::openCliErrorStream());
    }

    /**
     * Create HTTP IO Broker
     */
    public static function newHttpBroker(): Broker
    {
        return static::newBroker()
            ->addInputProvider(static::openHttpInputStream())
            ->addOutputReceiver(static::openHttpOutputStream());
    }
}
