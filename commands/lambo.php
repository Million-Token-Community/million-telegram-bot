<?php
    try {

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "lambo"
        ));

        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result["status"] === 200 && is_object($result["response"])) {

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );
            $response->payload = "Here's your lambo";
            array_push($responses, $response);

            $response = (object) array(
                "type" => "photo",
                "payload" => NULL
            );
            $response->payload = $result["response"]->url;
            array_push($responses, $response);

        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /lambo";
    }
?>