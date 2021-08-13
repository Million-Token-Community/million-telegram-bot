<?php

    # description / If a response is defined, send the message to Telegram

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

?>