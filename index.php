<?php
    require __DIR__."/includes/classes.php";
    require __DIR__."/includes/keys.php";
    require __DIR__."/includes/emoji.php";

    $path = "https://api.telegram.org/bot$apiToken";
    $update = json_decode(file_get_contents("php://input"), TRUE);

    $chatId = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];

    $responses = array();

    if (strpos($message, '/') === 0) {

        $commands = array_map(function($item) {
            return strtolower(str_replace(".php", "", basename($item)));
        }, glob(__DIR__."/commands/*.php"));

        $command = strtolower(str_replace("/", "", explode(" ", $message)[0]));

        if (in_array($command, $commands)) {
            require __DIR__."/commands/$command.php";
        }

    } elseif ((strpos($message, "when") !== FALSE || strpos($message, "wen") !== FALSE) 
                && (strpos($message, "moon") !== FALSE || strpos($message, "lambo") !== FALSE)) {
        $command = "/wenlambomoon";
        $response = (object) array(
            "type" => "animation",
            "payload" => "https://milliontoken.live/assets/img/hold.gif"
        );
        array_push($responses, $response);
    }

    require __DIR__.'/includes/stats.php';
    require __DIR__.'/includes/response.php';
    require __DIR__.'/includes/botHistory.php';

?>