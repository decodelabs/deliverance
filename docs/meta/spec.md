# Deliverance — Package Specification

> **Cluster:** `io`
> **Language:** `php`
> **Milestone:** `m1`
> **Repo:** `https://github.com/decodelabs/deliverance`
> **Role:** Data transfer interfaces

This document describes the purpose, contracts, and design of **Deliverance** within the Decode Labs ecosystem.

It is aimed at:

- Developers **using** Deliverance in their own applications or libraries.
- Contributors **maintaining or extending** Deliverance.
- Tools and AI assistants that need to reason about its behaviour.

---

## 1. Overview

### 1.1 Purpose

Deliverance provides shared data transfer interfaces for managing multiplex I/O operations in PHP. It offers a unified abstraction for reading from and writing to various data sources (streams, buffers, sockets) and provides an IO Broker pattern for grouping and managing multiple channels together. The package is designed to be used by framework systems that need to handle complex I/O scenarios like process management, HTTP request/response handling, and CLI interactions.

### 1.2 Non-Goals

Deliverance does **not**:

- Provide network protocol implementations (HTTP, TCP, UDP) — it only provides I/O abstractions
- Handle process spawning or management — it provides I/O interfaces for process communication
- Provide file system operations — it only handles I/O streams
- Handle data serialization or encoding — it works with raw strings
- Provide event loops or async I/O — it focuses on synchronous I/O with blocking control
- Handle authentication or authorization — it's a pure I/O abstraction layer
- Provide data validation or transformation — it only transfers data

---

## 2. Role in the Ecosystem

### 2.1 Cluster & Positioning

- **Cluster:** `io` (see Chorus taxonomy)
- Deliverance is an I/O abstraction package that provides data transfer interfaces for the Decode Labs ecosystem. It sits in the IO cluster alongside other I/O utilities. It depends on Exceptional and is used by packages like Atlas, Terminus, Eventful, Systemic, and Harvest for I/O operations.

### 2.2 Typical Usage Contexts

Typical places Deliverance appears:

- CLI application input/output handling
- Process communication (stdin/stdout/stderr)
- HTTP request/response body handling
- File stream operations
- Data buffering and proxying
- Multiplex I/O operations (multiple channels)
- Stream-based data transfer

Deliverance is intended to be used whenever code needs to abstract I/O operations, manage multiple I/O channels, or provide a consistent interface for reading from and writing to various data sources.

---

## 3. Public Surface

> This section focuses on the conceptual API, not every symbol.

### 3.1 Key Types

The primary public types are:

- `DecodeLabs\Deliverance`
  Static factory class providing convenience methods for creating channels and brokers.

- `DecodeLabs\Deliverance\Channel`
  Interface for bidirectional I/O channels. Extends `DataProvider` and `DataReceiver`. Represents a channel that can both read from and write to.

