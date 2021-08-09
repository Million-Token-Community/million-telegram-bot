<?php
    require 'lib/classes.php';
    require 'lib/keys.php';
    require 'lib/emoji.php';

    $botHistory = new BotHistory("botHistory.json");

    $path = "https://api.telegram.org/bot$apiToken";
    $update = json_decode(file_get_contents("php://input"), TRUE);

    $chatId = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];

    if (strpos($message, "/gas") === 0) {
        require "commands/gas.php";

    } elseif (strpos($message, "/price") === 0) {
        require "commands/price.php";

    } elseif (strpos($message, "/holders") === 0) {
        require "commands/holders.php";

    } elseif (strpos($message, "/volume") === 0) {
        require "commands/volume.php";

    } elseif (strpos($message, "/cat") === 0) {
        require "commands/cat.php";

    } elseif (strpos($message, "/lambo") === 0 || $message == "when lambo" || $message == "lambo when") {
        require "commands/lambo.php";

    }

    if (isset($response) && !is_null($response)) {
        $responseEnc = urlencode($response);
        $result = General::newHttpRequest($path."/sendmessage?chat_id=$chatId&text=$responseEnc&parse_mode=HTML", "GET");
        $botMessageId = $result["response"]->result->message_id;
    }

    if (isset($responsePhoto) && !is_null($responsePhoto)) {
        $responsePhotoEnc = urlencode($responsePhoto);
        $result = General::newHttpRequest($path."/sendPhoto?chat_id=$chatId&photo=$responsePhotoEnc&parse_mode=HTML", "GET");
        $botPhotoMessageId = $result["response"]->result->message_id;
    }

    # Clean up old bot messages
    $oldBotMessages = $botHistory->getOldMessages($message);
    foreach ($oldBotMessages as $msg) {
        General::newHttpRequest($path."/deleteMessage?chat_id=".$msg->chatId."&message_id=".$msg->botMessageId, "GET");
    }
    $botHistory->deleteOldMessages();

    # Save new message in bot history
    if (isset($botMessageId) && !is_null($botMessageId)) {
        $botHistory->saveMessage($chatId, $botMessageId, $message);
    }

    if (isset($botPhotoMessageId) && !is_null($botPhotoMessageId)) {
        $botHistory->saveMessage($chatId, $botPhotoMessageId, $message);
    }

?>