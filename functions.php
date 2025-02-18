<?php
function getFastestServer($servers) {
    $fastestServer = null;
    $fastestTime = PHP_INT_MAX;

    foreach ($servers as $server) {
        $start = microtime(true);
        $response = @file_get_contents($server . "?ping=1");
        $time = microtime(true) - $start;

        if ($response !== FALSE && $time < $fastestTime) {
            $fastestTime = $time;
            $fastestServer = $server;
        }
    }

    return $fastestServer;
}

function replicateData($action, $data) {
    $config = include("config.php");
    foreach ($config["replica_servers"] as $server) {
        $postData = json_encode(["action" => $action, "data" => $data]);
        $options = [
            "http" => [
                "header"  => "Content-Type: application/json",
                "method"  => "POST",
                "content" => $postData
            ]
        ];
        file_get_contents($server, false, stream_context_create($options));
    }
}
?>