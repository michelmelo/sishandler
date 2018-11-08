# ServDebt Improvement System Handler for Monolog

Allows ServDebt systems to dispatch logs to the ServDebt Improvement System API using Monolog.

## Require the package
```
composer require servdebt/sishandler
```

## Usage Example
You can have a look at the included `example.php` file or:
```
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Handler\SISHandler;

// Adjust your settings
$apiKey = "SISAPIKey";
$apiUrl = "SISServerURL/api/logs";

// Create a log channel
$log = new Logger("ExampleChannel");
$log->pushHandler(new SISHandler($apiUrl, $apiKey));

// Optionally add IntrospectionProcessor to get the file path and line number where the log was generated
$log->pushProcessor(new IntrospectionProcessor());

// Build an array with your own error data
$data = [
	"_id"      => "5bd983a152b659e726378bd1",
	"index"    => 0,
	"guid"     => "486010d2-8e64-452c-990a-8b59a608572a",
	"isActive" => true
];

// Dispatch the log
$log->warning("You error description", $data);
```