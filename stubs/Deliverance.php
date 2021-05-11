<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;
use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Deliverance\Context as Inst;
class Deliverance implements Proxy { use ProxyTrait; 
const VENEER = 'Deliverance';
const VENEER_TARGET = Inst::class;};
