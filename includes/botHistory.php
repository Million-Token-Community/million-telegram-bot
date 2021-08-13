<?php

    # description / Keep track of all messages the bot has sent. They should be deleted in Telegram when older than 1 minute.

    $botHistory = new BotHistory("botHistory.json");

    # Clean up old bot messages
    $oldBotMessages = $botHistory->getOldMessages($command);
    foreach ($oldBotMessages as $msg) {
        General::newHttpRequest($path."/deleteMessage?chat_id=".$msg->chatId."&message_id=".$msg->botMessageId);
    }
    $botHistory->deleteOldMessages();

    foreach ($botMessageIDs as $botMessageId) {
        $botHistory->saveMessage($chatId, $botMessageId, $command);
    }

?>