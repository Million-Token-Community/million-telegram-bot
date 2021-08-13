<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require __DIR__."/includes/classes.php";
    require __DIR__."/includes/keys.php";
    require __DIR__."/includes/emoji.php";

    $path = "https://api.telegram.org/bot$apiToken";
    $message = $_GET["message"];

    $response = NULL;

    $commands = array_map(function($item) {
        return strtolower(str_replace(".php", "", basename($item)));
    }, glob(__DIR__."/commands/*.php"));

    if (strpos($message, '/') === 0) {
        $command = strtolower(str_replace("/", "", explode(" ", $message)[0]));

        if (in_array($command, $commands)) {
            require __DIR__."/commands/$command.php";

        } elseif ($command === "history") {
            var_dump($botHistory->messages);
    
        } elseif ($command === "oldmessages") {
            var_dump($botHistory->getOldMessages());
    
        } elseif ($command === "deleteoldmessages") {
            var_dump($botHistory->deleteOldMessages());
    
        } elseif ($command === "stats") {
            $stats = new Statistics("stats.json");
            var_dump($stats->stats);
    
        }

    } elseif ((strpos($message, "when") !== FALSE || strpos($message, "wen") !== FALSE) 
                && (strpos($message, "moon") !== FALSE || strpos($message, "lambo") !== FALSE)) {
        $command = "/wenlambomoon";
        $responseAnimation = "https://milliontoken.live/assets/img/hold.gif";
    }

    if (isset($response) && !is_null($response)) {
        echo $response;
    }

    if (isset($responsePhoto) && !is_null($responsePhoto)) {
        echo $responsePhoto;
    }

    if (isset($responseAnimation) && !is_null($responseAnimation)) {
        echo $responseAnimation;
    }
?>