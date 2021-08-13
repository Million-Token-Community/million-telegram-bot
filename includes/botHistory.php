<?php

    # description / Keep track of all messages the bot has sent. They should be deleted in Telegram when older than 1 minute.

    $botHistory = new BotHistory("botHistory.json");

    # Clean up old bot messages
    if (isset($command) && !is_null($command)) {
        $oldBotMessages = $botHistory->getOldMessages($command);
        foreach ($oldBotMessages as $msg) {
            General::newHttpRequest($path."/deleteMessage?chat_id=".$msg->chatId."&message_id=".$msg->botMessageId);
        }
        $botHistory->deleteOldMessages();
    }

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