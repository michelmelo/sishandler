<?php

namespace Monolog\Handler;
use Monolog\Logger;

class DatadogHandler extends AbstractProcessingHandler
{
    public static $levels = [
        0 => "EMERGENCY",
        1 => "ALERT",
        2 => "CRITICAL",
        3 => "ERROR",
        4 => "WARNING",
        5 => "NOTICE",
        6 => "INFORMATIONAL",
        7 => "DEBUG",
    ];

    private $apiURL;
    private $apiKey;
    private $appKey;
    private $message;


    public function __construct($apiURL, $apiKey, $appKey, $level = Logger::DEBUG, $bubble = true)
    {
        $this->apiKey = $apiKey;
        $this->apiURL = $apiURL;
        $this->appKey = $appKey;
        parent::__construct($level, $bubble);
    }


    protected function write(array $record): void
    {
        $this->message = $record;
        $this->makeRequest($this->message);
    }


    protected function makeRequest($data)
    {
        $data = [
            "title"     => $data["message"],
            "text"      => $data["context"]["messages"] .
                            "\n\nUri: ".$data['context']['request']['server']['REQUEST_URI'] .
                            "\nUser: ". ($data['context']['user'] ?? ''),
            "priority"  => "normal",
            "tags"      => [$data["context"]["server"], app()->env],
            "alert_type" => $data["level_name"]
        ];
        $data = json_encode($data);

        // Prepare headers
        $headers = [
            "Content-Type"   => "application/json",
        ];

        foreach ($headers as $header => &$content)
            $content = "{$header}: {$content}";

        $headers = array_values($headers);

        $url = $this->apiURL ."?api_key={$this->apiKey}&application_key={$this->appKey}";
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_exec($curl);
        curl_close($curl);
    }
}
