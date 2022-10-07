# Deliverance

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/deliverance?style=flat)](https://packagist.org/packages/decodelabs/deliverance)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/deliverance.svg?style=flat)](https://packagist.org/packages/decodelabs/deliverance)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/deliverance.svg?style=flat)](https://packagist.org/packages/decodelabs/deliverance)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/decodelabs/deliverance/Integrate)](https://github.com/decodelabs/deliverance/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/deliverance?style=flat)](https://packagist.org/packages/decodelabs/deliverance)

### Shared data transfer interfaces for PHP

Deliverance is a middleware library intended to be used by other framework systems that need to manage multiplex IO operations.

_Get news and updates on the [DecodeLabs blog](https://blog.decodelabs.com)._

---

## Installation

Install via Composer:

```bash
composer require decodelabs/deliverance
```

## Usage

### Importing

Deliverance uses [Veneer](https://github.com/decodelabs/veneer) to provide a unified frontage under <code>DecodeLabs\Deliverance</code>.
You can access all the primary functionality via this static frontage without compromising testing and dependency injection.



### Channels

Channels represent simple in / out handlers and can be written to and read from:

```php
use DecodeLabs\Deliverance;

$stream = Deliverance::openStream('path/to/file');
$stream->writeLine('Hello world');

$stream = Deliverance::openCliOutputStream(); // Same as Deliverance::openStream(STDOUT);

$buffer = Deliverance::newBuffer();
$buffer->write('Some text to buffer');
echo $buffer->read(6); // "Some t"
```


### IO Broker

Channels can be grouped together and managed by an <code>IO Broker</code> -

```php
use DecodeLabs\Deliverance;

// Create a CLI IO handler
$broker = Deliverance::newBroker()
    ->addInputProvider(Deliverance::openStream(STDIN))
    ->addOutputReceiver(Deliverance::openStream(STDOUT))
    ->addErrorReceiver(Deliverance::openStream(STDERR));

// Shortcut to the above:
$broker = Deliverance::newCliBroker();


// Read line from CLI
$broker->setReadBlocking(true);
$text = $broker->readLine();

// Write it back to output
$broker->writeLine('INPUT: '.$text);
```

Once grouped, the Channels in an IO broker can be used as the interface between many different information sources; see [Systemic Unix process launcher](https://github.com/decodelabs/systemic/blob/develop/src/Systemic/Process/Launcher/Unix.php) for an example of an IO Broker managing input and output with <code>proc_open()</code>.


## Licensing
Deliverance is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
