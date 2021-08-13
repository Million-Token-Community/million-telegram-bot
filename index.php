<?php
    require 'lib/classes.php';
    require 'lib/keys.php';
    require 'lib/emoji.php';

    $botHistory = new BotHistory("botHistory.json");
    $stats = new Statistics("stats.json");

    $path = "https://api.telegram.org/bot$apiToken";
    $update = json_decode(file_get_contents("php://input"), TRUE);

    $chatId = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];

    if (strpos($message, "/gas") === 0) {
        $command = "/gas";
        require "commands/gas.php";

    } elseif (strpos($message, "/price") === 0) {
        $command = "/price";
        require "commands/price.php";

    } elseif (strpos($message, "/holders") === 0) {
        $command = "/holders";
        require "commands/holders.php";

    } elseif (strpos($message, "/volume") === 0) {
        $command = "/volume";
        require "commands/volume.php";

    } elseif (strpos($message, "/top1000") === 0) {
        $command = "/top1000";
        require "commands/top1000.php";

    } elseif (strpos($message, "/lambo") === 0) {
        $command = "/lambo";
        require "commands/lambo.php";

    } elseif ((strpos($message, "when") !== FALSE || strpos($message, "wen") !== FALSE) && (strpos($message, "moon") !== FALSE || strpos($message, "lambo") !== FALSE)) {
        $command = "/wenlambomoon";
        $responseAnimation = "https://milliontoken.live/assets/img/hold.gif";

    }

    if (isset($command)) {
        $stats->increment($command);
    }

    if (isset($response) && !is_null($response)) {
        $responseEnc = urlencode($response);
        $result = General::newHttpRequest($path."/sendmessage?chat_id=$chatId&text=$responseEnc&parse_mode=HTML");
        $botMessageId = $result["response"]->result->message_id;
    }

    if (isset($responsePhoto) && !is_null($responsePhoto)) {
        $responsePhotoEnc = urlencode($responsePhoto);
        $result = General::newHttpRequest($path."/sendPhoto?chat_id=$chatId&photo=$responsePhotoEnc&parse_mode=HTML");
        $botPhotoMessageId = $result["response"]->result->message_id;
    }

    if (isset($responseAnimation) && !is_null($responseAnimation)) {
        $responseAnimationEnc = urlencode($responseAnimation);
        $result = General::newHttpRequest($path."/sendAnimation?chat_id=$chatId&animation=$responseAnimationEnc");
        $botAnimationMessageId = $result["response"]->result->message_id;
    }

    # Clean up old bot messages
    $oldBotMessages = $botHistory->getOldMessages($message);
    foreach ($oldBotMessages as $msg) {
        General::newHttpRequest($path."/deleteMessage?chat_id=".$msg->chatId."&message_id=".$msg->botMessageId);
    }
    $botHistory->deleteOldMessages();

    if (isset($botMessageId) && !is_null($botMessageId)) {
        $botHistory->saveMessage($chatId, $botMessageId, $command);
    }

    if (isset($botPhotoMessageId) && !is_null($botPhotoMessageId)) {
        $botHistory->saveMessage($chatId, $botPhotoMessageId, $command);
    }

    if (isset($botAnimationMessageId) && !is_null($botAnimationMessageId)) {
        $botHistory->saveMessage($chatId, $botAnimationMessageId, $command);
    }

?>