- `DecodeLabs\Deliverance\Channel\Stream`
  Stream-based channel implementation. Wraps PHP stream resources (files, STDIN/STDOUT/STDERR, php://input, etc.).

- `DecodeLabs\Deliverance\Channel\Buffer`
  In-memory buffer channel implementation. Provides string-based buffering for I/O operations.

- `DecodeLabs\Deliverance\Broker`
  IO Broker class for managing multiple channels. Implements `DataProvider`, `DataReceiver`, and `Connector`. Groups input providers, output receivers, and error receivers together.

- `DecodeLabs\Deliverance\DataProvider`
  Interface for data sources that can be read from. Defines methods for reading data (`read`, `readAll`, `readChar`, `readLine`, `readTo`).

- `DecodeLabs\Deliverance\DataReceiver`
  Interface for data destinations that can be written to. Defines methods for writing data (`write`, `writeLine`, `writeBuffer`).

- `DecodeLabs\Deliverance\ErrorDataReceiver`
  Interface for error output destinations. Extends `DataReceiver` with error-specific methods (`writeError`, `writeErrorLine`, `writeErrorBuffer`).

- `DecodeLabs\Deliverance\DataSender`
  Interface for objects that can send data to a receiver. Defines methods for setting receivers and sending data.

- `DecodeLabs\Deliverance\Socket`
  Interface for socket-based channels. Extends `Channel` with socket-specific methods. Currently incomplete and exists for temporary compatibility.

- `DecodeLabs\Deliverance\DataReceiver\Proxy`
  Proxy implementation that forwards writes to a callable. Allows wrapping arbitrary objects as data receivers.

- `DecodeLabs\Deliverance\Broker\Connector`
  Interface for broker connectors. Extends multiple broker interfaces (`InputCollector`, `OutputBroadcaster`, `ErrorBroadcaster`, `ChannelConnector`, `DataBroadcaster`).

### 3.2 Main Entry Points

The main usage pattern is through the static `Deliverance` factory:

```php
use DecodeLabs\Deliverance;

$stream = Deliverance::openStream('path/to/file');
$buffer = Deliverance::newBuffer();
$broker = Deliverance::newCliBroker();
```

---

## 4. Dependencies

### 4.1 Decode Labs

- `decodelabs/exceptional` (required)
  Used for exception handling when I/O operations fail.

### 4.2 External

- None

### 4.3 Optional Integrations

- None

---

## 5. Behaviour & Contracts

### 5.1 Invariants

- Channels can be readable, writable, or both
- Stream channels wrap PHP stream resources
- Buffer channels use in-memory string storage
- Brokers manage multiple channels and broadcast operations
- Read operations return `null` when no data is available or at end
- Write operations return number of bytes written
- Channels can be closed, which invalidates them
- Blocking mode can be controlled via `readBlocking` property
- Error output is separate from standard output in brokers

### 5.2 Input & Output Contracts

**Channel Operations:**
- `isReadable(): bool` — Checks if channel is readable
- `isWritable(): bool` — Checks if channel is writable
- `read(int $length): ?string` — Reads up to `$length` bytes
- `readAll(): ?string` — Reads all available data
- `readChar(): ?string` — Reads a single character
- `readLine(): ?string` — Reads a line (up to newline)
- `readTo(DataReceiver $writer): static` — Reads all data and writes to receiver
- `write(?string $data, ?int $length): int` — Writes data (returns bytes written)
- `writeLine(?string $data): int` — Writes data with newline
- `writeBuffer(Buffer $buffer, int $length): int` — Writes from buffer
- `isAtEnd(): bool` — Checks if at end of input
- `close(): static` — Closes channel
- `readBlocking: bool` — Controls blocking mode for reads

**Broker Operations:**
- `addInputProvider(DataProvider $provider): static` — Adds input source
- `addOutputReceiver(DataReceiver $receiver): static` — Adds output destination
- `addErrorReceiver(ErrorDataReceiver $receiver): static` — Adds error destination
- `addChannel(Channel $channel, bool $input, bool $output, bool $error): static` — Adds channel with roles
- `hasChannel(Channel $channel): bool` — Checks if channel is registered
- `removeChannel(Channel $channel): static` — Removes channel
- `inputEnabled: bool` — Controls input processing
- `readBlocking: bool` — Controls blocking mode (applies to all input providers)
- Read operations iterate through input providers until data is found
- Write operations broadcast to all output receivers
- Error write operations broadcast to all error receivers

**Stream Channel:**
- Constructor accepts string (file path or stream URI) or resource
- Mode determines read/write capabilities ('r', 'w', 'a', 'x', 'c', '+')
- Blocking mode controlled via `stream_set_blocking()`
- Read/write capabilities determined from mode string

**Buffer Channel:**
- Constructor accepts optional initial buffer string
- Read operations consume from buffer
- Write operations append to buffer
- `__toString()` returns current buffer contents
- `readable` and `writable` properties control capabilities

### 5.3 Stream Modes

Stream channels support standard PHP stream modes:
- `'r'` — Read only
- `'w'` — Write only (truncate)
- `'a'` — Append (write only)
- `'x'` — Exclusive write (create)
- `'c'` — Write only (create if not exists)
- `'+'` — Read/write (combined with above)

### 5.4 Broker Channel Management

Brokers manage channels by object ID:
- Channels are stored in arrays keyed by `spl_object_id()`
- Same channel can be used for input, output, and error
- Channels can be added/removed dynamically
- Broker operations iterate through registered channels

### 5.5 Blocking Mode

Blocking mode affects read operations:
- `readBlocking = true`: Read operations block until data is available
- `readBlocking = false`: Read operations return immediately (non-blocking)
- Broker's `readBlocking` property sets blocking mode on all input providers
- Stream blocking mode controlled via `stream_set_blocking()`

---

## 6. Error Handling

- Stream open failures throw `Exceptional::Io` during construction
- Write failures throw `Exceptional::Io` when `fwrite()` returns false
- Read operations return `null` on errors (graceful degradation)
- Invalid operations (read on non-readable, write on non-writable) throw exceptions via trait checks
- CLI stream access throws `Exceptional::Runtime` if not in CLI SAPI
- Closed channels return `null` for reads and `0` for writes
- All exceptions use Exceptional for consistent error reporting

---

## 7. Configuration & Extensibility

- Channels can be created via factory methods or directly
- Brokers can be configured with multiple channels
- Custom channel implementations can be created by implementing `Channel` interface
- Custom data providers/receivers can be created by implementing respective interfaces
- Proxy receivers allow wrapping arbitrary objects
- Socket interface exists for future socket implementations (currently incomplete)

---

## 8. Interactions with Other Packages

### 8.1 Exceptional

Deliverance uses Exceptional for all exception handling, providing consistent error reporting across the ecosystem.

### 8.2 Atlas

Atlas uses Deliverance for file I/O operations, providing stream-based file reading and writing.

### 8.3 Terminus

Terminus uses Deliverance for CLI I/O operations, providing terminal input/output handling.

### 8.4 Eventful

Eventful uses Deliverance for event stream I/O operations.

### 8.5 Systemic

Systemic uses Deliverance for process I/O management, providing stdin/stdout/stderr handling for spawned processes.

### 8.6 Harvest

Harvest uses Deliverance for HTTP request/response body handling, providing stream-based body reading and writing.

---

## 9. Usage Examples

### 9.1 Basic Channel Operations

```php
use DecodeLabs\Deliverance;

// File stream
$stream = Deliverance::openStream('path/to/file', 'r');
$data = $stream->read(1024);
$stream->close();

// Buffer
$buffer = Deliverance::newBuffer('initial content');
$buffer->write(' more data');
$content = $buffer->readAll(); // 'initial content more data'
```

### 9.2 CLI I/O

```php
use DecodeLabs\Deliverance;

// CLI streams
$input = Deliverance::openCliInputStream();
$output = Deliverance::openCliOutputStream();
$error = Deliverance::openCliErrorStream();

$line = $input->readLine();
$output->writeLine('You entered: ' . $line);
$error->writeLine('Error message');
```

### 9.3 HTTP I/O

```php
use DecodeLabs\Deliverance;

// HTTP streams
$input = Deliverance::openHttpInputStream();
$output = Deliverance::openHttpOutputStream();

$body = $input->readAll();
$output->writeLine('Response body');
```

### 9.4 IO Broker

```php
use DecodeLabs\Deliverance;

// Create CLI broker
$broker = Deliverance::newCliBroker();

// Or build manually
$broker = Deliverance::newBroker()
    ->addInputProvider(Deliverance::openCliInputStream())
    ->addOutputReceiver(Deliverance::openCliOutputStream())
    ->addErrorReceiver(Deliverance::openCliErrorStream());

// Use broker
$broker->readBlocking = true;
$line = $broker->readLine();
$broker->writeLine('INPUT: ' . $line);
$broker->writeErrorLine('Error occurred');
```

### 9.5 Channel Management

```php
use DecodeLabs\Deliverance;

$broker = Deliverance::newBroker();
$channel = Deliverance::openStream('file.txt', 'r+');

// Add channel for multiple roles
$broker->addChannel($channel, input: true, output: true, error: false);

// Check if channel is registered
if ($broker->hasChannel($channel)) {
    // Channel is managed
}

// Remove channel
$broker->removeChannel($channel);
```

### 9.6 Data Transfer

```php
use DecodeLabs\Deliverance;

$source = Deliverance::openStream('input.txt', 'r');
$destination = Deliverance::openStream('output.txt', 'w');

// Read all from source and write to destination
$source->readTo($destination);

// Or manually
while (!$source->isAtEnd()) {
    $chunk = $source->read(8192);
    if ($chunk !== null) {
        $destination->write($chunk);
    }
}
```

### 9.7 Buffer Operations

```php
use DecodeLabs\Deliverance;

$buffer = Deliverance::newBuffer();
$buffer->write('Hello');
$buffer->write(' World');

$char = $buffer->readChar(); // 'H'
$line = $buffer->readLine(); // 'ello World' (if contains newline)
$all = (string)$buffer; // Remaining buffer contents
```

---

## 10. Implementation Notes (for Contributors)

### 10.1 Channel Interface

Channels extend both `DataProvider` and `DataReceiver`:
- Provides unified interface for bidirectional I/O
- `ioResource` property holds underlying resource (stream, null, etc.)
- Generic type parameter allows type-safe resource access

### 10.2 Stream Implementation

Stream channels wrap PHP stream resources:
- Mode string determines read/write capabilities
- Blocking mode controlled via `stream_set_blocking()`
- Read/write capabilities cached after first check
- Errors are caught and converted to `null` returns for reads
- Write errors throw exceptions

### 10.3 Buffer Implementation

Buffer channels use in-memory string storage:
- String buffer holds all data
- Read operations consume from buffer (substring operations)
- Write operations append to buffer
- `readable` and `writable` properties control capabilities
- `open` flag controls channel state

### 10.4 Broker Implementation

Brokers manage multiple channels:
- Channels stored in arrays keyed by object ID
- Separate arrays for input providers, output receivers, error receivers
- Read operations iterate through input providers until data found
- Write operations broadcast to all output receivers
- Error write operations broadcast to all error receivers
- `readBlocking` property sets blocking mode on all input providers

### 10.5 Trait Usage

Common functionality provided via traits:
- `DataProviderTrait` — Provides read operation implementations
- `DataReceiverTrait` — Provides write operation implementations
- `NoReadBlockingTrait` — Provides non-blocking read behavior
- Traits include validation checks (readable/writable)

### 10.6 Factory Methods

Static factory class provides convenience methods:
- `openStream()` — Opens file or stream resource
- `openCliInputStream()` — Opens STDIN
- `openCliOutputStream()` — Opens STDOUT
- `openCliErrorStream()` — Opens STDERR
- `openHttpInputStream()` — Opens php://input
- `openHttpOutputStream()` — Opens php://output
- `newBuffer()` — Creates buffer channel
- `newBroker()` — Creates empty broker
- `newCliBroker()` — Creates pre-configured CLI broker
- `newHttpBroker()` — Creates pre-configured HTTP broker

### 10.7 Error Handling

Error handling strategy:
- Read operations: Return `null` on errors (graceful degradation)
- Write operations: Throw exceptions on errors (fail fast)
- Stream operations: Catch exceptions and convert to `null` for reads
- Validation: Traits check readable/writable before operations

---

## 11. Testing & Quality

- **Code Quality Score:** 4.5/5
- **README Quality Score:** 3/5
- **Documentation Score:** 0/5 (this spec)
- **Test Coverage Score:** 0/5

See `composer.json` for supported PHP versions.

---

## 12. Roadmap & Future Ideas

- Complete Socket interface implementation
- Add async I/O support
- Add stream filtering capabilities
- Improve error handling and recovery
- Add test coverage
- Consider adding compression/decompression support
- Consider adding encryption/decryption support

---

## 13. References

- [Exceptional Package](https://github.com/decodelabs/exceptional) — Exception handling
- [Atlas Package](https://github.com/decodelabs/atlas) — Filesystem operations
- [Terminus Package](https://github.com/decodelabs/terminus) — CLI I/O
- [Eventful Package](https://github.com/decodelabs/eventful) — Event streams
- [Systemic Package](https://github.com/decodelabs/systemic) — Process management
- [Harvest Package](https://github.com/decodelabs/harvest) — HTTP stack
- [PHP Streams Documentation](https://www.php.net/manual/en/book.stream.php) — PHP stream functions
- [Chorus Package Index](../../../chorus/config/packages.json) — Ecosystem metadata

