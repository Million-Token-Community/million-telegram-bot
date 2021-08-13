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
            $response = (object) array(
                "type" => "htmltext",
                "payload" => $botHistory->getOldMessages()
            );
            array_push($responses, $response);
    
        } elseif ($command === "deleteoldmessages") {
            $response = (object) array(
                "type" => "htmltext",
                "payload" => $botHistory->deleteOldMessages()
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

    } elseif ((strpos($message, "when") !== FALSE || strpos($message, "wen") !== FALSE) 
                && (strpos($message, "moon") !== FALSE || strpos($message, "lambo") !== FALSE)) {
        $command = "/wenlambomoon";
        $response = (object) array(
            "type" => "htmltext",
            "payload" => "https://milliontoken.live/assets/img/hold.gif"
        );
        array_push($responses, $response);
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