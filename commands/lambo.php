<?php
    try {

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "lambo"
        ));
        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result["status"] === 200 && is_object($result["response"])) {
            $url = $result["response"]->url;
            $response = "Here's your lambo";
            $responsePhoto = $url;
        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $response = "Sorry, something went wrong with /lambo";
    }
?>