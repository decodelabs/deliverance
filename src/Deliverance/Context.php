<?php

/**
 * @package Deliverance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Deliverance;

use DecodeLabs\Deliverance\Channel\Buffer;
use DecodeLabs\Deliverance\Channel\Stream;

use DecodeLabs\Exceptional;

class Context
{
    /**
     * Open a stream Channel
     *
     * @param Channel|string|resource $stream
     */
    public function openStream(
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
    public function openCliInputStream(): Stream
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
    public function openCliOutputStream(): Stream
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
    public function openCliErrorStream(): Stream
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
    public function openHttpInputStream(): Stream
    {
        return new Stream('php://input', 'r');
    }

    /**
     * Open HTTP output Channel
     */
    public function openHttpOutputStream(): Stream
    {
        return new Stream('php://output', 'w');
    }



    /**
     * Create a new buffer Channel
     */
    public function newBuffer(?string $buffer = null): Buffer
    {
        return new Buffer($buffer);
    }


    /**
     * New IO Broker
     */
    public function newBroker(): Broker
    {
        return new Broker();
    }

    /**
     * Create STD IO Broker
     */
    public function newCliBroker(): Broker
    {
        return $this->newBroker()
            ->addInputProvider($this->openCliInputStream())
            ->addOutputReceiver($this->openCliOutputStream())
            ->addErrorReceiver($this->openCliErrorStream());
    }

    /**
     * Create HTTP IO Broker
     */
    public function newHttpBroker(): Broker
    {
        return $this->newBroker()
            ->addInputProvider($this->openHttpInputStream())
            ->addOutputReceiver($this->openHttpOutputStream());
    }
}
