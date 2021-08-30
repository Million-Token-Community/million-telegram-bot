<?php
    require __DIR__."/includes/classes.php";
    require __DIR__."/includes/keys.php";
    require __DIR__."/includes/emoji.php";

    $path = "https://api.telegram.org/bot$apiToken";
    $update = json_decode(file_get_contents("php://input"), TRUE);

    $chatId = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];
    $command = NULL;

    $bannedChannels = array(
        "-1001487932707" # Old TG Group run by Don Dee
    );

    $responses = array();

    if (in_array($chatId, $bannedChannels)) {
        $response = (object) array(
            "type" => "htmltext",
            "payload" => "This is an unofficial channel. Please head over to the official channel at @millionjacuzzibar."
        );
        array_push($responses, $response);

    } elseif (strpos($message, '/') === 0) {

        $commands = array_map(function($item) {
            return strtolower(str_replace(".php", "", basename($item)));
        }, glob(__DIR__."/commands/*.php"));

        $command = strtolower(str_replace("/", "", explode(" ", $message)[0]));
        $command = str_replace("@million_token_bot", "", $command);

        if (in_array($command, $commands)) {
            require __DIR__."/commands/$command.php";
        }

    }

    require __DIR__.'/includes/stats.php';
    require __DIR__.'/includes/response.php';
    require __DIR__.'/includes/botHistory.php';

?>