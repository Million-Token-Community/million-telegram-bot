<?php

    # description / If a response is defined, send the message to Telegram

    $botMessageIDs = array();

    foreach ($responses as $response) {
        if (isset($response)
            && is_object($response)
            && property_exists($response, "type")
            && $response->type !== NULL
            && $response->payload !== NULL) {

            $response->payload = urlencode($response->payload);
            
            switch ($response->type) {

                case 'htmltext':
                    $result = General::newHttpRequest($path."/sendmessage?chat_id=$chatId&text=".$response->payload."&parse_mode=HTML");
                    break;
                
                case 'photo':
                    $result = General::newHttpRequest($path."/sendPhoto?chat_id=$chatId&photo=".$response->payload);
                    break;
                
                case 'animation':
                    $result = General::newHttpRequest($path."/sendAnimation?chat_id=$chatId&animation=".$response->payload);
                    break;

            }

            array_push($botMessageIDs, $result->response->result->message_id);
        }
    }

?>