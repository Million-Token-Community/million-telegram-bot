<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require __DIR__."/includes/classes.php";
    require __DIR__."/includes/keys.php";
    require __DIR__."/includes/emoji.php";

    $botHistory = new BotHistory("botHistory.json");

    $path = "https://api.telegram.org/bot$apiToken";
    $message = $_GET["message"];

    $responses = array();

    $commands = array_map(function($item) {
        return strtolower(str_replace(".php", "", basename($item)));
    }, glob(__DIR__."/commands/*.php"));

    if (strpos($message, '/') === 0) {
        $command = strtolower(str_replace("/", "", explode(" ", $message)[0]));

        if (in_array($command, $commands)) {
            require __DIR__."/commands/$command.php";

        } elseif ($command === "history") {
            $response = (object) array(
                "type" => "htmltext",
                "payload" => $botHistory->messages
            );
            array_push($responses, $response);
    
        } elseif ($command === "oldmessages") {
            $chatId = $_GET['chatId'];
            $response = (object) array(
                "type" => "htmltext",
                "payload" => $botHistory->getOldMessages($chatId)
            );
            array_push($responses, $response);
    
        } elseif ($command === "deleteoldmessages") {
            $chatId = $_GET['chatId'];
            $response = (object) array(
                "type" => "htmltext",
                "payload" => $botHistory->deleteOldMessages($chatId)
            );
            array_push($responses, $response);
    
        } elseif ($command === "stats") {
            $stats = new Statistics("stats.json");
            $response = (object) array(
                "type" => "htmltext",
                "payload" => $stats->stats
            );
            array_push($responses, $response);

        }

    }

    foreach ($responses as $response) {
        if (is_object($response->payload)) {
            var_dump($response->payload);
            echo "<br />";
        } elseif (is_array($response->payload)) {
            print_r($response->payload);
            echo "<br />";
        } else {
            echo $response->payload."<br />";
        }
    }

?>