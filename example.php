<?php

require "vendor/autoload.php";

use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Handler\SISHandler;

// Adjust your settings
$apiKey = "SISAPIKey";
$apiUrl = "SISServerURL/api/logs";

// Create a log channel
$log = new Logger("SISLogExample");
$log->pushHandler(new SISHandler($apiUrl, $apiKey));

// Optionally add IntrospectionProcessor to get the file path and line number where the log was generated
$log->pushProcessor(new IntrospectionProcessor());

// Build an array with your own extra error data
$exampleExtraData = [
	"_id"      => "5bd983a152b659e726378bd1",
	"index"    => 0,
	"guid"     => "486010d2-8e64-452c-990a-8b59a608572a",
	"isActive" => true,
	"balance"  => "$3,077.07",
	"picture"  => "http://placehold.it/32x32",
	"age"      => 30,
	"eyeColor" => "green",
];

// Dispatch the log
$log->critical("WhoopsExceptionErrorException: Undefined property: stdClass::\$filPost 1", $exampleExtraData);
