<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Deliverance\Context as Inst;
use DecodeLabs\Deliverance\Channel as Ref0;
use DecodeLabs\Deliverance\Channel\Buffer as Ref1;
use DecodeLabs\Deliverance\Broker as Ref2;

class Deliverance implements Proxy
{
    use ProxyTrait;

    const VENEER = 'DecodeLabs\Deliverance';
    const VENEER_TARGET = Inst::class;

    public static Inst $instance;

    public static function openStream($stream, string $mode = 'a+'): Ref0 {
        return static::$instance->openStream(...func_get_args());
    }
    public static function openCliInputStream(): Ref0 {
        return static::$instance->openCliInputStream();
    }
    public static function openCliOutputStream(): Ref0 {
        return static::$instance->openCliOutputStream();
    }
    public static function openCliErrorStream(): Ref0 {
        return static::$instance->openCliErrorStream();
    }
    public static function openHttpInputStream(): Ref0 {
        return static::$instance->openHttpInputStream();
    }
    public static function openHttpOutputStream(): Ref0 {
        return static::$instance->openHttpOutputStream();
    }
    public static function newBuffer(?string $buffer = NULL): Ref1 {
        return static::$instance->newBuffer(...func_get_args());
    }
    public static function newBroker(): Ref2 {
        return static::$instance->newBroker();
    }
    public static function newCliBroker(): Ref2 {
        return static::$instance->newCliBroker();
    }
    public static function newHttpBroker(): Ref2 {
        return static::$instance->newHttpBroker();
    }
};
